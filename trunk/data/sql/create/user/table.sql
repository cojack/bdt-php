CREATE SCHEMA "user";
SET search_path TO "user";

CREATE TABLE "user" (
   "id_user" SERIAL PRIMARY KEY NOT NULL,
   "user_login" VARCHAR(32) NOT NULL,
   "user_pwd" VARCHAR(40) NOT NULL,
   "last_login" TIMESTAMP,
   "add_date" TIMESTAMP DEFAULT NOW() NOT NULL,
   "mod_date" TIMESTAMP
);

CREATE TABLE "group" (
   "id_group" SERIAL PRIMARY KEY NOT NULL,
   "group_name" VARCHAR(12) NOT NULL,
   "group_desc" VARCHAR(255)
);

CREATE TABLE "user_to_group" (
   "id_user" INTEGER REFERENCES "user",
   "id_group" INTEGER REFERENCES "group",
   PRIMARY KEY ( "id_user", "id_group" )
);