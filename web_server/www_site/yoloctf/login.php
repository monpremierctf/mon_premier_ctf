<?php

    session_start ();
    if (isset($_POST['login']) && isset($_POST['password'])) {

        include "ctf_sql.php";

        $login = mysqli_real_escape_string($mysqli, $_POST['login']);
        $passwd = md5($_POST['password']);

        $request = "SELECT * FROM users WHERE login='" . $login . "' and passwd = '". $passwd."'";
        //echo $request;
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        if($count>0) {
            // dans ce cas, tout est ok, on peut démarrer notre session
            $row = $result->fetch_array();
            // on enregistre les paramètres de notre visiteur comme variables de session ($login et $pwd) (notez bien que l'on utilise pas le $ pour enregistrer ces variables)
            $_SESSION['login'] = $login;
            $_SESSION['uid'] = $row['UID'];

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
  
<div class="row">
    <div class="col-sm-1">
      
    </div>
    <div class="col-sm-4 text-center">
      <h3>Participant</h3>
      <p><img src="player_02_200.png" alt="Participant"></p>
      <form action="login.php"  method="post">
		<div class="form-group text-left">
		  <label for="usr">Login</label>
		  <input type="text" class="form-control" id="login" name="login">
        </div>
        <div class="form-group text-left">
		  <label for="usr">Password</label>
		  <input type="password" class="form-control" id="password" name="password">
		</div>
		<button type="submit" class="btn btn-primary">Login</button>
	  </form>
    </div>
    <div class="col-sm-4 text-center">
      <h3>Anonymous</h3>        
      <p><img src="admin_02_200.png" alt="Anonymous"></p>
      <form action="register.php"  method="post">
		<div class="form-group text-center">
		  Pas encore de compte ?
		</div>
		<button type="submit" class="btn btn-primary">Register</button>
	  </form>
    </div>
  </div>
</div>
  


  
</body>
</html>



