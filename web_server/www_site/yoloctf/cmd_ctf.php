<?php
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    header_remove("X-Powered-By");
    header("X-XSS-Protection: 1");
    header('X-Frame-Options: SAMEORIGIN'); 
    header("Access-Control-Allow-Origin: *");
    //header("Content-Type: application/json; charset=UTF-8");

    session_start ();
    require_once('ctf_mail.php');
    require_once('ctf_sql.php');

    //////////////////////////////////////////////////////////////////////////////
    // MUST HAVE a UID
    //
    if ( ! isset($_SESSION['uid'] )) {
        //echo "Ko, merci de vous connecter.";
        // 401 Unauthorized
        http_response_code(401);
        echo json_encode(array("message" => "Please authenticate."));
        exit();
    }


    //
    // Resend validation mail
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

    //
    // Set  email address
    //
    if (isset($_GET['setEmail'])){
        $newmail = $_GET['setEmail'];
        // Check email format
        if (!filter_var($newmail, FILTER_VALIDATE_EMAIL)) {
            // 400 Bad Request
            http_response_code(400);
            echo json_encode(array("message" => "Bad email format."));
            exit();
        }
        $uid = $_SESSION['uid'];
        $request = "UPDATE users SET mail='$newmail' WHERE UID='$uid';";
        $result = $mysqli->query($request);
        if($result===true) {
            http_response_code(200);
            echo json_encode(array("message" => "eMail mis à jour."));
            $_SESSION['mail']=$newmail;
        } else {
            // 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "Erreur sql [".$request."] ".$mysqli->error));
            //echo $request;
            //printf("Update failed: %s %s\n", $mysqli->error, $result);
        }
        exit();
    }



    function username_exist($name) {
        global $mysqli;
        $request = "SELECT * FROM users WHERE login='$name'";
        $result = $mysqli->query($request);
        $count  = $result->num_rows;
        return ($count>0);
    }

    //
    // Set name
    //
    if (isset($_GET['setLogin'])){
        $desiredname = $_GET['setLogin'];
        // Whorst waf ever :)
        // Remove some char
        $desiredname = str_replace("'", "", $desiredname);

        // Escape string
        $newname = mysqli_real_escape_string($mysqli, $desiredname);

        
        // Check exist ?
        if (username_exist($newname)) {
            // 400 Bad Request
            http_response_code(400);
            echo json_encode(array("message" => "Existing name."));
            exit();
        }

        // Save new name
        $uid = $_SESSION['uid'];
        $request = "UPDATE users SET login='$newname' WHERE UID='$uid';";
        $result = $mysqli->query($request);
        if($result===true) {
            http_response_code(200);
            echo json_encode(array("message" => "Login mis à jour."));
            $_SESSION['login']=$newname;
        } else {
            // 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "Erreur sql [".$request."] ".$mysqli->error));
            //echo $request;
            //printf("Update failed: %s %s\n", $mysqli->error, $result);
        }
        exit();
    }


    //
    // change Passwd
    //
    if (isset($_GET['setPassword'])) { 
        $desired_passwd = $_GET['setPassword'];

        // Check email format
        if (strlen($desired_passwd)<3) {
            // 400 Bad Request
            http_response_code(400);
            echo json_encode(array("message" => "Too short."));
            exit();
        }

        $newpwd = md5($desired_passwd);

        $uid = $_SESSION['uid'];
        $request = "UPDATE users SET passwd='$newpwd' WHERE UID='$uid';";
        $result = $mysqli->query($request);
        if($result===true) {
            http_response_code(200);
            echo json_encode(array("message" => "Password mis à jour."));
        } else {
            // 500 Internal Server Error
            http_response_code(500);
            echo json_encode(array("message" => "Erreur sql [".$request."] ".$mysqli->error));
        }
        exit();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Account must be in state Enabled
    //
    if ( ($_SESSION['status']!=='enabled')) {
        //echo "Ko, Mail non validé ou Compte bloqué.";
        // 401 Unauthorized
        http_response_code(401);
        echo json_encode(array("message" => "Mail non validé ou Compte bloqué."));
        exit();
    }


    //
    // Create CTF
    //
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



    
    

?>