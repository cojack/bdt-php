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
 * BDT_Bootstrap klasa odpowiedzialna za rozruch aplikacji
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.2
 * @since      22.09.2010
 * @package    BDT
 * @charset    utf8
 **/

require_Once( './../lib/BDT/BDT_Loader.php' );

abstract class BDT_Bootstrap {

   /**
    * Obiekt klasy BDT_Debbuger
    *
    * @access  private
    * @var     object   BDT_Debbuger
    */
   private $_debug;

   /**
    * Obiekt klasy BDT_Template
    *
    * @access  private
    * @var     object   BDT_Template
    */
   private $_tpl;

   /**
    * Obiekt klasy BDT_Template
    *
    * @access  private
    * @var     object   BDT_Route
    */
   private $_route;

   /**
    * Obiekt klasy BDT_Template
    *
    * @access  private
    * @var     object   BDT_Dispatcher
    */
   private $_dispatcher;

   /**
    * Zmienna środowiskowa
    *
    * @access   protected
    * @var      boolean
    */
   protected $_environment;

   /**
    * Konstruktor klasy
    * Wywołuje w kolejności wszystkie metody w sobie, tymsamym wywołuje mechanizm łancuchowy całej aplikacji
    *
    * @param    void
    * @access   public
    * @return   void
    */
   public function __construct() {
      BDT_Loader::initialize();
      $this->_initDebug()->_initRouter()->_initTemplate()->_initDispatcher();
   }

   /**
    * Inicjalizacja obsługi szablonów
    *
    * @param   void
    * @access  private
    * @return  this
    */
   private function _initTemplate() {

      BDT_Loader::loadFile( array( './lib/BDT/BDT_Template' ) );

      $this->_tpl = new BDT_Template( $this->_debug, $this->_route );

      $this->_debug->setTpl( $this->_tpl );

      return $this;
   }

   /**
    * Inicjalizacja routera w tym też request
    *
    * @param   void
    * @access  private
    * @return  this
    */
   private function _initRouter() {
      BDT_Loader::loadFile( array( './lib/BDT/BDT_Route' ) );

      $this->_route = new BDT_Route( $this->_debug, $this->_tpl );

      $this->_route->initialize();

      return $this;
   }

   /**
    * Inicjalizacja obsługi akcji
    *
    * @param   void
    * @access  private
    * @return  void
    */
   private function _initDispatcher() {
      BDT_Loader::loadFile( array( './lib/BDT/BDT_Dispatcher' ) );

      $this->_dispatcher = new BDT_Dispatcher( $this->_debug, $this->_tpl, $this->_route );

      $this->_dispatcher->handleEvent();
   }

   /**
    * Inicjalizacja obsługi debugera
    *
    * @param   void
    * @access  private
    * @return  this
    */
   private function _initDebug() {
      BDT_Loader::loadFile( array( './lib/BDT/BDT_Debugger' ) );

      $this->_debug = BDT_Debugger::initialize( $this->_environment );

      return $this;
   }

   /**
    * Niszczymy obiekty klas, zwalniamy pamięć, ważna jest kolejność destrukcji obiektów, przeciwna niż przy tworzeniu.
    *
    * @return  void
    */
   public function __destruct() {
      unset( $this->_dispatcher, $this->_tpl , $this->_route, $this->_debug );
      exit(0);
   }

}