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
 * BDT_SQL_Query klasa implementuje zapytania do bazy danych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_SQL_Query  {

   /**
    *
    *
    */
   protected $_conn;

   /**
    *
    *
    */
   protected $_mapper;

   /**
    *
    *
    */
   private $_cacheKey = null;

   /**
    *
    *
    */
   private $_cacheTime = 0;

   /**
    *
    *
    */
   public function __construct( $mapper ) {
      $this->_mapper = $mapper;

      $this->_conn = BDT_SQL_Connect::connect('read');
   }

   /**
    *
    *
    */
   public function prepare( $sql, $cache = FALSE ) {
      $stm = $this->_conn->prepare( $sql );

      if($cache) {
         $this->_cacheQuery($stm);
      }

      return $stm;
   }

   /**
    *
    *
    */
   public function setCacheKey( $key = null ) {
      $this->_cacheKey = $key;
   }

   /**
    *
    *
    */
   public function setCacheTime( $time = 0 ) {
      $this->_cacheTime = $time;
   }

   /**
    *
    * @param BDT_SQL_Procedure $procedure
    * @return bool Wynik procedury
    */
   public function callProcedure( BDT_SQL_Procedure $procedure ) {
      $engine = 'BDT_SQL_' . strtoupper( $this->_conn->getAttribute(PDO::ATTR_DRIVER_NAME) );

      require_once( './lib/BDT/Database/Components/BDT_SQL_PL.php' );
      require_once( './lib/BDT/Database/Components/Procedures/'. $engine .'.php' );

      $plsql = new $engine( $procedure );
      return $plsql->invoke();
   }

   /**
    *
    * 
    * @param PDOStatement $stm
    * @return void
    */
   private function _cacheQuery( PDOStatement $stm ) {
      if(!$this->_cacheKey) {
         throw new Exception('Brak klucza do cachowanie zapytania');
      }

      $m = new Memcached();
      $m->addServer('localhost', 11211);
      $m->setOption(Memcached::OPT_PREFIX_KEY, get_class($this->_mapper).'_');

      $stm->setCache($m, $this->_cacheKey, $this->_cacheTime);
   }
}