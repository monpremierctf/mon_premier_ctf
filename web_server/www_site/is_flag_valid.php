<?php
    include ("ctf_challenges.php");
    //print $_GET['id']." ".$_GET['flag'];
    if (isFlagValid($_GET['id'],trim($_GET['flag']))){
        print "ok";
    } else {
        print "ko";
    }
?>