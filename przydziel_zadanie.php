<?php
    session_start();
    if($_SESSION['user']){
    }
    else{ 
       header("location:bd.php");
    }

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
        if ($conn == false) {
            Print '<script>alert("Database connection error!");</script>';
            Print '<script>window.location.assign("home.php");</script>';
            exit();
        }
       $Zadania_id = pg_escape_string($_POST['Zadania_id']);
       $Pracownicy_id_podzial = pg_escape_string($_POST['Pracownicy_id_podzial']);


       $query = pg_query("INSERT INTO Podzial_zadan
                    VALUES ('$Pracownicy_id_podzial', '$Zadania_id')"); //SQL query
        if ($query == false) {
            Print '<script>alert("Error executing query!");</script>';
            Print '<script>window.location.assign("home.php");</script>';
            exit();
        }
        if (pg_affected_rows($query) == 0) {
            Print '<script>alert("Query did not affect any rows.");</script>';
            Print '<script>window.location.assign("home.php");</script>';
            exit();
        } 
    }

   header("location:home.php");
   pg_close($conn);
?>