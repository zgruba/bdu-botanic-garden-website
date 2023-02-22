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

       $old_pass = pg_escape_string($_POST['old_pass']);
       $new_pass = pg_escape_string($_POST['new_pass']);

        $check = pg_query("SELECT * FROM users WHERE username = '$user' and password = '$old_pass'");
        if (pg_num_rows($check) == 0){
            Print '<script>alert("Invalid old password!");</script>';
            Print '<script>window.location.assign("home.php");</script>';
            exit();
        }

       $query = pg_query("UPDATE users
                    SET password = '$new_pass'
                    WHERE username = '$user'");
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