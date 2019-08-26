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



    include "ctf_sql.php";
    $uid = $_SESSION['uid'];

    //
    // Validate account
    // Url received by mail
    // https://localhost/yoloctf/register.php?validate=5d60eb2b3bbd4
    // status : waiting_email_validation -> enabled
    //
    if (isset($_GET['validate'])) {
          $arg_uid = $_GET['validate'];

        // Validate the loggued profile ?
        if ($_GET['validate'] === $uid){
            $_SESSION['status'] = 'enabled';
        }
        
        $request = "UPDATE users SET status = 'enabled' WHERE UID='$arg_uid' AND status='waiting_email_validation'; ";
        $result = $mysqli->query($request);
        $count  = $result->affected_rows;
        if ($result) {
            header ('location: index.php?p=Welcome_validated');
            // yop
            // redirect : acount xx validated
            exit();
        } else {
            echo $request;
            printf("Update failed: %s\n", $mysqli->error);
            exit();
        }
    }

    //
    // Register a new account
    //
    if (isset($_POST['login']) && isset($_POST['password']) ) {

        // Login, passwd too short ??
        if ((strlen($_POST['login'])<2) or (strlen($_POST['password'])<2)) {
            echo '<body onLoad="alert(\'Login ou mot de passe un peu court...\')">';
            echo '<meta http-equiv="refresh" content="0;URL=register.php">';
            exit();
        }

        // Invitation Code ??
        if (isset($ctf_register_code)&&($ctf_register_code!='')) {
            if(strtoupper($_POST['code'])!==strtoupper($ctf_register_code)) {
                echo '<body onLoad="alert(\'Code Invitation invalide\')">';
                echo '<meta http-equiv="refresh" content="0;URL=register.php">';
                exit();
            }
        }

         // CTF Code ??
         if (isset($_POST['code'])&&($_POST['code']!='')) {
            
        }
        
        include "ctf_mail.php";

        $login = mysqli_real_escape_string($mysqli, $_POST['login']);
        $passwd = md5($_POST['password']);
        $mail = mysqli_real_escape_string($mysqli, $_POST['mail']);
        $pseudo = mysqli_real_escape_string($mysqli, $_POST['pseudo']);


        // Login already exist ?
        $request = "SELECT * FROM users WHERE login='" . $login . "'";
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        if($count>0) {
            echo '<body onLoad="alert(\'Ce login est déjà existant\')">';
            echo '<meta http-equiv="refresh" content="0;URL=register.php">';
        }
        else {
            // On sauve tout ça dans la base
            $uid = uniqid ("");
            $status = 'enabled';
            // Send mail ?
            if ($ctf_require_email_validation =='true'){
                $status = 'waiting_email_validation';
                $to = $_POST['mail'];
                ctf_send_validation_mail($uid, $to);  
                
            }
            $_SESSION['status'] = $status;
            
            $request = "INSERT into users (login, passwd, mail, pseudo, UID, status) VALUES ('$login', '$passwd', '$mail','$pseudo', '$uid', '$status')";
            $result = $mysqli->query($request);
            $count  = $result->affected_rows;
            if ($result) {
                // on enregistre les paramètres de notre visiteur comme variables de session 
                $_SESSION['login'] = $login;
                $_SESSION['uid'] = $uid;
                // on redirige notre visiteur vers une page de bienvenue
                if ($ctf_require_email_validation =='true'){
                    header ('location: index.php?p=Welcome_waiting_validation');
                } else {
                    header ('location: index.php?p=Welcome_validated');
                }
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
        <?php if (isset($ctf_register_code)&&($ctf_register_code!='')) { ?>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Code Invitation (*)</label>
		  <input type="text" class="col-6 form-control" id="code" name="code">
          <label for="usr" class="col-2"></label>
		</div>
        <?php } ?>
		<button type="submit" class="btn btn-primary" onclick="return checkRegisterForm()">Register</button>
	  </form>
    </div>


    <script>
        function checkRegisterForm()
        {
            // Check name is available

            // Check fields are filled
            
            //alert("checkRegisterForm");
            return true;
        }
    </script>


        </div>
        </div>
    </div>
</div>


  
</body>
</html>



