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
 * BDT_SQL_Table klasa implementuje tabele w bazie danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/

BDT_Loader::loadFile( array(
   './lib/BDT/Collection/Components/BDT_SQL_Collumn_Collection',
   './lib/BDT/Database/Components/BDT_SQL_Column'
) );

class BDT_SQL_Table {

   private $_tableName;

   private $_columns;

   protected $_functions = array();

   public function __construct( $model ) {

      $this->_columns = new BDT_SQL_Column_Collection;
      $this->_columns->setLoadCallback( 'setTable', $model );

   }

   public function setTableName( $tableName ) {
      $this->_tableName = $tableName;
   }

   public function addColumn( $column, $definition = array() ) {
      $this->_columns->addItem( new BDT_SQL_Column( $column, $definition), $column );
   }

   public function getColumn( $columnName ) {
      return $this->_columns->getItem( $columnName );
   }

   public function getTableName() {
      return $this->_tableName;
   }
}