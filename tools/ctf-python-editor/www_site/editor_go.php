<?php
if (isset($_POST['code'])) {

    // Read flags from ENV
    // php is not allowed to read env variables
    //$json = shell_exec('echo $FLAGS');
    //echo "Flag env =$json";
    //var_dump($json);

    // Read flags from File
    //if ($json=="") {
        if (file_exists("./flags.txt")) {
            $json = file_get_contents("./flags.txt");
        }
    //}
    //echo "Flag file =[$json]";
    //var_dump($json);


    // Default Flags
    if ($json=="") {
        $json = '[{"val":"1","flag":""},{"val":"2","flag":""}]';
    }
    //echo "Flag default =$json";
    //var_dump($json);


    //if (isset($_ENV['FLAGS'])) { 
    //    $json =  $_ENV['FLAGS']; 
    //}
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