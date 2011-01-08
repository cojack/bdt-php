<?php

require_once('./app/lib/session/session.php');

class BDT_Handler_Default extends BDT_Back_Event_Handler {
   /**
    * Obiekt sessji
    *
    * @var Session
    */
   private $_session;

   public function preEvent() {
      $this->_session = new Session;
      $this->_session->impress();

      if( !$this->_session->isLogged() ) {
         $this->_route->getRequest()->setHeader('location', '/admin/login');
      }
   }

   public function indexEvent(){
      $mapper = BDT_Database::getMapper( 'user' );
      return $this->_view->display();
   }
}