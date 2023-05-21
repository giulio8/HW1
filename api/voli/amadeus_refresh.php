<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/connection.php";

function refreshAccessToken() {
    global $amadeus_hostname, $amadeus_client_id, $amadeus_client_secret;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $amadeus_hostname . "/v1/security/oauth2/token");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=" . $amadeus_client_id . "&client_secret=" . $amadeus_client_secret);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);
    if ($response['state'] == "approved") {
        return $response['access_token'];
    }
    return null;
}

?>