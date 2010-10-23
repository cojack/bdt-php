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

   protected $_debug;

   protected $_space = 'content';

   private $_tpl;

   private $_viewCheckSum;

   public function setDebug( BDT_Debugger $debug ) {
      $this->_debug = $debug;
      return $this;
   }

   public function setTpl( BDT_Template $tpl ) {
      $this->_tpl = $tpl;
      return $this;
   }

   public function setRoute( BDT_Route $route ) {
      $this->_route = $route;
      return $this;
   }

   public function handledEvent() {

      $action = $this->_route->action . 'Event';

      if ( method_exists( $this, $action ) ) {
         if ( is_callable( array($this, $action), true ) )
         {
            try {

               $this->_setView();

               if( !$this->_tpl->isCachedItem( $this->_space, $this->_viewCheckSum, $this->_view ) ) {

                  call_user_func( array( $this, $action ) );

                  $this->_tpl->renderItem( $this->_space, $this->_viewCheckSum, $this->_view );
               }

               $this->_tpl->appendView( $this->_view );

            } catch ( Exception $error ) {
               trigger_error( $error->getMessage() , E_USER_WARNING );
            }
         }
         else
            throw new Exception ( sprintf( dgettext( 'errors', 'Nie można wywołać akcji %s' ) , $action ) );

      } else
         throw new Exception ( sprintf( dgettext( 'errors', 'Brak obsługi akcji %s' ) , $action ) );

   }

   private function _setView() {
      $this->_view = $this->_tpl->getView();

      $this->_view->setModule( $this->_route->controller );

      $this->_viewCheckSum = sha1( $this->_route->interface . '/' . $this->_route->controller . '/' . $this->_route->action );
   }

   public function __destruct() {
      $this->_tpl->getHTML();
   }

}
