<?php
    include 'ctf_env.php'; 
    
    //  $passwd from ctf_env.php
    $mysqli = new mysqli("webserver_mysql", "ctfuser", $passwd, "dbctf");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
?>