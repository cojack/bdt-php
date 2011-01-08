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
 * BDT_Loader klasa odpowiedzialna za ładowanie plików
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.29.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_Loader {

   private static $_path;

   public static function set_path($path) {
      chdir($path.'/../../');
      self::$_path = getcwd();

      set_include_path(get_include_path() . PATH_SEPARATOR . self::$_path);
   }


   /**
    * Metoda wczytuje pojedyncze pliki XML
    *
    * @access  public
    * @param   string    $files   Względna ścieżka do pliku
    * @return  object    Xml object
    */
   public static function loadFileXML( $file ) {
      libxml_use_internal_errors( TRUE );
      $sxe = new SimpleXMLElement( self::$_path . '/' . $file . '.xml', NULL, TRUE );
      if( $sxe === FALSE ) {
         $errorMsg  = 'Błąd w pliku XML: ' . $file . '.xml' . '<br />';
         $errorMsg .= 'Ścieżka: ' . self::$_path . '/' . $file . '.xml' . '<br />';
         foreach( libxml_get_errors() as $error ) {
            $errorMsg .=  '<p>' . $error->message . '</p><br />';
         }
         throw new Exception( $errorMsg , E_USER_WARNING );
      } else {
         return $sxe;
      }
   }
   
   public static function loadFileINI( $file ) {
      $ini = parse_ini_file( self::$_path . '/' . $file . '.ini', true);
      if( $ini === FALSE ) {
         throw new Exception( 'Zła ścieżka i/lub nazwa dla pliku: '. $file . "\n" . 'Ścieżka: ' . self::$_path . $file . '.ini', E_USER_WARNING );
      } else {
         return $ini;
      }
   }


   /**
    * Metoda sprawdza czy dany plik jest możliwy do wczytania
    *
    * @param   string   $file    Względna ścieżka do pliku
    * @return  boolean  true, false
    */
   private static function _checkFile( $file ) {
      if( is_file( self::$_path . '/' . $file ) ) {
         if( is_readable( self::$_path . '/' . $file ) ) {
            return TRUE;
         } else {
            throw new Exception( 'Plik: '. $file . ' jest nie dla odczytu, sprawdź uprawnienia.' , E_USER_WARNING );
         }
      } else {
         throw new Exception( 'Zła ścieżka i/lub nazwa dla pliku: '. $file . "\n" . 'Ścieżka: ' . self::$_path  . '/' .  $file , E_USER_WARNING );
      }
      return FALSE;
   }
}