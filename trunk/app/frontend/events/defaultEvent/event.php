<?php

class BDT_Handler_Default extends BDT_Front_Event_Handler {
   public function indexEvent(){
      $this->_view->name = 'world';
      $this->_view->lang = 'pl';
      return $this->_view->display();
   }
}