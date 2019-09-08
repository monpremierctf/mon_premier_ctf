<?php
    $admin = getenv('CTF_ADMIN_ACCOUNT')?getenv('CTF_ADMIN_ACCOUNT'):'admin';
    $passwd = getenv('MYSQL_USER_PASSWORD')?getenv('MYSQL_USER_PASSWORD'):'passwordforctfuser';

    $scoreboard_aff = getenv('CTF_SCOREBOARD_AFF')?getenv('CTF_SCOREBOARD_AFF'):'all';
    $ctf_locale_enabled = getenv('CTF_LOCALE_ENABLED')?getenv('CTF_LOCALE_ENABLED'):'false';
    // mail
    $ctf_mail_enabled  = getenv('CTF_MAIL_ENABLED')?getenv('CTF_MAIL_ENABLED'):'false';
    $ctf_mail_username = getenv('CTF_MAIL_USERNAME')?getenv('CTF_MAIL_USERNAME'):'yoloctf';
    $ctf_mail_passwd   = getenv('CTF_MAIL_PASSWD')?getenv('CTF_MAIL_PASSWD'):'passwdnotset';
    $ctf_mail_frommail = getenv('CTF_MAIL_FROMMAIL')?getenv('CTF_MAIL_FROMMAIL'):'yoloctf@gmail.com';
    $ctf_mail_fromname = getenv('CTF_MAIL_FROMNAME')?getenv('CTF_MAIL_FROMNAME'):'YoloCTF';
    $ctf_require_email_validation = getenv('CTF_REQUIRE_EMAIL_VALIDATION')?getenv('CTF_REQUIRE_EMAIL_VALIDATION'):'false';

    // Register require code
    $ctf_register_code = getenv('CTF_REGISTER_CODE')?getenv('CTF_REGISTER_CODE'):'';

    // Custom Header
    $ctf_title    = getenv('CTF_TITLE')?getenv('CTF_TITLE'):'YOLO CTF';
    $ctf_subtitle = getenv('CTF_SUBTITLE')?getenv('CTF_SUBTITLE'):'Mon premier CTF';
    $ctf_logo1 = getenv('CTF_LOGOFILE_1')?getenv('CTF_LOGOFILE_1'):'';
    $ctf_logo2 = getenv('CTF_LOGOFILE_2')?getenv('CTF_LOGOFILE_2'):'';
    $ctf_logo3 = getenv('CTF_LOGOFILE_3')?getenv('CTF_LOGOFILE_3'):'';
    $ctf_url1 = getenv('CTF_URL_1')?getenv('CTF_URL_1'):'';
    $ctf_url2 = getenv('CTF_URL_2')?getenv('CTF_URL_2'):'';
    $ctf_url3 = getenv('CTF_URL_3')?getenv('CTF_URL_3'):'';
?>