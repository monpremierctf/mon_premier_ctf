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

<!--- Page Header  -->
<?php
    include "Parsedown.php";
    $Parsedown = new Parsedown();
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




        
<?php
    $UID="0";
    $login="guest";
    if (isset($_SESSION['login'] )) {
        $login=$_SESSION['login'];
        $UID = $_SESSION['uid'];
    }
    if (isset($_POST['text'])) {
       
        include "ctf_sql.php";

        $name = mysqli_real_escape_string($mysqli, $_POST['name']);
        $mail = mysqli_real_escape_string($mysqli, $_POST['mail']);
        $text = mysqli_real_escape_string($mysqli, $_POST['text']);

        
        $request = "INSERT into feedbacks (name, mail, txt, UID, login) VALUES ('$name', '$mail', '$text','$UID', '$login')";
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
         //echo "Error: " . $mysqli->error . "<br>";
        echo '<body onLoad="alert(\'Merci pour votre commentaire.\')">';
    } 

?>    


    <div class="col-sm-10 text-center">
    <form action="feedback.php"  method="post">
        <div class="form-group text-left row">
        <label for="usr" class="col-2">Nom</label>
        <input type="text" class="col-6 form-control" id="name" name="name" value="<?php echo isset($_SESSION['login'])?htmlspecialchars($_SESSION['login']):"Guest"; ?>">
        <label for="usr" class="col-2"></label>
        </div>
        <div class="form-group text-left  row ">
        <label for="usr" class="col-2">Mail</label>
        <input type="text" class="col-6 form-control" id="mail" name="mail" value="<?php echo isset($_SESSION['mail'])?htmlspecialchars($_SESSION['mail']):""; ?>">
        <label for="usr" class="col-2"></label>
        </div>
        <div class="form-group text-left  row ">
        <label for="usr" class="col-2">Feedback</label>
        <textarea class="form-control col-6" rows="5" id="comment" name="text" required></textarea>
        <label for="usr" class="col-2"></label>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    </div>




<?php

function dumpUserList(){
    echo "<h4>Feedbacks</h4></br>";	
    include "ctf_sql.php";
    $user_query = "SELECT name, mail, txt, UID, login FROM feedbacks;";
    if ($result = $mysqli->query($user_query)) {
        while ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['name']);
            $mail = htmlspecialchars($row['mail']);
            $text = htmlspecialchars($row['txt']);
            $login = htmlspecialchars($row['login']);            
            $uid = $row['UID'];

            echo "[".htmlspecialchars($login)."]  ".$uid."</br>";	
            echo htmlspecialchars($text)."</br></br>";	
        }
        $result->close();
    }
    $mysqli->close();
}


    if (isset($_SESSION['login'] )) {     
        $admin = getenv('PHP_ADMIN_ACCOUNT')?getenv('PHP_ADMIN_ACCOUNT'):'admin';
        if (($_SESSION['login']=== $admin )) {
            /// Show feedbacks
            dumpUserList();
        }
    }
    
    
?>    
 

         </div>
        </div>
    </div>
</div>


  
</body>
</html>




