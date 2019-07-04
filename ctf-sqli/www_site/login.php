<!DOCTYPE html>
<html lang="fr">
<head>
  <title>McCoy Private Dungeon</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/yoloctf/js/bootstrap.min.css">
  <link rel="stylesheet" href="/yoloctf/style.css">
</head>
<body>

<div class="col-sm-6 text-center">


<h2>McCoy Private Dungeon</h2>
</br>




<?php 
//
// Get parameters
$login = "guest";
if ($_POST['login']) { $login = $_POST['login'];}

$passwd = ""; 
if ($_POST['passwd']) { $passwd = $_POST['passwd']; }



//
// connect to mysql
include "my_sql.php";


$conn = new mysqli($host, $user, $pass,$dbname);
if ($conn->connect_error) {
    die("Connection to mysql failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM users WHERE login='$login' AND passwd=md5('$passwd')";


$result = $conn->query($sql);



if ($login == "admin") {
   if ($passwd == "lambay") {
      print "GREETINGS PROFESSOR MCCAIN";
      print "HOW ARE YOU FEELING TODAY ?";
      print "IT S BEEN A LONG TIME";
   } else {
      print "Nicely tried. Good bye.";
   }
} elseif ($result->num_rows > 0) {
   print "<h3>Hello "  .$login ."</h3></br></br>";
   print "you have 1 message:</br>";
   print "=><a href=\"getmsg.php?idmsg=673489\">New Message</a></br>";
}  else {

    print "<img src=\"beware_cat05.png\" alt=\"Beware of the cat\">";
    print "</br></br>";
    print "<h4>Please authenticate</h4>";


    print "<form action='login.php' method=\"post\">";
    print "<div class='form-group text-left row-8'>";
    print "<label for='usr' class='col-2'>Login</label><input class='form-control' type='login' name='login' required='required'/></br>";
    print "</div>";
    print "<div class='form-group text-left'>";
    print "<label for='usr' class='col-2'>Password</label> <input class='form-control' type=\"passwd\" name=\"passwd\"/></br>";
    print "</div>";
    print "<input type=\"submit\" value=\"Submit\"/>";
    print "</form>";
}

print "</br></br><pre><code>Debug mode : ON</br>";
print "[SQL request :".htmlspecialchars($sql)."]</br>";
print "[num_rows=".$result->num_rows . "]</code></pre></br>";



?>


</div>
</body>


