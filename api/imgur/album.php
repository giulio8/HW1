<?php

$hostname = "https://api.imgur.com";
$refresh_token = "91a588c1f9167616dc661d0ff54aeada9c8491b6";
$client_id = "c6cbe3454179b73";
$client_secret = "8d929538ebbe2c3c03606f3d4289de844b811632";
$access_token = "4ff0cee8c20f23e25b193c3ce2f4613a4aa19277";
$album_hash = "Xy5vujj";

function setHeaders($access_token) {
    return array(
        "Authorization: Bearer " . $access_token
    );
}

function albumRequest() {
    global $access_token, $hostname, $album_hash;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $hostname . "/3/album/" . $album_hash);
    curl_setopt($curl, CURLOPT_HTTPHEADER, setHeaders($access_token));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

echo albumRequest();

?>

