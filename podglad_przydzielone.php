<html>
<head>
    <title>Ogród botaniczny</title>
</head>

<?php
    session_start();
    if($_SESSION['user']){
    }
    else{ 
       header("location:bd.php");
    }
?>

<body>
<h2>Podgląd zleceń </h2>

<?php
    $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
    if ($conn == false) {
        Print '<script>alert("Database connection error!");</script>';
        Print '<script>window.location.assign("home.php");</script>';
        exit();
    }

    $query = pg_query("SELECT P.id as pid, Z.id as zid, * FROM Pracownicy as P JOIN Podzial_Zadan as PZ ON P.id = Pracownicy_id 
    LEFT JOIN Zadania as Z ON Z.id = Zadania_id JOIN Specjalizacje as S ON S.id = Specjalizacje_id ORDER BY pid, zid" );
    if (pg_num_rows($query) == 0) {
        Print "<h2> Brak danych </h2>";
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
            Print '<tr>';
            Print '<td align="center">' . $row['zid'] . "</td>";
            Print '<td align="center">' . $row['data'] . "</td>";
            Print '<td align="center">' . $row['polecenie'] . "</td>";
            Print '<td align="center">' . $row['nazwa'] . "</td>";
            Print '<td align="center">' . $row['prace_id'] . "</td>";
            Print '</tr>';
        }
    }
   pg_close($conn);
?>
</table>
<br/>
<a href="home.php"> Wróć do strony domowej</a>

</body>
</html>