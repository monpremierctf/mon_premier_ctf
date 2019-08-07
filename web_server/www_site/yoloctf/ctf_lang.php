<?php

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
header_remove("X-Powered-By");
header("X-XSS-Protection: 1");
header('X-Frame-Options: SAMEORIGIN'); 
session_start ();

function get_lang_from_http() {
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

function setLangage($language){
  $_SESSION['lang']=$language;
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


if (isset($_GET['cmd'])) {
    if ($_GET['cmd']=="getLang") {
        echo getLangage();
    }
    if ($_GET['cmd']=="setLang") {
        if (isset($_GET['lang'])) {
            if ($_GET['lang']=="en") {
                $_SESSION['lang']=$_GET['lang'];
                setLangage("en");
            } else {
                $_SESSION['lang']="fr";
                setLangage("fr");
            }
        }
        echo $_SESSION['lang'];
    }
}

?>