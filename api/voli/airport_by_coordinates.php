<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/connection.php";
require_once "amadeus_data.php";
require_once "amadeus_refresh.php";
include $_SERVER['DOCUMENT_ROOT'] . "/api/openweather/coordinates.php";


function airportRequest($queryString) {
    try {
        $coordinates = requestCoordinates($queryString);
        $lat = $coordinates["lat"];
        $lon = $coordinates["lon"];
    } catch (Exception $e) {
        throw $e;
    }
    //
    global $amadeus_access_token, $amadeus_hostname;
    $amadeus_access_token = refreshAccessToken();
    if ($amadeus_access_token == null) {
        throw new Exception("Errore nella richiesta del token");
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $amadeus_hostname . "/v1/reference-data/locations/airports?latitude=" . $lat . "&longitude=" . $lon);
    curl_setopt($curl, CURLOPT_HTTPHEADER, setHeaders($amadeus_access_token));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    if ($response && isset(json_decode($response, true)['data'])) {
        $response = json_decode($response, true);
        if ($response['meta']['count'] == 0) {
            return null;
        }
        return $response['data'][0];
    } else {
        throw new Exception("Errore nella richiesta degli aeroporti");
    }//

    //return json_decode(file_get_contents("airport.json"), true);
}


?>