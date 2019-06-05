
<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Y0L0 CTF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/yoloctf/js/bootstrap.min.css">
  <link rel="stylesheet" href="/yoloctf/style.css">
</head>
<body>

<!--- Page Header  -->

    <div class="col-sm-4 text-center">

        
    <?php
    if (($_POST['login']=="admin") && ($_POST['password']=="admin")) {
        echo '
        <h3>Authentification OK</h3>
        <p><img src="img/ok_1.gif" alt="OK"></p>
        <div class="form-group text-left">
        <label for="usr">Flag_C3st_0ouv3rt</label>
        </div>';
    } else {
        echo '
        <h3>Authentification V1.0</h3>
        <p><img src="img/guard_1.jpg" alt="STOP"></p>
        <form action=""  method="post">
          <div class="form-group text-left">
            <label for="usr">Login</label>
            <input type="text" class="form-control" id="login" name="login">
          </div>
          <div class="form-group text-left">
            <label for="usr">Password</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>';
    }
    ?>

  


</div>
</body>