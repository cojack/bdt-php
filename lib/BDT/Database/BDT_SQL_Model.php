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
 * BDT_SQL_Model klasa implementuje model w bazie danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/

BDT_Loader::loadFile( array(
   './lib/BDT/Collection/Components/BDT_SQL_Function_Collection',
   './lib/BDT/Database/Components/BDT_SQL_Function'
) );

abstract class BDT_SQL_Model {

   protected $_query;

   private $_functions;

   private $_table;

   private $_validators = array (
      'smallint' => 'is_int',
      'integer' => 'is_int',
      'bigint' => 'is_int',
      'decimal' => 'is_float',
      'numeric' => 'is_numeric',
      'real' => 'is_float',
      'float' => 'is_float',
      'varchar' => 'is_string',
      'text' => 'is_string',
      'boolean' => 'is_bool'
   );

   public function __construct() {

      $this->_query = new BDT_SQL_Query( $this );

      $this->_table = new BDT_SQL_Table( $this );

      $this->_functions = new BDT_SQL_Function_Collection;
      $this->_functions->setLoadCallback( 'setProcedures', $this );

   }

   protected function _setTableName( $name ) {
      $this->_table->setTableName( $name );
   }

   protected function _addColumn( $column, $definition ) {
      $this->_table->addColumn( $column, $definition );
   }

   protected function _addProcedure( $name, $arguments, $return = 'VOID' ) {
      $this->_functions->addItem( new BDT_SQL_Function( $name, $arguments, $return ), $name );
   }


   public function getProcedure( $procedureName ) {
      return $this->_functions->getItem( $procedureName );
   }

   public function getTable() {
      return $this->_table;
   }
}