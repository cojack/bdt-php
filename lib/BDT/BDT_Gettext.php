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
 * BDT_Gettext klasa odpowiedzialna za gettext
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      29.09.2010
 * @package    BDT
 * @charset    utf8
 **/
final class BDT_Gettext {

   private $_domain = 'messages';
   private $_lang;
   private $_language = array('pl' => 'pl_PL');
   private $_paths = array ();

   /**
    * Konstruktor klasy
    *
    * Ustawia zmienne środowiskowe
    *
    * @param    $lang
    * @access   public
    * @return   void
    */
   public function __construct( $lang ) {
      $this->_lang = $lang;

      putenv( 'LANGUAGE=' . $this->_language[ $this->_lang ] );

      setlocale(
         LC_MESSAGES,
         $this->_language[ $this->_lang ] . ".utf8",
         $this->_language[ $this->_lang ] . ".UTF8",
         $this->_language[ $this->_lang ] . ".utf-8",
         $this->_language[ $this->_lang ] . ".UTF-8",
         $this->_language[ $this->_lang ]
      );

   }

   /**
    * Metoda ustawia domene dla gettext
    * oraz ustawia domyślną domene
    *
    * @param    void
    * @access   public
    * @return   Object  BDT_Gettext
    */
   public function initDomain() {
      bindtextdomain( $this->_domain, './data/language' );
      bind_textdomain_codeset( $this->_domain, 'UTF-8' );
      textdomain( $this->_domain );
      return $this;
   }

   /**
    * Metoda dodaje dodatkową domenę
    *
    * @param    void
    * @access   public
    * @return   void
    */
   public function bindDomain() {
      bindtextdomain( $this->_domain, './data/language' );
   }

   public function setDomain( $domain ) {
      $this->_domain = $domain;
      return $this;
   }
}