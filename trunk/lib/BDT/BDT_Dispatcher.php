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
 * Dispatcher - zarządzanie akcjami
 *
 * @author  Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link    http://aichra.pl
 * @version 0.1
 * @since   14.04.2010
 * @package BDT
 * @charset utf8
 **/
class BDT_Dispatcher {

   private $_route;

   /**
    * Konstruktor klasy
    *
    * Metoda ustawia akcję
    *
    * @access  public
    * @param   string   $event   Nazwa modułu
    * @return  void
    */
   public function __construct( $route ) {

      $this->_route = $route;
   }

   /**
    * Metoda ładuje akcję
    *
    * @method  handleEvent
    * @access  public
    * @param   void
    * @return  void
    */
   public function handleEvent() {
      require_once('./lib/BDT/Event/BDT_Event_Handler.php');
      require_once('./lib/BDT/Event/BDT_'.ucfirst(substr($this->_route->getInterface(), 0, -3)).'_Event_Handler.php');
      require_once('./app/' . $this->_route->getInterface() . '/events/' . $this->_route->getController() . 'Event/event.php');

      $eventName = 'BDT_Handler_' . ucfirst( $this->_route->getController() );

      if( class_exists( $eventName ) ) {
         $event = new $eventName;
         $event->setRoute( $this->_route );
         $event->handledEvent();
      } else {
         throw new Exception ( sprintf( dgettext( 'errors', 'Nie ma takiego kontrolera: %s' ) , $eventName ) );
      }
   }
}