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
 * BDT_View klasa odpowiedzialna za warstwe prezentacji modułów
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_View {

   private $_content;

   private $_path;

   private $_tpl;

   private $_route;

   private $_module;

   private $_data = array();

   public function __construct( $path, $tpl, $route ) {
      $this->_path = $path;
      $this->_tpl = $tpl;
      $this->_route = $route;
   }

   public function getPath() {
      return $this->_path;
   }

   public function setContent( $content ) {
      $this->_content = $content;
   }

   public function getContent() {
      return $this->_content;
   }

   public function setModule( $module ) {
      $this->_module = $module;
   }

   public function getModule() {
      return $this->_module;
   }

   public function slot( $class, $action = 'render' ) {
      BDT_Loader::loadFile( array(
         './lib/BDT/Template/Helpers/BDT_Slot',
         './app/' . $this->_route->interface . '/slots/' . $class,
      ) );

      if( class_exists( $class ) ) {
         $slot = new $class( $this->_tpl, $this->_route );
         if( $action != 'render' && method_exists( $class, $action ) ) {
            $slot->{$action}();
         }
         $slot->render();
      }

      return $slot->getContent();
   }

   /**
    * Ustawia wartość zmiennej
    *
    * @param   string    $name
    * @param   string    $value
    * @return  void
    */
   public function __set( $name, $value ) {
      $this->_data[$name] = $value;
   }

   /**
    * Zwraca wartość zmiennej
    *
    * @param   string    $name
    * @return  mixed     value
    */
   public function __get( $name ) {
      if( array_key_exists( $name, $this->_data ) )
         return $this->_data[$name];
   }

}