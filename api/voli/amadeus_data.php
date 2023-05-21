<?php
$amadeus_hostname = "https://test.api.amadeus.com";
$amadeus_client_id = "Y8I33cyCvXeAHHt9A6AxGO2KwvzMXblS";
$amadeus_client_secret = "PUsaWUwSBvVERsIH";
$amadeus_access_token = "m51A5qJtGmZmjpEqDq1C1aAVgBFI";

function setHeaders($access_token) {
    return array(
        "Authorization: Bearer " . $access_token
    );
}
?>