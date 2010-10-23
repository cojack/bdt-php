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

   private $_tpl;

   private $_debug;

   /**
    * Konstruktor klasy
    *
    * Metoda ustawia akcję
    *
    * @access  public
    * @param   string   $event   Nazwa modułu
    * @return  void
    */
   public function __construct( $debug, $tpl, $route ) {

      $this -> _debug = $debug;

      $this->_tpl = $tpl;

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
      try {
         BDT_Loader::loadFile( array(
            './lib/BDT/Event/BDT_Event_Handler',
            './lib/BDT/Event/BDT_Front_Event_Handler',
            './lib/BDT/Event/BDT_Back_Event_Handler',
            './app/' . $this->_route->interface . '/modules/' . $this->_route->controller . '/actions/' . $this->_route->controller
         ) );

         $name = 'BDT_Handler_' . ucfirst( $this->_route->controller );

         if( class_exists( $name ) ) {
            $handObj = new $name;
            $handObj->setDebug( $this->_debug )->setTpl( $this->_tpl )->setRoute( $this->_route );
            $handObj->handledEvent();
         } else { echo _( 'Nie ma takiego kontrolera' ) ;
            throw new Exception ( _( 'Nie ma takiego kontrolera' ) );
         }
      } catch ( Exception $error ) {
         trigger_error( $error, E_USER_WARNING );
      }
   }
}