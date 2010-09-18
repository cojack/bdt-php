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

   protected function inputs( $arguments = array() ) {
      $params = '';
      $count = count( $arguments );
      for( $i = 0; $i < $count; $i ++ ) {
         $column = $this->_model->getTable()->getColumn( $arguments[ $i ] );

         $params .= ':' . $arguments[ $i ] . '::' . $column->getType();
         $values[ $i ][ 'value' ] = $column->getValue();
         $values[ $i ][ 'name' ] = $arguments[ $i ];

         if( $i < $count - 1 )
            $params .= ', ';
      }

      return array( $params, $values );
   }

   public function callProcedure( $procedure ) {
      $function = $this->_model->getProcedure( $procedure );
      list( $inputs, $values ) = $this->inputs( $function->getArguments() );

      $this->prepare( 'SELECT * FROM "' . $procedure . '" ( ' . $inputs . ' ); ' );

      foreach( $values as $value ) {
         $this->_statment->bindParam( ':' . $value[ 'name' ], $value[ 'value' ], $value[ 'cast' ] );
      }

      $this->_statment->execute();

      return $this->_statment->fetch(PDO::FETCH_OBJ);
   }
}