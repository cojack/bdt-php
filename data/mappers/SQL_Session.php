<?php

class SQL_Session extends SQL_Session_Procedure {

   public function checkSession($phpSessionId, $userAgent, $sessionLifespan, $sessionTimeout) {
      $sth = $this->_query->prepare( <<<SQL
         SELECT 
            "id_session" 
         FROM 
            "session"."user" 
         WHERE 
            "id_ascii" = :php_session_id
         AND 
            (EXTRACT(SECOND FROM (NOW() - "add_date")) < :session_lifespan::INT )
         AND
            "user_agent" = :user_agent
         AND
            ( (EXTRACT(SECOND FROM (NOW() - "mod_date")) < :session_timeout::INT ) OR "mod_date" IS NULL );
SQL
      );

      $sth->bindParam(':php_session_id', $phpSessionId, PDO::PARAM_STR);
      $sth->bindParam(':session_lifespan', $sessionLifespan, PDO::PARAM_INT);
      $sth->bindParam(':user_agent', $userAgent, PDO::PARAM_STR);
      $sth->bindParam(':session_timeout', $sessionTimeout, PDO::PARAM_INT);
      $sth->execute();

      return $sth->rowCount();
   }

   public function sessionExists($id_ascii) {
      $sth = $this->_query->prepare( <<<SQL
         SELECT 
            id_session, 
            loged::INTEGER, 
            id_user
         FROM 
            "session"."user" 
         WHERE 
            "id_ascii" = :id_ascii;
SQL
      );

      $sth->bindParam(':id_ascii', $id_ascii, PDO::PARAM_STR);
      $sth->execute();
      return $sth->fetch(PDO::FETCH_LAZY);
   }

}