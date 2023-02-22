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
<h2>Lokalizacje </h2>

<table border="1px" width="100%">
<tr>
    <th>ID Lokalizacji</th>
    <th>Miejscowosc</th>
    <th>Ulica</th>
</tr>

<?php
    $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
    if ($conn == false) {
        Print '<script>alert("Database connection error!");</script>';
        Print '<script>window.location.assign("home.php");</script>';
        exit();
    }

    $query = pg_query("SELECT * FROM Lokalizacje as L LEFT JOIN Obszary as O ON O.lokalizacje_id = L.id ORDER BY (O.numer, L.miejscowosc, L.ulica)");
    if (pg_num_rows($query) == 0) {
        Print "<h2> Brak danych </h2>";
    } else {
        $lok = -1;
        while($row = pg_fetch_array($query)) {
            if($lok !=  $row['id']){
                Print "<tr>";

                Print '<td align="center">' . $row['id'] . "</td>";
                Print '<td align="center">' . $row['miejscowosc'] . "</td>";
                Print '<td align="center">' . $row['ulica'] . "</td>";
                
                Print "</tr>";
            }
            $lok =  $row['id']; 
        }
    }

    Print '</table>';
    Print '<br/>';

    Print'<h2>Obszary</h2>';

    Print '<table border="1px" width="100%">';
    Print '<tr>';
        Print '<th>Numer Obszaru</th>';
        Print '<th>Miejscowosc</th>';
        Print '<th>Ulica</th>';
        Print '<th>ID Lokalizacji</th>';
    Print '<tr>';
    $query = pg_query("SELECT * FROM Lokalizacje as L LEFT JOIN Obszary as O ON O.lokalizacje_id = L.id ORDER BY (O.numer, L.miejscowosc, L.ulica)");
    if (pg_num_rows($query) == 0) {
        Print "<h2> Brak danych </h2>";
    } else {
        while($row = pg_fetch_array($query)) {
            if($row['numer'] != NULL){
                Print "<tr>";

                Print '<td align="center">' . $row['numer'] . "</td>";
                Print '<td align="center">' . $row['miejscowosc'] . "</td>";
                Print '<td align="center">' . $row['ulica'] . "</td>";
                Print '<td align="center">' . $row['id'] . "</td>";

                Print "</tr>";
            }
        }
    }
   pg_close($conn);
?>
</table>
<br/>

<a href="home.php"> Wróć do strony domowej</a>

</body>
</html>