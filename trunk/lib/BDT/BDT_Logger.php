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
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      08.09.2009
 * @package    BDT
 * @charset    utf8
 **/
final class Logger {

   const LOGGER_DEBUG    = 100;
   const LOGGER_INFO     = 75;
   const LOGGER_NOTICE   = 50;
   const LOGGER_WARNING  = 25;
   const LOGGER_ERROR    = 10;
   const LOGGER_CRITICAL = 5;

   /**
    * @method __construct
    * @access private
    * @param  void
    * @return void
    *
    * Singleton pattern
    *
    */
   private function __construct() { // tak używam singletona! można mnie ukamieniować
   }

   /**
    * @method register
    * @access public
    * @param  $logName string
    * @param  $conn - instance of Doctrine connection
    * @return void
    *
    * Method register a app
    *
    */
   public static function register($logName, Doctrine_Manager $conn) {
      Debugger::load( array( './app/controlers/DatabaseLogger.php' ) );

      $objBack = new DatabaseLogger();

      self::manageBackends($logName, $objBack);

   }

   /**
    * @method getInstance()
    * @access public
    * @param  $name string
    * @return manageBackends()
    *
    * Method return a instance of registered app
    *
    */
   public static function getInstance($name) {
      return self::manageBackends($name);
   }

   /**
    * @method manageBackends
    * @access private
    * @param  $name string
    * @param  $objBack - instance of LoggerBackend
    * @return string registered app
    */
   private static function manageBackends($name, LoggerBackend $objBack = null) {
      static $backEnds;

      if( !isset($backEnds) )
         $backEnds = array();

      if(! isset($objBack)) {
         if( isset($backEnds[$name]) )
            return $backEnds[$name];
         else
            throw new Exception(Errors::_getError('l_01'));
      } else
         $backEnds[$name] = $objBack;
   }

   /**
    * @method  levelToString
    * @access  public
    * @static  true
    * @param   int      $logLevel
    * @return  string   name of the access
    *
    * Method convert sent level of the messages to string (name is)
    *
    */
   public static function levelToString($logLevel) {
      switch ($logLevel) {
         case self::LOGGER_DEBUG:
            return 'LOGGER_DEBUG';
            break;
         case self::LOGGER_INFO:
            return 'LOGGER_INFO';
            break;
         case self::LOGGER_NOTICE:
            return 'LOGGER_NOTICE';
            break;
         case self::LOGGER_WARNING:
            return 'LOGGER_WARNING';
            break;
         case self::LOGGER_ERROR:
            return 'LOGGER_ERROR';
            break;
         case self::LOGGER_CRITICAL:
            return 'LOGGER_CRITICAL';
            break;
         default:
            return '[unknown]';
      }
   }
}