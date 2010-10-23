<?php
global $routing;
$routing = array(
   array(
      'name' => 'home',
      'url' => '',
      'parametrs' => array (
         'interface' => 'frontend',
         'controller' => 'default',
         'action' => 'index'
      ),
   ),
   array(
      'url' => ':controller/:action/:lang',
      'parametrs' => array (
         'interface' => 'frontend',
         'lang' => 'pl',
         'requirements' => array(
            'lang' => '\d{1,2}'
         ),
      ),
   ),
   array(
      'url' => 'admin/:controller/:action',
      'parametrs' => array (
         'interface' => 'backend',
         'controller' => 'login',
         'action' => 'index',
      ),
   ),
);

global $controllers;
$controllers = array (
   'default',
   'login'
);