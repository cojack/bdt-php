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
 * BDT_SQL_Validator klasa implementuje walidacje zmiennych
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/
class BDT_SQL_Validator {

   private $_model;

   private $_request;

   public function __construct( $model, $request ) {
      $this->_model = $model;
      $this->_request = $request;

      $this->_model->setValidator();
   }

   public function setConstraint( $column, $validator, $options ) {
      switch ( $validator ) {

         case 'min' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_MINLENGTH, $options );
            break;

         case 'max' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_MAXLENGTH, $options );
            break;

         case 'a-z' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_PERMITTEDCHARACTERS, $options );
            break;

         case '!a-z' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_NONPERMITTEDCHARACTERS, $options );
            break;

         case '<' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_LESSTHAN, $options );
            break;

         case '>' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_MORETHAN, $options );
            break;

         case '==' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_EQUALTO, $options );
            break;

         case '!=' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_NOTEQUALTO, $options );
            break;

         case '#' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_MUSTMATCHREGEXP, $options );
            break;

         case '!#' :
            $objConstraint = new BDT_Constraint( BDT_Constraint::CT_MUSTNOTMATCHREGEXP, $options );
            break;

         default:
            throw new Exception( 'Nie ma takiego validatora' );

      }

      $this->_request->addConstraint( $column, BDT_Request::VERB_METHOD_POST, $objConstraint );
   }

}