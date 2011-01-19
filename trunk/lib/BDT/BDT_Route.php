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
 * BDT_Route klasa odpowiedzialna za routing
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      29.03.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_Route {

   /**
    * Zmienna przechowywująca informacje o routerze.
    *
    * @access   private
    * @var      array
    */
   private $_url;

   /**
    * Obiekt klasy Request
    *
    * @access   private
    * @var      Object      BDT_Request
    */
   private static $_request;

   /**
    * Obiekt klasy BDT_Language, btw niefortunna nazwa klasy...
    *
    * @access   private
    * @var      Object      BDT_Language
    */
   private $_gettext;

   /**
    * Obiekt klasy Horde_Routes_Mapper
    *
    * @access   private
    * @var      Object      Horde_Routes_Mapper
    */
   private $_mapper;

   /**
    * Konstruktor klasy
    *
    * Wczytuje odpowiednie pliki, tworzy obiekty klas potrzebnych do dalszego działania
    *
    * @param    void
    * @access   public
    * @return   void
    */
   public function __construct() {
      require_once('./lib/Horde/Mapper.php');
      require_once('./lib/Horde/Exception.php');
      require_once('./lib/Horde/Route.php');
      require_once('./lib/Horde/Utils.php');
      require_once('./lib/BDT/Request/BDT_Request.php');
      require_once('./lib/BDT/BDT_Gettext.php');

      self::$_request = new BDT_Request;
      self::$_request->setRedirectOnConstraintFailure( TRUE );
   }

   /**
    * Inicjalizacja elementów klasy
    *
    * @param    void
    * @access   public
    * @return   void
    */
   public function initialize() {
      $this->_checkUrl()->_setRoute();

      $lang = $this->lang; // ale lipa w tej linijce, nie mam pomysłu jak to inaczej zrobić

      if( isset( $lang ) ) {
         $this->_setLanguage();
      }
   }

   /**
    * Ustawienia języka aplikacji (?!)
    *
    * @param    void
    * @access   pirvate
    * @return   void
    */
   private function _setLanguage() {
      $this->_gettext = new BDT_Gettext( $this->lang );
      $this->_gettext->setDomain('bdt')->initDomain();
   }

   /**
    * Ustawiamy routing
    *
    * Przepraszam że używam zmiennych globalnych $routing oraz $controllers ale nie miałem lepszego pomysłu na
    * optymalne parsowanie danych do tablicy php.
    *
    * @param    void
    * @access   private
    * @return   self        BDT_Route
    */
   private function _setRoute() {
      $this->_mapper = include_once('./config/routing.php');
      $this->_route = $this->_mapper->match( $this->_url );

      if( $this->_route == NULL ) {
         $this->_route['controller'] = 'error';
         $this->_route['action'] = 'e404';
      }

      $routing = NULL;
      $controllers = NULL;

      return $this;
   }

   /**
    * Metoda pobiera dane z adresu oraz nimi w pewnym stopniu zarządza
    *
    * @param    void
    * @access   private
    * @return   self        BDT_Route
    */
   private function _checkUrl() {
      $url = self::$_request->getParameterValue( 'q' );

      $this->_url = '/';

      if( isset( $url ) )
         $this->_url .= mb_strtolower( $url, 'UTF-8' );

      $this->_url = strip_tags( $this->_url );
      return $this;
   }

   /**
    * Metoda zwraca obiekt BDT_Request
    *
    * @param    void
    * @access   public
    * @return   Object      BDT_Request
    */
   public function getRequest() {
      return self::$_request;
   }

   /**
    * Metoda zwraca obiekt Horde_Routes_Utils
    *
    * @param    void
    * @access   public
    * @return   Object      Horde_Routes_Utils
    */
   public function getUtils() {
      return $this->_mapper->utils;
   }

   /**
    * Metoda zwraca obiekt BDT_Language
    *
    * @param    void
    * @access   public
    * @return   Object      BDT_Language
    */
   public function getGettext() {
      return $this->_gettext;
   }

   public function getController() {
      return $this->_route[ 'controller' ];
   }

   public function getInterface() {
      return $this->_route[ 'interface' ];
   }

   public function getAction() {
      return $this->_route[ 'action' ];
   }

   /**
    * Magiczna metoda dostępowa
    *
    * Średnio podoba mi się jej implementacja
    *
    * @param    void
    * @access   public
    * @return   mixed       string, else NULL
    */
   public function __get( $name ) {
      if( array_key_exists( $name, $this->_route ) )
         return $this->_route[ $name ];
      else if( $name == 'interface' )
         return 'frontend';
      else
         return NULL;
   }
}