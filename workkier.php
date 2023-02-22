<html>
<head>
    <title>Ogród botaniczny</title>
</head>

<?php
    session_start();
    if($_SESSION['user']){
        $user = $_SESSION['user'];
    }
    else{ 
       header("location:bd.php");
    }
?>

<body>
<h2>Użytkownik: <?php Print $user?> </h2>
<a href="logout.php"> Wyloguj </a> <br>
<a href="home.php"> Wróć do panelu kierownika </a>
<h2> Profil pracownika </h2>
<?php

    $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
    if ($conn == false) {
        Print '<script>alert("Database connection error!");</script>';
        Print '<script>window.location.assign("workkier.php");</script>';
        exit();
    }

    $query = pg_query("SELECT P.id as pid, * FROM Pracownicy as P LEFT JOIN Specjalizacje_Pracownikow as SP ON P.id = SP.Pracownicy_id 
    JOIN Specjalizacje as S ON S.id = Specjalizacje_id JOIN users as U ON P.id = U.Pracownicy_id WHERE username = '$user'");
    if (pg_num_rows($query) == 0) {
        Print "<h2> Brak danych </h2>";
    } else {
        $lok = -1;
        while($row = pg_fetch_array($query)) {
            if($lok != $row['pid']){
                Print '</table>';
                Print '<table border="1px" width="100%">';
                Print "<h3>";
                echo 'ID Pracownika: '; 
                echo $row['pid'];
                echo '<br>';
                echo 'Imię: '; 
                echo $row['imie'];
                echo '<br>';
                echo 'Nazwisko: ';
                echo $row['nazwisko'];
                echo '<br>';
                Print "</h3>";
                $lok = $row['pid'];
            }
            Print "<tr>";
            Print '<td align="center">' . $row['nazwa'] . "</td>";
            Print "</tr>";
        }
    }
    Print '</table>';
    Print '<br/>';

    Print '<h2> Przydzielone zadania </h2>';

    $query = pg_query("SELECT P.id as pid, Z.id as zid, * FROM Pracownicy as P JOIN Podzial_Zadan as PZ ON P.id = PZ.Pracownicy_id 
    LEFT JOIN Zadania as Z ON Z.id = Zadania_id JOIN Specjalizacje as S ON S.id = Specjalizacje_id JOIN users as U ON P.id = U.Pracownicy_id 
    WHERE username = '$user' ORDER BY pid, zid" );
    if (pg_num_rows($query) == 0) {
        Print "<h3> Brak danych </h3>";
    } else {
        $lok = -1;
        while($row = pg_fetch_array($query)) {
            if($lok != $row['pid']){
                Print '</table>';
                Print '<table border="1px" width="100%">';
                Print '<tr>';
                    Print '<th>ID</th>';
                    Print '<th>Data</th>';
                    Print '<th>Polecenie</th>';
                    Print '<th>Specjalizacja</th>';
                    Print '<th>ID Pracy</th>';
                Print '<tr>';
                $lok = $row['pid'];
            }
            Print '<tr>';
            Print '<td align="center">' . $row['zid'] . "</td>";
            Print '<td align="center">' . $row['data'] . "</td>";
            Print '<td align="center">' . $row['polecenie'] . "</td>";
            Print '<td align="center">' . $row['nazwa'] . "</td>";
            Print '<td align="center">' . $row['prace_id'] . "</td>";
            Print '</tr>';
        }
    }
    Print '</table>';
    Print '<br/>';
    Print '<h2>Prace w których bierzesz udział </h2>';


    $query = pg_query("SELECT P.id as pid, Z.id as zid, * FROM Pracownicy as P JOIN Podzial_Zadan as PZ ON P.id = PZ.Pracownicy_id 
    LEFT JOIN Zadania as Z ON Z.id = Zadania_id JOIN users as U ON P.id = U.Pracownicy_id JOIN Prace as PR ON PR.id = Z.Prace_id
    WHERE username = '$user' ORDER BY pid, PR.id");
    if (pg_num_rows($query) == 0) {
        Print "<h3> Brak danych </h>";
    } else {
        Print '<table border="1px" width="100%">
        <tr>
            <th>ID Pracy</th>
            <th>Rodzaj</th>
            <th>Od kiedy</th>
            <th>Do kiedy</th>
            <th>Numer obszaru</th>
        </tr>';
        $lok = -1;
        while($row = pg_fetch_array($query)) {
            if($row['id'] != NULL and $lok != $row['id']){
                $lok = $row['id'];
                Print "<tr>";

                Print '<td align="center">' . $row['id'] . "</td>";
                Print '<td align="center">' . $row['rodzaj'] . "</td>";
                Print '<td align="center">' . $row['od_kiedy'] . "</td>";
                Print '<td align="center">' . $row['do_kiedy'] . "</td>";
                Print '<td align="center">' . $row['obszar_numer'] . "</td>";
                
                Print "</tr>";
            }
        }
    }

    Print '</table>';
    Print '<br/>';

    Print '<h2>Lokalizacje i obszary w których pracujesz </h2>';

    $query = pg_query("SELECT L.id as lid, * FROM Lokalizacje as L LEFT JOIN Obszary as O ON O.lokalizacje_id = L.id 
    LEFT JOIN Prace as PR ON PR.Obszar_numer = O.numer JOIN Zadania as Z ON PR.id = Z.Prace_id JOIN Podzial_Zadan as PZ ON Z.id = Zadania_id 
    JOIN Pracownicy as P ON P.id = PZ.Pracownicy_id JOIN users as U ON P.id = U.Pracownicy_id WHERE username = '$user' ORDER BY (O.numer, L.miejscowosc, L.ulica)");
    if (pg_num_rows($query) == 0) {
        Print "<h3> Brak danych </h3>";
    } else {
        Print '<table border="1px" width="100%">';
        Print '<tr>';
            Print '<th>Numer Obszaru</th>';
            Print '<th>Miejscowosc</th>';
            Print '<th>Ulica</th>';
            Print '<th>ID Lokalizacji</th>';
        Print '<tr>';
        $lok = -1;
        while($row = pg_fetch_array($query)) {
            if($row['numer'] != NULL and $lok != $row['numer']){
                $lok = $row['numer'];
                Print "<tr>";

                Print '<td align="center">' . $row['numer'] . "</td>";
                Print '<td align="center">' . $row['miejscowosc'] . "</td>";
                Print '<td align="center">' . $row['ulica'] . "</td>";
                Print '<td align="center">' . $row['lid'] . "</td>";

                Print "</tr>";

            }
        }
    }

   pg_close($conn);
?>
</table>
<br/>

</body>
</html>