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
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      23.03.2010
 * @package    BDT
 * @subpackage web
 * @charset    utf8
 **/

require_once('./../lib/BDT/BDT_Bootstrap.php');

class Bootstrap extends BDT_Bootstrap {
   
   /**
    * Pseoud inicjalizator klasy
    *
    * @param   void
    * @access  protected
    * @return  void
    */
   public function __construct() {

      /**
       * Typ środowiska w którym się poruszamy
       *
       * @var  bool  TRUE     developerskie ( debugger ( wyświetlanie błędów ), brak logowania błędów )
       * @var  bool  FALSE    produkcyjne ( brak wyświetlania błędów, logowanie błędów do db )
       */
      $this->_environment = TRUE;

      parent::__construct();
   }
}

/**
 * Żeby się zbytnio nie bawić, wywołujemy klasę raz dwa trzy
 */
new Bootstrap;