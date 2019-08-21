<?php
    $admin = getenv('CTF_ADMIN_ACCOUNT')?getenv('CTF_ADMIN_ACCOUNT'):'admin';
    $passwd = getenv('MYSQL_USER_PASSWORD')?getenv('MYSQL_USER_PASSWORD'):'passwordforctfuser';
    $scoreboard_aff = getenv('CTF_SCOREBOARD_AFF')?getenv('CTF_SCOREBOARD_AFF'):'all';
    // mail
    $ctf_mail_enabled  = getenv('CTF_MAIL_ENABLED')?getenv('CTF_MAIL_ENABLED'):'false';
    $ctf_mail_username = getenv('CTF_MAIL_USERNAME')?getenv('CTF_MAIL_USERNAME'):'yoloctf';
    $ctf_mail_passwd   = getenv('CTF_MAIL_PASSWD')?getenv('CTF_MAIL_PASSWD'):'passwdnotset';
    $ctf_mail_frommail = getenv('CTF_MAIL_FROMMAIL')?getenv('CTF_MAIL_FROMMAIL'):'yoloctf@gmail.com';
    $ctf_mail_fromname = getenv('CTF_MAIL_FROMNAME')?getenv('CTF_MAIL_FROMNAME'):'YoloCTF';
    $ctf_require_email_validation = getenv('CTF_REQUIRE_EMAIL_VALIDATION')?getenv('CTF_REQUIRE_EMAIL_VALIDATION'):'false';
?>