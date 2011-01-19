<?php

require_once('./app/lib/session/session.php');
require_once('./app/lib/user/user.php');

class BDT_Handler_Default extends BDT_Back_Event_Handler {
   /**
    * Obiekt sessji
    *
    * @var Session
    */
   protected $_session;

   public function preEvent() {
      parent::preEvent();

      $this->_user = new User;
      $this->_user->setSession( $this->_session );

      if( !$this->_user->isLogged() ) {
         $this->_request->setHeader('location', '/admin/login');
      }

      $this->_user->setIdUser( $this->_session->getIdUser() );
   }

   public function indexEvent(){
      return $this->_view->display();
   }
}