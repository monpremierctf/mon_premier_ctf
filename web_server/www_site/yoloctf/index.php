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
<!--
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
-->
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

/*
            function print_ctf_intro($challenge){
                // Check challenge name
                echo "$challenge";
                if (in_array($challenge, getCategories())) {
                    $filename = "intro/".$challenge."/challenges_intro.md";
                    echo $filename;
                    if (file_exists($filename)) {
                        $string = file_get_contents($filename);
                        print $Parsedown->text($string);
                    }
                }
            }
  */              

            
            $p = $_GET['p'];
            $intro = getIntro($p);
            if ($intro!=null) {
                $string = $intro['description'];
                print $Parsedown->text($string);
                print "<p class='chall-spacer'><p>";
                if ($intro['docker']!=null){
                    ctf_div_server_status($intro['docker']);
                }
                html_dump_cat($intro['category']);
            }
            /*
            if ($p==="Ghost in the Shell") {
                //print_ctf_intro($p);
                $string = file_get_contents("head_shell.md");
                print $Parsedown->text($string);
                ctf_div_server_status(2);
                html_dump_cat($p);
            }
            elseif ($p==="Privilege Escalation") {
                $string = file_get_contents("head_pesc.md");
                print $Parsedown->text($string);
                ctf_div_server_status(4);
                html_dump_cat($p);
            }
            elseif ($p==="Network protocol") {
                $string = file_get_contents("head_net.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="SQLi") {
                $string = file_get_contents("head_sqli.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="Buffer overflows") {
                $string = file_get_contents("head_overflows.md");
                print $Parsedown->text($string);
                ctf_div_server_status(5);
                html_dump_cat($p);
            }
            elseif ($p==="Decode") {
                $string = file_get_contents("head_decode.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="Exploit") {
                $string = file_get_contents("head_exploit.md");
                print $Parsedown->text($string);
                ctf_div_server_status("ctf-exploit");
                html_dump_cat($p);
            }
            elseif ($p==="Password") {
                $string = file_get_contents("head_passwd.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="File Upload") {
                $string = file_get_contents("head_upload.md");
                print $Parsedown->text($string);
                ctf_div_server_status(6);
                html_dump_cat($p);
            }
            */
            elseif ($p==="Dashboard") {
                include "containers.php";
            }
            elseif ($p==="Welcome_1") {
                $string = file_get_contents("welcome_1.md");
                print $Parsedown->text($string);
            }
            else {
                $string = file_get_contents("head_index.md");
                print $Parsedown->text($string);
            }


            
        ?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




