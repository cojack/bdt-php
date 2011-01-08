<?php

$mapper = new Horde_Routes_Mapper;

$mapper->connect( 
   'home',
   '/',
   array (
      'interface' => 'frontend',
      'controller' => 'default',
      'action' => 'index'
   )
);

$mapper->connect(
   ':controller/:action/:lang',
   array (
      'interface' => 'frontend',
      'lang' => 'pl',
      'requirements' => array(
         'lang' => '[a-z]{1,2}'
      ),
   )
);

$mapper->connect(
   'admin/:controller/:action',
   array (
      'interface' => 'backend',
      'controller' => 'default',
      'action' => 'index',
   )
);

$mapper->createRegs( array(
   'default',
   'login'
   )
);

return $mapper;