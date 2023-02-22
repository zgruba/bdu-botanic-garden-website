<html>
    <head>
        <title>Ogród botaniczny</title>
    </head>
    <body>
        <!-- if logged in then home.php--> 
        <h2>Logowanie</h2>
        <a href="bd.php">Wróć do strony głównej</a>
        <br/><br/>
        <form action="checklogin.php" method="POST">
           Wpisz nazwę użytkownika: <input type="text" 
           name="username" required="required" /> <br/>
           Wpisz hasło: <input type="password" 
           name="password" required="required" /> <br/>
           <input type="submit" value="Zaloguj"/>
        </form>
    </body>
</html>