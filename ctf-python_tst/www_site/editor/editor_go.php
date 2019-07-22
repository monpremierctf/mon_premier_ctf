<?php
if (isset($_POST['code'])) {

 
    $json = '[{"val":"1","flag":"flag1"},{"val":"2","flag":"flag2"}]';
    if (isset($_ENV['FLAGS'])) { 
        $json =  $_ENV['FLAGS']; 
    }
    $array = json_decode($json, true);
    //var_dump($array);
    //foreach ($array as $item) {
    //    var_dump($item);
    //}


    $code_b64 = $_POST['code'];
    $code = base64_decode ($code_b64);
    $file = fopen("/tmp/code.py", "w"); 
    fwrite($file , $code); //->__toString()
    fclose($file );
    $output = shell_exec('sudo -u yolo python /tmp/code.py 2>&1');
    echo "$ python /tmp/code.py\n".$output;

    foreach ($array as $item) {
        $output_flag =  $item['val'];
        //echo "item: ".$item['val'];
        if (substr($output, 0, strlen($output_flag)) === $output_flag) { 
            echo "Flag: ".$item['flag'];
        };
    }
} 
?>