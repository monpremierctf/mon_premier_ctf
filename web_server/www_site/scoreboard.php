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


       
        <div style="width:75%;">
		<canvas id="canvas"></canvas>
	    </div>

<?php
        //$conn = mysqli_connect("mysql_webserver","root","AZ56FG78HJZE34","dbctf");
		//$ mysql -u root -p'AZ56FG78HJZE34' -h 127.0.0.1 -P 3306 -D dbctf
        $mysqli = new mysqli("mysql_webserver", "root", "AZ56FG78HJZE34", "dbctf");
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

		$user_query = "SELECT UID FROM users;";
		if ($result = $mysqli->query($user_query)) {
			/* fetch object array */
			while ($row = $result->fetch_assoc()) {
				//UID,CHALLID, fdate, isvalid, flag
				//var_dump($row);
				$uid = $row['UID'];
				//printf ("[%s] </br>", $uid);
				if ($uid!="") {
					$count=0;
					$query = "SELECT UID,CHALLID, fdate, isvalid, flag FROM flags WHERE UID='$uid';";
					if ($fresult = $mysqli->query($query)) {
						/* fetch object array */
						while ($frow = $fresult->fetch_assoc()) {
							//UID,CHALLID, fdate, isvalid, flag
							//var_dump($row);
							//printf ("%s (%s) (%s) (%s)</br>", $frow['UID'], $frow['flag'], $frow['isvalid'], $frow['fdate']);
							if ($frow['isvalid']) { $count++;}
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
			}
		
			/* free result set */
			$result->close();
		}

		/* close connection */
		$mysqli->close();
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
					new Date('2019-05-18T10:20:30Z'),
					new Date('2019-05-18T20:20:30Z')
				],
				datasets: [{
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
			
				, {
					label: 'Dataset with point data',
					backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
					borderColor: window.chartColors.green,
					fill: false,
					data: [

						{ x: '5/18/2019 15:32', y: 1}, { x: '5/18/2019 15:32', y: 2}, { x: '5/18/2019 15:37', y: 3}, { x: '5/18/2019 15:50', y: 4}, { x: '5/18/2019 15:50', y: 5}, { x: '5/18/2019 15:51', y: 6}, { x: '5/18/2019 15:53', y: 7}, { x: '5/18/2019 15:53', y: 8}, { x: '5/18/2019 15:54', y: 9}, { x: '5/18/2019 15:56', y: 9}, { x: '5/18/2019 16:3', y: 1}, { x: '5/18/2019 16:4', y: 2}, 
				],
				}]
			},
			options: {
				title: {
					text: 'Chart.js Time Scale'
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
							labelString: 'value'
						}
					}]
				},
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myLine = new Chart(ctx, config);

		};

		document.getElementById('randomizeData').addEventListener('click', function() {
			config.data.datasets.forEach(function(dataset) {
				dataset.data.forEach(function(dataObj, j) {
					if (typeof dataObj === 'object') {
						dataObj.y = randomScalingFactor();
					} else {
						dataset.data[j] = randomScalingFactor();
					}
				});
			});

			window.myLine.update();
		});

		var colorNames = Object.keys(window.chartColors);
		document.getElementById('addDataset').addEventListener('click', function() {
			var colorName = colorNames[config.data.datasets.length % colorNames.length];
			var newColor = window.chartColors[colorName];
			var newDataset = {
				label: 'Dataset ' + config.data.datasets.length,
				borderColor: newColor,
				backgroundColor: color(newColor).alpha(0.5).rgbString(),
				data: [],
			};

			for (var index = 0; index < config.data.labels.length; ++index) {
				newDataset.data.push(randomScalingFactor());
			}

			config.data.datasets.push(newDataset);
			window.myLine.update();
		});

		document.getElementById('addData').addEventListener('click', function() {
			if (config.data.datasets.length > 0) {
				config.data.labels.push(newDate(config.data.labels.length));

				for (var index = 0; index < config.data.datasets.length; ++index) {
					if (typeof config.data.datasets[index].data[0] === 'object') {
						config.data.datasets[index].data.push({
							x: newDate(config.data.datasets[index].data.length),
							y: randomScalingFactor(),
						});
					} else {
						config.data.datasets[index].data.push(randomScalingFactor());
					}
				}

				window.myLine.update();
			}
		});

		document.getElementById('removeDataset').addEventListener('click', function() {
			config.data.datasets.splice(0, 1);
			window.myLine.update();
		});

		document.getElementById('removeData').addEventListener('click', function() {
			config.data.labels.splice(-1, 1); // remove the label first

			config.data.datasets.forEach(function(dataset) {
				dataset.data.pop();
			});

			window.myLine.update();
		});
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




