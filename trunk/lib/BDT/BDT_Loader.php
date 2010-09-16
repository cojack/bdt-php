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

/**
 * @TODO    Błędy w postaci GETTEXT
 */
class BDT_Loader {

   private static $_path;

   private static $_files;

   public static function initialize() {
      chdir( './..' );
      self::$_path = getcwd();

      require_once( self::$_path . DIRECTORY_SEPARATOR . './lib/BDT/Collection/Components/BDT_File_Collection.php' );

      self::$_files = new BDT_File_Collection;
   }

   /**
    * Metoda przyjmuje argument jako tablice iteracyjną
    * Adres do pliku powinien zaczynać się od ./ i uwzględnić
    * zmianę ścieżki podczas wczytywania danych.
    * Zachowywujemy się tak jakbyśmy byli w / tego cms'a.
    *
    * @access  public
    * @param   array    $files   Tablica w postaci array ( 'typ' => 'plik', 'typ' => 'plik', 'typ' => 'plik' );
    * @return  void
    */
   public static function loadFile( $files = array() ) {
      foreach( $files as $fileType => $file ) {
         $method = '_loadFile' . ( is_int( $fileType ) ? 'PHP' : mb_strtoupper( $fileType, 'UTF-8' ) );
         self::$method( $file );
      }
   }

   private static function _loadFilePHP( $file ) {
      require_once( self::$_path . DIRECTORY_SEPARATOR . $file . '.php' );
   }

   /**
    * Metoda wczytuje pojedyncze pliki XML
    *
    * @access  public
    * @param   string    $files   Względna ścieżka do pliku
    * @return  object    Xml object
    */
   private static function _loadFileXML( $file ) {
      libxml_use_internal_errors( TRUE );
      $sxe = new SimpleXMLElement( self::$_path . DIRECTORY_SEPARATOR . $file . '.xml', NULL, TRUE );
      if( $sxe === FALSE ) {
         $errorMsg  = 'Błąd w pliku XML: ' . $file . '.xml' . '<br />';
         $errorMsg .= 'Ścieżka: ' . self::$_path . DIRECTORY_SEPARATOR . $file . '.xml' . '<br />';
         foreach( libxml_get_errors() as $error ) {
            $errorMsg .=  '<p>' . $error->message . '</p><br />';
         }
         trigger_error( $errorMsg , E_USER_WARNING );
      } else {
         self::$_files->addItem( $sxe, basename( $file ) . '.xml' );
      }

      $sxe = NULL;
   }


   private static function _loadFileINI( $file ) {
      $ini = parse_ini_file( self::$_path . DIRECTORY_SEPARATOR . $file . '.ini', true);

      if( $ini === FALSE ) {
         trigger_error( 'Zła ścieżka i/lub nazwa dla pliku: '. $file . "\n" . 'Ścieżka: ' . self::$_path . $file . '.ini', E_USER_WARNING );
      } else {
         self::$_files->addItem( $ini, basename( $file ) . '.ini' );
      }

      $ini = NULL;
   }

   public static function getFiles( $files = array() ) {
      if( empty( $files ) )
         return NULL;

      $n = count( $files );

      $tabFiles = array();

      try {
         if( $n <= 1 ) {
               $tabFiles = self::getFile( $files[ 0 ][ 'name' ], (boolean)$files[ 0 ][ 'name' ] );
         } else {
            foreach( $files as $file )
               $tabFiles[] = self::getFile( $file['name'], (boolean)$file['name'] );
         }
      } catch( BDT_Collection_Exception $error ) {
         trigger_error( $error->getMessage() , E_USER_WARNING  );
      }

      return $tabFiles;
   }


   private static function getFile( $file, $deleteFile = FALSE ) {
      $tmpFile = self::$_files->getItem( $file );
      if( $deleteFile === TRUE ) {
         self::$_files->removeItem( $file );
      }

      return $tmpFile;
   }


   /**
    * Metoda sprawdza czy dany plik jest możliwy do wczytania
    *
    * @param   string   $file    Względna ścieżka do pliku
    * @return  boolean  true, false
    */
   private static function _checkFile( $file ) {
      if( is_file( self::$_path.$file ) ) {
         if( is_readable( self::$_path.$file ) ) {
            return TRUE;
         } else {
            trigger_error( 'Plik: '. $file . ' jest nie dla odczytu, sprawdź uprawnienia.' , E_USER_WARNING );
         }
      } else {
         trigger_error( 'Zła ścieżka i/lub nazwa dla pliku: '. $file . "\n" . 'Ścieżka: ' . self::$_path . $file , E_USER_WARNING );
      }
      return FALSE;
   }
}