<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once "amadeus_data.php";
require_once "amadeus_refresh.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';
include "airport_by_coordinates.php";
// Check if the user is authenticated
/* if (!$userid = checkAuth()) {
    http_response_code(401);
    exit;
} */

// Check if the request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit;
}

$error = array();

// Check if the request fields are filled
if (empty($_GET['origin']) || empty($_GET['destination']) || empty($_GET['departureDate']) || empty($_GET['returnDate'])) {
    $http_code = 400;
    $error[] = "Dati insufficienti";
}

// Set the variables for the query
$origin = $_GET["origin"];
$destination = $_GET["destination"];
$departureDate = $_GET["departureDate"];
$returnDate = $_GET["returnDate"];

// Get the IATA codes for the origin and destination
try {
    $origin_code = airportRequest($origin)["iataCode"];
    $destination_code = airportRequest($destination)["iataCode"];
} catch (Exception $e) {
    $http_code = 500;
    $error[] = $e->getMessage();
}

$city_map = array();
if (!isset($http_code))
    $http_code = 200;

function flightRequest($origin_code, $destination_code, $departureDate, $returnDate)
{
    global $amadeus_access_token, $amadeus_hostname;
    $amadeus_access_token = refreshAccessToken();
    if ($amadeus_access_token == null) {
        $error[] = "Errore di autenticazione";
        return null;
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $amadeus_hostname . "/v2/shopping/flight-offers?originLocationCode=" . $origin_code . "&destinationLocationCode=" . $destination_code . "&departureDate=" . $departureDate . "&returnDate=" . $returnDate . "&adults=1&max=3");
    curl_setopt($curl, CURLOPT_HTTPHEADER, setHeaders($amadeus_access_token));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    if ($response == false) {
        $error[] = "Errore nella richiesta dei voli da Amadeus";
        return null;
    }
    $response = json_decode($response, true);
    return $response;
}

function getCityAndCountry($iataCode)
{
    global $conn;
    $iataCode = mysqli_real_escape_string($conn, $iataCode);
    $query = "SELECT city,country FROM aeroporti WHERE iata_code = '$iataCode'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    return $row;
}

$response = array();

if (count($error) === 0) {
    $data = flightRequest($origin_code, $destination_code, $departureDate, $returnDate);
    $flights = $data['data'];
    //$flights = json_decode(file_get_contents("flights.json"), true)['data'];
    if ($flights === null) {
        $http_code = 500;
        $error[] = "Errore nella richiesta dei voli";
    } else if (count($flights) === 0) {
        $error[] = "Nessun volo trovato";
    } else {
        $dict = $data['dictionaries'];
        //$dict = json_decode(file_get_contents("flights.json"), true)['dictionaries'];
    }
}

if (count($error) === 0) {
    $flights_resp = array();
    foreach ($flights as $flight) {
        $comp = $dict['carriers'][$flight['itineraries'][0]['segments'][0]['carrierCode']];
        $flight_resp = ["id" => $flight['id'], "prezzo" => $flight['price']['total'], "valuta" => $flight['price']['currency'], "solaAndata" => $flight['oneWay'], "postiDisponibili" => $flight["numberOfBookableSeats"], "compagnia" => $comp];
        foreach ($flight['itineraries'] as $idx => &$itinerary) {
            $segments = $itinerary['segments'];
            $itinerary_resp = array();
            foreach ($segments as $key => $segment) {
                $segments_resp[$key]['partenza'] = $segment['departure'];
                if (!isset($city_map[$segment['departure']['iataCode']])) {
                    $city_map[$segment['departure']['iataCode']] = getCityAndCountry($segment['departure']['iataCode']);
                }
                $segments_resp[$key]['partenza']['city'] = $city_map[$segment['departure']['iataCode']]['city'];
                $segments_resp[$key]['partenza']['country'] = $city_map[$segment['departure']['iataCode']]['country'];
                $segments_resp[$key]['arrivo'] = $segment['arrival'];
                if (!isset($city_map[$segment['arrival']['iataCode']])) {
                    $city_map[$segment['arrival']['iataCode']] = getCityAndCountry($segment['arrival']['iataCode']);
                }
                $segments_resp[$key]['arrivo']['city'] = $city_map[$segment['arrival']['iataCode']]['city'];
                $segments_resp[$key]['arrivo']['country'] = $city_map[$segment['arrival']['iataCode']]['country'];
            }
            $itinerary_resp['tratte'] = $segments_resp;
            if ($idx === 0)
                $flight_resp['andata'] = $itinerary_resp;
            else
                $flight_resp['ritorno'] = $itinerary_resp;
        }
        $flights_resp[] = $flight_resp;
    }

    header("Content-Type: application/json");
    echo json_encode($flights_resp);

} else {
    http_response_code($http_code);
    header("Content-Type: application/json");
    echo json_encode($error);
}

?>