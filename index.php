<!doctype html>
<html lang="en">
<head>
  <title>WebGL Globe</title>
  <meta charset="utf-8">
  <style type="text/css">
    html {
      height: 100%;
    }

    body {
      margin: 0;
      padding: 0;
      background: #000000 url(../globe/loading.gif) center center no-repeat;
      color: #ffffff;
      font-family: sans-serif;
      font-size: 13px;
      line-height: 20px;
      height: 100%;
    }

    #info {

      font-size: 11px;
      position: absolute;
      bottom: 5px;
      background-color: rgba(0, 0, 0, 0.8);
      border-radius: 3px;
      right: 10px;
      padding: 10px;

    }

    a {
      color: #aaa;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .bull {
      padding: 0px 5px;
      color: #555;
    }

    #title {
      position: absolute;
      top: 20px;
      width: 320px;
      left: 20px;
      background-color: rgba(0, 0, 0, 0.2);
      font: 20px/20px Georgia;
      padding: 15px;
    }

    .year {

      font: 16px Georgia;
      line-height: 26px;
      height: 30px;
      text-align: center;
      float: left;
      width: 90px;
      color: rgba(255, 255, 255, 0.4);

      cursor: pointer;
      -webkit-transition: all 0.1s ease-out;
    }

    .year:hover, .year.active {
      font-size: 23px;
      color: #fff;
    }

    #ce span {
      display: none;
    }

    #ce {
      width: 107px;
      height: 55px;
      display: block;
      position: absolute;
      bottom: 15px;
      left: 20px;
      background: url(../globe/ce.png);
    }


  </style>
</head>
<body>

<div id="container"></div>

<div id="info">
  The <a href="http://www.chromeexperiments.com/globe">WebGL Globe</a> is an
  open platform for visualizing geographic data. <span
    class="bull">&bull;</span> <a href="http://earthquake.usgs.gov/earthquakes/feed/v1.0/csv.php">Data from earthquake.usgs.gov</a> <span class="bull">&bull;</span>Adaptation By <a href="http://www.georgegilmartin.me">George Gilmartin</a>
</div>

<div id="title">
  Weekly Earthquakes
</div>

<a id="ce" href="http://www.chromeexperiments.com/globe">
  <span>This is a Chrome Experiment</span>
</a>

<script type="text/javascript" src="../globe/third-party/Detector.js"></script>
<script type="text/javascript" src="../globe/third-party/three.min.js"></script>
<script type="text/javascript" src="../globe/globe.js"></script>
<script type="text/javascript">
/*
0 - White
1 - Gray
2 - Purple
3 - Blue
4 - Dark Blue
5 - Navy Blue
6 - Orange
7 - Red
8 - Dark Red
9 - Brown
10 - Gold
11 - Yellow
12 - Light Green
13 - Dark Green
14 - Peach
15 - Dark Purple
16 - Orange
17 - Green
18 - Light Yellow
19 - Piss Yellow
20 - Ice Blue
*/
  var globe = DAT.Globe(document.getElementById('container'), {
    colorFn: function(label) {
       return new THREE.Color([
         0xd9d9d9, 0xb6b4b5, 0x9966cc, 0x15adff, 0x3e66a3,
         0x216288, 0xff7e7e, 0xff1f13, 0xc0120b, 0x5a1301, 0xffcc02,
         0xedb113, 0x9fce66, 0x0c9a39,
         0xfe9872, 0x7f3f98, 0xf26522, 0x2bb673, 0xd7df23,
         0xe6b23a, 0x7ed3f7][label]);
    }
  });

  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'earthquakes.json', true);
  xhr.onreadystatechange = function(e) {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var data = JSON.parse(xhr.responseText);
        window.data = data;
        globe.addData(data, {format: 'legend'});
        globe.createPoints();
        globe.animate();
        document.body.style.backgroundImage = 'none'; // remove loading
      }
    }
  };
  xhr.send(null);

</script>
<?php
	
	/*$dir = 'Earthquake_JSON';

	 // create new directory with 744 permissions if it does not exist yet
	 // owner will be the user/group the PHP script is run under
	 if (!file_exists($dir)) {
	  mkdir ($dir, 0744);
	 }

 	file_put_contents ($dir.'/test.txt', 'Hello File');
 	$filename = "earthquakes.json";
 	if (file_exists($filename)) {
    	echo "The file $filename exists";
	} else {
    	echo "The file $filename does not exist";
	}*/
 	
	$urlHourly = "http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_hour.csv";
	$urlDaily = "http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_day.csv";
	$urlWeekly = "http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_week.csv";
	$urlMonthly = "http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_month.csv";
	
	$file = fopen($urlWeekly,"r") or die("Failure to open Earthquake feed"); 
	$masterArray = array();
	$i = 0;
	while(!feof($file)){
		$masterArray[$i] = fgetcsv($file);
		$i = $i + 1;
	}
	fclose($file);
	$count = count($masterArray);
	$fp = fopen("earthquakes.json", "w") or die("Failure to open JSON file");
	
	fwrite($fp, "[");
	
	$csvArray = array();
	$arrayValue = '';
	$y = 0;
	$z = 0;
	for($y = 1; $y < $count; $y++){
		$counter = 0;
		for($z = 0; $z <= 14; $z++){
			$counter = $counter + 1;
			if ($z == 1 || $z == 2 || $z == 4){
				if($z == 4){
					$masterArray[$y][$z] = $masterArray[$y][$z] * 0.1;
					$magnitude = $masterArray[$y][$z];
				}
				
				$arrayValue = $masterArray[$y][$z];
				
				fwrite($fp, $arrayValue);
				if ($counter == 5){
					fwrite($fp, ",");
					if($magnitude <= 0.01){
						fwrite($fp, "1");
					}
					elseif ($magnitude > 0.01 && $magnitude < 0.1){
						fwrite($fp, "12");
					}
					elseif ($magnitude >= 0.1 && $magnitude < 0.2){
						fwrite($fp, "13");
					}
					elseif ($magnitude >= 0.2 && $magnitude < 0.3){
						fwrite($fp, "17");
					}
					elseif ($magnitude >= 0.3 && $magnitude < 0.4){
						fwrite($fp, "18");
					}
					elseif ($magnitude >= 0.4 && $magnitude < 0.5){
						fwrite($fp, "19");
					}
					elseif ($magnitude >= 0.5 && $magnitude < 0.6){
						fwrite($fp, "6");
					}
					elseif ($magnitude >= 0.6 && $magnitude < 0.7){
						fwrite($fp, "7");
					}
					else{
						fwrite($fp, "8");
					}
					
				}
				if ($y != $count - 1 || $z != 4){
					fwrite($fp, ",");
				}else{
					
				}
			}
		}
	}
	fwrite($fp, "]");
	fclose($fp);
?>

</body>

</html>
