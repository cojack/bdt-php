<?php

class SQL_User extends SQL_User_Procedure {

   public function userExists($login, $passwd) {
      $sth = $this->_query->prepare( <<<SQL
         SELECT
            "id_user"
         FROM
            "user"."user"
         WHERE
            "user_login" = :login
         AND
            "user_pwd" = :passwd
SQL
      );

      $sth->bindParam(':login', $login, PDO::PARAM_STR);
      $sth->bindParam(':passwd', $passwd, PDO::PARAM_STR);
      $sth->execute();
      return array( $sth->fetch(PDO::FETCH_LAZY), $sth->rowCount() );
   }
}