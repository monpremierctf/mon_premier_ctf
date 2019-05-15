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

    echo "===================";
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,'http://host.docker.internal:8080/listChallengeBox/');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
    //curl_setopt ($curl_handle, CURLOPT_PORT , 8080);
    $json1 = curl_exec($curl_handle);
    curl_close($curl_handle);
    echo $json1;

    //$json = file_get_contents('http://localhost:8080/listChallengeBox');
    echo $json;
    $ret = json_decode($json);
    print $ret;

    $bob = file_get_contents_curl('http://localhost:8080/listChallengeBox');
    echo $bob;
    echo "===================";


    
        $yo = json_decode('{"a":1,"b":2,"c":3,"d":4,"e":5}', true);
        $yo = json_decode('[{"a":1,"b":2,"c":3,"d":4,"e":5}]', true);
        $yo = json_decode('[{"a":1,"b":2,"c":3,"d":4,"e":5}, {"a":1,"b":2,"c":3,"d":4,"e":5}]', true);
        $yo = json_decode("{'a':1,'b':2}", true);
        $yo = json_decode('[
            {"a":1,"b":2,"c":3,"d":4,"e":5,}, 
            {"a":1,"b":2,"c":3,"d":4,"e":5,}
            ]', true);
        $yo = json_decode('[
            {"Name":"/ctf-transfert_24","Id":"08b032246d024cf7924557f1eac6053ac97d8b169e5e2d312ab67a4f87819157","Uid":"CTF_UID_24","port":"32785"},
            {"Name":"/xtermjs3130_xtermjs_24","Id":"bf968f9e6b837e80e60c13cf61304da74873bd0246f13ce45ebc75400ddb8938","Uid":"CTF_UID_24","port":"32782"},
            {"Name":"/ctf-transfert_23","Id":"6bf6375f2fe92302b4b8dc15df9c4a5df2b035e59de97c2f9d8c59ce8d17c16f","Uid":"CTF_UID_23","port":"32781"},
            {"Name":"/xtermjs3130_xtermjs_23","Id":"555d6b2ea31abd4a802aa46511d855b87121262820e4319d5f7d4e9f6eb21b54","Uid":"CTF_UID_23","port":"32780"},
            {"Name":"/ctf-transfert_22","Id":"bedcb08e31098cd05f029404838f4ada3aaf047818f7b54cda5e47d51ae44217","Uid":"CTF_UID_22","port":"32779"},
            {"Name":"/xtermjs3130_xtermjs_22","Id":"7a1821caa7019f487b4bacd27fd6e3815028b64e15ad14cbf6a96e2496f87810","Uid":"CTF_UID_22","port":"32778"}]',true);
        
        var_dump($yo);
        echo "xxx================xx";

        print_r($yo[0]['Name']);
        print_r($yo[1]['Name']);
        echo "xxx================xx";

        foreach ($yo as $k=>$v){
            echo $v; // etc.
        }
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




