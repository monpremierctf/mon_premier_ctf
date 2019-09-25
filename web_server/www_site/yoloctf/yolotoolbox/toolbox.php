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
  <link rel="stylesheet" href="/yoloctf/js/bootstrap.min.css">
  <link rel="stylesheet" href="/yoloctf/style.css">
  <script src="/yoloctf/js/jquery.min.js"></script>
  <script src="/yoloctf/js/popper.min.js"></script>
  <script src="/yoloctf/js/bootstrap.min.js"></script>
  <script src="/yoloctf/js/ctf-utils.js"></script>
	<style>
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>


</head>
<body>

<!--- Page Header  -->
<?php
    include "../Parsedown.php";
    $Parsedown = new Parsedown();
    
    
    function pre_process_desc_for_md_doc($desc)
    {
    // Remplacer \r\n et \r par \n et mettre des espaces autour de ```
    $desc =  str_replace ("\r\n", "\n", $desc);
    $desc =  str_replace ("\r", "\n", $desc);
    $desc =  str_replace ("\n\n", "\n \n", $desc);
    $desc =  str_replace ("\n```\n", "\n ``` \n", $desc);
    $desc_out="";


    
    $is_in_code=false; // Ne pas mettre de </br> dans un bloc de code ```, ni en fin de liste - 
    foreach(preg_split('~[\n]~', $desc) as $line) {
        if (trim($line)=='.') { $line="</br>";}
        $desc_out = $desc_out.$line." \n";
        /*
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
        */
    } 
    return $desc_out;
    }

?>


<!---- Header container ---->  
<div class="container-fluid">

<div class="jumbotron ctf-title text-center">
<div class="row">
    
    <div class="col-md-4">
        <h1 class="row-md-4 ctf-title-size">YOLO CTF</h1>
        <p><pre class='row-md-4 ctf-subtitle-size'>Toolbox</pre></p>  
        <a href="/yoloctf/index.php"><pre class="row-md-4 text-left"><=== Back to CTF</pre></a>
    </div>
    
    
</div>
</div>


<?php
    function getH2($text) {
        $ret = array();
        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        # remove surrounding line breaks
        $text = trim($text, "\n");

        # split text into lines
        $lines = explode("\n", $text);
        foreach ($lines as $line){
            if (substr( $line, 0, 3 ) === "## ") {
                $line = trim($line);
                $ret[] = substr($line, 3);
            }

        }
        return $ret;
    }

    $menu = array(
        array('id'=>'Intro', 'name'=> 'Intro', 'file'=> 'p_intro.md'),
        array('id'=>'NetEnum', 'name'=> 'Net Enum', 'file'=> 'p_netenum.md'),
        array('id'=>'Apps', 'name'=> 'Application', 'file'=> 'p_apps.md'),
        array('id'=>'Shells', 'name'=> 'Reverse Shells', 'file'=> 'p_shells.md'),
        array('id'=>'EscUx', 'name'=> 'Escalation Ux', 'file'=> 'p_esc_ux.md'),
    );

    
    $filename = $menu[0]['file'];

    foreach ($menu as $topic) {
        if ($_GET['p']===$topic['id']) { $filename = $topic['file']; }
    }

    $file = file_get_contents($filename);
    $string = pre_process_desc_for_md_doc($file);
    $links = getH2($string);

?>

<div class="container-fluid">
    <div class="row">
        <!--- Page TOC  -->


        <div class="col-md-2 "> 
        <?php

        foreach ($menu as $topic) {
            print '<a href="toolbox.php?p='.$topic['id'].'"><pre class="ctf-menu-color">'.$topic['name'].'</pre></a> ';
            if ($filename === $topic['file']) {
                foreach ($links as $id) {
                    print '<a href="#'.str_replace(" ", "-", $id).'"><pre class="ctf-submenu-color ctf-submenu-size">- '.$id.'</pre></a> ';
                }
            }
        }


        
        ?>
        </div>

        <!--- Page Content  -->
        <div class="col-10">
        <div class="row-md-auto">

<?php

    print $Parsedown->text($string);

?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




