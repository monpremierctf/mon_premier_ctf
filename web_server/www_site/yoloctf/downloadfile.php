<?php

    session_start ();
    include "ctf_challenges.php";

    $challengeId=$_GET['id'];
    $path = getChallengeFileLocation($challengeId);
    //echo $path;
    
    if ($path===""){
        die("Error: File not found.");
    }
    

    $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/yoloctf/uploads/".$path;
    //echo $attachment_location;
    if (file_exists($attachment_location)) {

        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($attachment_location));
        header("Content-Disposition: attachment; filename=".basename($attachment_location));
        readfile($attachment_location);
        die();        
    } else {
        die("Error: File not found.");
    } 


?>
