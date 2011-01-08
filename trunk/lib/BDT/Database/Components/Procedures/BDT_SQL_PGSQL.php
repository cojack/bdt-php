<?php

class BDT_SQL_PGSQL implements BDT_SQL_PL {

   private $_procedure;

   private $_sql = array();

   public function __construct(BDT_SQL_Procedure $procedure) {
      $this->_procedure = $procedure;
   }

   private function _prepareQuery() {
      foreach($this->_procedure->getArguments() as $argument) {
         $this->_sql[] = ' :' . $argument->getName() .'::' . $argument->getType();
      }
   }

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

      return $sth->execute();
   }

   public function invoke() {
      $this->_prepareQuery();
      $this->_executeQuery();
   }

}