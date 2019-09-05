<?php
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    header_remove("X-Powered-By");
    header("X-XSS-Protection: 1");
    header('X-Frame-Options: SAMEORIGIN'); 
    session_start ();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Y0L0 CTF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!--
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
-->
  <link rel="stylesheet" href="/yoloctf/js/bootstrap.min.css">
  <link rel="stylesheet" href="/yoloctf/style.css">
  <script src="/yoloctf/js/jquery.min.js"></script>
  <script src="/yoloctf/js/popper.min.js"></script>
  <script src="/yoloctf/js/bootstrap.min.js"></script>
  <script src="/yoloctf/js/ctf-utils.js"></script>
</head>
<body>

<!--- Page Header  -->
<?php
    //include "ctf_challenges.php";
    include "Parsedown.php";
    $Parsedown = new Parsedown();
    include 'header.php'; 

?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">Flag non validé</h5>
        
      </div>
      <div class="modal-body" id="myModalContent">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


<!-- Page -->
<div class="container-fluid">
    <div class="row">
        <!--- Page TOC  -->
        <div class="col-md-auto">
            <?php include 'toc.php' ?>
        </div>

        <!--- Page Content  -->
        <div class="col-10">
        <div class="row-md-auto">

        <?php
        
            $p = $_GET['p'];
            $intro = getIntro($p);
            if ($intro!=null) {
              /*
                if ((getLangage()=='en')&&(strlen($intro['description_en'])>0)) {
                   $string = $intro['description_en'];
                } else {
                   $string = $intro['description'];
                }
                */
                $string = getLocalizedIndex($intro, 'description');
                $string = pre_process_desc_for_md($string);
                print $Parsedown->text($string);
                print "<p class='chall-spacer'><p>";
                if ($intro['docker']!=null){
                    ctf_div_server_status($intro['docker']);
                }
                html_dump_cat($intro['category']);
            }
            elseif ($p==="Dashboard") {
                include "containers.php";
            }
            elseif ($p==="Profile") {
              include "p_profile.php";
            }
            elseif ($p==="Xterm") {
                include "my_term.php";
            }
            
            elseif ($p==="Welcome_validated") {
              $string = file_get_contents("p_welcome_validated.md");
              print $Parsedown->text($string);
          }
            elseif ($p==="Welcome_waiting_validation") {
                $string = file_get_contents("p_welcome_waiting_validation.md");
                print $Parsedown->text($string);
            }
            else {
                $string = file_get_contents("head_index.md");
                print $Parsedown->text($string);
            }

        ?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




