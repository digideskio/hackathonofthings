<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Harness racing</title>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
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
    $horseRow->humidity = countPercent($previousRaces, $currentHumidity, 'humidity');
    $horseRow->luminance = countPercent($previousRaces, $currentLuminance, 'luminance');
    $horseRow->pressure = countPercent($previousRaces, $currentPressure, 'pressure');
    $horseRow->temperature = countPercent($previousRaces, $currentTemperature, 'temperature');
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
</body>
</html>

