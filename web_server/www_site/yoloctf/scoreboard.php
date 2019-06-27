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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
	<script src="https://www.chartjs.org/samples/latest/scales/time/../../../../dist/2.8.0/Chart.min.js"></script>
	<script src="https://www.chartjs.org/samples/latest/scales/time/../../utils.js"></script>
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


       
        <div>
		<canvas id="canvas"></canvas>
	    </div>

<?php

function dumpFlagDataSet(){
		include "ctf_sql.php";

		$user_query = "SELECT login, UID FROM users;";
		if ($result = $mysqli->query($user_query)) {
			/* fetch object array */
			while ($row = $result->fetch_assoc()) {
				//UID,CHALLID, fdate, isvalid, flag
				//var_dump($row);
				$uid = $row['UID'];
				$login = $row['login'];
				//printf ("[%s] </br>", $uid);
				if ($uid!="") {
					//rgb(255, 99, 132)
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
	label: '$login',
	backgroundColor: color('rgb($r, $g, $b)').alpha(0.5).rgbString(),
	borderColor: 'rgb($r, $g, $b)',
	fill: false,
	data: [";					
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

					echo "],
				},";
				}
			}
		
			/* free result set */
			$result->close();
		}

		/* close connection */
		$mysqli->close();
	}
?>
        <script>
		var timeFormat = 'MM/DD/YYYY HH:mm';

		function newDate(days) {
			return moment().add(days, 'd').toDate();
		}

		function newDateString(days) {
			return moment().add(days, 'd').format(timeFormat);
		}

		var color = Chart.helpers.color;
		var config = {
			type: 'line',
			data: {
				labels: [ // Date Objects
					//newDate(0),
					//newDate(1),
					//new Date('2019-05-18T10:20:30Z'),
					//new Date('2019-05-18T20:20:30Z')
				],
				
				datasets: [
					/*{
					label: 'My First dataset',
					backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
					borderColor: window.chartColors.red,
					fill: false,
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor()
					],
				}, {
					label: 'My Second dataset',
					backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
					borderColor: window.chartColors.blue,
					fill: false,
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor()
					],
				}, {
					label: 'Dataset with point data',
					backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
					borderColor: window.chartColors.green,
					fill: false,
					data: [{
						x: newDateString(0),
						y: randomScalingFactor()
					}, {
						x: newDateString(5),
						y: randomScalingFactor()
					}, {
						x: newDateString(7),
						y: randomScalingFactor()
					}, {
						x: newDateString(15),
						y: randomScalingFactor()
					}],
				}
			
				,
				*/
				/* {
					label: 'Dataset with point data',
					backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
					borderColor: window.chartColors.green,
					fill: false,
					data: [*/
<?php dumpFlagDataSet(); ?>
			/*			
				],
				}
			*/]
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
		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myLine = new Chart(ctx, config);

		};
	



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
        echo "Merci de vous connecter.";
    }



 
?>
         </div>
        </div>
    </div>
</div>


  
</body>
</html>




