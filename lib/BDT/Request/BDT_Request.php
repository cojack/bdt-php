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
 * BDT_Request
 *
 * @author     Przemysław Czekaj <przemyslaw.czekaj@aichra.pl>
 * @link       http://aichra.pl
 * @version    0.1
 * @since      03.23.2010
 * @package    BDT
 * @charset    utf8
 **/

BDT_Loader::loadFile( array(
   './lib/BDT/Request/BDT_Constraint',
   './lib/BDT/Request/BDT_Constraint_Failure'
) );

class BDT_Request {

   /**
    * Typ parametru przesyłany przez przeglądarkę, HTTP GET.
    *
    * @param    int
    * @access   const
    */
   const VERB_METHOD_GET = 1;

   /**
    * Typ parametru przesyłany przez przeglądarkę, HTTP POST.
    *
    * @param    int
    * @access   const
    */
   const VERB_METHOD_POST = 2;

   /**
    * Typ parametru przesyłany przez przeglądarkę, COOKIES.
    *
    * @param    int
    * @access   const
    */
   const VERB_METHOD_COOKIE = 4;

   /**
    * Kopia $_GET - tablicy asociacyjnej zmiennych HTTP GET przesłanych w tym wywołaniu.
    *
    * @param    array
    * @access   private
    */
   private $_arGetVars;

   /**
    * Kopia $_POST - tablicy asociacyjnej zmiennych HTTP POST przesłanych w tym wywołaniu.
    *
    * @param    array
    * @access   private
    */
   private $_arPostVars;

   /**
    * Kopia $_COOKIE - tablicy asociacyjnej istniejących wcześniej cookies przesłanych w tym wywołaniu.
    *
    * @param    array
    * @access   private
    */
   private $_arCookieVars;

   /**
    * Kopia $_REQUEST - sumarycznej tablicy asociacyjnej zmiennych GET, POST i cookies.
    * W przypadku, kiedy zmienne GET i POST mają takie same nazwy, pierwszeństwo jest określone w pliku php.ini
    *
    * @param    array
    * @access   private
    */
   private $_arRequestVars;

   /**
    * W przypadku, gdy użytkownik został przekierowany z powrotem na pierwotną stronę przez obiekt BDT_Request w rezulatacie przesłania parametrów,
    * które nie przeszły testów prawidłowości, zawiera kopię oryginalnego, przesłanego obiektu BDT_Request.
    *
    * @param    object
    * @access   private
    */
   private $_objOriginalRequestObject;

   /**
    * Wartość logiczna określająca, czy obiekt BDT_Request został utworzony w efekcie przekierowania będąćego skutkiem nieprzejścia testów prawidłowości.
    *
    * @param    boolean
    * @access   private
    */
   private $_blIsRedirectFollowingConstraintFailure;

   /**
    * Określa, czy w przypadku,gdy test prawidłowości wypadł negatywnie,obiekt BDT_Request powinien automatycznie przekierować przeglądarkę na pierwotną stronęczy pod jakiś inny adres URL.
    *
    * @param    boolean
    * @access   private
    */
   private $_blRedirectOnConstraintFailure;

   /**
    * Wprzypadku, gdy test prawidłowości wypadł negatywnie, a zmienna $_blRedirectOnConstraintFailure ma wartość true,
    * zawiera docelowy adres URL przekierowania. Jeżeli postawiono tu wartośćpustą, użyta zostanies trona będąca źródłem wywołania.
    *
    * @param    string
    * @access   private
    */
   private $_strConstraintFailureRedirectTargetURL;

   /**
    * W przypadku, kiedy zmienna $_strConstraintFailureRedirectTargetURL ma wartość pustą, a $_blRedirectOnConstraintFailure jest ustawiona na true i nie jest dostępna strona będąca źródłem wywołania,
    * jest to adres URL, pod który skierowana zostaniep rzeglądarka.
    *
    * @param    string
    * @access   private
    */
   private $_strConstraintFailureDefaultRedirectTargetURL;

   /**
    * Tablica ograniczeń, której indeksy zawierają trzy wzywskazywane kluczami komponenty wtablicy asocjacyjnej.
    * Zawiera ona
    * po pierwsze nazwę parametru, którego dotyczy ograniczenie,
    * pod rugiesposób,w jaki parametr ma być przesiany ( jakostała )
    * i po trzecie obiekt ograniczenia reprezentujący test, jaki mazostać zastosowany.
    *
    * @param    array
    * @access   private
    */
   private $_arObjParameterMethodConstraintHash;

   /**
    * Tablica obiektów reprezentujących testy, które wypadły negatywnie, przy założeniu że jakiekolwiek testy były prowadzone.
    *
    * @param    array
    * @access   private
    */
   private $_arObjConstraintFailure;

   /**
    * Wartość logiczna wskazująca, czy na danym obiekcie żądania przeprowadzono już testy prawidłowości.
    *
    * @param    boolean
    * @access   private
    */
   private $_blHasRunConstraintTests;

   /**
   * Tworzy egzemplarz obiektu request.
   * Nie wymaga podawania żadnych parametrów, ale sprawdza wartości
   * - $_REQUEST,
   * - $_POST,
   * - $_GET
   * - $_COOKIE
   * i na ich podstawie inicjalizuje zmienne składowe. Dodatkowo sprawdza, czy istnieje cookie o nazwie phprqcOriginalRequestObject.
   * Jeżeli istnieje, przyjmuje się, że po odrzuceniu w efekcie negatywnych wyników testów prawidłowości, powinien zostać zwrócony odrębny obiekt request.
   * To cookie jest następnie zerowane, aby niestworzyć nieskończonej pętli w momencieu tworzenia obiektu reprezentującego pierwotne żądanie.
   * Jego zawartość jest następnie odszeregowywana (funkcją stripshlashes() ) do nowego obiektu żądania,
   * którego zawartość jest potem udostępniana do odczytu poprzez funkcję dostępowa getOriginalRequestObjectFol1owingConstraintFai1ure.
   *
   * @param     boolean     $check_for_cookie   default true
   * @return    void
   */
   public function __construct($check_for_cookie = true) {
    /*/ Import variables
    global $_REQUEST;
    global $_GET;
    global $_POST;
    global $_COOKIE;*/
    $this->_arGetVars = $_GET;
    $this->_arPostVars = $_POST;
    $this->_arCookieVars = $_COOKIE;
    $this->_arRequestVars = $_REQUEST;
    if ($check_for_cookie) {
      if ( isset( $this->_arCookieVars["phprqcOriginalRequestObject"] ) ) {
        $cookieVal = $this->_arRequestVars["phprqcOriginalRequestObject"];
        $this->_blIsRedirectFollowingConstraintFailure = true;
        if (strlen($cookieVal) > 0) {
          $strResult = setcookie ("phprqcOriginalRequestObject", "", time() - 3600, "/");
          $origObj = unserialize(stripslashes($cookieVal));
          $this->_objOriginalRequestObject = &$origObj;
          $this->_arRequestVars["phprqcOriginalRequestObject"] = "";
          $this->_arGetVars["phprqcOriginalRequestObject"] = "";
          $this->_arPostVars["phprqcOriginalRequestObject"] = "";
        };
        $this->_blIsRedirectOnConstraintFailure  = true;
      } else {
        $this->_blIsRedirectOnConstraintFailure  = false;
      };
    } else {
      $this->_blIsRedirectOnConstraintFailure  = false;
    };
    $this->_arObjParameterMethodConstraintHash = Array();
    $this->_arObjConstraintFailure = Array();
    $this->_blHasRunConstraintTests = false;
  }

  public function isRedirectFollowingConstraintFailure() {
    return($this->_blIsRedirectOnConstraintFailure);
  }

  public function getOriginalRequestObjectFollowingConstraintFailure() {
    if ($this->_blIsRedirectOnConstraintFailure) {
      return($this->_objOriginalRequestObject);
    };
  }

  public function setRedirectOnConstraintFailure($blTrueOrFalse) {
    $this->_blRedirectOnConstraintFailure  = $blTrueOrFalse;
  }

  public function setConstraintFailureRedirectTargetURL($strURL) {
    $this->_strConstraintFailureRedirectTargetURL = $strURL;
  }

  public function setConstraintFailureDefaultRedirectTargetURL($strURL) {
    $this->_strConstraintFailureDefaultRedirectTargetURL = $strURL;
  }

  public function getParameterValue($strParameter) {
    if( isset( $this->_arRequestVars[$strParameter] ) )
      return($this->_arRequestVars[$strParameter]);

   return NULL;
  }

  public function getParameters() {
    return($this->_arRequestVars);
  }

  public function getCookies() {
    return($this->_arCookieVars);
  }

  public function getPostVariables() {
    return($this->_arPostVars);
  }

  public function getGetVariables() {
    return($this->_arGetVars);
  }

  public function addConstraint($strParameter, $intMethod, BDT_Constraint $objConstraint) {
    $newHash["PARAMETER"] = $strParameter;
    $newHash["METHOD"] = $intMethod;
    $newHash["CONSTRAINT"] = $objConstraint;
    $this->_arObjParameterMethodConstraintHash[] = $newHash;
  }

  public function validConstraints() {
    $this->_blHasRunConstraintTests = true;
    $anyFail = false;
    for ($i=0; $i<=sizeof($this->_arObjParameterMethodConstraintHash)-1; $i++) {
      $strThisParameter = $this->_arObjParameterMethodConstraintHash[$i]["PARAMETER"];
      $intThisMethod = $this->_arObjParameterMethodConstraintHash[$i]["METHOD"];
      $objThisConstraint = $this->_arObjParameterMethodConstraintHash[$i]["CONSTRAINT"];
      $varActualValue = "";
      if ($intThisMethod & self::VERB_METHOD_COOKIE) {
        $varActualValue = $this->_arCookieVars[$strThisParameter];
      };
      if ($intThisMethod & self::VERB_METHOD_GET) {
        $varActualValue = $this->_arGetVars[$strThisParameter];
      };
      if ($intThisMethod & self::VERB_METHOD_POST) {
        $varActualValue = $this->_arPostVars[$strThisParameter];
      };
      $intConstraintType = $objThisConstraint->getConstraintType();
      $strConstraintOperand = $objThisConstraint->getConstraintOperand();

      $thisFail = false;
      $objFailureObject = new BDT_Constraint_Failure($strThisParameter, $intThisMethod, $objThisConstraint);
      switch ($intConstraintType) {
        case BDT_Constraint::CT_MINLENGTH:
          if (strlen((string)$varActualValue) < (integer)$strConstraintOperand) {
            $thisFail = true;
          };
          break;
        case BDT_Constraint::CT_MAXLENGTH:
          if (strlen((string)$varActualValue) > (integer)$strConstraintOperand) {
            $thisFail = true;
          };
          break;
        case BDT_Constraint::CT_PERMITTEDCHARACTERS:
          for ($j=0; $j<=strlen($varActualValue)-1; $j++) {
              $thisChar = substr($varActualValue, $j, 1);
              if (strpos($strConstraintOperand, $thisChar) === false) {
                $thisFail = true;
              };
            };
          break;
        case BDT_Constraint::CT_NONPERMITTEDCHARACTERS:
          for ($j=0; $j<=strlen($varActualValue)-1; $j++) {
              $thisChar = substr($varActualValue, $j, 1);
              if (!(strpos($strConstraintOperand, $thisChar) === false)) {
                $thisFail = true;
              };
            };
          break;
        case BDT_Constraint::CT_LESSTHAN:
          if ($varActualValue >= $strConstraintOperand) {
            $thisFail = true;
          };
          break;
        case BDT_Constraint::CT_MORETHAN:
          if ($varActualValue <= $strConstraintOperand) {
            $thisFail = true;
          };
          break;
        case BDT_Constraint::CT_EQUALTO:
          if ($varActualValue != $strConstraintOperand) {
            $thisFail = true;
          };
          break;
        case BDT_Constraint::CT_NOTEQUALTO:
          if ($varActualValue == $strConstraintOperand) {
            $thisFail = true;
          };
          break;
        case BDT_Constraint::CT_MUSTMATCHREGEXP:
          if (!(preg_match($strConstraintOperand, $varActualValue))) {
            $thisFail = true;
          };
          break;
        case BDT_Constraint::CT_MUSTNOTMATCHREGEXP:
          if (preg_match($strConstraintOperand, $varActualValue)) {
            $thisFail = true;
          };
          break;
      };
      if ($thisFail) {
        $anyFail = true;
        $this->_arObjConstraintFailure[] = $objFailureObject;
      };
    };
    if ($anyFail) {
      if ($this->_blRedirectOnConstraintFailure) {
          $targetURL = $_ENV["HTTP_REFERER"];
          if (!$targetURL) {
            $targetURL = $this->_strConstraintFailureDefaultRedirectTargetURL;
          };
          if ($this->_strConstraintFailureRedirectTargetURL) {
            $targetURL = $this->_strConstraintFailureRedirectTargetURL;
          };
          if ($targetURL) {
            $objToSerialize = $this;
            $strSerialization = serialize($objToSerialize);
            $strResult = setcookie ("phprqcOriginalRequestObject", $strSerialization, time() + 3600, "/");
            header("Location: $targetURL");
            exit(0);
          };
      };
    };
    return(!($anyFail));  // Returns TRUE if all tests passed, otherwise returns FALSE
  }

  public function getConstraintFailures() {
    if (!$this->_blHasRunConstraintTests) {
      $this->validConstraints();
    };
    return($this->_arObjConstraintFailure);
  }
}