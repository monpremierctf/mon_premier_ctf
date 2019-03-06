<!doctype html><html>    
<head><meta http-equiv="X-UA-Compatible" content="IE=edge" />         
      <meta charset="utf-8">		
      <meta name = "viewport" content="width=device-width, initial-scale=1, minimal-ui">		
      <title>Private Vault</title>		
      <link rel = "stylesheet" href= "site.css" >               
      <style>            
        body {        
            box - sizing: border - box;        
            min - width: 200px;        
            max - width: 980px;    
            margin: 0 auto;    
            padding: 45px;    
        }		
      </style>	
</head>
<body>	
<article class="site-body">
<h1>McCoy Private Dungeon</h1>
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
$host = 'mysql';
$user = 'root';
$pass = 'rootpassword';
$dbname = 'dbmccoy';

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
   print "<h2>Hello "  .$login ."</h2></br></br>";
   print "you have 1 message:</br>";
   print "=><a href=\"getmsg.php?idmsg=673489\">New Message</a></br>";
}  else {

    print "<img src=\"beware_cat05.png\" alt=\"Beware of the cat\">";
    print "</br></br>";
    print "<h2>Please authenticate</h2>";
    print "<form action='login.php' method=\"post\">";
    print "Name     <input type=\"login\" name=\"login\" required=\"required\"/></br>";
    print "Password <input type=\"passwd\" name=\"passwd\"/></br>";
    print "<input type=\"submit\" value=\"Submit\"/>";
    print "</form>";
}

print "</br></br><pre><code>Debug mode : ON</br>";
print "[SQL request :".$sql."]</br>";
print "[num_rows=".$result->num_rows . "]</code></pre></br>";



?>

</article>
</body>


