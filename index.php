<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../img/favicon.ico">

    <title>Harnestica</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">

    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/thingsee.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/bs-3.3.5/jqc-1.11.3,dt-1.10.10,af-2.1.0/datatables.min.css"/>
</head>

<body>


<?php
include 'passwords.php';
include 'curl.php';
include 'weather.php';
include 'db.php';

$weather = new Weather();
$database = new Database();
$thingsee = new ThingSee();


$thingsee->getToken();
$currentData = $thingsee->getData();
$events = $currentData->events;
foreach($events as $event) {
    $cause = $event->cause;
    $senses = $cause->senses;
    foreach($senses as $sense) {
        $weather->setValue($sense->sId, $sense->val, $sense->ts);
    }
}

$horses = $database->getHorses();

$horseRows = array();

$currentHumidity = $weather->getHumidity();
$currentLuminance = $weather->getLuminance();
$currentPressure = $weather->getPressure();
$currentTemperature = $weather->getTemperature();

foreach ($horses as $horse) {
    $horseRow = $horse;
    $previousRaces = $database->getHorseRaces($horse->horse_id);
    $i = 0;
	$horseRow->temperature =  countPercent($previousRaces, $currentTemperature, 'temperature');
    $horseRow->humidity = countPercent($previousRaces, $currentHumidity, 'humidity');
    $horseRow->luminance = countPercent($previousRaces, $currentLuminance, 'luminance');
    $horseRow->pressure = countPercent($previousRaces, $currentPressure, 'pressure');
    
    $horseRow->totalWinning = ($horseRow->humidity + $horseRow->luminance + $horseRow->pressure + $horseRow->temperature) / 4;
    array_push($horseRows, $horseRow);
}

function countPercent($previousRaces, $currenctHumidity, $field) {
    $closestHumidity = 0;
    $humidityPosition = 0;
    $i = 0;
    foreach($previousRaces as $race) {
        if ($i == 0) {
            $closestHumidity = $race->$field;
            $humidityPosition = $race->position;
        } else {
            if (abs($currenctHumidity - $closestHumidity) > abs($race->$field - $currenctHumidity)) {
                $closestHumidity = $race->$field;
                $humidityPosition = $race->position;
            }
        }
        $i++;
    }
    return round((1 - ($humidityPosition / 6)) * 100, 2);
}
?>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#">
                <i class="fa fa-trophy"></i> <span class="light">Harnestica</span>
            </a>
        </div>
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
            <ul class="nav navbar-nav">
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li>
                    <a class="page-scroll" href="#">Wages</a>
                </li>
                <li>
                    <a class="page-scroll" href="#">Profile</a>
                </li>
                <li>
                    <a class="page-scroll" href="#">Settings</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="jumbotron">
    <div class="container">
        <h1 style="color: #ffffff">Harnestica!</h1>

        <div class="row">
		
			<div class="col-md-3 iconimg temperature">
			<h2 class="iconimg_text"><?php echo $currentTemperature; ?> ºC
				</h2>
            </div>
			
			<div class="col-md-3 iconimg humidity">
                <h2 class="iconimg_text"><?php echo $currentHumidity; ?> % rh
                </h2>
            </div>
		
            <div class="col-md-3 iconimg luminance">
                <h2 class="iconimg_text"><?php echo $currentLuminance; ?> lux
                </h2>
			</div>
	
            <div class="col-md-3 iconimg pressure">
                <h2 class="iconimg_text"><?php echo round($currentPressure, 2); ?> hPa
                </h2>
            </div>
			
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="btn-group btn-group-lg" data-toggle="buttons">
                <?php
                foreach($database->getRaces() as $race) {
                    echo '<label class="btn btn-default btn-block active date-filter" id="'.$race->race_id.'">
                            '.mb_substr($race->date, 0, 10).'
                          </label>';
                }
                ?>
            </div>
        </div>

        <div class="col-md-9">

            <table class="table table-bordered" id="horse_standing">
                <thead style="font-weight: bold">
                <td>Horse</td>
                <td>Winning rate by temperature</td>
                <td>Winning rate by humidity</td>
                <td>Winning rate by atmospheric pressure</td>
                <td>Winning rate by luminance</td>
                <td>Total winning rate</td>
                <td>Trend</td>
                </thead>

                <tbody>

                <?php
                foreach($horseRows as $row) {
                    $trend = rand(0, 1) == 1 ? 't' : 'f';
                    echo '<tr>';
                    echo '<td>' . utf8_encode($row->name) . '</td>';
                    echo '<td>' . round($row->temperature) . ' %</td>';
                    echo '<td>' . round($row->humidity) . ' %</td>';
                    echo '<td>' . round($row->pressure) . ' %</td>';
                    echo '<td>' . round($row->temperature) . ' %</td>';
                    echo '<td>' . round($row->totalWinning) . ' %</td>';
                    echo '<td>'.$trend.'</td>';
                    echo '</tr>';
                }
                ?>
                <tbody>
            </table>
            <div class='overlay'>
                <i class="fa fa-spinner fa-spin table-loader"></i>
            </div>
        </div>

    </div>


    <footer class="navbar navbar-default navbar-fixed-bottom">
        <div class="container">
            <p class="text-muted">&copy; Sami Suo-Heikki & Teemu Kuutti </p>
        </div>
    </footer>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/s/bs-3.3.5/jqc-1.11.3,dt-1.10.10,af-2.1.0/datatables.min.js"></script>
<script src="js/thingsee.js"></script>
</body>
</html>


