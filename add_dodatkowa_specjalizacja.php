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
       $id_prac = pg_escape_string($_POST['Pracownicy_id']);
       $id_spec = pg_escape_string($_POST['Specjalizacje_id']);

      $query = pg_query("INSERT INTO Specjalizacje_Pracownikow
                    VALUES ('$id_prac', '$id_spec')"); //SQL query
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