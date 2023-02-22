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
       $rodzaj = pg_escape_string($_POST['rodzaj']);
       $od_kiedy = pg_escape_string($_POST['od_kiedy']);
       $do_kiedy = pg_escape_string($_POST['do_kiedy']);
       $nr_obszaru = pg_escape_string($_POST['Obszar_numer']);

       $query = pg_query("INSERT INTO Prace
                    VALUES (DEFAULT, '$rodzaj', '$od_kiedy', '$do_kiedy', '$nr_obszaru')"); //SQL query
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