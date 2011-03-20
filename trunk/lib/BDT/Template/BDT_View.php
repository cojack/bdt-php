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

require_once('./lib/BDT/Template/BDT_View_Variable.php');
require_once('./lib/BDT/Collection/Components/BDT_View_Variable_Collection.php');

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

   protected $_template = null;

   protected $_data = null;

   protected $_elemnts = array();

   public function __construct( $template ) {
      $this->_template = $template;
      $this->_data = new BDT_View_Variable_Collection;

      $this->_elemnts = array(
         'view' => $this
      );

   }

   public function display() {
      $this->_template->display( $this->_elemnts );
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

   public function addViewElement( $elementName, $element ) {
      $this->_elemnts[$elementName] = $element;
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

   public function __isset( $name ) {
      return $this->_data->exists( $name );
   }

}