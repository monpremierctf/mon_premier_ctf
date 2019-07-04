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
$idmsg = "0";
if ($_GET['idmsg']) { $idmsg = $_GET['idmsg'];}

//
// connect to mysql
include "my_sql.php";

$conn = new mysqli($host, $user, $pass,$dbname);
if ($conn->connect_error) {
    die("Connection to mysql failed: " . $conn->connect_error);
} 
$sql = "SELECT msg FROM messages WHERE idmsg= $idmsg";

$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo "Message id [". $idmsg . "]</br><p>";
    while($row = $result->fetch_assoc()) {
        echo "<blockquote>" . $row['msg'] . "</blockquote>";
    }
    echo "</p>";
} else {
    echo "Unknown message id [". $idmsg . "]";
}
$conn->close();


print "</br></br><pre><code>Debug mode : ON</br>";
print "[SQL request :".htmlspecialchars($sql)."]</br>";
print "[num_rows=".$result->num_rows . "]</code></pre></br>";





?>



</article>
</body>


