<html>
    <head>
        <title>Ogród botaniczny</title>
    </head>
    <?php
    session_start(); //starts the session
    if($_SESSION['user']){ // checks if the user is logged in  
    }
    else{
        header("location: bd.php"); // redirects if user is not logged in
    }
    $user = $_SESSION['user'];

 // IF NOT KIEROWNIK REDIRECT TO WORK.PHP
    $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
    $query = pg_query("Select * from users WHERE username='$user' AND kierownik = 'true'");
    if(pg_num_rows($query) == 0){
        header("location: work.php");
    }
    ?>
    <body>
        <?php Print "<h2>Użytkownik: $user</h2>"?>
        <a href="logout.php">Wyloguj</a>
        <h3> Zmień hasło: </h3>
        <form action="zmianahaslakier.php" method="POST">
            Wpisz stare hasło: <input type="password" 
            name="old_pass" required="required" /> <br/>
            Wpisz nowe hasło: <input type="password" 
            name="new_pass" required="required" /> <br/>
            <input type="submit" value="Zmień"/>
        </form>
        <h2>Panel pracownika</h2>
        <a href="workkier.php">Przejdź do panelu pracownika</a>
        <h2>Panel kierownika</h2>
        <a href="podglad_lok_ob.php">Wyświetl lokalizacje i obszary</a><br/><br/>
        <a href="podglad_prac_zad.php">Wyświetl prace i zadania</a><br/><br/>
        <a href="podglad_specjalizacje.php">Wyświetl dostępne specjalizacje</a><br/><br/>
        <a href="podglad_pracownicy.php">Wyświetl dane pracowników</a><br/><br/>
        <a href="podglad_przydzielone.php">Wyświetl podział zadań</a><br/><br/>
        <form action="add_lokalizacja.php" method="POST">
            Dodaj lokalizację: <br/>
                                ulica <input type="text" name="ulica" /> <br/>
                                miejscowość <input type="text" name="miejscowosc" /> <br/>
            <input type="submit" value="Dodaj lokalizację"/>
        </form>

        <form action="add_obszar.php" method="POST">
            Dodaj obszar: <br/>
                                ID lokalizacji <input type="text" name="Lokalizacja_id" /> <br/>
            <input type="submit" value="Dodaj obszar"/>
        </form>

        <form action="add_pracownik.php" method="POST">
            Dodaj nowego pracownika: <br/>
                                imię <input type="text" name="imie" /> <br/>
                                nazwisko <input type="text" name="nazwisko" /> <br/>
                                ID specjalizacji <input type="text" name="domyslna_specjalizacja" /> <br/>
            <input type="submit" value="Dodaj pracownika"/>
        </form>

        <form action="add_dodatkowa_specjalizacja.php" method="POST">
            Dodaj specjalizacje pracownikowi: <br/>
                                ID pracownika <input type="text" name="Pracownicy_id" /> <br/>
                                ID specjalizacji <input type="text" name="Specjalizacje_id" /> <br/>
            <input type="submit" value="Dodaj pracownikowi specjalizacje"/>
        </form>

        <form action="awans.php" method="POST">
            Awansuj pracownika na kierownika: <br/>
                                ID pracownika <input type="text" name="Pracownicy_id" /> <br/>
            <input type="submit" value="Awansuj"/>
        </form>

        <form action="add_praca.php" method="POST">
            Dodaj nową pracę: <br/>
                                rodzaj <input type="text" name="rodzaj" /> <br/>
                                od kiedy: <input type="date" name="od_kiedy" /> <br/>
                                do kiedy: <input type="date" name="do_kiedy" /> <br/>
                                numer obszaru <input type="text" name="Obszar_numer" /> <br/>
            <input type="submit" value="Dodaj pracę"/>
        </form>

        <form action="add_zadanie.php" method="POST">
            Dodaj nowe zadanie: <br/>
                                polecenie <input type="text" name="polecenie" /> <br/>
                                data <input type="date" name="data_zadania" /> <br/>
                                ID Pracy <input type="text" name="Prace_id_zad" /> <br/>
                                ID wymaganej specjalizacji <input type="text" name="wymagana_spec" /> <br/>
            <input type="submit" value="Dodaj zadanie"/>
        </form>
    
        <form action="przydziel_zadanie.php" method="POST">
            Przydziel zadanie: <br/>
                                ID zadania <input type="text" name="Zadania_id" /> <br/>
                                ID pracownika <input type="text" name="Pracownicy_id_podzial" /> <br/>
            <input type="submit" value="Przydziel zadanie"/>
        </form>

        <form action="zmien_termin_zadania.php" method="POST">
            Zmień termin zadania: <br/>
                                ID zadania <input type="text" name="zad_zmiana" /> <br/>
                                nowa data <input type="date" name="data_zadania" /> <br/>
            <input type="submit" value="Zmien termin"/>
        </form>
	</body>
</html>