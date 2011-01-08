<?php

class BDT_Handler_Login extends BDT_Back_Event_Handler {
   /**
    * Obiekt sessji
    *
    * @var Session
    */
   private $_session;

   public function preEvent() {
      $this->_session = new Session;
      $this->_session->impress();

      if( $this->_session->isLogged() ) {
         $this->_route->getRequest()->setHeader('location', '/admin');
      }
   }

   public function indexEvent(){
     $this->_session->login( 'cojack', 'tuniu1987' );
      return $this->_view->display();
   }

   public function loginEvent(){
   }

   public function logoutEvent(){
   }
}