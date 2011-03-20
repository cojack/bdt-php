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
 * BDT_SQL_PDO_Statement
 *
 * @author     Davey Shafik
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_SQL_PDO_Statement extends PDOStatement {
   const NO_MAX_LENGTH = -1;

   protected $connection;
   protected $bound_params = array();

   private $cache = null;
   private $cacheKey = null;
   private $cacheTime = null;

   protected function __construct(PDO $connection) {
      $this->connection = $connection;
   }

   public function bindParam($paramno, &$param, $type = PDO::PARAM_STR, $maxlen = null, $driverdata = null) {
      $this->bound_params[$paramno] = array(
         'value' => &$param,
         'type' => $type,
         'maxlen' => (is_null($maxlen)) ? self::NO_MAX_LENGTH : $maxlen,
         // ignore driver data
      );

      $result = parent::bindParam($paramno, $param, $type, $maxlen, $driverdata);
   }

   public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR) {
      $this->bound_params[$parameter] = array(
         'value' => $value,
         'type' => $data_type,
         'maxlen' => self::NO_MAX_LENGTH
      );
      parent::bindValue($parameter, $value, $data_type);
   }

   public function setCache( $cache, $cacheKey, $cacheTime ) {
      $this->cache = $cache;
      $this->cacheKey = $cacheKey;
      $this->cacheTime = $cacheTime;
   }

   public function getSQL($values = array()) {
      $sql = $this->queryString;

      if (sizeof($values) > 0) {
         foreach ($values as $key => $value) {
            $sql = str_replace($key, $this->connection->quote($value), $sql);
         }
      }

      if (sizeof($this->bound_params)) {
         foreach ($this->bound_params as $key => $param) {
            $value = $param['value'];
            if (!is_null($param['type'])) {
               $value = self::cast($value, $param['type']);
            }
            if ($param['maxlen'] && $param['maxlen'] != self::NO_MAX_LENGTH) {
               $value = self::truncate($value, $param['maxlen']);
            }
            if (!is_null($value)) {
               $sql = str_replace($key, $this->connection->quote($value), $sql);
            } else {
               $sql = str_replace($key, 'NULL', $sql);
            }
         }
      }
      return $sql;
   }

   public function execute(array $input_parameters = null) {
      $stop = 0;
      if( !$this->cache || !($rs = unserialize($this->cache->get($this->cacheKey))) ) {
         $start = microtime(true);
         $rs = parent::execute($input_parameters);
         $stop = microtime(true) - $start;
         if($this->cache)  {
            $rs = $this->fetchAll();
            $this->cache->set($this->cacheKey, serialize($rs), $this->cacheTime);
         }
      }
      BDT_Debugger::setSql( $this->getSQL(), $stop );
      return $rs;
   }

   static protected function cast($value, $type) {
      switch ($type) {
         case PDO::PARAM_BOOL:
            return (bool) $value;
            break;
         case PDO::PARAM_NULL:
            return null;
            break;
         case PDO::PARAM_INT:
            return (int) $value;
         case PDO::PARAM_STR:
         default:
            return $value;
      }
   }

   static protected function truncate($value, $length) {
      return substr($value, 0, $length);
   }
}