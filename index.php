<?php

//initialize the session
$ch = curl_init();

//set options
curl_setopt($ch, CURLOPT_URL, "http://www.example.com");

//execute the session
curl_exec($ch);

//close the session
curl_close($ch);

?>