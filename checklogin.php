<?php
    session_start();
    $username = pg_escape_string($_POST['username']);
    $password = pg_escape_string($_POST['password']);
    $bool = true;
   
    $conn = pg_connect("host=***** dbname=***** user=***** password=*****");
    if ($conn == false) {
       Print '<script>alert("Database connection error!");</script>';
       Print '<script>window.location.assign("db.php");</script>';
       exit();
    }
    $query = pg_query("Select * from users WHERE username='$username'"); // Query the 
                                                                            // users table
    if ($query == false) {
      Print '<script>alert("Error executing query!");</script>';
      Print '<script>window.location.assign("db.php");</script>';
      exit();
   }
                                                                         
    $exists = pg_num_rows($query); //Checks if username exists
    $table_users = "";
    $table_password = "";
    if($exists > 0) //IF there are no returning rows or no existing username
    {
       while($row = pg_fetch_assoc($query)) // display all rows from query
       {
          $table_users = $row['username'];     // the first username row is 
                                               // passed on to $table_users, 
                                               // and so on until the query is finished
          $table_password = $row['password'];  // the first password row is passed on 
                                               // to $table_password, and so on 
                                               // until the query is finished
       }
       if(($username == $table_users) && ($password == $table_password)) // checks if there 
                                                                  // are any matching fields
       {
          if($password == $table_password)
          {
             $_SESSION['user'] = $username;    // set the username in a session. 
                                               // This serves as a global variable
             header("location: home.php");     // redirects the user to the authenticated 
                                               // home page
          }
       }
       else
       {
        Print '<script>alert("Incorrect Password!");</script>';        // Prompts the user
        Print '<script>window.location.assign("login.php");</script>'; // redirects to login.php
       }
    }
    else
    {
        Print '<script>alert("Incorrect username!");</script>';        // Prompts the user
        Print '<script>window.location.assign("login.php");</script>'; // redirects to login.php
    }

    // Zamkniecie polaczenie z baza.
    pg_close($conn);
?>