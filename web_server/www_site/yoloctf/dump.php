<?php
    session_start ();
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


<div class="jumbotron text-center">
  <h1>Y0L0 CTF</h1>
  <p>Mon premier CTF !</p> 
</div>
  
<div class="container-fluid">

<?php

    if ($_SESSION['login'] ==='admin') {
        include "ctf_sql.php";

        $request = "SELECT * FROM users";
        $result = $mysqli->query($request);
        if ($result->num_rows > 0) {
                echo '<div class="row">';
                echo '<div class="col-1">Id</div>';
                echo '<div class="col-2">Login</div>';
                echo '<div class="col-2">Mail</div>';
                echo '<div class="col-2">Pseudo</div>';
                echo '<div class="col-2">UID</div>';
                echo '</div>';
            while($row = $result->fetch_assoc()) {
                echo '<div class="row">';
                echo '<div class="col-1">'. $row["id"].'</div>';
                echo '<div class="col-2">'. htmlspecialchars($row["login"]).'</div>'; 
                echo '<div class="col-2">'. htmlspecialchars($row["mail"]).'</div>';
                echo '<div class="col-2">'. htmlspecialchars($row["pseudo"]).'</div>';
                echo '<div class="col-2">'. $row["UID"].'</div>';
                echo '</div>';
            }
        } else {
            echo "0 results";
        }
        $mysqli->close();
    }

?>
    
        
    
</div>


  
</body>
</html>



