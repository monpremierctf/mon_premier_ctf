<?php
    session_start ();
    
    
    function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    if (isset($_SESSION['login'] )) {
        if (isset($_GET['create'])){
            $url = 'http://challenge-box-provider:8080/createChallengeBox/?uid='.$_SESSION['uid'].'&cid='.$_GET['create'];
            //echo "==".$url."";
            $json1 = file_get_contents_curl($url);
            echo $json1;
        }
        if (isset($_GET['status'])){
            $url = 'http://challenge-box-provider:8080/statusChallengeBox/?uid='.$_SESSION['uid'].'&cid='.$_GET['status'];
            //echo "==".$url."";
            $json1 = file_get_contents_curl($url);
            echo $json1;
        }
        if (isset($_GET['terminate'])){
            $url = 'http://challenge-box-provider:8080/stopChallengeBox/?uid='.$_SESSION['uid'].'&cid='.$_GET['terminate'];
            //echo "==".$url."";
            $json1 = file_get_contents_curl($url);
            echo $json1;
        }
    } else {
        echo "Merci de vous connecter.";
    }

?>
