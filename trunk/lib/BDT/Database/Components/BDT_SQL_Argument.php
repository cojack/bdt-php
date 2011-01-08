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
 * BDT_SQL_Column klasa implementuje kolumnę w bazie danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_SQL_Argument {
   private $_name;

   private $_type;

   private $_error = NULL;

   private $_value = NULL;

   public function __construct( $name, $type, $cast ) {
      $this->_name = $name;
      $this->_type = $type;
      $this->_cast = $cast;
   }

   public function getName() {
      return $this->_name;
   }

   public function getType() {
      return $this->_type;
   }

   public function getCast() {
      return $this->_cast;
   }

   public function getError() {
      return $this->_error;
   }

   public function setError( $errorMessage ) {
      $this->_error = $errorMessage;
   }

   public function setValue( $value ) {
      $this->_value = $value;
   }

   public function getValue() {
      return $this->_value;
   }

}