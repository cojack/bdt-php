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

require_once('./lib/BDT/Collection/BDT_Collection_Iterator.php');
require_once('./lib/BDT/Exception/BDT_Collection_Exception.php');

/**
 * BDT_Collection klasa kolekcji (implementacja mapy)
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      11.09.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_Collection implements IteratorAggregate {

   /**
    * Obiekty przechowywane w klasie
    *
    * @param    array
    * @access   private
    */
   private $_members = array();

   /**
    * Nazwa funkcji do wywołania przy utworzeniu/wywołaniu kolekcji
    *
    * @param    string
    * @access   private
    */
   protected $_onload;

   /**
    * Wartość logiczna, stwierdzająca czy funkcja do załadowaniu, już została załadowana
    *
    * @param    boolean
    * @access   private
    */
   protected $_isLoaded = false;

   /**
    * Metoda dodaje obiekty do kolekcji, na podstawie unikatowego klucza
    *
    * @param    object      Obiekt kolekcji
    * @param    string      nazwa klucza, unikatowa
    * @access   public
    * @throw    BDT_Collection_Exception
    * @return   void
    */
   public function addItem( $obj, $key = NULL ) {
      $this->_checkCallback();

      if( $key ) {
         if( isset( $this->_members[ $key ] ) )
            throw new BDT_Collection_Exception( sprintf( dgettext( 'errors', 'Klucz %s jest już zajęty' ), htmlspecialchars( $key, ENT_QUOTES, 'UTF-8' ) ) );
         else
            $this->_members[ $key ] = $obj;
      } else
         $this->_members[] = $obj;
   }

   /**
    * Metoda usuwa z kolekcji obiekt na podstawie klucza
    *
    * @param    string      nazwa klucza
    * @access   public
    * @throw    BDT_Collection_Exception
    * @return   void
    */
   public function removeItem( $key ) {
      $this->_checkCallback();

      if( isset( $this->_members[ $key ] ) )
         unset( $this->_members[ $key ] );
      else
         throw new BDT_Collection_Exception( sprintf( dgettext( 'errors', 'Błędny klucz %s' ), htmlspecialchars( $key, ENT_QUOTES, 'UTF-8' ) ) );
   }

   /**
    * Metoda zwraca obiekt na podstawie klucza
    *
    * @param    string      nazwa klucza kolekcji
    * @access   public
    * @throw    BDT_Collection_Exception
    * @return   object      Obiekt kolekcji
    */
   public function getItem( $key ) {
      $this->_checkCallback();

      if( isset( $this->_members[ $key ] ) )
         return $this->_members[ $key ];
      else
         throw new BDT_Collection_Exception( sprintf( dgettext( 'errors', 'Błędny klucz %s' ), htmlspecialchars( $key, ENT_QUOTES, 'UTF-8' ) ) );
   }

   /**
    * Metoda zwraca wszystkie klucze kolekcji
    *
    * @param    void
    * @access   public
    * @return   array
    */
   public function keys() {
      $this->_checkCallback();

      return array_keys( $this->_members );
   }

   /**
    * Metoda zwraca rozmiar kolekcji
    *
    * @param    void
    * @access   public
    * @return   int
    */
   public function length() {
      $this->_checkCallback();

      return count( $this->_members );
   }

   /**
    * Metoda sprawdza na podstawie klucza czy dana kolekcja występuje
    *
    * @param    string      nazwa klucza kolekcji
    * @access   public
    * @return   boolean     true jeżeli istnieje, false w innym wypadku
    */
   public function exists( $key ) {
      $this->_checkCallback();

      return isset( $this->_members[ $key ] );
   }

   /**
    * A to jest magiczna metoda, nie wywoływać sama się wywołuje przy próbie użycia foreach na elementach kolekcji
    *
    */
   public function getIterator() {
      $this->_checkCallback();

      return new BDT_Collection_Iterator( clone $this );
   }

   public function setLoadCallback( $functionName, $objOrClass = NULL ) {
      if( $objOrClass )
         $callback = array( $objOrClass, $functionName );
      else
         $callback = $functionName;

      if( !is_callable( $callback, false, $callableName ) ) {
         throw new BDT_Collection_Exception( sprintf( dgettext( 'errors', 'Funkcja zwrotna %s nieprawidłowa!' ), htmlspecialchars( $callableName, ENT_QUOTES, 'UTF-8' ) ) );
         return FALSE;
      }
      $this->_onload = $callback;
   }

   protected function _checkCallback() {
      if( isset( $this->_onload ) && !$this->_isLoaded ) {
         $this->_isLoaded = true;
         call_user_func( $this->_onload, $this );
      }
   }
}