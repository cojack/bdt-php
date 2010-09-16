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
class BDT_Slot {

   private $_space = 'slot';

   private $_tpl;

   private $_path;

   protected $_slotName;

   private $_html;

   public function __construct( $tpl, $path ) {
      $this->_tpl = $tpl;
      $this->_path = $path;
   }

   public function getPath() {
      return '/templates/' . $this->_slotName . '.phtml';
   }

   public function render() {
      if( $this->_html = $this->_tpl->isCachedSlot( $this->_space, sha1( $this->_slotName ) ) ) {
      }
      else
         $this->_html = $this->_tpl->renderSlot( $this->_space, sha1( $this->_slotName ), clone ($this) );

      //var_dump($content,$content2 );
   }

   public function getContent() {
      return $this->_html;
   }
}