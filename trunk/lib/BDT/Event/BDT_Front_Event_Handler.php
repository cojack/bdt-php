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
 * BDT_Front_Event_Handler klasa odpowiedzialna za obsługę akcji
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
abstract class BDT_Front_Event_Handler implements BDT_Event_Handler {

   protected $_route;

   protected $_view;

   private $_tpl;

   public function setRoute( BDT_Route $route ) {
      $this->_route = $route;
      return $this;
   }

   public function preEvent() {} // pusta, najlepiej taką zostawić

   public function handledEvent() {
      $action = $this->_route->getAction() . 'Event';

      if ( method_exists( $this, $action ) ) {
         if ( is_callable( array($this, $action), true ) ) {
            $this->_setView();
            $this->preEvent();
            call_user_func( array( $this, $action ) );
            $this->postEvent();
         }
         else
            throw new Exception ( sprintf( dgettext( 'errors', 'Nie można wywołać akcji %s' ) , $action ) );
      } else
         throw new Exception ( sprintf( dgettext( 'errors', 'Brak obsługi akcji %s' ) , $action ) );
   }

   public function postEvent() {} // pusta, najlepiej taką zostawić

   private function _setView() {
      require_once('./lib/BDT/BDT_Template.php');

      $this->_tpl = new BDT_Template( array('./', './app/' . $this->_route->getInterface() . '/templates') );
      $this->_tpl->setView('./app/' . $this->_route->getInterface() . '/events/' . $this->_route->getController() . 'Event/templates/' . $this->_route->getAction() . 'Event.tpl');
      $this->_view = $this->_tpl->getView();
   }

}
