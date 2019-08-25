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
  <link rel="stylesheet" href="style.css">
  <script src="/yoloctf/js/jquery.min.js"></script>
  <script src="/yoloctf/js/popper.min.js"></script>
  <script src="/yoloctf/js/bootstrap.min.js"></script>

  <script src="/yoloctf/js/moment.min.js"></script>
	<script src="/yoloctf/js/Chart.min.js"></script>
	<script src="/yoloctf/js/Chart_utils.js"></script>
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
    include "Parsedown.php";
    $Parsedown = new Parsedown();
	include 'header.php'; 
	require_once 'ctf_env.php'; 
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

function dumpUserFlagDataSet($uid){
	include "ctf_sql.php";
	$count=0;
	$query = "SELECT UID,CHALLID, fdate, isvalid, flag FROM flags WHERE UID='$uid';";
	if ($fresult = $mysqli->query($query)) {
		/* fetch object array */
		while ($frow = $fresult->fetch_assoc()) {
			//UID,CHALLID, fdate, isvalid, flag
			//var_dump($row);
			//printf ("%s (%s) (%s) (%s)</br>", $frow['UID'], $frow['flag'], $frow['isvalid'], $frow['fdate']);
			if ($frow['isvalid']) { 
				$chall = getChallengeById($frow['CHALLID']);
				if ($chall!=null){
					$count+=$chall['value'];
				} else {
					$count++;
				}
			}
			$dd = $frow['fdate'];
			$format = '%Y-%m-%d %H:%M:%S'; // 
			//$dd = '2019-05-18 15:32:15';
			//$d = strptime($dd , $format);
			$d = date_parse($dd);
			//$jsdate = "$d[tm_mon]/$d[tm_mday]/$d[tm_year] $d[tm_hour]:$d[tm_min]";
			$jsdate = "$d[month]/$d[day]/$d[year] $d[hour]:$d[minute]";
			//print_r($d);
			echo " { x: '$jsdate', y: $count},";
		}
		$fresult->close();
	}
}

function getNbUsers(){
	include "ctf_sql.php";
	
	$user_query = "SELECT count(*) as nbusers FROM users;";
	if ($user_result = $mysqli->query($user_query)) {
		$row = $user_result->fetch_assoc();
		//echo "Error: " . $mysqli->error . "<br>";
		//echo $row['nbusers'];
		return $row['nbusers'];
	}
	return 0;
}

function dumpFlagDataSetCurrentUser() {
	$r = 240; $g = 20;	$b = 80;
	echo "{
		label: '".htmlspecialchars($_SESSION['login'], ENT_QUOTES| ENT_HTML401)."',   
		backgroundColor: color('rgb($r, $g, $b)').alpha(0.5).rgbString(),
		borderColor: 'rgb($r, $g, $b)',
		fill: false,
		data: [";					
	dumpUserFlagDataSet($_SESSION['uid']);
	echo "],
	},";
}

function dumpFlagDataSet($pageId, $ctfuid='') {
		include "ctf_sql.php";
		$min = $pageId*20;
		
		if ($ctfuid==='') {
			$user_query = "SELECT login, UID FROM users LIMIT $min, 20;";
		} else {
			$user_query = "SELECT users.login, users.UID FROM users INNER JOIN ctfsusers ON users.UID = ctfsusers.UIDUSER WHERE ctfsusers.UIDCTF='$ctfuid' LIMIT $min, 20;";
		}

		if ($user_result = $mysqli->query($user_query)) {
			while ($row = $user_result->fetch_assoc()) {
				$uid = $row['UID'];
				$login = $row['login'];
				if ($uid!="") {

					if ($_SESSION['login']===$login){
						$r = 240;
						$g = 20;
						$b = 80;
					} else {
						$r = rand(0, 88);
						$g = 40+rand(0, 80);
						$b = 40+rand(0, 80);
					}
					
					echo "{
						label: '".htmlspecialchars($login, ENT_QUOTES| ENT_HTML401)."',
						backgroundColor: color('rgb($r, $g, $b)').alpha(0.5).rgbString(),
						borderColor: 'rgb($r, $g, $b)',
						fill: false,
						data: [";					
					dumpUserFlagDataSet($uid);
					echo "],
					},";
				}
			}
		
			/* free result set */
			$user_result->close();
		} else {
			echo "pb query";
		}

		/* close connection */
		$mysqli->close();
	}
?>
        

<?php
	


	function getNbUsersInCTF($uidctf)
	{
		include "ctf_sql.php";
		$uidctf_sqlsafe = mysqli_real_escape_string($mysqli, $uidctf);
		$request = "SELECT * FROM ctfsusers WHERE UIDCTF='$uidctf_sqlsafe'";
		$result = $mysqli->query($request);
		$count  = $result->num_rows;
		return $count;
	}

	
	if ($scoreboard_aff=='user_only') {
		// Online: User in a dynamic CTF
		if (isset($_SESSION['ctfuid'])&&($_SESSION['ctfuid']!=='')) {
			//echo "<div class='col-2'>dynamic CTF : ".$_SESSION['ctfname']."</div>";	
			$nbusers = getNbUsersInCTF($_SESSION['ctfuid']);
			$nbpages = floor($nbusers/20);
			//echo "<br>nbusers=$nbusers nbpages=$nbpages";
		// Online: User only
		} else {
			$nbusers = 1;
			$nbpages = 0;
		}
	// Offline : All users
	} else {
		$nbusers = getNbUsers();
		$nbpages = floor($nbusers/20);
	}
 	
	//
	// Let print the scoreboards with datas
	//
	for ($pageid = 0; $pageid <= $nbpages; $pageid++) { 
		echo "
			<div>
			<canvas id='canvas_$pageid'></canvas>
			</div>
		";
	}
	echo "
	<script>
		var timeFormat = 'MM/DD/YYYY HH:mm';

		function newDate(days) {
			return moment().add(days, 'd').toDate();
		}

		function newDateString(days) {
			return moment().add(days, 'd').format(timeFormat);
		}

		var color = Chart.helpers.color;
	";
	for ($pageid = 0; $pageid <= $nbpages; $pageid++) { 
		echo "
		var config_$pageid = {
			type: 'line',
			data: {
				labels: [],
				
				datasets: [	";	
		if ($scoreboard_aff=='user_only')		{
			if (isset($_SESSION['ctfuid'])&&($_SESSION['ctfuid']!=='')) {
				dumpFlagDataSet($pageid, $_SESSION['ctfuid']);
			} else {
				dumpFlagDataSetCurrentUser();
			}
		} else {
		 	dumpFlagDataSet($pageid);
		}		 
		echo "
				]
			},
			options: {
				title: {
					text: 'Scoreboard'
				},
				scales: {
					xAxes: [{
						type: 'time',
						time: {
							parser: timeFormat,
							// round: 'day'
							tooltipFormat: 'll HH:mm'
						},
						scaleLabel: {
							display: true,
							labelString: 'Date'
						}
					}],
					yAxes: [{
						scaleLabel: {
							display: true,
							labelString: 'Flags'
						}
					}]
				},
			}
		};";
	}	
	echo "window.onload = function() {";
	for ($pageid = 0; $pageid <= $nbpages; $pageid++) { 
		echo "
			var ctx_$pageid = document.getElementById('canvas_$pageid').getContext('2d');
			//window.myLine = new Chart(ctx, config_0);
			l_$pageid = new Chart(ctx_$pageid, config_$pageid);
			";
	}
	echo "	};";
	
?>	



	</script>

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




