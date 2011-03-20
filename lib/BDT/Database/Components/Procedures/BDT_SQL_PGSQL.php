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
 *
 *
 */
class BDT_SQL_PGSQL implements BDT_SQL_PL {

   /**
    * Obiekt procedury
    *
    * @var BDT_SQL_Procedure
    */
   private $_procedure;

   /**
    *
    *
    */
   private $_sql = array();

   /**
    *
    *
    */
   public function __construct(BDT_SQL_Procedure $procedure) {
      $this->_procedure = $procedure;
   }

   /**
    *
    *
    */
   private function _prepareQuery() {
      foreach($this->_procedure->getArguments() as $argument) {
         $this->_sql[] = ' :' . $argument->getName() .'::' . $argument->getType();
      }
   }

   /**
    *
    *
    */
   private function _executeQuery() {
      $sql = '
         SELECT 
            * 
         FROM 
            "'.$this->_procedure->getSchema().'"."'.$this->_procedure->getName().'"
         (
            '.implode(',', $this->_sql).'
         );';

      $conn = BDT_SQL_Connect::connect('write');
      $sth = $conn->prepare($sql);

      foreach($this->_procedure->getArguments() as $argument) {
         $sth->bindParam(':'.$argument->getName(), $argument->getValue(), $argument->getCast());
      }

      $rs = $sth->execute();

      if($return = $this->_procedure->getReturn()) {
         $result = $sth->fetch(PDO::FETCH_LAZY);
         $this->_procedure->setArgument( $return, null, null );
         $this->_procedure->getArgument( $return )->setValue( $result->{$return} );
      }

      return $rs;
   }

   /**
    *
    *
    */
   public function invoke() {
      $this->_prepareQuery();
      return $this->_executeQuery();
   }

}