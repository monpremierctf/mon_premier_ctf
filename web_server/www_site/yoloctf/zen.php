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
    include 'ctf_env.php'; 
    include "Parsedown.php";
    $Parsedown = new Parsedown();
    include 'header.php'; 

?>


<div class="container-fluid">
    <div class="row">
        <!--- Page TOC  -->
        <div class="col-md-auto">
            <?php include 'toc.php' ?>
        </div>

        <!--- Page Content  -->
        <div class="col">
        <div class="container">


<?php
    


    function dumpUserList(){
        include "ctf_sql.php";
        $user_query = "SELECT login, UID FROM users;";
        if ($result = $mysqli->query($user_query)) {
            while ($row = $result->fetch_assoc()) {
                $uid = $row['UID'];
                $login = $row['login'];
                echo "[".htmlspecialchars($login)."]  ".$uid."</br>";	
            }
            $result->close();
        }
        $mysqli->close();
    }


    function dumpUserFlags() {
        include "ctf_sql.php";
		$user_query = "SELECT login, UID FROM users;";
		if ($user_result = $mysqli->query($user_query)) {
			while ($row = $user_result->fetch_assoc()) {
				$uid = $row['UID'];
				$login = $row['login'];
                echo "</br><u>[".htmlspecialchars($login)."]  ".$uid."</u></br>";	
                $query = "SELECT UID,CHALLID, fdate, isvalid, flag FROM flags WHERE UID='$uid';";
                if ($fresult = $mysqli->query($query)) {
                   
                    while ($frow = $fresult->fetch_assoc()) {
                        $chall = getChallengeById($frow['CHALLID']);
                        if ($frow['isvalid']) {
                            printf ("%s (%s) (%s): ok</br>", $frow['fdate'], $frow['CHALLID'], $chall['name']);
                        } else {
                            printf ("%s (%s) (%s) </br>", $frow['fdate'], $frow['CHALLID'], htmlspecialchars($frow['flag']));
                        }
                    }
                    $fresult->close();	
                }		
			}
			$user_result->close();
		}
		$mysqli->close();
    }
    
    function clearFlags(){
        include "ctf_sql.php";
		$query = "DELETE FROM flags;";
		if ($result = $mysqli->query($query)) {
			
		}
		$mysqli->close();

    }

    function clearUsers(){
        include "ctf_sql.php";
        clearFlags();
		$query = "DELETE FROM users  where login!='$admin';";
		if ($result = $mysqli->query($query)) {
			
		}
		$mysqli->close();

    }

    function dumpUserContainersList($cont){
        include "ctf_sql.php";
        $user_query = "SELECT login, UID FROM users;";
        if ($result = $mysqli->query($user_query)) {
            while ($row = $result->fetch_assoc()) {
                $uid = $row['UID'];
                $login = $row['login'];
                echo "<u>[".htmlspecialchars($login)."]  ".$uid."</u></br>";	
                if ($cont != null)	{
                    foreach ($cont as $c) {
                        if ('CTF_UID_'.$uid == $c->Uid) {
                            echo "    - ".$c->Name."</br>";
                        }
                    }
                }
            }
            $result->close();
        }
        $mysqli->close();
    }
?>



<?php
    
    
    function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        //curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }



    if (isset($_SESSION['login'] )) {
        // $admin from ctf_env.php
        if (($_SESSION['login']=== $admin )) {
            // Actions
            if (isset($_GET['clearFlags'])){
                clearFlags();
            }
            
            // Get containers
            $url = 'http://challenge-box-provider:8080/listChallengeBox/';
            $json = file_get_contents_curl($url);
            $cont = json_decode($json);

            echo "<h4>Php sessions</h4> ";
            echo "Nb sessions : ". get_active_users();

            echo "<h4>Users</h4> ";
            dumpUserList();
            print "</br>";

            echo "<h4>Flags submited</h4> ";
            dumpUserFlags();

            
            echo "<h4>Containers</h4> ";
            echo "Nb Containers = ".count($cont)."</br>";
            dumpUserContainersList($cont);
            
            echo "</br>".$json;
            
            echo "<h4>BDD</h4>";
            print '<a href="zen.php?clearFlags" ><pre class="ctf-menu-color">[ClearFlags]</pre></a> ';


        } else {

        }

            

    } else {
        //echo "Merci de vous connecter.";
    }



 
?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




