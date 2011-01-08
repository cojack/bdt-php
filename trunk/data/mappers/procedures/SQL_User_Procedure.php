<?php

class SQL_User_Procedure extends BDT_SQL_Mapper {

   public function _setProcedures() {
      parent::_setProcedure('user', 'user_login', 'id_session' );
   }

   public function setUserLoginArguments() {
      
   }

   public function _setValidators() {
      parent::_setValidator();
   }

}