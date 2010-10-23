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

BDT_Loader::loadFile( array(
   './lib/BDT/Template/BDT_View_Variable',
   './lib/BDT/Collection/Components/BDT_View_Variable_Collection'
) );

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

   /**
    * Zawartość widoku
    *
    * @var      string
    * @access   protected
    */
   protected $_content = null;

   protected $_path = null;

   protected $_tpl = null;

   protected $_route = null;

   protected $_module = null;

   protected $_data = null;

   public function __construct( $path, $tpl, $route ) {
      $this->_path = $path;
      $this->_tpl = $tpl;
      $this->_route = $route;
      $this->_data = new BDT_View_Variable_Collection;
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

   /**
    * Ustawia wartość zmiennej
    *
    * @param   string    $name
    * @param   string    $value
    * @return  void
    */
   public function __set( $name, $value ) {
      $this->_data->addItem( new BDT_View_Variable( $name, $value ), $name );
   }

   /**
    * Zwraca wartość zmiennej
    *
    * @param   string    $name
    * @return  mixed     value
    */
   public function __get( $name ) {
      return $this->_data->getItem( $name );
   }

}