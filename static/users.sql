drop table  if exists users CASCADE;

CREATE TABLE users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(50),
    kierownik bool,
    Pracownicy_id INT NOT NULL REFERENCES Pracownicy
);

CREATE OR REPLACE FUNCTION dod_user()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO users VALUES (CONCAT('User', NEW.id), CONCAT('PassU', NEW.id), 'false', NEW.id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER triggerAutoregister AFTER INSERT ON Pracownicy
FOR EACH ROW EXECUTE PROCEDURE dod_user();