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
 * BDT_Slot klasa odpowiedzialna za warstwe prezentacji slotów
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_View_Variable {
   private $_name;

   private $_value;

   private $_error = NULL;

   public function __construct( $name, $value ) {
      $this->_name = $name;
      $this->_value = $value;
   }

   public function __toString() {
      return $this->_value;
   }

   public function getValue() {
      return $this->_value;
   }

   public function setError( $error ) {
      $this->_error = $error;
   }

   public function getError() {
      return $this->_error;
   }
}