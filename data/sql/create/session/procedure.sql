SET search_path TO "session";

CREATE FUNCTION "update_session"( INTEGER ) RETURNS VOID AS $$
      UPDATE
         "session"."user"
      SET
         "mod_date" = NOW()
      WHERE
         "id_session" = $1;
$$ LANGUAGE 'sql';

CREATE FUNCTION "insert_session"( VARCHAR, VARCHAR ) RETURNS INTEGER AS $$
   DECLARE
      "id_session" INTEGER DEFAULT NULL;
   BEGIN
      PERFORM
         1
      FROM
         "session"."user"
      WHERE
         "id_ascii" = $1;

      IF NOT FOUND THEN

         INSERT INTO
            "session"."user"
         (
            "id_ascii",
            "loged",
            "id_user",
            "user_agent"
         )
         VALUES
         (
            $1,
            'f',
            NULL,
            $2
         ) 
         RETURNING 
            "session"."user"."id_session" INTO "id_session";

      END IF;

      RETURN "id_session";
   END;
$$ LANGUAGE 'plpgsql';

CREATE FUNCTION "login"( INTEGER, INTEGER ) RETURNS VOID AS $$
      UPDATE
         "session"."user"
      SET
         "loged" = TRUE,
         "id_user" = $1
      WHERE
         "id_session" = $2;

      UPDATE
         "user"."user"
      SET
         "last_login" = NOW()
      WHERE
         "id_user" = $1;
$$ LANGUAGE 'sql';

CREATE FUNCTION "logout"( INTEGER ) RETURNS VOID AS $$
      UPDATE
         "session"."user"
      SET
         "loged" = FALSE,
         "id_user" = NULL
      WHERE
         "id_session" = $1;
$$ LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION "clean_session" ( VARCHAR, INTEGER ) RETURNS VOID AS $$
      DELETE FROM
         "session"."user"
      WHERE
         "id_ascii" = $1
      OR
         ( EXTRACT(EPOCH FROM ( NOW() - "add_date" ))::INTEGER > $2 );

      DELETE FROM
         "session"."variable"
      WHERE
         "id_session" NOT IN ( SELECT "id_session" FROM "session"."user" );
$$ LANGUAGE 'sql';