<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/connection.php";
require_once "amadeus_data.php";
require_once "amadeus_refresh.php";
include $_SERVER['DOCUMENT_ROOT'] . "/api/openweather/coordinates.php";


function airportRequest($queryString) {
    $coordinates = requestCoordinates($queryString);
    $lat = $coordinates["lat"];
    $lon = $coordinates["lon"];
    /*global $access_token, $hostname;
    $access_token = refreshAccessToken();
    if ($access_token == null) {
        return null;
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $hostname . "/v1/reference-data/locations/airports?latitude=" . $lat . "&longitude=" . $lon);
    curl_setopt($curl, CURLOPT_HTTPHEADER, setHeaders($access_token));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);
    if ($response['meta']['count'] == 0) {
        return null;
    }
    return $response['data'][0];*/

    return json_decode(file_get_contents("airport.json"), true);
}


?>