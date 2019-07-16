<?php

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

function check_already_requested_code($uid) {
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
        echo "Code saved in base"        
    } else {
        echo $request;
        printf("Insert failed: %s\n", $mysqli->error);
        exit();
    }

    // send
    send_mail($user, $uid, $mail, $code);

}

function send_mail($user, $uid, $mail, $code)
    // Recipients
    $to = "$mail"; 

    // Subject
    $subject = 'YoloCTF - email validation';

    // Validation url
    $ref="https://yoloctf.org/yoloctf/validate.php?ref=$code"

    // Message
    $message = "
    <html>
    <head>
    <title>YoloCTF - email validation</title>
    </head>
    <body>
    <p>Pour valider votre mail, cliquez sur le lien ci-dessous</p>
    <a href='$ref'>$ref</a>
    </body>
    </html>
    ";

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = "To: $user <$mail>";
    $headers[] = "From: YoloCTF <yoloctf@gmail.com>";
    $headers[] = "Cc: yoloctf@gmail.com";


    // Mail it
    $ret = mail($to, $subject, $message, implode("\r\n", $headers));
    if($ret)){
        echo 'Your mail has been sent successfully.';
    } else{
        echo 'Unable to send email. Please try again.';
    }
}

?>