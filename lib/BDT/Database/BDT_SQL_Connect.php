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
 * BDT_SQL_Connect klasa odpowiedzialna za połączenie z bazą danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
final class BDT_SQL_Connect {

   private static $_conn;

   private function __construct() {
      $this->_config = (object)BDT_Loader::getFiles( array( array(
          'name' => 'database.ini',
          'delete' => TRUE
      ) ) );

      $dsn  = $this->_config->engine . ':';
      $dsn .= 'host=' . $this->_config->host;
      $dsn .= isset( $this->_config->port ) ? ';port=' . $this->_config->port : ';';
      $dsn .= ';dbname=' . $this->_config->dbname;

      try {
         self::$_conn = new PDO( $dsn, $this->_config->user, $this->_config->password );
      } catch( PDOException $error ) {
         trigger_error( $error->getMessage(), E_USER_ERROR );
      }

   }

   private function __clone() {}

   public static function getInstance( $config ) {
      BDT_Loader::loadFile( $config );

      if( !isset( self::$_conn ) ) {
         new BDT_SQL_Connect();
      }

      return self::$_conn;

   }

   public static function getConn() {
      return isset( self::$_conn ) ? self::$_conn : FALSE;
   }

}