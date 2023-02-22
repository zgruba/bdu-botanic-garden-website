<?php
    session_start();
    if($_SESSION['user']){
        $user = $_SESSION['user'];
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

       $pracownik = pg_escape_string($_POST['Pracownicy_id']);

       $query = pg_query("UPDATE users
                    SET kierownik = 'true'
                    WHERE pracownicy_id = $pracownik");
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