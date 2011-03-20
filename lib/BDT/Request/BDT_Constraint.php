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
 * BDT_Constraint
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_Constraint {

   /**
    * Minimalny rozmiar
    */
   const CT_MINLENGTH = 1;

   /**
    * Maksymalny rozmiar
    */
   const CT_MAXLENGTH = 2;

   /**
    * Dozwolone znaki
    */
   const CT_PERMITTEDCHARACTERS = 3;

   /**
    * Niedozwolone znaki
    */
   const CT_NONPERMITTEDCHARACTERS = 4;

   /**
    *  Mniejsze niż
    */
   const CT_LESSTHAN = 5;

   /**
    * Większe niż
    */
   const CT_MORETHAN = 6;

   /**
    * Równe z
    */
   const CT_EQUALTO = 7;

   /**
    * Różne od
    */
   const CT_NOTEQUALTO = 8;

   /**
    * Wyrażenie musi zostać spełnione
    */
   const CT_MUSTMATCHREGEXP = 9;

   /**
    * Wyrażenie nie może zostać spełnione
    */
   const CT_MUSTNOTMATCHREGEXP = 10;

   const CT_NOTEMPTY = 11;

   private $_intConstraintType;
   private $_strConstraintOperand;

   public function __construct($intConstraintType, $strConstraintOperand, $strMessage ) {
      $this->_intConstraintType = $intConstraintType;
      $this->_strConstraintOperand = $strConstraintOperand;
      $this->_strConstraintMessage = $strMessage;
   }

   public function getConstraintType() {
      return($this->_intConstraintType);
   }

   public function getConstraintOperand() {
      return($this->_strConstraintOperand);
   }

   public function getConstraintMessage() {
      return($this->_strConstraintMessage);
   }
}
