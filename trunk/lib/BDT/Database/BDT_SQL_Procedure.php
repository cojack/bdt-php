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

require_once('./lib/BDT/Collection/BDT_Collection.php');
require_once('./lib/BDT/Collection/Components/BDT_SQL_Argument_Collection.php');
require_once('./lib/BDT/Database/Components/BDT_SQL_Argument.php');

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
class BDT_SQL_Procedure {

   private $_arguments;

   private $_procedureName;

   private $_procedureReturn;

   private $_mapper;

   public function __construct( $schema, $name, $return, $mapper ) {

      $this->_procedureSchema = $schema;
      $this->_procedureName = $name;
      $this->_procedureReturn = $return;

      $this->_mapper = $mapper;

      $this->_arguments = new BDT_SQL_Argument_Collection;
      $this->_arguments->setLoadCallback( 'set'. $this->_array_implode_prefix('', explode('_', $name)) .'Arguments', $mapper, $this );
   }

   public function initVariable( BDT_Request $request ) {
      foreach( $this->_arguments as $argument ) {
         $argument->setValue( $request->getParameterValue( $argument->getName() ) );
      }
   }

   public function setArgument( $name, $type, $cast ) {
      $this->_arguments->addItem( new BDT_SQL_Argument( $name, $type, $cast ), $name );
   }

   public function getArgument( $name ) {
      return $this->_arguments->getItem( $name );
   }

   public function getArguments() {
      return $this->_arguments;
   }

   public function getSchema() {
      return $this->_procedureSchema;
   }

   public function getName() {
      return $this->_procedureName;
   }

   public function getReturn() {
      return $this->_procedureReturn;
   }

   private function _array_implode_prefix($outer_glue, $arr){
      array_walk( $arr ,  array($this, "_prefix"));
      return implode($outer_glue, $arr);
   }

   private function _prefix(&$value, $key){
      $value = ucfirst($value);
   }

}