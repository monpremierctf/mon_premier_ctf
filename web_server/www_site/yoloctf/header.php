
<!---- Includes ---->
<?php
require_once('ctf_challenges.php');
?>

<!---- Is Logged ---->
<?php if (isset($_SESSION['login'] )) { ?>
    
    <div class="container-fluid">
    <div class="col-md-1 float-right">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButtonLang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php print getLangage() ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonLang" id="MyLangList">
                <a class="dropdown-item" href="#" id="lang-fr">Français</a>
                <a class="dropdown-item" href="#" id="lang-en">English</a>
            </div>
        </div>
        <p><img class="row-md-auto float-center" src="img/player_02_200.png" width="80" height="80" alt="Participant" ></p>
        <div class="row-md-auto float-center font-weight-bold">
        <?php print  htmlspecialchars($_SESSION['login']) ?>
        </div>
        <button type="button" class="btn btn-default float-center btn-warning" id="Logout" value="Logout">Logout</button>
        </div>
    </div>   
    <script>
    $(document).ready(function() {
        $("#Logout").click(function(){
            alert("Deconnection");
            window.location.href = "logout.php";
        }); 
    });
    </script>

<!---- Is NOT Logged ---->
<?php } else { ?>
        
        <div class="container-fluid">
    <div class="row float-right">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButtonLang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php print getLangage() ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonLang"  id="MyLangList">
                <a class="dropdown-item" href="#" id="lang-fr">Français</a>
                <a class="dropdown-item" href="#" id="lang-en">English</a>
            </div>
        </div>
        <div class="col-md-auto float-right">anonymous</div>
        <button type="button" class="col-md-auto btn btn-default col float-right btn-warning" id="Login" value="Login">Login</button>
    </div>
    </div>  
    <script>
    $(document).ready(function() {
        $("#Login").click(function(){
            window.location.href = "login.php";
        }); 
        
    });
    </script>
 <?php } ?>   

<div class="jumbotron ctf-title text-center">
<h1 class="ctf-title-size">Y0L0 CTF</h1>
<p ><pre class="ctf-subtitle-size">Mon premier CTF</pre></p> 
</div>

<script>
    $(document).ready(function() {
       
        $("#lang-fr").click(function(e){
            $("#dropdownMenuButtonLang").html("fr");
            $.get( "ctf_lang.php?cmd=setLang&lang=fr", function( data, status ) {
                if (data=="fr") { window.location.reload(); }
            });
        
        });
        $("#lang-en").click(function(e){
            $("#dropdownMenuButtonLang").html("en");
            $.get( "ctf_lang.php?cmd=setLang&lang=en", function( data, status ) {
                if (data=="en") { window.location.reload(); }
            });
        });
    });
</script>
