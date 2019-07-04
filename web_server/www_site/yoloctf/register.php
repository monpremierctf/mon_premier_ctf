<?php

    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    header_remove("X-Powered-By");
    header("X-XSS-Protection: 1");
    header('X-Frame-Options: SAMEORIGIN'); 
    session_start ();


    function file_get_poke($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);  // 1s timeout
        //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }



    if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['code'])) {

        if ((strlen($_POST['login'])<2) or (strlen($_POST['password'])<2)) {
            // 
            echo '<body onLoad="alert(\'Login ou mot de passe un peu court...\')">';
            // puis on le redirige vers la page de login
            echo '<meta http-equiv="refresh" content="0;URL=register.php">';
           
            exit();
        }

        if(strtoupper($_POST['code'])!=="YOLO") {
            // Le visiteur n'a pas été reconnu comme étant membre de notre site. On utilise alors un petit javascript lui signalant ce fait
            echo '<body onLoad="alert(\'Code Invitation invalide\')">';
            // puis on le redirige vers la page de login
            echo '<meta http-equiv="refresh" content="0;URL=register.php">';
           
            exit();
        }

        include "ctf_sql.php";

        $login = mysqli_real_escape_string($mysqli, $_POST['login']);
        $passwd = md5($_POST['password']);
        $mail = mysqli_real_escape_string($mysqli, $_POST['mail']);
        $pseudo = mysqli_real_escape_string($mysqli, $_POST['pseudo']);

        $request = "SELECT * FROM users WHERE login='" . $login . "'";
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        if($count>0) {
            // Le visiteur n'a pas été reconnu comme étant membre de notre site. On utilise alors un petit javascript lui signalant ce fait
            echo '<body onLoad="alert(\'Ce login est déjà existant\')">';
            // puis on le redirige vers la page de login
            echo '<meta http-equiv="refresh" content="0;URL=register.php">';
            
           
        }
        else {
            
            
            // On sauve tout ça
            $uid = uniqid ("");
            $request = "INSERT into users (login, passwd, mail, pseudo, UID) VALUES ('$login', '$passwd', '$mail','$pseudo', '$uid')";
            $result = $mysqli->query($request);
            $count  = $result->affected_rows;
            if($result) {
                // on enregistre les paramètres de notre visiteur comme variables de session ($login et $pwd) (notez bien que l'on utilise pas le $ pour enregistrer ces variables)
                $_SESSION['login'] = $login;
                $_SESSION['uid'] = $uid;
                // on redirige notre visiteur vers une page de notre section membre
                header ('location: index.php?p=Welcome_1');
            } else {
                echo $request;
                printf("Insert failed: %s\n", $mysqli->error);
                exit();
            }

            // create user Network
            //$dummy = file_get_poke('http://challenge-box-provider:8080/createChallengeBox/?uid='.$_SESSION['uid'].'&cid=1');
        }
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Y0L0 CTF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/yoloctf/js/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="/yoloctf/js/jquery.min.js"></script>
</head>
<body>

<?php
    include "Parsedown.php";
    $Parsedown = new Parsedown();
?>


<div class="jumbotron ctf-title text-center">
<h1 class="ctf-title-size">Y0L0 CTF</h1>
<p ><pre class="ctf-subtitle-size">Mon premier CTF</pre></p> 
</div>

  
<div class="container-fluid">
    <div class="row">
        
        <div class="col">
        <div class="container">



    <div class="col-sm-10 text-center">
	  <form action="register.php"  method="post">
		<div class="form-group text-left row">
		  <label for="usr" class="col-2">Login (*)</label>
		  <input type="text" class="col-6 form-control" id="login" name="login">
          <label for="usr" class="col-2">Votre identifiant de connection</label>
        </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Password (*)</label>
		  <input type="password" class="col-6 form-control" id="password" name="password">
          <label for="usr" class="col-2"></label>
        </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Mail</label>
		  <input type="text" class="col-6 form-control" id="mail" name="mail">
          <label for="usr" class="col-2"></label>
        </div>
        <!---
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Pseudo</label>
		  <input type="text" class="col-6 form-control" id="pseudo" name="pseudo">
          <label for="usr" class="col-2">Le Pseudo à afficher sur le tableau de score à la place du login.</label>
        </div>
        -->
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Code Invitation (*)</label>
		  <input type="text" class="col-6 form-control" id="code" name="code">
          <label for="usr" class="col-2"></label>
		</div>
		<button type="submit" class="btn btn-primary">Register</button>
	  </form>
    </div>


        </div>
        </div>
    </div>
</div>


  
</body>
</html>



