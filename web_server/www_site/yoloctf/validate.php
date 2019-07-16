<?php

    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    header_remove("X-Powered-By");
    header("X-XSS-Protection: 1");
    header('X-Frame-Options: SAMEORIGIN'); 
    session_start ();

    if (isset($_GET['code'])  {

        include "ctf_sql.php";

        $code = mysqli_real_escape_string($mysqli, $_POST['code']);

        $request = "SELECT * FROM mails WHERE code='$code'";
        //echo $request;
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        if($count>0) {
            // Le code existe dans la base
            $row = $result->fetch_array();
            $login =  $row['login'];
            $uid =  $row['UID'];
            // on enregistre les paramètres de notre visiteur comme variables de session ($login et $pwd) (notez bien que l'on utilise pas le $ pour enregistrer ces variables)
            $_SESSION['login'] = $login;
            $_SESSION['uid'] = $row['UID'];

            // on redirige notre visiteur vers une page de notre section membre
            header ('location: index.php');
        }
        else {
            // Le visiteur n'a pas été reconnu comme étant membre de notre site. On utilise alors un petit javascript lui signalant ce fait
            echo '<body onLoad="alert(\'Code non valide...\')">';
            // puis on le redirige vers la page de login
            echo '<meta http-equiv="refresh" content="0;URL=login.php">';
        }
    }
    
?>