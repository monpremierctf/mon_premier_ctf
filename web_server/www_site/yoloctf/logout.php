<?php
    session_start();
    unset($_SESSION['login']);
    unset($_SESSION);
    session_destroy();
    header('Location: index.php');
    die();
?>