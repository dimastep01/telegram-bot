<?php

$city = urlencode("Никополь");
$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$city");

$decoded = json_decode($json, true);
 

var_dump($decoded['results'][0]['geometry']['location']);
