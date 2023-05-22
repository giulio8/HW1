<?php
$o_w_hostname = "https://api.openweathermap.org";
$o_w_api_key = "95461e08659a9465c43c7df7b7f96f4a";

function requestCoordinates($queryString) {
    /*global $o_w_hostname, $o_w_api_key;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $o_w_hostname . "/geo/1.0/direct?q=" . $queryString . "&limit=5&appid=" . $o_w_api_key);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    $responseArray = json_decode($response, true);*/
    $responseArray = array(
        array(
            "name" => "Roma",
            "lat" => 41.8919,
            "lon" => 12.5113,
            "country" => "IT",
            "state" => "Lazio"
        ),
        array(
            "name" => "Roma",
            "lat" => 41.8919,
            "lon" => 12.5113,
            "country" => "IT",
            "state" => "Lazio"
        ),
        array(
            "name" => "Roma",
            "lat" => 41.8919,
            "lon" => 12.5113,
            "country" => "IT",
            "state" => "Lazio"
        ),
        array(
            "name" => "Roma",
            "lat" => 41.8919,
            "lon" => 12.5113,
            "country" => "IT",
            "state" => "Lazio"
        ),
        array(
            "name" => "Roma",
            "lat" => 41.8919,
            "lon" => 12.5113,
            "country" => "IT",
            "state" => "Lazio"
        )
    );
    return $responseArray[0];
}

?>