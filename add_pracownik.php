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
       $imie = pg_escape_string($_POST['imie']);
       $nazwisko = pg_escape_string($_POST['nazwisko']);
       $domyslna_specjalizacja = pg_escape_string($_POST['domyslna_specjalizacja']);
      
       $query = pg_query("INSERT INTO Pracownicy
                    VALUES (DEFAULT,'$imie','$nazwisko', '$domyslna_specjalizacja')"); //SQL query
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