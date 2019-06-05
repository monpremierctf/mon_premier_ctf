<?php

function passwd_access_ok($img, $flag) {
    print '
    <h3>Acces Granted</h3>
    <p><img src="'.$img.'" alt="OK"></p>
    <div class="form-group text-left">
    <label for="usr">'.$flag.'</label>
    </div>';
}




function passwd_login($title, $img) {
    echo '
    <h3>'.$title.'</h3>
    <p><img src="'.$img.'" alt="STOP"></p>
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