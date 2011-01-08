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
 * BDT_SQL_Query klasa implementuje zapytania do bazy danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_SQL_Query  {

   protected $_conn;

   protected $_mapper;

   public function __construct( $mapper ) {
      $this->_mapper = $mapper;

      $this->_conn = BDT_SQL_Connect::connect('read');
   }

   public function prepare( $sql ) {
      return $this->_conn->prepare( $sql );
   }

   public function callProcedure( BDT_SQL_Procedure $procedure ) {
      $driver = $this->_conn->getAttribute(PDO::ATTR_DRIVER_NAME);

      $engine = 'BDT_SQL_' . strtoupper( $driver );

      require_once( './lib/BDT/Database/Components/BDT_SQL_PL.php' );
      require_once( './lib/BDT/Database/Components/Procedures/'. $engine .'.php' );

      $plsql = new $engine( $procedure );
      return $plsql->invoke();
   }
}