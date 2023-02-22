drop table  if exists Pracownicy CASCADE;
drop table  if exists Specjalizacje CASCADE;
drop table  if exists Zadania CASCADE;
drop table  if exists Prace CASCADE;
drop table  if exists Obszary CASCADE;
drop table  if exists Lokalizacje CASCADE;
drop table  if exists Specjalizacje_Pracownikow CASCADE;
drop table  if exists Podzial_Zadan CASCADE;


CREATE TABLE Lokalizacje (
    id SERIAL PRIMARY KEY,
    ulica VARCHAR(50) NOT NULL,
    miejscowosc VARCHAR(30) NOT NULL
);

CREATE TABLE Obszary (
    numer SERIAL PRIMARY KEY,
    Lokalizacje_id INT NOT NULL REFERENCES Lokalizacje
);

CREATE TABLE Prace (
    id SERIAL PRIMARY KEY,
    rodzaj VARCHAR(30) NOT NULL,
    od_kiedy DATE NOT NULL,
    do_kiedy DATE NOT NULL,
    Obszar_numer INT NOT NULL REFERENCES Obszary 
    CHECK (do_kiedy>=od_kiedy)
);

CREATE TABLE Specjalizacje (
    id SERIAL PRIMARY KEY,
    nazwa VARCHAR(30) UNIQUE NOT NULL
);

CREATE OR REPLACE FUNCTION czy_data(d DATE, p INT)
RETURNS BOOLEAN AS
$$
BEGIN
IF d<=(SELECT do_kiedy FROM Prace WHERE id = p) AND d>=(SELECT od_kiedy FROM Prace WHERE id = p)
THEN RETURN true;
ELSE RETURN false;
END IF;
END;
$$ LANGUAGE PLpgSQL;

CREATE TABLE Zadania (
    id SERIAL,
    polecenie VARCHAR(50) NOT NULL,
    data DATE NOT NULL, 
    Prace_id INT NOT NULL REFERENCES Prace,
    Specjalizacje_id INT NOT NULL REFERENCES Specjalizacje,
    PRIMARY KEY(id),
    UNIQUE(Prace_id, data),
    CHECK(czy_data(data, Prace_id))
);

CREATE OR REPLACE FUNCTION liczba_pracownikow()
RETURNS BOOLEAN AS
$$
BEGIN
IF (SELECT COUNT(*) FROM Pracownicy) <50
    THEN RETURN true;
ELSE RETURN false;
END IF;
END;
$$ LANGUAGE PLpgSQL;

CREATE TABLE Pracownicy (
    id SERIAL,
    imie VARCHAR(20) NOT NULL,
    nazwisko VARCHAR(30) NOT NULL,
    domyslna_specjalizacja_id INT NOT NULL REFERENCES Specjalizacje,
    PRIMARY KEY(id),
    CHECK(liczba_pracownikow())
);

CREATE TABLE Specjalizacje_Pracownikow (
    Pracownicy_id INT NOT NULL REFERENCES Pracownicy,
    Specjalizacje_id INT NOT NULL REFERENCES Specjalizacje,
    PRIMARY KEY(Pracownicy_id, Specjalizacje_id)
);

CREATE TABLE Podzial_Zadan (
    Pracownicy_id INT NOT NULL REFERENCES Pracownicy,
    Zadania_id INT NOT NULL REFERENCES Zadania ON UPDATE CASCADE,
    UNIQUE(Zadania_id)
    PRIMARY KEY(Pracownicy_id, Zadania_id)
);


CREATE OR REPLACE FUNCTION czy_kolizja()
RETURNS TRIGGER AS 
$$
BEGIN 
    If EXISTS (SELECT daty_pr.data FROM (SELECT data FROM Podzial_zadan as p JOIN Zadania as z ON p.zadania_id = z.id WHERE Pracownicy_id = NEW.Pracownicy_id) as daty_pr,
        (SELECT data FROM Zadania WHERE id = NEW.Zadania_id) as data_zad
        WHERE daty_pr.data = data_zad.data)
        THEN RAISE EXCEPTION 'Kolizja zadań pracownika';
    END IF;
		RETURN NEW;
	END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER triggerCollision BEFORE UPDATE OR INSERT ON Podzial_Zadan
FOR EACH ROW EXECUTE PROCEDURE czy_kolizja();

CREATE OR REPLACE FUNCTION kolizja_daty()
RETURNS TRIGGER AS 
$$
BEGIN 
    If EXISTS (SELECT daty_pr.data, daty_pr.indeksy FROM (SELECT data, p.zadania_id as indeksy FROM Podzial_zadan as p JOIN Zadania as z ON p.zadania_id = z.id
                WHERE Pracownicy_id in (SELECT Pracownicy_id FROM Podzial_Zadan WHERE Zadania_id = NEW.id)) as daty_pr
        WHERE daty_pr.data = NEW.data and daty_pr.indeksy != NEW.id)
        THEN RAISE EXCEPTION 'Kolizja zadań pracownika';
    END IF;
		RETURN NEW;
	END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER triggerDateCollision BEFORE UPDATE OR INSERT ON Zadania
FOR EACH ROW EXECUTE PROCEDURE kolizja_daty();

CREATE OR REPLACE FUNCTION czy_dobra_specjalizacja()
RETURNS TRIGGER AS 
$$
BEGIN 
    If EXISTS (SELECT * FROM Specjalizacje_Pracownikow 
        WHERE Pracownicy_id = NEW.Pracownicy_id AND Specjalizacje_id = (SELECT Specjalizacje_id FROM Zadania WHERE id = NEW.Zadania_id))
        THEN RETURN NEW;
    END IF;
		RAISE EXCEPTION 'Pracownik nie ma wymaganej specjalizacji';
	END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER triggerCheckSpec BEFORE UPDATE OR INSERT ON Podzial_Zadan
FOR EACH ROW EXECUTE PROCEDURE czy_dobra_specjalizacja();

CREATE OR REPLACE FUNCTION rel_prac_spec()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO Specjalizacje_Pracownikow VALUES (NEW.id, NEW.domyslna_specjalizacja_id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER triggerAutorel AFTER INSERT ON Pracownicy
FOR EACH ROW EXECUTE PROCEDURE rel_prac_spec();

