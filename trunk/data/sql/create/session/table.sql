CREATE SCHEMA "session";

CREATE TABLE "session"."user" (
  "id_session" SERIAL PRIMARY KEY NOT NULL,
  "id_user" INTEGER REFERENCES "user"."user" DEFAULT NULL,
  "id_ascii" VARCHAR(32) UNIQUE,
  "loged" BOOLEAN,
  "user_agent" VARCHAR(32),
  "add_date" TIMESTAMP DEFAULT NOW(),
  "mod_date" TIMESTAMP
);

CREATE TABLE "session"."variable" (
  "id_variable" SERIAL PRIMARY KEY NOT NULL,
  "id_session" INTEGER REFERENCES "session"."user",
  "variable_name" VARCHAR(64),
  "variable_value" TEXT
);
