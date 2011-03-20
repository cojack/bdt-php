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

require_once('./lib/BDT/Collection/Components/BDT_SQL_Procedure_Collection.php');
require_once('./lib/BDT/Database/BDT_SQL_Procedure.php');

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
abstract class BDT_SQL_Mapper {

   protected $_query;

   private $_procedures;

   private $_validator;

   public function __construct() {
      $this->_query = new BDT_SQL_Query( $this );

      $this->_procedures = new BDT_SQL_Procedure_Collection;
      $this->_procedures->setLoadCallback( '_setProcedures', $this );
   }

   protected function _setProcedure( $schema, $name, $return = 'VOID' ) {
      $this->_procedures->addItem( new BDT_SQL_Procedure( $schema, $name, $return, clone( $this ) ), $name );
   }

   public function getProcedure( $procedureName ) {
      return $this->_procedures->getItem( $procedureName );
   }

   public function executeProcedure( BDT_SQL_Procedure $procedure, $autoInit = true ) {
      if($autoInit) {
         $procedure->initVariable( BDT_Route::getRequest() );
      }
      return $this->_query->callProcedure( $procedure );
   }


   public function isValid() {
      require_once('./lib/BDT/Database/Components/BDT_SQL_Validator.php');

      $this->_validator = new BDT_SQL_Validator( BDT_Route::getRequest() );
      $this->_setValidators();

      return BDT_Route::getRequest()->validConstraints();
   }

   protected function _setValidator( $argument, $validator, $options, $error ) {
      $this->_validator->setConstraint( $argument, $validator, $options, $error );
   }

   /**
    * Jeszcze nei wiem jak to zrobie ;)
    *
    */
   public function setConstraintFailure( BDT_Request $request ) {
      $failingRequest = $request->getOriginalRequestObjectFollowingConstraintFailure();
      $constraintFailures = $failingRequest->getConstraintFailures();
      $n = count( $constraintFailures );
      for( $i = 0; $i < $n; $i++ ) {
         $constraintFailure = &$constraintFailures[$i];
         $failingConstraint = $constraintFailure->getFailedConstraintObject();
         $column = $this->_procedures->getColumn( $constraintFailure->getParameterName() );
         $column->setError( $failingConstraint->getConstraintMessage() );
      }

   }

   abstract protected function _setValidators();

   abstract protected function _setProcedures();
}