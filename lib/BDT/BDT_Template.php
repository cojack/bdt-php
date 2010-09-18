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
 * BDT_Template klasa odpowiedzialna za parsowanie i wyświetlanie szablonów
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      22.09.2009
 * @package    BDT
 * @charset    utf8
 **/
class BDT_Template {

   /**
    * @var     obj
    * @access  private
    */
   private $_tpl;

   /**
    * @var     obj
    * @access  private
    */
   private $_views;

   /**
    * Lista miejsc wraz z ich zawartością.
    * @var array
    */
   private $_places = array();

   private $_html;

   private $_layout = 'index';

   /**
    *
    * @var     obj
    * @access  private
    */
   private $_paths;

   private $_debug;

   private $_route;

   /**
    * Konstruktor klasy
    *
    * Ładujemy Opt_Class, wczytujemy konfigurację, oraz ustawiamy opcję dla klasy
    *
    * @method __construct
    * @access public
    * @param  void
    * @return void
    */
   public function __construct( $debug, $route ) {

      $this->_debug = $debug;
      $this->_route = $route;

      BDT_Loader::loadFile( array(
         './lib/BDT/Collection/Components/BDT_View_Collection',
         './lib/BDT/Template/BDT_View',
         'ini' => './config/paths'
      ) );

      $this->_views = new BDT_View_Collection;

      $this->_paths = (object)BDT_Loader::getFiles( array(
         array(
            'name' => 'paths.ini',
            'delete' => FALSE
         )
      ) );
   }

   /**
    * @method layout
    * @access public
    * @return void
    *
    */
   public function getView() {
      try {
         return new BDT_View( DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $this->_route->interface . '/modules/' . $this->_route->controller . '/templates/' . $this->_route->action . 'Event.phtml', clone( $this ), $this->_route );
      } catch( Exception $error ) {
         trigger_error( $error->getMessage(), E_USER_WARNING );
      }
   }

   public function slot( $name, $method ) {

      if( class_exists( $name ) )
        $this->slots[] = call_user_func( array( $name, $method ) );
   }

   /**
   * Dodaje widok do podanego miejsca.
   *
   * @param string $place Nazwa miejsca
   * @param Opt_View $view Widok przypisywany do danego miejsca
   */
   public function appendView( BDT_View $view ) {
      $this->_views->addItem( $view, $view->getModule() );
   }

   public function isCachedView( $location, $path, BDT_View $view ) {

      BDT_Loader::loadFile( array(
         './lib/Cache/cache.class',
         './lib/Cache/fileCacheDriver.class'
      ) );

      try {
         $this->_cache = new Cache();
         $this->_cache->addDriver( 'file', new FileCacheDriver( $this->_paths->cacheDir ) );

         $content = $this->_cache->get( $location , $path, 1 );

         if( $content === FALSE )  #nie ma danych w cache
            return FALSE;

         $view -> setContent( $content );

         return TRUE;

      } catch ( CacheException $error ){
         trigger_error( 'Error cache: ' . $error->getMessage(), E_USER_WARNING );
      }
   }


   public function isCachedSlot( $location, $path ) {

      BDT_Loader::loadFile( array(
         './lib/Cache/cache.class',
         './lib/Cache/fileCacheDriver.class'
      ) );

      try {
         $this->_cache = new Cache();
         $this->_cache->addDriver( 'file', new FileCacheDriver( $this->_paths->cacheDir ) );

         $content = $this->_cache->get( $location , $path, 1 );

         if( $content === FALSE )  #nie ma danych w cache
            return FALSE;

         return $content;

      } catch ( CacheException $error ){
         trigger_error( 'Error cache: ' . $error->getMessage(), E_USER_WARNING );
      }
   }

   public function renderView( $location, $path, BDT_View $view ) {

      ob_start();

      include( $this->_paths->sourceDir . $view->getPath() );

      $content = ob_get_contents();

      ob_end_clean();

      $this->_cache->set( $location, $path, $content ); #ustawia dane do cache

      $view -> setContent( $content );

      return $this;
   }

   public function renderSlot( $location, $path, BDT_Slot $slot ) {

      ob_start();

      include( $this->_paths->sourceDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $this->_route->interface . $slot->getPath() );

      $content = ob_get_contents();

      ob_end_clean();

      $this->_cache->set( $location, $path, $content ); #ustawia dane do cache

      return $content;
   }

   /**
    * Metoda dopowiedzialna za wyświetlanie zparsowanego kodu html
    *
    * @method __destruct
    * @access public
    * @param  void
    * @return void
    *
    */
   public function getHTML() {
      $content = '';

      foreach( $this->_views as $key )
         $content .= $key->getContent();

      include( $this->_paths->sourceDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $this->_route->interface . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'index.phtml'  );

   }
}