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
 * BDT_SQL_Argument klasa implementuje argument przesyłany do funkcji
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_SQL_Argument {

   /**
    * Nazwa argumentu
    *
    * @var string
    */
   private $_name;

   /**
    * Typ argumentu
    *
    * @var string
    */
   private $_type;

   /**
    * Typ PDO::PARAM_*
    *
    * @var integer
    */
   private $_cast;

   /**
    * Komunikat błedu
    *
    * @var string
    */
   private $_error = NULL;

   /**
    * Wartość argumentu
    * 
    * @var mixed
    */
   private $_value = NULL;

   /**
    * Konstruktor klasy
    * Inicjalizuje własności klasy
    *
    * @param $name string Nazwa argumentu
    * @param $type string Typ argumentu
    * @param $cast integer Typ PDO::PARAM_*
    */
   public function __construct( $name, $type, $cast ) {
      $this->_name = $name;
      $this->_type = $type;
      $this->_cast = $cast;
   }

   /**
    * Funkcja zwraca nazwę argumentu procedury
    *
    * @param void
    * @return string
    */
   public function getName() {
      return $this->_name;
   }

   /**
    * Funkcja zwraca typ PL/SQL procedury np: INTEGER, VARCHAR[] itp
    *
    * @param void
    * @return string
    */
   public function getType() {
      return $this->_type;
   }

   /**
    * Funkcja zwraca typ PDO::PARAM_*
    *
    * @param void
    * @return string
    */
   public function getCast() {
      return $this->_cast;
   }

   /**
    * Metoda do wyciągania w szablonie i nie tylko błedu jeżeli walidacja tego pola
    * się nie powiodła
    *
    * @param void
    * @return string
    */
   public function getError() {
      return $this->_error;
   }

   /**
    * Metoda do ustawiania komunikatu błedu przy błędenj validacji
    *
    * @param string $errorMessage Komunikat błedu
    * @return void
    */
   public function setError( $errorMessage ) {
      $this->_error = $errorMessage;
   }

   /**
    * Metoda do ustawiania wartości argumentu
    *
    * @param mixed $value Wartość
    * @return void
    */
   public function setValue( $value ) {
      $this->_value = $value;
   }

   /**
    * Metoda zwraca wartość już odpowiednio spreparowaną dla danego serwera SQL
    *
    * @param string $engine Nazwa sterownika
    * @return string
    */
   public function getValue( $engine = NULL ) {
      $typeDst = './lib/BDT/Database/Components/Procedures/'.$engine.'/Argument/'.$engine.'_'.strtoupper($this->_type).'_Type.php';
      if( $engine && file_exists( $typeDst ) ) {
         require_once('./lib/BDT/Database/Components/Procedures/'.$engine.'/'.$engine.'_Argument.php');
         require_once($typeDst);
         $argument = $engine.'_'.strtoupper($this->_type).'_Type';
         $objArgument = new $argument( $this->_value );
         $objArgument->prepareType();
         return $objArgument->getPreparedValue();
      }
      return $this->_value;
   }

}