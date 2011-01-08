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

require_once('./lib/BDT/Database/Components/BDT_SQL_PDO.php');
require_once('./lib/BDT/Database/Components/BDT_SQL_PDO_Statement.php');

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
class BDT_SQL_Connect {

   private static $_conn = array();

   private static function _prepareConnect($type) {
      $config = BDT_Loader::loadFileINI( './config/database' );

      $dsn  = $config[$type]['engine'] . ':';
      $dsn .= 'host=' . $config[$type]['host'];
      $dsn .= isset( $config[$type]['port'] ) ? ';port=' . $config[$type]['port'] : ';';
      $dsn .= ';dbname=' . $config[$type]['dbname'];

      $pdo = new BDT_SQL_PDO( $dsn, $config[$type]['user'], $config[$type]['password'] );
      $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

      return $pdo;
   }

   public static function connect($type) {
      if( !isset( self::$_conn[ $type ] ) || !( self::$_conn[ $type ] instanceof PDO ) ) {
         self::$_conn[ $type ] = self::_prepareConnect( $type );
      }

      return self::$_conn[ $type ];
   }

}