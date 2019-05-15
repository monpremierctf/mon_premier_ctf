<?php
    session_start ();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Y0L0 CTF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

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

    if (isset($_SESSION['login'] )) {
        echo '
    <div class="container-fluid">
    <div class="row float-right">
        <div class="col-md-auto float-right">'.$_SESSION['login'].'</div>
        <button type="submit" class="col-md-auto btn btn-default col float-right btn-warning">Logout</button>
    </form>
    </div>
    </div>   '; 
    } else {
        echo '
        <div class="container-fluid">
    <div class="row float-right">
        <div class="col-md-auto float-right">anonymous</div>
        <button type="submit" class="col-md-auto btn btn-default col float-right btn-warning">Login</button>
    </form>
    </div>
    </div>  ';
    }
    function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    echo "=======1============";
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'http://challenge-box-provider:8080/listChallengeBox/');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
    //curl_setopt ($curl_handle, CURLOPT_PORT , 8080);
    $json1 = curl_exec($curl_handle);
    curl_close($curl_handle);
    echo $json1;
    $yo = json_decode($json1);
    var_dump($yo);
    echo "xxx================xx";

 
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




