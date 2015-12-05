<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Harness racing</title>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</head>
<body>
<?php
include 'curl.php';
include 'weather.php';

$weather = new Weather();


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
/*var_dump("<pre>");
var_dump($weather->getHumidity());
var_dump($weather->getLuminance());
var_dump($weather->getPressure());
var_dump($weather->getTemperature());*/
?>
</body>
</html>

