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
class BDT_SQL_Column {
   private $_name;

   private $_type;

   private $_notNull = FALSE;

   private $_maxLength = NULL;

   private $_definitions = array();

   public function __construct( $name, $definition = array() ) {
      $this->_name = $name;
      $this->_definition = $definition;

      $this->_type = isset( $definition[ 'type' ] ) ? $definition[ 'type' ] : NULL;
      $this->_notNull = isset( $definition[ 'notNull' ] ) ? $definition[ 'notNull' ] : NULL;
      $this->_maxLength = isset( $definition[ 'maxLength' ] ) ? $definition[ 'maxLength' ] : NULL;
   }

   public function getType() {
      return $this->_type;
   }

}