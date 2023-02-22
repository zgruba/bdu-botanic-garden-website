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
<h2>Prace </h2>

<table border="1px" width="100%">
<tr>
    <th>ID Pracy</th>
    <th>Rodzaj</th>
    <th>Od kiedy</th>
    <th>Do kiedy</th>
    <th>Numer obszaru</th>
</tr>
<?php
    $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
    if ($conn == false) {
        Print '<script>alert("Database connection error!");</script>';
        Print '<script>window.location.assign("home.php");</script>';
        exit();
    }

    $query = pg_query("SELECT * FROM Prace ORDER BY id");
    if (pg_num_rows($query) == 0) {
        Print "<h2> Brak danych </h2>";
    } else {
        while($row = pg_fetch_array($query)) {
            Print "<tr>";

            Print '<td align="center">' . $row['id'] . "</td>";
            Print '<td align="center">' . $row['rodzaj'] . "</td>";
            Print '<td align="center">' . $row['od_kiedy'] . "</td>";
            Print '<td align="center">' . $row['do_kiedy'] . "</td>";
            Print '<td align="center">' . $row['obszar_numer'] . "</td>";
            
            Print "</tr>";
        }
    }

    Print '</table>';
    Print '<br/>';

    Print'<h2>Zadania</h2>';

    Print '<table border="1px" width="100%">';
    Print '<tr>';
        Print '<th>ID</th>';
        Print '<th>Data</th>';
        Print '<th>Polecenie</th>';
        Print '<th>Specjalizacja</th>';
        Print '<th>ID Pracy</th>';
    Print '<tr>';
    $query = pg_query("SELECT Z.id as zid, * FROM Zadania as Z LEFT JOIN Specjalizacje as S ON S.id = Z.specjalizacje_id ORDER BY zid");
    if (pg_num_rows($query) == 0) {
        Print "<h2> Brak danych </h2>";
    } else {
        while($row = pg_fetch_array($query)) {
            Print "<tr>";

            Print '<td align="center">' . $row['zid'] . "</td>";
            Print '<td align="center">' . $row['data'] . "</td>";
            Print '<td align="center">' . $row['polecenie'] . "</td>";
            Print '<td align="center">' . $row['nazwa'] . "</td>";
            Print '<td align="center">' . $row['prace_id'] . "</td>";

            Print "</tr>";
        
        }
    }
   pg_close($conn);
?>
</table>
<br/>

<a href="home.php"> Wróć do strony domowej</a>

</body>
</html>