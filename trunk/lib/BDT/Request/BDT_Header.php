<?php

class BDT_Header {

   public static function e404() {
      header("HTTP/1.0 404 Not Found");
      exit(0);
   }

   public static function eLocation() {
      $args = func_get_args();
      header('Location: ' . $args[1]);
      exit(0);
   }
}