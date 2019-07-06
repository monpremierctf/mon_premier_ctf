<?php
    $passwd = getenv('MYSQL_USER_PASSWORD')?getenv('MYSQL_USER_PASSWORD'):'passwordforctfuser';
    $mysqli = new mysqli("webserver_mysql", "ctfuser", $passwd, "dbctf");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

?>