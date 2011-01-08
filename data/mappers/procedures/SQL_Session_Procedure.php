<?php

class SQL_Session_Procedure extends BDT_SQL_Mapper {

   public function _setProcedures() {
      parent::_setProcedure( 'session', 'insert_session', 'id_session' );
      parent::_setProcedure( 'session', 'delete_session', FALSE );
      parent::_setProcedure( 'session', 'clean_session', FALSE );
      parent::_setProcedure( 'session', 'update_session', FALSE );
      parent::_setProcedure( 'session', 'login', FALSE );
      parent::_setProcedure( 'session', 'logout', FALSE );
   }

   public function setInsertSessionArguments(BDT_SQL_Procedure $procedure) {
      $procedure->setArgument('id_ascii', 'VARCHAR', PDO::PARAM_STR);
      $procedure->setArgument('user_agent', 'VARCHAR', PDO::PARAM_STR);
   }

   public function setDeleteSessionArguments(BDT_SQL_Procedure $procedure) {
      $procedure->setArgument('id_ascii','VARCHAR', PDO::PARAM_STR);
   }

   public function setCleanSessionArguments(BDT_SQL_Procedure $procedure) {
      $procedure->setArgument('id_ascii','VARCHAR', PDO::PARAM_STR);
      $procedure->setArgument('lifespan','INTEGER', PDO::PARAM_INT);
   }

   public function setUpdateSessionArguments(BDT_SQL_Procedure $procedure) {
      $procedure->setArgument('id_session','INTEGER', PDO::PARAM_INT);
   }

   public function setLoginArguments(BDT_SQL_Procedure $procedure) {
      $procedure->setArgument('id_user','INTEGER', PDO::PARAM_INT);
      $procedure->setArgument('id_session','INTEGER', PDO::PARAM_INT);
   }

   public function setLogoutArguments(BDT_SQL_Procedure $procedure) {
      $procedure->setArgument('id_session','INTEGER', PDO::PARAM_INT);
   }

   public function _setValidators() {
      parent::_setValidator();
   }

}