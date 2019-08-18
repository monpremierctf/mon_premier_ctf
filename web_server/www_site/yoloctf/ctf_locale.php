<?php
    $ctf_labels["login_with_account"] = "Participant";
    $ctf_labels["login_without_account"] = 'Pas encore de compte ?';
    $ctf_labels["login_without_account_en"] = 'No account yet ?';
    $ctf_labels["login_create_account"]  = 'Créer un compte ';
    $ctf_labels["login_create_account_en"]  = 'Create account';
    $ctf_labels["login_invalid_credentials"] = "Login/password non valides...";
    $ctf_labels["login_invalid_credentials_en"] = "Invalid credentials...";

    function get_lang_from_http(){
        $langs = array();
    
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
    
            if (count($lang_parse[1])) {
                // create a list like "en" => 0.8
                $langs = array_combine($lang_parse[1], $lang_parse[4]);
            
                // set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ($val === '') $langs[$lang] = 1;
                }
    
                // sort list based on value	
                arsort($langs, SORT_NUMERIC);
            }
        }
        return $langs;
    }
    
    function getLocalizedIndex($tab,$index)
    {
        if ((getLangage()=='en')&&(strlen($tab[$index.'_en'])>0)) {
            $string = $tab[$index.'_en'];
        } else {
            $string = $tab[$index];
        }
        return $string;
    }
    function getLangage() {
        // current Session
        if (isset($_SESSION['lang'] )) { 
            return $_SESSION['lang'];
        }
        // Stored in User profile
    
    
        // From HTTP request
        $langs = get_lang_from_http();
        $l= 'fr'; // Default french
        foreach ($langs as $lang => $val) {
        if (strpos($lang, 'fr') === 0) {
            $l= 'fr'; break;
        } 
        if (strpos($lang, 'en') === 0) {
            $l= 'en'; break;
        } 
        }
        
        $_SESSION['lang']=$l;
        return $l;
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