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
      return $this;
   }

   public function bindParam( $parameter, $variable, $dataType ) {
      $this->_statment->bindParam( $parameter, $variable, $dataType );
   }

   public function execute( $data = array() ) {
      try {
         $this->_statment->execute( $data );
      } catch ( PDOException $error ) {
         trigger_error( $error->getMessage(), E_USER_WARNING );
      }
      return $this;
   }

   public function toArray() {
      //var_dump($this->_statment);
      try {
         $result = $this->_statment->fetchAll();
      } catch ( PDOException $error ) {
         trigger_error( $error->getMessage(), E_USER_WARNING );
      }
      return $result;
   }

   protected function inputs( $inputs = array() ) {
      $params = '';
      $count = count( $inputs );
      for( $i = 0; $i < $count; $i ++ ) {
         $column = $this->_model->getTable()->getColumn( $inputs[ $i ] );

         $params .= ':' . $inputs[ $i ] . '::' . $column->getType();
         if( $i < $count - 1 )
            $params .= ', ';
      }

      return $params;
   }

   public function procedure( $procedure ) {
      try {
         $function = $this->_model->getProcedure( $procedure );
         $inputs = $this->inputs( $function->getArguments() );

         $this->prepare( 'SELECT * FROM "' . $procedure . '" ( ' . $inputs . ' ); ' );

         $this->_statment->bindParam( ':userLogin', $master, PDO::PARAM_STR );
         $this->_statment->bindParam( ':userPasswd', $pwd, PDO::PARAM_STR );
         $this->_statment->execute();
         //var_dump($this->_statment);
         return $this->_statment->fetch(PDO::FETCH_OBJ);
         /*foreach( $this->_model->getData() as $column ) {
            $this->bindParam( ':' );
         }*/
         //var_dump($this->_statment);
      } catch ( PDOException $error ) {
         trigger_error( $error->getMessage(), E_USER_WARNING );
      }
   }
}