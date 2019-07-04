
<script>
// Status at startup
$(document).ready(function() {
      var timer = null;
      tt = 60;
      $.get("containers_cmd.php?status=1", function(data) { 
          if (data=="Ko") {
           
            txt = "Votre terminal s\'initialise. Il créé son réseau dédié et démarre une nouvelle instance... Ca prend du temps... </br> Dans "+tt+" secondes vous cliquerez sur le lien ci-dessous... Pas avant hein ?</br>Un refresh de cette page va vous freezer le butineur..</br></br>Xterm status: En cours de creation.. Patience... ";
            $("#XtermStatus").html(txt);

            timer = setInterval(function() {
                tt = tt-1;
                $('.Timer').text(tt + " Seconds");
                if (tt<30) {clearInterval(timer);}
            }, 1000);
            $.get("containers_cmd.php?create=1", function(data) { 
                if (data=="ko") {
                    $("#XtermStatus").html("Xterm status: Pb at creation");
                } else  {
                    var resp = JSON.parse(data);
                    //$("#ServerStatusctf-shell").html(resp.Name); 
                    var txt = "Xterm status: Running as "+resp.Name;
                    //txt = '<a href="https://'.$_SERVER['HTTP_HOST'].'/ctf-tool-xterm_'.$_SESSION['uid'].'/" ><pre>[Mon terminal]</pre></a>';
                    if (resp.Port  !== undefined ) { 
                    if (resp.Port  !=0) {
                        txt = txt + "</br>Host="+window.location.host; 
                        txt = txt + "</br>Port="+resp.Port; 
                        //txt = txt + "</br></br>Accès avancé possible en : IP="+window.location.host+" -PORT="+resp.Port;
                    }
                    }
                    $("#XtermStatus").html(txt);
                }
            }); 
          } else  {
             var resp = JSON.parse(data);
             var txt = "Xterm status: Running as "+resp.Name;
             $("#XtermStatus").html(txt);
          }
      }); 
      timer = setInterval(function() {
                tt = tt-1;
                $('.Timer').text(tt + " Seconds");
                if (tt<30) {clearInterval(timer);}
            }, 1000);

      
});


</script>


<?php
    
    if (isset($_SESSION['login'] )) {
        echo '<p id="XtermStatus">Server status : stopped</p></br>';
        //echo $_SESSION['uid'];
        //$json1 = file_get_contents_curl('http://challenge-box-provider:8080/createChallengeBox/?uid='.$_SESSION['uid'].'&cid=1');
        //echo $json1;
        //$yo = json_decode($json1, true);
        //var_dump($yo);
        //echo '<a href="http://localhost:'.$yo['Port'].'" target="_blank"><pre>[Mon terminal]</pre></a>';
        //echo '<a href="#" onclick="goPort('.$yo['Port'].'); " ><pre>[Mon terminal]</pre></a>';
        echo '<a href="https://'.$_SERVER['HTTP_HOST'].'/ctf-tool-xterm_'.$_SESSION['uid'].'/" target="_blank"><pre>[Cliquer ICI pour ouvrir mon terminal dans un nouvel onglet]</pre></a>';
        echo "</br>";
        echo "Sur le nouvel onglet de votre xterm: </br>";
        echo "- Si vous avez un '404 not found', l'instance n'est pas encore up. Atendez 60 secondes et faites un Refresh de la page.</br>";
        echo "- Si vous avez un 'Bad Gateway', l'instance est up et s'initialise. Attendez 10 secondes, et faites un Refresh de la page.</br></br>";
        echo "Les copier/coller dans le terminal se font avec le menu du click droit.</br>";
        echo "N'hésitez pas à redimensionner le nombre de colonnes de votre terminal.</br>";
        echo "Vous pouvez avoir autant d'onglets avec un terminal que nécessaire: clickez n fois sur le lien ci-dessus.</br>";
        //echo($yo['Name']);
        /*
        echo 'Accès avancé distant possible en : </br>';
        echo 'IP   : '.$_SERVER['HTTP_HOST'];
        echo "</br>";
        echo 'Port : '.$yo['Port'];
        echo "</br>";
        */
        
    } else {
        echo "Veuillez vous Identifier. Merci";
    }
    
?>
        

  