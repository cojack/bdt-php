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

/**
 * @TODO    Błędy w postaci GETTEXT
 */
class BDT_Route {

   /**
    * Zmienna przechowywująca informacje o routerze.
    *
    * @access   private
    * @var      array
    */
   private $_url;

   private $_request;

   private $_mapper;

   public function __construct() {
      BDT_Loader::loadFile( array(
         './lib/Horde/Mapper',
         './lib/Horde/Exception',
         './lib/Horde/Route',
         './lib/Horde/Utils',
         'xml' => './config/routing',
         './lib/BDT/Request/BDT_Request'
      ) );

      $this->_request = new BDT_Request;

      $this->_mapper = new Horde_Routes_Mapper;
   }

   public function initialize() {
      $this->_checkUrl()->_setRoute();
   }

   private function _setRoute() {
      $routeXml = BDT_Loader::getFiles( array(
         array(
            'name' => 'routing.xml',
            'delete' => TRUE
         )
      ) );

      $n = count( $routeXml[0]->define );

      for( $i = 0; $i < $n; $i++) {
         $this->_mapper->connect( (string)$routeXml[0]->define[$i]->name, (string)$routeXml[0]->define[$i]->url, (array)$routeXml[0]->define[$i]->parametrs );
      }

      $controllers = (array)$routeXml[0]->controllers;

      $this->_mapper->createRegs( $controllers['controller'] );

      $this->_route = $this->_mapper->match( $this->_url );
   }

   private function _checkUrl() {
      $url = $this->_request->getParameterValue( 'q' );

      $this->_url = '/';

      if( isset( $url ) )
         $this->_url .= mb_strtolower( $url, 'UTF-8' );

      $this->_url = strip_tags( $this->_url );

      return $this;
   }

   public function getRequest() {
      return $this->_request;
   }

   public function __get( $name ) {
      if( array_key_exists( $name, $this->_route ) )
         return $this->_route[$name];
      else if( $name == 'interface' )
         return 'frontend';
   }
}