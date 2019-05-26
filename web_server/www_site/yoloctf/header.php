

<?php
if (isset($_SESSION['login'] )) {
        echo '
    <div class="container-fluid">
    <div class="col-md-1 float-right">
        
        <p><img class="row-md-auto float-center" src="player_02_200.png" width="80" height="80" alt="Participant" ></p>
        <div class="row-md-auto float-center font-weight-bold">'.$_SESSION['login'].'</div>
        <button type="button" class="btn btn-default float-center btn-warning" id="Logout" value="Logout">Logout</button>
        </div>
    </div>   
    <script>
    $(document).ready(function() {
        $("#Logout").click(function(){
            alert("Logout");
            window.location.href = "logout.php";
        }); 
    });
    </script>
    '; 
    } else {
        echo '
        <div class="container-fluid">
    <div class="row float-right">
        <div class="col-md-auto float-right">anonymous</div>
        <button type="button" class="col-md-auto btn btn-default col float-right btn-warning" id="Login" value="Login">Login</button>
    </div>
    </div>  
    <script>
    $(document).ready(function() {
        $("#Login").click(function(){
            //alert("Login");
            window.location.href = "login.php";
        }); 
    });
    </script>';
    }
    echo'
    ';

?>

<div class="jumbotron text-center">
<h1>Y0L0 CTF</h1>
<p>Mon premier CTF !</p> 
</div>
