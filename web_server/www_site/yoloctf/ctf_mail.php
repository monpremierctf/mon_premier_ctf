<?php



require_once('ctf_mailer.php');


function datetime_get_seconds($interval)
{
    $days = $interval->format('%a');
    $seconds = 0;
    if($days){
        $seconds += 24 * 60 * 60 * $days;
    }
    $hours = $interval->format('%H');
    if($hours){
        $seconds += 60 * 60 * $hours;
    }
    $minutes = $interval->format('%i');
    if($minutes){
        $seconds += 60 * $minutes;
    }
    $seconds += $interval->format('%s');
    return $seconds;
}


function is_allowed_to_send_validation_mail() {
    $request_date =  $_SESSION['last_validation_mail_sent_date'];
    $d = new DateTime ($request_date);
    $now = new DateTime("now");
    $interval = $d->diff($now);
    $sec = datetime_get_seconds($interval);
    $sec_val = (int)$sec;
    //echo "request_date=$request_date sec=$sec ";
    
    // 1 code par 10s max
    if (($sec_val>10)||(! isset($_SESSION['last_validation_mail_sent_date']))) {
        $_SESSION['last_validation_mail_sent_date'] = date('Y-m-d H:i:s');;
        return true;
    }
    return false;   

}

/*

function check_already_requested_code_db($uid) {
    $request = "SELECT * FROM mails WHERE uid='$uid'";
    $result = $mysqli->query($request);
    $count  = $result->num_rows;
    if($count>0) {
        // L'utilisateur a déjà demandé un code
        $row = $result->fetch_array();
        $request_date =  $row['request_date'];
        $d = DateTime ($request_date);
        $now = DateTime("now");
        $interval = $d.date_diff($now);
        $sec = datetime_get_seconds($interval);
        echo $sec;

        // 1 code par 10s max
        if ($sec<10) {
            return True;
        }
        return False;
    }
    else {
        // C'est good
        return False;
    }

}

*/


function ctf_send_validation_mail($uid, $to) {
    
    // Check last sent code
    if (! is_allowed_to_send_validation_mail()) {
        return false;
    }

    $url = "https://".$_SERVER['HTTP_HOST']."/yoloctf/register.php?validate=".$uid;
    
    $subject = "[Yolo CTF] Account validation";
    $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>[Yolo CTF] Activation de compte</title>
    </head>
    <body>
    <div style="font-family: Arial, Helvetica, sans-serif; ">
        <h1>Activation de compte.</h1>
        
        <p>Pour activer votre compte sur Yolo CTF, veuillez cliquer sur ce lien : <a href="{{URL}}">{{URL}}</a></p>
    </div>
    </body>
    </html>';

    $htmlbody = $html; //file_get_contents('mail_contents.html');
    $htmlbody = str_replace("{{URL}}", $url, $htmlbody);
    //$altbody = "Pour activer votre compte sur Yolo CTF, veuillez cliquer sur le lien ci-dessous : ".$url;
    return ctf_send_gmail($to, $subject, $htmlbody, "");

}


/*

function send_validation_code($uid)
{
    // Check last sent code
    if (check_already_requested_code($uid)) {
        return;
    }

    // Save code in base
    $code = uniqid ("").uniqid ("");
    $request = "INSERT into mails (login, UID, mail, code, request_date) VALUES ('$login', '$uid', '$mail','$code', now())";
    $result = $mysqli->query($request);
    $count  = $result->affected_rows;
    if($result) {
        echo "Code saved in base";        
    } else {
        echo $request;
        printf("Insert failed: %s\n", $mysqli->error);
        exit();
    }

    // send
    send_mail($user, $uid, $mail, $code);

}



function send_mail($user, $uid, $mail, $code) {
    // Recipients
    $to = $mail; 

    // Subject
    $subject = 'YoloCTF - email validation';

    // Validation url
    $ref="https://yoloctf.org/yoloctf/validate.php?ref=$code";

    // Message
    $message = '
    <html>
    <head>
    <title>YoloCTF - email validation</title>
    </head>
    <body>
    <p>Pour valider votre mail, cliquez sur le lien ci-dessous</p>
    <a href="$ref">$ref</a>
    </body>
    </html>
    ';

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = "To: $user <$mail>";
    $headers[] = "From: YoloCTF <yoloctf@gmail.com>";
    $headers[] = "Cc: yoloctf@gmail.com";


    // Mail it
    $ret = mail($to, $subject, $message, implode("\r\n", $headers));
    if ($ret){
        echo 'Your mail has been sent successfully.';
    } else{
        echo 'Unable to send email. Please try again.';
    }
}

*/
?>