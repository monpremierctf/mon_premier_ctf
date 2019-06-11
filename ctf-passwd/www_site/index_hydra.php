<!DOCTYPE html>
<html lang="fr">
<head>
  <title>CTF: Passw0rds v2.0</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/yoloctf/js/bootstrap.min.css">
  <link rel="stylesheet" href="/yoloctf/style.css">
</head>
<body>

<!--- Page Header  -->

<div class="col-sm-4 text-center">
    <?php
    include "passwd_utils.php";

    if (($_POST['login']=="admin") && ($_POST['password']=="jessica")) {
        passwd_access_ok("img/ok_2.jpg", "Flag_C0mm3_d4ns_un_m0ul1n");
    } else {
        passwd_login("Authentification V2.0", "img/guard_2.jpg");
    }
    ?>
</div>
</body>