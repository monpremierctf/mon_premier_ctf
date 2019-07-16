<?php
    $admin = getenv('CTF_ADMIN_ACCOUNT')?getenv('CTF_ADMIN_ACCOUNT'):'admin';
    $passwd = getenv('MYSQL_USER_PASSWORD')?getenv('MYSQL_USER_PASSWORD'):'passwordforctfuser';
    $scoreboard_aff = getenv('CTF_SCOREBOARD_AFF')?getenv('CTF_SCOREBOARD_AFF'):'all';
    $use_mail = getenv('CTF_USE_MAIL')?getenv('CTF_USE_MAIL'):'false';
    
?>