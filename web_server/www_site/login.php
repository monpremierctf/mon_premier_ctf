<?php

    session_start ();
    if (isset($_POST['login']) && isset($_POST['password'])) {


        //$conn = mysqli_connect("mysql_webserver","root","AZ56FG78HJZE34","dbctf");
        $mysqli = new mysqli("mysql_webserver", "root", "AZ56FG78HJZE34", "dbctf");
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $login = mysqli_real_escape_string($mysqli, $_POST['login']);
        $passwd = md5($_POST['password']);

        $request = "SELECT * FROM users WHERE login='" . $login . "' and passwd = '". $passwd."'";
        //echo $request;
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        if($count>0) {
            // dans ce cas, tout est ok, on peut démarrer notre session
           
            // on enregistre les paramètres de notre visiteur comme variables de session ($login et $pwd) (notez bien que l'on utilise pas le $ pour enregistrer ces variables)
            $_SESSION['login'] = $_POST['login'];

            // on redirige notre visiteur vers une page de notre section membre
            header ('location: index.php');
        }
        else {
            // Le visiteur n'a pas été reconnu comme étant membre de notre site. On utilise alors un petit javascript lui signalant ce fait
            echo '<body onLoad="alert(\'Login/password non valides...\')">';
            // puis on le redirige vers la page de login
            echo '<meta http-equiv="refresh" content="0;URL=login.php">';
        }
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Y0L0 CTF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<?php
    include "Parsedown.php";
    $Parsedown = new Parsedown();
?>

<div class="jumbotron text-center">
  <h1>Y0L0 CTF</h1>
  <p>Mon premier CTF !</p> 
</div>
  
<div class="container-fluid">
    <div class="row">
        
        <div class="col">
        <div class="container">



    <div class="col-sm-4 text-center">
	  <form action="login.php"  method="post">
		<div class="form-group text-left">
		  <label for="usr">Login</label>
		  <input type="text" class="form-control" id="login" name="login">
        </div>
        <div class="form-group text-left">
		  <label for="usr">Password</label>
		  <input type="text" class="form-control" id="password" name="password">
		</div>
		<button type="submit" class="btn btn-primary">Login</button>
	  </form>
    </div>


        </div>
        </div>
    </div>
</div>


  
</body>
</html>



