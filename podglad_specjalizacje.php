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
<h2>Specjalizacje </h2>

<table border="1px" width="100%">
<tr>
    <th>ID Specjalizacji</th>
    <th>Nazwa</th>
</tr>
<?php
    $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
    if ($conn == false) {
        Print '<script>alert("Database connection error!");</script>';
        Print '<script>window.location.assign("home.php");</script>';
        exit();
    }

    $query = pg_query("SELECT * FROM Specjalizacje ORDER BY id ");
    if (pg_num_rows($query) == 0) {
        Print "<h2> Brak danych </h2>";
    } else {
        while($row = pg_fetch_array($query)) {
            Print "<tr>";

            Print '<td align="center">' . $row['id'] . "</td>";
            Print '<td align="center">' . $row['nazwa'] . "</td>";
            
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