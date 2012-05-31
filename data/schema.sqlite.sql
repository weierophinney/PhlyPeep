CREATE TABLE peep
(
    identifier    CHARACTER(8) PRIMARY KEY NOT NULL,
    username      VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL,
    display_name  VARCHAR(50) DEFAULT NULL,
    timestamp     INTEGER NOT NULL,
    peep_text     TEXT NOT NULL
);

CREATE INDEX peep_username ON peep(username);
