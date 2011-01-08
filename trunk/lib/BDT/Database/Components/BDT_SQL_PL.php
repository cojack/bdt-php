<?php

interface BDT_SQL_PL {
   public function __construct(BDT_SQL_Procedure $procedure);

   public function invoke();
}
