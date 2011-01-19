<?php

require_once('./app/lib/session/session.php');
require_once('./app/lib/user/user.php');

class BDT_Handler_Login extends BDT_Back_Event_Handler {
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

      if( $this->_user->isLogged() ) {
         $this->_request->setHeader('location', '/admin');
      }
   }

   public function indexEvent(){
      return $this->_view->display();
   }

   public function loginEvent(){
      $result = $this->_user->login( $this->_request->getParameterValue('login-user'), $this->_request->getParameterValue('login-pwd') );
      if($result) {
         $this->_view->response = array(
            'success' => TRUE,
            'respond' => 'Zalogowany pomyślnie'
         );
      } else {
         $this->_view->response = array(
            'success' => FALSE,
            'errors' => array(
               'reason' =>'Błędny login lub hasło'
            )
         );
      }

      $this->_request->setHeader('json');
      return $this->_view->display();
   }

   public function logoutEvent(){
   }
}