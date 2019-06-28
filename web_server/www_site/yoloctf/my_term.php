<?php
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    header_remove("X-Powered-By");
    header("X-XSS-Protection: 1");
    header('X-Frame-Options: SAMEORIGIN'); 
    session_start ();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Y0L0 CTF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="/yoloctf/js/bootstrap.min.css">
  <link rel="stylesheet" href="/yoloctf/style.css">


  
  <script src="/yoloctf/js/jquery.min.js"></script>
  <script src="/yoloctf/js/popper.min.js"></script>
  <script src="/yoloctf/js/bootstrap.min.js"></script>
  <script src="/yoloctf/js/ctf-utils.js"></script>

</head>
<body>

<?php

    
    function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    
?>
<!--- Page Header  -->
<?php
    include 'header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <!--- Page TOC  -->
        <div class="col-md-auto">
            <?php include 'toc.php' ?>
        </div>

        <!--- Page Content  -->
        <div class="col">
        <div class="container">
        <script>
            function goPort(p){
                window.location.port=p;
                window.location.filepath="/";
                window.location.protocol="http";
                window.location.href="http://"+window.location.hostname+":"+p;
            }
        </script>

   <?php
    
    if (isset($_SESSION['login'] )) {
        //echo $_SESSION['uid'];
        $json1 = file_get_contents_curl('http://challenge-box-provider:8080/createChallengeBox/?uid='.$_SESSION['uid'].'&cid=1');
        //echo $json1;
        $yo = json_decode($json1, true);
        //var_dump($yo);
        //echo '<a href="http://localhost:'.$yo['Port'].'" target="_blank"><pre>[Mon terminal]</pre></a>';
        //echo '<a href="#" onclick="goPort('.$yo['Port'].'); " ><pre>[Mon terminal]</pre></a>';
        echo 'Votre terminal s\'initialise. Laissez lui 10 secondes et cliquez ici : <a href="https://'.$_SERVER['HTTP_HOST'].'/ctf-tool-xterm_'.$_SESSION['uid'].'/" ><pre>[Mon terminal]</pre></a>';
        echo "</br>";
        echo "Si vous avez un 'Bad Gateway', attendez 10 secondes, et faites un Refresh de la page.</br>";
        echo "Les copier/coller dans le teminal se font avec le menu du click droit.</br>";
        echo "N'hésitez pas à redimensionner le nombre de colonnes de votre terminal.</br>";
        //echo($yo['Name']);
        /*
        echo 'Accès avancé distant possible en : </br>';
        echo 'IP   : '.$_SERVER['HTTP_HOST'];
        echo "</br>";
        echo 'Port : '.$yo['Port'];
        echo "</br>";
        */
        
    } else {
        echo "Veuillez vous Identifier. Merci";
    }
    
?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




