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

class BDT_Render {

   public function __construct() {


      BDT_Loader::loadFile( array(
         './lib/Cache/cache.class',
         './lib/Cache/fileCacheDriver.class'
      ) );
   }

   public function isCached( $location, $path, BDT_View $view ) {

      BDT_Loader::loadFile( array(
         './lib/Cache/cache.class',
         './lib/Cache/fileCacheDriver.class'
      ) );

      try {
         $this->_cache = new Cache();
         $this->_cache->addDriver( 'file', new FileCacheDriver( $this->_paths->cacheDir ) );

         $content = $this->_cache->get( $location , $path, 1 );

         if( $content === FALSE )  #nie ma danych w cache
            return FALSE;

         $view->setContent( $content );

         return TRUE;

      } catch ( CacheException $error ){
         trigger_error( 'Error cache: ' . $error->getMessage(), E_USER_WARNING );
      }
   }

   public function render( $location, $path, BDT_View $view ) {
      ob_start();

      include( $this->_paths->sourceDir . $view->getPath() );

      $content = ob_get_contents();

      ob_end_clean();

      $this->_cache->set( $location, $path, $content ); #ustawia dane do cache

      $view -> setContent( $content );

      return $this;
   }

}