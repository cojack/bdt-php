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
   private $_views;

   private $_layout = 'index';

   /**
    * Kolekcja obiektów Css
    *
    * @var      Object      BDT_View_Css
    * @access   private
    */
   private $_css;

   /**
    * Kolekcja obiektów js
    *
    * @var      Object      BDT_View_Js
    * @access   private
    */
   private $_js;

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
         './lib/BDT/Exception/BDT_Template_Exception',
         './lib/BDT/Collection/Components/BDT_View_Collection',
         './lib/BDT/Collection/Components/BDT_Helper_Css_Collection',
         './lib/BDT/Collection/Components/BDT_Helper_Js_Collection',
         './lib/BDT/Template/BDT_View',
         './lib/BDT/Template/BDT_Helper',
         './lib/BDT/Template/Helpers/BDT_Helper_Slot',
         './lib/BDT/Template/Helpers/BDT_Helper_Css',
         './lib/BDT/Template/Helpers/BDT_Helper_Js',
      ) );

      $this->_views = new BDT_View_Collection;

      $this->_css = new BDT_Helper_Css_Collection;
      $this->_css->setLoadCallback( 'setCss', $this );

      $this->_js = new BDT_Helper_Js_Collection;
      $this->_js->setLoadCallback( 'setJs', $this );

      $this->_paths = (object)BDT_Loader::getFiles( array(
         array(
            'name' => 'paths.ini',
            'delete' => TRUE
         )
      ) );
   }

   public function setCss() {
      $this->getHelper( 'css' )->setPath( 'reset' );
      if( $this->_route->interface == 'frontend' ) {
         $this->getHelper( 'css' )->setPath( 'main' );
         $this->getHelper( 'css' )->setPath( 'style' );
      } else {
         $this->getHelper( 'css' )->setPath( '../js/ext/resources/css/ext-all' );
      }
   }

   public function setJs() {
      if( $this->_route->interface == 'frontend' ) {
         $this->getHelper( 'js' )->setPath( 'bdt' );
      } else {
         $this->getHelper( 'js' )->setPath( 'ext/adapter/ext/ext-base' );
         $this->getHelper( 'js' )->setPath( 'ext/ext-all-debug' );
      }
   }

   /**
    * @method layout
    * @access public
    * @return void
    *
    */
   public function getView() {
      return new BDT_View( '/app/' . $this->_route->interface . '/modules/' . $this->_route->controller . '/templates/' . $this->_route->action . 'Event.phtml', clone( $this ), $this->_route );
   }

   private function _slotHelper( $class, $action = 'render' ) {
      BDT_Loader::loadFile( array(
         './app/' . $this->_route->interface . '/slots/' . $class,
      ) );

      if( class_exists( $class ) ) {
         $slot = new $class( '/app/' . $this->_route->interface . '/templates/' . $class . '.phtml', clone( $this ) );

         $space = 'slot';
         $checkSum = sha1( $this->_route->interface . '/' . $class );

         if( !$this->isCachedItem( $space, $checkSum, $slot ) ) {

            call_user_func( array( $slot, $action ) );

            $this->renderItem( $space, $checkSum, $slot );
         }

         return $slot->getContent();

      }
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

   public function getHelper( $helperName, $slotProperties = array() ) {
      if( $helperName == 'slot' ) {
         return $this->_slotHelper( $slotProperties[ 0 ], $slotProperties[ 1 ] );
      }

      $helper = 'BDT_Helper_' . ucfirst( $helperName );

      BDT_Loader::loadFile( array( './lib/BDT/Template/Helpers/' . $helper  ) );

      if( !class_exists( $helper ) ) {
         throw new BDT_Template_Exception( sprintf( dgettext( 'errors', 'Brak helpera o nazwie %s' ) , htmlspecialchars( $helper, ENT_QUOTES, 'UTF-8' ) ) );
      }

      $objHelper = new $helper;

      if ( $objHelper instanceof BDT_Helper_Css ) {
         $this->_css->addItem( $objHelper );
      } else if ( $objHelper instanceof BDT_Helper_Js ) {
         $this->_js->addItem( $objHelper );
      } else if ( $objHelper instanceof BDT_Helper_Url ) {
         $objHelper->setUtils( $this->_route->getUtils() );
      }

      return $objHelper;
   }

   public function isCachedItem( $location, $path, BDT_View $view ) {

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

         $view->setContent( $content );

         return TRUE;

      } catch ( CacheException $error ){
         trigger_error( 'Error cache: ' . $error->getMessage(), E_USER_WARNING );
      }
   }

   public function renderItem( $location, $path, BDT_View $view ) {
      ob_start();

      include( $this->_paths->sourceDir . $view->getPath() );

      $content = ob_get_contents();

      ob_end_clean();

      $this->_cache->set( $location, $path, $content ); #ustawia dane do cache

      $view -> setContent( $content );

      return $this;
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

      include( $this->_paths->sourceDir . '/app/' . $this->_route->interface . '/templates/index.phtml'  );
   }

   public function getAJAX( $typeRespond ) {
      $content = '';

      foreach( $this->_views as $key )
         $content .= $key->getContent();

      if( $typeRespond == 'json' ) {
         echo json_encode( $content );
      } else {
         echo $content;
      }
   }
}