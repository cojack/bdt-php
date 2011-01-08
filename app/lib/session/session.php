<?php
class Session {
   private $php_session_id;
   private $native_session_id;
   private $dbhandle;
   private $logged_in;
   private $user_id;
   private $session_timeout = 600;      # 10 minutowy maksymalny czas nieaktywnoci sesji
   private $session_lifespan = 3600;    # 1 godzinny maksymalny czas trwania sesji.
   private $_mapper;
   
   public function __construct() {
      $this->_mapper = BDT_Database::getMapper('session');

      # Inicjalizuje mechanizm obsługi sesji
      session_set_save_handler(
            array($this, '_session_open_method'), 
            array($this, '_session_close_method'), 
            array($this, '_session_read_method'), 
            array($this, '_session_write_method'), 
            array($this, '_session_destroy_method'), 
            array($this, '_session_gc_method')
      );

      # Sprawdza przesłane cookie o ile je przesłano; jeżeli wygląda podejrzanie zostaja z miejsca anulowane     
      $strUserAgent = md5($_SERVER["HTTP_USER_AGENT"]);
      if(isset($_COOKIE["PHPSESSID"])) {
         # Kontrola bezpieczeństwa i ważności
         $this->php_session_id = $_COOKIE["PHPSESSID"];

         if ($this->_mapper->checkSession($this->php_session_id, $strUserAgent, $this->session_lifespan, $this->session_timeout) === 0) {
         # Usuwa z bazy danych - w tym samym czasie usuwane s¹ przeterminowane sesje
         # Usuwa nieprzydatne zmienne sesji
         $procedure = $this->_mapper->getProcedure('clean_session');
         $procedure->getArgument( 'id_ascii' )->setValue( $this->php_session_id );
         $procedure->getArgument( 'lifespan' )->setValue( $this->session_lifespan );
         $this->_mapper->executeProcedure($procedure, false);
         # Pozbywa siê identyfikatora, wymuszając na PHP nadanie nowego
         unset($_COOKIE["PHPSESSID"]);
         };  
      };
      # Ustawia czas ¿ycia cookie
      session_set_cookie_params($this->session_lifespan);
      # Wywo³uje metodê session_start by zainicjowaæ sesjê
      session_start();
   }
   
   public function impress() {
      if ($this->native_session_id) {
         $procedure = $this->_mapper->getProcedure('update_session');
         $procedure->getArgument( 'id_session' )->setValue( $this->native_session_id );
         $this->_mapper->executeProcedure($procedure, false);
      }
   }
    
   public function isLogged() {
      return($this->logged_in);
   }
   
   public function getUserId() {
      if ($this->logged_in) {
         return($this->user_id);
      } else {
         return(false);
      };
   }
   
   public function getUserObject() {
      if ($this->logged_in) {
         if (class_exists("user")) {
            $objUser = new User($this->user_id);
            return($objUser);
         } else {
            return(false);
         };
      };
   }
   
   public function getSessionIdentifier() {
      return $this->php_session_id;
   }
    
   public function login($strUsername, $strPlainPassword) {
      $strSHA1Password = sha1($strPlainPassword);

      $user = BDT_Database::getMapper('user');
      list( $result, $count ) = $user->userExists( $strUsername, $strSHA1Password);

      if ( $count > 0 ) {
         $this->user_id = $result->id_user;
         $this->logged_in = true;

         $procedure = $this->_mapper->getProcedure( 'login' );
         $procedure->getArgument( 'id_user' )->setValue( $this->user_id );
         $procedure->getArgument( 'id_session' )->setValue( $this->native_session_id );
         $this->_mapper->executeProcedure( $procedure, false );

         return TRUE;
      } 

      return FALSE;
   }

   public function logout() {
      if ($this->logged_in === TRUE) {

         $procedure = $this->_mapper->getProcedure('logout');
         $procedure->getArgument('id_session')->setValue( $this->native_session_id );
         $this->_mapper->executeProcedure($procedure, false);

         $this->logged_in = false;
         $this->user_id = 0;
         return TRUE;
      }
      
      return FALSE;
   }

   public function __get($nm) {
      $result = false;
      //$result = pg_query("SELECT wartosc_zmiennej FROM zmienna_sesji WHERE identyfikator_sesji = " . $this->native_session_id . " AND nazwa_zmiennej = '" . $nm . "'");
      if (pg_num_rows($result)>0) {
         $row = pg_fetch_array($result);
         return(unserialize($row["wartosc_zmiennej"]));
      }

      return FALSE;
   }


   public function __set($nm, $val) {
      $strSer = serialize($val);
      $stmt = "INSERT INTO zmienna_sesji(identyfikator_sesji, nazwa_zmiennej, wartosc_zmiennej) VALUES(" . $this->native_session_id . ", '$nm', '$strSer')";
      //$result = pg_query($stmt);
   }

   
   private function _session_open_method($save_path, $session_name) {
      # Do nothing
      return TRUE;
   }
   
   public function _session_close_method() {
      return TRUE;
   }
   
   private function _session_read_method($id) {
      # Służy do ustalenia, czy nasza sesja w ogóle istnieje
      $strUserAgent = md5($_SERVER["HTTP_USER_AGENT"]);

      $this->php_session_id = $id;

      $result = $this->_mapper->sessionExists($id);
      if( $result && count( $result ) > 0 ) {
         $this->native_session_id = $result->id_session;
         if( $result->loged == 1) {
            $this->logged_in = true;
            $this->user_id = $result->id_user;
         } else {
            $this->logged_in = false;
         };
      } else {
         $this->logged_in = false;
         # Konieczne jest stworzenie wpisu w bazie danych
         $procedure = $this->_mapper->getProcedure( 'insert_session' );
         $procedure->getArgument( 'id_ascii' )->setValue( $this->php_session_id );
         $procedure->getArgument( 'user_agent' )->setValue( $strUserAgent );
         $this->_mapper->executeProcedure( $procedure, FALSE );

         # Teraz pobiera prawdziwy identyfikator
         $this->native_session_id = $procedure->getArgument( 'id_session' )->getValue();
      };
      # Zwraca jedynie ci¹g pusty*/
      return("");
   }

   public function _session_write_method($id, $sess_data) {
      return(true);
   }

   private function _session_destroy_method( $id ) {
      $procedure = $this->_mapper->getProcedure( 'delete_session' );
      $procedure->getArgument( 'id_ascii' )->setValue( $id );
      return $this->_mapper->executeProcedure( $procedure, FALSE );
      //$result = pg_query("DELETE FROM \"sesja_uzytkownika\" WHERE identyfikator_sesji_ascii = '$id'");
      //return($result);
   }

    
   private function _session_gc_method($maxlifetime) {
      return TRUE;
   }
}