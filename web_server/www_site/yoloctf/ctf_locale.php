<?php
    $ctf_labels["login_with_account"] = "Participant";
    $ctf_labels["login_without_account"] = 'Pas encore de compte ?';
    $ctf_labels["login_without_account_en"] = 'No account yet ?';
    $ctf_labels["login_create_account"]  = 'Créer un compte ';
    $ctf_labels["login_create_account_en"]  = 'Create account';
    $ctf_labels["login_invalid_credentials"] = "Login/password non valides...";
    $ctf_labels["login_invalid_credentials_en"] = "Invalid credentials...";

    require_once('ctf_lang.php');
    
    function getLocalizedIndex($tab,$index)
    {
        if ((getLangage()=='en')&&(strlen($tab[$index.'_en'])>0)) {
            $string = $tab[$index.'_en'];
        } else {
            $string = $tab[$index];
        }
        return $string;
    }

    
    function getLocalizedLabel($label)
    {
        global $ctf_labels;
        if ((getLangage()=='en')&&(strlen($ctf_labels[$label.'_en'])>0)) {
            $string = $ctf_labels[$label.'_en'];
        } else {
            $string = $ctf_labels[$label];
        }
        return $string;
    }
?>
