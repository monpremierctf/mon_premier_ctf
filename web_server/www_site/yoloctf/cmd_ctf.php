<?php
    session_start ();
    require_once('ctf_mail.php');


    // Allowed to interact ?
    if ( ! isset($_SESSION['uid'] )) {
        echo "Ko, merci de vous connecter.";
        exit();
    }

    //
    // vaidation mail
    //
    if (isset($_GET['resendValidationMail'])){
        if ($_SESSION['status']!=='waiting_email_validation') {
            echo "Mail non nécessaire.";
            exit();
        }

        
        if (ctf_send_validation_mail($_SESSION['uid'], $_SESSION['mail'])) {
            echo "Mail envoyé.";
        } else {
            echo "Mail non envoyé.";
        }
        exit();
    }

    if ( ($_SESSION['status']!=='enabled')) {
        echo "Ko, Mail non validé ou Compte bloqué.";
        exit();
    }

    // Create CTF
    if (isset($_GET['create'])){
        // Get ctf ?
        $request = "SELECT * FROM ctfs WHERE UIDADMIN='$uid'";
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        if($count>0) {
            $row = $result->fetch_array();
            // id , creation_date datetime, UIDCTF VARCHAR(45) NULL, ctfname VARCHAR(200) NULL, UIDADMIN 
            $ctfname =  $row['ctfname'];
            $creation_date =  $row['creation_date'];
            $d = DateTime ($creation_date);
        }

        $creation_date = DateTime("now");
        $name = $_GET['create'];
        $request = "INSERT into ctfs (creation_date, UIDCTF, ctfname, UIDADMIN) VALUES ('$creation_date', 'AAZZEE', '$name','$pseudo', '".$_SESSION['uid']."');";
        $result = $mysqli->query($request);
        $count  = $result->affected_rows;
        if($result) {
            echo "CTF created"        ;
        } else {
            echo $request;
            printf("Insert failed: %s\n", $mysqli->error);
            exit();
        }
    }


    // change Passwd
    if ((isset($_GET['newpwd'])) and (isset($_GET['oldpwd']))){
        // Get ctf ?
        $newpwd = $_GET['newpwd'];
        $oldpwd = $_GET['oldpwd'];


 ===>>> tBC       
        $request = "UPDATE users SET passwd WHERE UID='$uid' FROM ctfs WHERE UIDADMIN='$uid'";
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        if($count>0) {
            $row = $result->fetch_array();
            // id , creation_date datetime, UIDCTF VARCHAR(45) NULL, ctfname VARCHAR(200) NULL, UIDADMIN 
            $ctfname =  $row['ctfname'];
            $creation_date =  $row['creation_date'];
            $d = DateTime ($creation_date);
        }

        $creation_date = DateTime("now");
        $name = $_GET['create'];
        $request = "INSERT into ctfs (creation_date, UIDCTF, ctfname, UIDADMIN) VALUES ('$creation_date', 'AAZZEE', '$name','$pseudo', '".$_SESSION['uid']."');";
        $result = $mysqli->query($request);
        $count  = $result->affected_rows;
        if($result) {
            echo "CTF created"        ;
        } else {
            echo $request;
            printf("Insert failed: %s\n", $mysqli->error);
            exit();
        }
    }

    
    

?>