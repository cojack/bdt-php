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

   const CT_MINLENGTH = 1;
   const CT_MAXLENGTH = 2;
   const CT_PERMITTEDCHARACTERS = 3;
   const CT_NONPERMITTEDCHARACTERS = 4;
   const CT_LESSTHAN = 5;
   const CT_MORETHAN = 6;
   const CT_EQUALTO = 7;
   const CT_NOTEQUALTO = 8;
   const CT_MUSTMATCHREGEXP = 9;
   const CT_MUSTNOTMATCHREGEXP = 10;

  private $_intConstraintType;
  private $_strConstraintOperand;

  public function __construct($intConstraintType, $strConstraintOperand) {
    $this->_intConstraintType = $intConstraintType;
    $this->_strConstraintOperand = $strConstraintOperand;
  }

  public function getConstraintType() {
    return($this->_intConstraintType);
  }

  public function getConstraintOperand() {
    return($this->_strConstraintOperand);
  }
}
