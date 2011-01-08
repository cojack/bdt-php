<?php
/**
 *  Basic PHP Develop Tools (BDT)
 *  Copyright (C) 2010 Aichra.pl
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

/**
 * BDT_Debugger klasa od debugowania
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      25.23.2010
 * @package    BDT
 * @charset    utf8
 **/
final class BDT_Debugger {

   /**
    * Informacja o tym czy debugger ma być używany.
    *
    * @var        boolean
    * @access     public
    * @staticvar
    */
   public static $debug = FALSE;

   /**
    * Zmienna profiler do zarządzania zapytaniami sql
    *
    * @var        variable
    * @access     private
    * @staticvar
    */
   private static $profiler;

   private static $selfObj;

   private $timer = array();

   /**
    * Tablica z wynikami debuggera
    *
    * @var        array
    * @access     private
    * @staticvar
    */
   private static $debugArray = array();

   private $_tpl;

   /**
    * Konstruktor klasy, prywatny, nie możemy utworzyć instancji tej klasy.
    *
    * @method  __construct
    * @access  private
    * @param   void
    * @return  void
    */
   private function __construct() {
      if( self::$debug ) {
         ini_set('display_errors', 1 );
         set_error_handler( array( $this, 'errorHandler' ), E_ALL );
      } else {
         ini_set('display_errors', 0 );
      }
   }

   public static function initialize( $debug = FALSE ) {
      self::$debug = $debug;
      return isset(self::$selfObj) ? self::$selfObj : self::$selfObj = new BDT_Debugger;
   }

   public function start() {
      $time = microtime( true );
      $this->_tmpTimer = array(
         'class' => $class,
         'method' => $method,
         'timeStart' => $time
      );
   }

   public function stop() {
      array_merge( $this->_tmpTimer, array( 'timeStop' => microtime( true ) ) );

      $this->timer[] = $this->_tmpTimer;

      $this->_tmpTimer = array();
   }

   private function _backtrace() {
      $this->_backtrace = debug_backtrace();
   }

   public function setTpl( $tpl ) {
      $this->_tpl = $tpl;
   }

   /**
    * Metoda do ręcznego zarządzania błedami w php
    *
    * @param unknown_type $errno
    * @param unknown_type $errstr
    * @param unknown_type $errfile
    * @param unknown_type $errline
    * @return unknown_type
    */
   public function errorHandler( $errLvl, $errMsg, $errFile, $errLine ) {
      switch ( $errLvl ) {
         case E_USER_ERROR:
           echo "<b>My ERROR</b> [$errLvl] $errMsg<br />\n";
           echo "  Fatal error on line $errLine in file $errFile";
           echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
           echo "Aborting...<br />\n";
           break;

         case E_USER_WARNING:
           echo "<b>My WARNING</b> [$errLvl] $errMsg<br />\n";
           break;

         case E_USER_NOTICE:
           echo "<b>My NOTICE</b> [$errLvl] $errMsg<br />\n";
           break;

         default:
           echo "Unknown error type: [$errLvl] $errMsg<br />\n";
           echo "on line $errLine in file $errFile <br />\n";
           break;
      }
   }

   public function setError($error){ var_dump($error); }

   /**
    * Metoda ustawia zmieną profiler.
    *
    * @method  setProfiler
    * @access  private
    * @static
    * @param   string      $profiler      Zmienna do debugowania zapytań sql
    * @return  void
    */
   public function setProfiler( $profiler ) {
      self::$profiler = $profiler;
   }



   /**
    * Metoda do przechowywania błędów z Loggera
    *
    * @method  error
    * @access  public
    * @static
    * @param   string   $errorMsg      Treść wiadomości o błędzie z loggera
    * @param   integer  $errorLvl      Wartość wagi błędu w postaci integer
    * @param   string   $errorModule   String z nazwą modułu, w której wystąpił błąd
    * @return  void
    */
   public function error( $error = array() ) {
      self::$debugArray['errors'][] = array(
         'desc' => $error['smessage'],
         'ordinary' => $error['sloglevel'],
         'module' => $error['smodule']
      );
   }

   /**
    * TODO wszystko ;)
    */
   public function route() {
      self::$debugArray['route'][] = array ();
   }

   /**
    * Metoda do pobierania informacji z OPT nt parsowania szablonu
    *
    * @method  template
    * @access  public
    * @static
    * @param   void
    * @return  void
    */
   public function template() {
      $tpl = Opl_Debug_Console::getInfo();
      self::$debugArray['template'] = $tpl['opt_views']['values'];
   }

   /**
    * Metoda do zapisywania do tablicy debugera informacji o zapytaniach sql i czasie ich wykonania
    *
    * @method  sql
    * @access  public
    * @static
    * @param   void
    * @return  void
    */
   private function sql() {
      $time = 0; // czas wykonania wszystkich zapytań
      $n = 1; // ilość wszystkich zapytań

      foreach (self::$profiler as $event) {
         self::$debugArray['sql']['queries'][] = array(
            'time' => $event->getElapsedSecs(),
            'query' => $event->getQuery(),
            'params' => $event->getParams()
         );
         $time += $event->getElapsedSecs();
         $n++;
      }

      if( !empty( self::$debugArray['sql']['queries'] ) ) {
         self::$debugArray['sql']['total'] = array(
            'totalTime' => $time,
            'queryCount' => $n
         );
      }
   }

   /**
    * Metoda zwraca tablice z informacjami nt działania aplikacji, informację o:
    * - wczytanych plikach
    * - zapytaniach sql
    * - bledach z loggera
    * - informacje z router ( TODO )
    * - parsowania teplatki
    *
    * @method  getInfo
    * @access  public
    * @static
    * @param   void
    * @return  array    Tablica z informacjami
    */
   public static function getInfo(){
      if( self::$debug ) {
         self::sql(); // logi z sql'a
         self::template(); // logi z templatki
         return self::$debugArray;
      }
   }
}