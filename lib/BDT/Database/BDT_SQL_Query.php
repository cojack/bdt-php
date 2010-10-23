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

BDT_Loader::loadFile( array( './lib/BDT/Collection/Components/BDT_SQL_Query_Collection' ) );

class BDT_SQL_Query  {

   private $_where = array();

   private $_from;

   private $_statment;

   private $sql;

   protected $_conn;

   protected $_inputs;

   protected $_model;

   public function __construct( $model ) {
      $this->_model = $model;

      $this->_conn = BDT_SQL_Connect::getConn();

      $this->query = new BDT_SQL_Query_Collection;
   }

   public function prepare( $sql ) {
      try {
         $this->_statment = $this->_conn->prepare( $sql );
      } catch ( PDOException $error ) {
         trigger_error( $error->getMessage(), E_USER_WARNING );
      }
      return $this->_statment;
   }

   public function toArray() {
      try {
         $result = $this->_statment->fetchAll();
      } catch ( PDOException $error ) {
         trigger_error( $error->getMessage(), E_USER_WARNING );
      }
      return $result;
   }

   public function callProcedure( $procedureName ) {
      $driver = BDT_SQL_Connect::getConn()->getAttribute(PDO::ATTR_DRIVER_NAME);

      $engine = 'BDT_SQL_' . mb_convert_case( $driver, MB_CASE_UPPER, 'UTF-8' );

      BDT_Loader::loadFile( array( './lib/BDT/Database/Components/Procedures/'. $engine ) );

      $procedure = new $engine( $this->_model, $this);

      return $procedure->executeProcedure();
   }
}