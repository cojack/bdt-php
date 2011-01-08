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

require_once('./lib/BDT/Exception/BDT_Database_Exception.php');
require_once('./lib/BDT/Database/BDT_SQL_Connect.php');
require_once('./lib/BDT/Database/BDT_SQL_Query.php');
require_once('./lib/BDT/Database/BDT_SQL_Procedure.php');
require_once('./lib/BDT/Database/BDT_SQL_Mapper.php');

/**
 * BDT_Database klasa odpowiedzialna za obsługę bazy danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_Database {

   public function __construct() {
   }

   public function getMapper( $mapper ) {

      $mapper = ucfirst( $mapper );
      require_once('./data/mappers/procedures/SQL_' . $mapper . '_Procedure.php');
      require_once('./data/mappers/SQL_' . $mapper . '.php');

      $pgfm = 'SQL_' . $mapper;

      if( !class_exists( $pgfm ) )
         throw new BDT_Database_Exception( sprintf( 'Nie ma takiej klasy %s', $pgfm ) );

      return new $pgfm;
   }
}