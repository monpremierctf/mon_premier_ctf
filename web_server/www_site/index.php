
<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Y0L0 CTF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
       function ctf_validate(id, flag_field)
        {
            flag = $(flag_field).val();
            $.get( "is_flag_valid.php?id="+id+"&flag="+flag, function( data, status ) {
                //alert("Data[" + data + "] Status[" + status+"]");
                if (data=='ok') {
                    alert("Flag validé ! Félicitation !!!");
                    $(flag_field).css({ 'color': 'green' });
                } else {
                    alert("Flag non validé...");
                    $(flag_field).css({ 'color': 'red' });
                }
            })
        .fail(function() {
            $(flag_field).css({ 'color': 'black' });
            });
            ;
        }   
  </script>
</head>
<body>

<?php
    include "Parsedown.php";
    $Parsedown = new Parsedown();
    # echo $Parsedown->text('Hello _Parsedown_!'); 
    # prints: <p>Hello <em>Parsedown</em>!</p>
?>

<div class="jumbotron text-center">
  <h1>Y0L0 CTF</h1>
  <p>Mon premier CTF !</p> 
</div>
  
<div class="container-fluid">
    <div class="row">
        <div class="col-md-auto">
            <?php
            include ("ctf_challenges.php");

            print '<a href="index.php">';
                print "<pre>";
                print "Intro";
                print "</pre>";
                print '</a> ';
            foreach(getCategories() as $cat){
                print '<a href="index.php?p='.$cat.'">';
                print "<pre>";
                print ($cat);
                print "</pre>";
                print '</a> ';
            }
            print '<a  ><pre> </pre></a> ';
            print '<a href="http://localhost:3000" target="_blank"><pre>[Mon terminal]</pre></a> ';
  
            ?>
        </div>

        <div class="col">
        <div class="container">

        <?php
            $p = $_GET['p'];
            if ($p==="Ghost in the Shell") {
                $string = file_get_contents("head_shell.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="Privilege Escalation") {
                $string = file_get_contents("head_pesc.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="Network protocol") {
                $string = file_get_contents("head_net.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="SQLi") {
                $string = file_get_contents("head_sqli.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="Buffer overflows") {
                $string = file_get_contents("head_overflows.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="Decode") {
                $string = file_get_contents("head_decode.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            }
            elseif ($p==="File Upload") {
                $string = file_get_contents("head_upload.md");
                print $Parsedown->text($string);
                html_dump_cat($p);
            } else {
                $string = file_get_contents("head_index.md");
                print $Parsedown->text($string);
            }


            /*
            foreach(getCategories() as $cat){
                print'<!-- Categorie : Begin -->';
                print '<div class="container">';
                    print '<div class="row">';
                    print '<h4>';
                    print ($cat);
                    print "</h4>";
                    print "</div>";
                    
                    html_dump_cat($cat);
                    print '</br></br>';
                   
                print "</div>";
                print'<!-- Categorie : End -->';
            }
                */
        ?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




