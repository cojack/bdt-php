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

require_once('./lib/BDT/Exception/BDT_Template_Exception.php');
require_once('./lib/BDT/Collection/BDT_Collection.php');
require_once('./lib/BDT/Template/BDT_View.php');

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

   private $_path;

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
   public function __construct($paths = array()) {
      $this->_twig = new Twig_Environment(new Twig_Loader_Filesystem( $paths, array(
         'cache' => './tmp/cache'
      )));

      //$this->_tpl->addExtension(new GettextTwig());
   }

   public function setView($path) {
      $this->_path = $path;
   }

   /**
    * @method layout
    * @access public
    * @return void
    *
    */
   public function getView() {
      return new BDT_View( $this->_twig->loadTemplate( $this->_path ) );
   }

   /*
   private function _slotHelper( $class, $action = 'render' ) {
      require_once('./app/' . $this->_route->getInterface() . '/slots/' . $class);

      if( class_exists( $class ) ) {
         $slot = new $class( './app/' . $this->_route->getInterface() . '/templates/' . $class . '.phtml', clone( $this ) );

         $space = 'slot';
         $checkSum = sha1( $this->_route->getInterface() . '/' . $class );

         if( !$this->isCachedItem( $space, $checkSum, $slot ) ) {

            call_user_func( array( $slot, $action ) );

            $this->renderItem( $space, $checkSum, $slot );
         }

         return $slot->getContent();

      }
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
   */
}