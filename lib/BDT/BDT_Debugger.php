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
    */
   private static $_debug = FALSE;

   /**
    * Instancja własnej klasy
    *
    * @var BDT_Debugger
    */
   private static $selfObj;

   /**
    * Tablica z wynikami debuggera
    *
    * @var        array
    */
   private static $_debugArray = array(
      'sql' => array(
         0 => array (
            'Query',
            'Time'
         ),
      ),
      'error' => array(
         0 => array(
            'Message'
         ),
      ),
      'route' => array(),
      'request' => array(
         0 => array(
            'Post',
            'Get',
            'Cookie',
            'Server'
         )
      )
   );

   /**
    * Konstruktor klasy, prywatny, nie możemy utworzyć instancji tej klasy.
    *
    * @param   void
    * @return  void
    */
   private function __construct() {
      if( self::$_debug ) {
         ini_set('display_errors', 1 );
         require_once('./lib/FirePHP/FirePHP.class.php'); // (object oriented API)
         $this->_firePHP = new FirePHP;
         $this->_firePHP->setEnabled(true);
         set_error_handler( array( $this, 'errorHandler' ), E_ALL );
      } else {
         ini_set('display_errors', 0 );
      }
   }

   /**
    * Inicjalizacja klasy
    *
    * @param boolean $debug czy jest włączona opcja debugowania
    */
   public static function initialize( $debug = FALSE ) {
      self::$_debug = $debug;
      return isset(self::$selfObj) ? self::$selfObj : self::$selfObj = new BDT_Debugger;
   }

   public static function getInstance() {
      return self::$selfObj;
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
            $message = "My ERROR [$errLvl] $errMsg\n";
            $message .= "  Fatal error on line $errLine in file $errFile";
            $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")\n";
            $message .= "Aborting...\n";
            break;

         case E_USER_WARNING:
            $message = "My WARNING [$errLvl] $errMsg\n";
            break;

         case E_USER_NOTICE:
            $message = "My NOTICE [$errLvl] $errMsg\n";
            break;

         default:
            $message = "Unknown error type: [$errLvl] $errMsg\n";
            $message .= "on line $errLine in file $errFile\n";
            break;
      }

      self::setError( new Exception( $message ) );
   }

   public static function getDebug() {
      return self::$_debug;
   }

   public function out( $type, $value = null ) {
      call_user_func_array( array($this->_firePHP, $type), array( $value ) );
   }

   public static function setRoute( array $route ) {
      self::$_debugArray['route'] = array(
         array_keys($route),
         array_values($route)
      );
   }

   public static function setRequest( BDT_Request $request ) {
      self::$_debugArray['request'][] = array(
         'post' => $request->getPostVariables(),
         'get' => $request->getGetVariables(),
         'cookies' => $request->getCookies(),
         'server' => $_SERVER,
      );
   }

   /**
    * Metoda do zapisywania do tablicy debugera informacji o zapytaniach sql i czasie ich wykonania
    *
    * @param   void
    * @return  void
    */
   public function setSql( $query, $time ) {
      static $timeTotal = 0; // czas wykonania wszystkich zapytań
      static $n = 1; // ilość wszystkich zapytań

      self::$_debugArray['sql'][$n] = array(
         'query' => $query,
         'time' => $time
      );

      $timeTotal += $time;
      $n += 1;
   }

   public function setError( Exception $error ) {
      self::$_debugArray['error'][] = array(
         'message' => $error->getMessage(),
      );
   }

   private function _getSql() {
      $this->_firePHP->table( 'SQL', self::$_debugArray['sql'] );
   }

   private function _getError() {
      $this->_firePHP->table( 'Errors', self::$_debugArray['error'] );
   }

   private function _getRoute() {
      $this->_firePHP->table( 'Route', self::$_debugArray['route'] );
   }

   private function _getRequest() {
      $this->_firePHP->table( 'Request', self::$_debugArray['request'] );
   }

   /**
    * Metoda zwraca tablice z informacjami nt działania aplikacji, informację o:
    * - wczytanych plikach
    * - zapytaniach sql
    * - bledach z loggera
    * - informacje z router ( TODO )
    * - parsowania teplatki
    *
    * @param   void
    * @return  array    Tablica z informacjami
    */
   public function getInfo(){
      if( self::$_debug ) {
         $this->_getSql(); // logi z sql'a
         $this->_getError();
         $this->_getRoute();
         $this->_getRequest();
         //self::template(); // logi z templatki
         //return self::$_debugArray;
      }
   }
}