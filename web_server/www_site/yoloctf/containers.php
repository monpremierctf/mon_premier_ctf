
<?php
    
    
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
        echo '<div class="col">    <div class="container">';
        echo "<h3>Challenge containers</h3>";

        $json1 = file_get_contents_curl('http://challenge-box-provider:8080/listChallengeBox/');
        //echo $json1;
        $yo = json_decode($json1, true);
        //var_dump($yo);
        foreach($yo as $item) {
            $name = $item["Name"];
            $port = $item["port"];
            echo '<div class="form-group text-left  row-6">';
            echo '<label for="usr" class="col-4">'.$name.'</label>';
            echo '<label for="usr" class="col-2">'.$port.'</label>';
            echo '</div>';
        }
        echo '</div></div>';
    } else {
        echo "Merci de vous connecter.";
    }

 

 
?>



