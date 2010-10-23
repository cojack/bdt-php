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
 * BDT_Database klasa odpowiedzialna za obsługę bazy danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
final class BDT_Database {

   private $_function;

   private $_view;

   private $_conn;

   private $_model;

   private $_table;

   private static $_config = array( './config/database' => 'ini'  );

   private function __construct() {
      BDT_Loader::loadFile( array (
         './lib/BDT/Database/BDT_SQL_Connect',
         './lib/BDT/Database/BDT_SQL_Query',
         './lib/BDT/Database/BDT_SQL_Table',
         './lib/BDT/Database/BDT_SQL_Model',
         './lib/BDT/Exception/BDT_Database_Exception'
         )
      );

      $this->_conn = BDT_SQL_Connect::getInstance( self::$_config );
   }

   public function getModel( $model ) {

      $this->_model = ucfirst( $model );
      BDT_Loader::loadFile( array(
         './data/models/Tables/SQL_' . $this->_model . '_Table',
         './data/models/SQL_' . $this->_model
         )
      );

      $PGFM_model = 'SQL_' . $this->_model;

      if( !class_exists( $PGFM_model ) )
         throw new BDT_Database_Exception( 'Nie ma takiej klasy' );

      $objModel = new $PGFM_model;

      self::menageBackends( $this->_model, $objModel );

      return self::menageBackends( $this->_model );
   }

   public static function initialize() {
      static $selfObj;

      if( !isset( $selfObj ) )
         $selfObj = new BDT_Database;

      return $selfObj;
   }

   private static function menageBackends( $name, $objBack = NULL ) {
      static $backEnds;

      if( !isset( $backEnds ) )
         $backEnds = array();

      if( !isset( $objBack ) ) {
         if( isset( $backEnds[ $name ] ) )
            return $backEnds[ $name ];
         else
            throw new BDT_Database_Exception( 'Błędny klucz' );
      } else {
         $backEnds[ $name ] = $objBack;
      }
   }
}