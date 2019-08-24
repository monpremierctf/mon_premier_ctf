<?php

// 
// To test : export $(grep -v '^#' ../../.env | xargs); php ctf_mailer.php
//

include 'ctf_env.php'; 

require (dirname(__FILE__).'/lib_mail/PHPMailer-5.2/class.phpmailer.php');
require (dirname(__FILE__).'/lib_mail/PHPMailer-5.2/class.smtp.php');


function test_send_gmail()
{
    ctf_send_gmail(
        'sebastien.josset@gmail.com', 
        'PHPMailer SMTP test', 
        file_get_contents('mail_contents.html'), 
        'This is a plain-text message body');

}


function ctf_send_gmail($to, $subject, $htmlbody, $altbody)
{
    global $ctf_mail_username, $ctf_mail_passwd, $ctf_mail_frommail, $ctf_mail_fromname;
    return send_gmail($ctf_mail_username, $ctf_mail_passwd, $ctf_mail_frommail, $ctf_mail_fromname, 
        $to, $subject, $htmlbody, $altbody);

}


function send_gmail($username, $passwd, $frommail, $fromname, $to, $subject, $htmlbody, $altbody='')
{
    global $ctf_mail_enabled;

    if ($ctf_mail_enabled !== 'true') {
        return false;
    }


    //SMTP needs accurate times, and the PHP time zone MUST be set
    //This should be done in your php.ini, but this is how to do it if you don't have access to that
    date_default_timezone_set('Etc/UTC');

    
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
    //Set the hostname of the mail server
    $mail->Host = "smtp.gmail.com";
    // secure transfer enabled REQUIRED for Gmail
    $mail->SMTPSecure = 'tls'; 
    //Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = 587;
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication
    $mail->Username = $username;
    //Password to use for SMTP authentication
    $mail->Password = $passwd;
    //Set who the message is to be sent from
    $mail->setFrom($frommail, $fromname);
    //Set an alternative reply-to address
    $mail->addReplyTo($frommail, $fromname);
    //Set who the message is to be sent to
    $mail->addAddress($to);
    //Set the subject line
    $mail->Subject = $subject;
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML($htmlbody, dirname(__FILE__));
    //Replace the plain text body with one created manually
    if (strlen($altbody) >0 ) 
    {
        $mail->AltBody = $altbody;
    }
    //Attach an image file
    //$mail->addAttachment('./player_02_200.png');

    //send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        //echo "Message sent!";
        return true;
    }
}

?>
