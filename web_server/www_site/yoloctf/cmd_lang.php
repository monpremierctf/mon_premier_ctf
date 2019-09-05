<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
header_remove("X-Powered-By");
header("X-XSS-Protection: 1");
header('X-Frame-Options: SAMEORIGIN'); 
session_start ();


require_once('ctf_lang.php');


if (isset($_GET['cmd'])) {
    if ($_GET['cmd']=="getLang") {
        echo getLangage();
    }
    if ($_GET['cmd']=="setLang") {
        if (isset($_GET['lang'])) {
            if ($_GET['lang']=="en") {
                setLangage("en");
            } else {
                setLangage("fr");
            }
        }
        echo $_SESSION['lang'];
    }
}

?>