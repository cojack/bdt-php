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

BDT_Loader::loadFile( array(
   './lib/BDT/Collection/Components/BDT_SQL_Procedure_Collection',
   './lib/BDT/Database/Components/BDT_SQL_Procedure'
) );

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
abstract class BDT_SQL_Model {

   private $_query;

   private $_procedures;

   private $_table;

   private $_validator;

   public function __construct() {
      $this->_query = new BDT_SQL_Query( $this );

      $this->_table = new BDT_SQL_Table( $this );

      $this->_procedures = new BDT_SQL_Procedure_Collection;
      $this->_procedures->setLoadCallback( 'setProcedures', $this );
   }

   protected function _setTableName( $name ) {
      $this->_table->setTableName( $name );
   }

   protected function _addColumn( $column, $type, $isArray = FALSE ) {
      $this->_table->addColumn( $column, $type, $isArray );
   }

   protected function _addProcedure( $name, $arguments, $return = 'VOID' ) {
      $this->_procedures->addItem( new BDT_SQL_Procedure( $name, $arguments, $return ), $name );
   }

   protected function _addValidator( $column, $validator, $options, $error ) {
      $this->_validator->setConstraint( $column, $validator, $options, $error );
   }

   public function isValid( $request ) {
      BDT_Loader::loadFile( array(
         './lib/BDT/Database/Components/BDT_SQL_Validator',
      ) );

      $this->_validator = new BDT_SQL_Validator( $this, $request );
      $this->setValidators();

      return $request->validConstraints();
   }

   public function setConstraintFailure( $request ) {
      $failingRequest = $request->getOriginalRequestObjectFollowingConstraintFailure();
      $constraintFailures = $failingRequest->getConstraintFailures();
      $n = count( $constraintFailures );
      for( $i = 0; $i < $n; $i++ ) {
         $constraintFailure = &$constraintFailures[$i];
         $failingConstraint = $constraintFailure->getFailedConstraintObject();
         $column = $this->_table->getColumn( $constraintFailure->getParameterName() );
         $column->setError( $failingConstraint->getConstraintMessage() );
      }

   }

   public function execProcedure( $procedure, BDT_Request $request ) {
      $this->_table->initVariable( $request );
      $this->_query->callProcedure( $procedure );
   }

   public function getProcedure( $procedureName ) {
      return $this->_procedures->getItem( $procedureName );
   }

   public function setQuery( $sql ) {
      return $this->_query->prepare( $sql );
   }

   public function getTable() {
      return $this->_table;
   }
}