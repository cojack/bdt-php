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
   public function __construct( $paths = array() ) {
      $this->_twig = new Twig_Environment(new Twig_Loader_Filesystem( $paths ), array(
         'cache' => './tmp/cache',
         'auto_reload' => BDT_Debugger::getDebug(),
      ));

      //$this->_tpl->addExtension(new GettextTwig());
   }

   public function setPath( $path ) {
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
}