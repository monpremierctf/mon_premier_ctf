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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>
<body>


<div class="jumbotron text-center">


    <h1>Y0L0 CTF</h1>
    <p>Mon premier CTF !</p> 
</div>










<div class="container-fluid">
    <div class="row">
        <div class="col-md-auto">
            <?php
            include ("ctf_challenges.php");

            print '<a href="index.php">';
                print "<pre>";
                print "Intro";
                print "</pre>";
                print '</a> ';
            foreach(getCategories() as $cat){
                print '<a href="index.php?p='.$cat.'">';
                print "<pre>";
                print ($cat);
                print "</pre>";
                print '</a> ';
            }
            print '<a  ><pre> </pre></a> ';
            print '<a href="http://localhost:3000" target="_blank"><pre>[Mon terminal]</pre></a> ';
  
            ?>
        </div>

        <div class="col">
        <div class="container">

       

<?php
    include "Parsedown.php";
    $Parsedown = new Parsedown();

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

    //echo "=======1============</br>";
    $json1 = file_get_contents_curl('http://challenge-box-provider:8080/listChallengeBox/');
    //echo $json1;
    $yo = json_decode($json1, true);
    //var_dump($yo);
    foreach($yo as $item) {

        echo($item['Name']);
        echo($item['Port']);
        echo "</br>";

    }
    //echo "xxx================xx";

 
?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




