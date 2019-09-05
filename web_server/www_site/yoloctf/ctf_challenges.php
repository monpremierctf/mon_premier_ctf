<?php

require_once('ctf_locale.php');

$string = file_get_contents("./db/challenges.json");
$challenges = json_decode($string, true);
$string = file_get_contents("./db/flags.json");
$flags = json_decode($string, true);
$string = file_get_contents("./db/files.json");
$files = json_decode($string, true);
$string = file_get_contents("./db/intros.json");
$intros = json_decode($string, true);
$string = file_get_contents("./db/hints.json");
$hints = json_decode($string, true);



function getChallengeCount(){
  global $challenges;
  return $challenges['count'];
}

function dumpChallengesNames(){
  global $challenges;
  foreach ($challenges['results'] as $c) {
    print_r($c['id']);
    print("\n");
    print_r($c['name']);
    print("\n");
  }
}

function getIntro($cat){
  global $intros;
  foreach ($intros['results'] as $i) {
    if ($i['category']==$cat){
      return $i;
    }
  }
  return null;
}

function getChallengeById($challId){
  global $challenges;
  foreach ($challenges['results'] as $c) {
    if ($c['id']==$challId){
      return $c;
    }
  }
  return null;
}


function isFlagValid($id, $flag){
  global $flags;
  foreach ($flags['results'] as $f) {
    if ($f['challenge_id']==$id) {
      //print "Found id";
      $a = trim($f['content']);
      $b = trim($flag);
      //var_dump ($a);
      //var_dump ($b);
      if (strcmp($a,$b)==0) {
        return true;
      }
      
    } 
  }  
  
  return false;
}

function getCategoryLabel($cat){
  $label="";
  $intro = getIntro($cat);

  if ($intro!=null) {
      $label = getLocalizedIndex($intro,'label');
  }
  return $label;
}

function getCategories(){
  global $challenges;
  $categories = array();
  foreach ($challenges['results'] as $c) {
    if (!in_array($c['category'], $categories)) {
      $categories[] = $c['category'];
    }
    
  }
  return $categories;
}

function debug() {
  global $challenges;
  global $flags;
  global $files;

  print_r($files);

  print_r($files['count']);
  print_r($files['results'][0]);
  foreach ($files['results'] as $f) {
      print_r($f['id']);
      print("\n");
      print_r($f['location']);
      print("\n");
  }
  print getChallengeCount();
}


function getChallengeFileLocation($challengeId) {
  global $files;

  foreach ($files['results'] as $f) {
    //echo $f['challenge_id']."==".$challengeId."\n";
    if ($f['challenge_id']==$challengeId) {
      //echo "ok";
      return $f['location'];
    }    
  }
  return "";
}


function pre_process_desc_for_md($desc)
{
  // Remplacer \r\n et \r par \n et mettre des espaces autour de ```
  $desc =  str_replace ("\r\n", "\n", $desc);
  $desc =  str_replace ("\r", "\n", $desc);
  $desc =  str_replace ("\n\n", "\n \n", $desc);
  $desc =  str_replace ("\n```\n", "\n ``` \n", $desc);
  $desc_out="";

  $is_in_code=false; // Ne pas mettre de </br> dans un bloc de code ```
  foreach(preg_split('~[\n]~', $desc) as $line) {
    if (trim($line)=='.') { $line=" ";}
    if (strpos($line, "```") !== false) {
      $desc_out = $desc_out.$line." \n ";
      $is_in_code = ! $is_in_code;
    } else {
      if ( $is_in_code) {
        $desc_out = $desc_out.$line." \n "; 
      } else {
        if (! ($desc_out=="" and $line=='')) {  // Si la première ligne est vide, on ne met pas de </br>
          $desc_out = $desc_out.$line."</br>\n "; 
        }
      }
    }
  } 
  return $desc_out;
}



function html_dump_cat($cat) {
  global $challenges;
  global $files;
  global $hints;
  global $Parsedown;

  foreach ($challenges['results'] as $c) {
    if ($c['category']==$cat) {
      print '<div class="ctf-chall-container">';
        
        // titre
        print '<div class="row chall-titre bg-secondary text-white">';
          print '<div class="col-sm text-left">';
          print getLocalizedIndex($c, 'name');
          print "</div>";
          print '<div class="col-sm text-right">';
          print ($c['value']);
          print "</div>";
        print "</div>";


        // Description
        print '<div class="ctf-chall-container chall-desc">';
        $desc = getLocalizedIndex($c, 'description');
      
        
        $server="";
        // YOP : FIX : Get from Intro
        if ($cat==="Terminal") {$server="ctf-shell"."_".$_SESSION['uid'];}
        if ($cat==="Ghost in the Shell") {$server="ctf-shell"."_".$_SESSION['uid'];}
        if ($cat==="Privilege Escalation") {$server="ctf-escalation"."_".$_SESSION['uid'];}
        if ($cat==="SQLi") {$server="ctf-sqli"."_".$_SESSION['uid'];}
        if ($cat==="Buffer overflows") {$server="ctf-buffer"."_".$_SESSION['uid'];}
        if ($cat==="File Upload") {$server="ctf-transfert"."_".$_SESSION['uid'];}
        if ($cat==="Exploit") {$server="ctf-exploit"."_".$_SESSION['uid'];}
        if ($cat==="Python") {$server="ctf-python"."_".$_SESSION['uid'];}

        if (isset($c['docker'])){
          if (($c['docker'])!="") {
            $server=$c['docker']."_".$_SESSION['uid'];
            
          }
        }        
        $desc = str_replace("IPSERVER", $server, $desc);
        $desc = str_replace("CTF_UID", $_SESSION['uid'], $desc);

        if (isset($_SERVER['HTTP_HOST'])) {
          $desc = str_replace("{IP_SERVER}", $_SERVER['HTTP_HOST'], $desc);
        }

        
        $desc_out = pre_process_desc_for_md($desc);
        //print $desc_out;
        print $Parsedown->text($desc_out);
        print "</div>";

        // Hints   
        foreach ($hints['results'] as $h) {
          if ($h['challenge_id']===$c['id']) {
            $desc = getLocalizedIndex($h, 'content');
            $desc = $Parsedown->text($desc);
            print '<div class="row chall-desc bg-light">';
            print '<div class="col-md-auto text-left">  <label for="usr">Indice:</label>  </div>
            <div class="col text-left"><label id="hint_'.$h['id'].'"  style="display: none;" >'.$desc.'</label></div>
            <div class="col-2 text-right"><button type="Button" class="btn btn-primary" onclick="ctf_toggle_hide(\'#hint_'.$h['id'].'\')">Afficher</button></div>';
            print "</div>";
            

          }
        }


        // Files
        foreach ($files['results'] as $f) {
          if ($f['challenge_id']===$c['id']) {
            print '<div class="row chall-desc bg-light">';
            print '
            <a href="downloadfile.php?id='.$f['challenge_id'].'" download>
            <button  class="btn btn-primary">Download '.basename($f['location']).'</button>
            </a>';
            print "</div>";
          }
        }
        // Server
        if (isset($c['docker'])){
          if (($c['docker'])!="") {
            //echo $c['docker'];
            ctf_div_server_status($c['docker']);
          }
        }

        // Flag
        print '<div class="row chall-desc bg-light">';
        print '
            <div class="col-md-auto text-left"><label for="usr">Flag:</label></div>
            <div class="col text-left"><input type="text" class="form-control" id="flag_'.$c['id'].'" name="code" onLoad="ctf_onload('.$c['id'].', \'#flag_'.$c['id'].'\')"></div>
            <script>$("#flag_'.$c['id'].'").ready(function(){ ctf_onload('.$c['id'].', \'#flag_'.$c['id'].'\') })</script>
            <div class="col-2 text-right"><button type="Submit" class="btn btn-primary" onclick="ctf_validate('.$c['id'].', \'#flag_'.$c['id'].'\')">Submit</button></div>';
        print "</div>";
        print '<div class="row chall-spacer">  </div>';
      print "</div>";
    }
  }     
}


function get_active_users(){
  $sp = ini_get("session.save_path");
  if ($sp=="") { $sp = "/tmp";}
  $h = opendir($sp);
  $nb_users = 0;
  if ($h== false) return 1;
  while (($file = readdir($h))!=false){
      if (preg_match("/^sess/", $file)) $nb_users++;
  }
  //$nb_users = count(scandir($sp))-2;
  return $nb_users;
}


function ctf_div_server_status($id) {

//echo '</br>';
//echo 'HTTP_CLIENT_IP='.$_SERVER['HTTP_CLIENT_IP'].'</br>';
//echo 'HTTP_X_FORWARDED_FOR='.$_SERVER['HTTP_X_FORWARDED_FOR'].'</br>';  // Ok IP src, placé par traefik
//echo 'REMOTE_ADDR='.$_SERVER['REMOTE_ADDR'].'</br>';
//echo 'HTTP_HOST='.$_SERVER['HTTP_HOST'].'</br>';
echo '     
<p></p>
<p>Démarrez votre serveur dédié en cliquant sur [Start server].</p>
</br>
<p id="ServerStatus'.$id.'">Server status : stopped</p>
</br>
<p><button type="button" class="btn btn-default btn-warning" id="StartServer'.$id.'" value="StartServer">Start Server</button>
<button type="button" class="btn btn-default btn-warning" id="StopServer'.$id.'" value="StopServer">Stop Server</button></p>

<script>
// Status at startup
$(document).ready(function() {


      $.get("containers_cmd.php?status='.$id.'",function(data) { 
          if (data=="ko_not_logged" || data =="Merci de vous connecter.") {
              $("#ServerStatus'.$id.'").html("Server status: Please log in..");
          } else if (data=="ko") {
              $("#ServerStatus'.$id.'").html("Server status: Problem... Cant start");
          } else  {
             var resp = JSON.parse(data);
             //$("#ServerStatus'.$id.'").html(resp.Name); 
             var txt = "Server status: Running as "+resp.Name;
             if (resp.Port  !== undefined ) { 
               if (resp.Port  !=0) {
                 txt = txt + "</br>Host="+window.location.host; 
                 txt = txt + "</br>Port="+resp.Port; 
                 //txt = txt + "</br></br>Accès avancé possible en : IP="+window.location.host+" -PORT="+resp.Port;
               }
             }
             $("#ServerStatus'.$id.'").html(txt);
          }
      });



});

// Start button
$(document).ready(function() {

    $("#StartServer'.$id.'").click(function(){
        $("#ServerStatus'.$id.'").html("Server status: Starting...");
        $.get("containers_cmd.php?create='.$id.'",function(data) { 
            if (data=="ko") {
                $("#ServerStatus'.$id.'").html("Server status: Problem... Cant start");
            } else  {
               $("#ServerStatus'.$id.'").html(data);
               var resp = JSON.parse(data);
               //$("#ServerStatus'.$id.'").html(resp.Name); 

                var txt = "Server status: Running as "+resp.Name;
                /*
                if (resp.Port  !== undefined ) { 
                  if (resp.Port  !=0) {
                    txt = txt + "</br>Host="+window.location.host; 
                    txt = txt + "</br>Port="+resp.Port; 
                    //txt = txt + "</br></br>Accès avancé possible en : IP="+window.location.host+" -PORT="+resp.Port;
                  }
                }
                */
                $("#ServerStatus'.$id.'").html(txt);

            }
        });

    }); 

});
$(document).ready(function() {
    // Stop button
    $("#StopServer'.$id.'").click(function(){
      $("#ServerStatus'.$id.'").html("Server status: Stopping...");
      $.get("containers_cmd.php?terminate='.$id.'",function(data) { 
          if (data=="ko") {
              $("#ServerStatus'.$id.'").html("Server status: Problem... Cant stop");
          } else  {
             $("#ServerStatus'.$id.'").html("Server status: "+data);
          }
      });
    }); 
});

</script>
';

}
?>
