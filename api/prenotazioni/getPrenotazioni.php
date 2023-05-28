<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';
// Check if the user is authenticated
/* if (!$userid = checkAuth()) {
    http_response_code(401);
    exit;
} */$userId = "pippo1";

// Check if the request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit;
}

$error = array();

// Check if the request fields are filled
//

// Set the variables for the query
//

$city_map = array();
if (!isset($http_code))
    $http_code = 200;

function flightRequest()
{
    global $userId, $conn;
    $utente = mysqli_real_escape_string($conn, $userId);
    $query = "SELECT * FROM Prenotazioni WHERE utente = '$utente'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $prenotazione = mysqli_fetch_assoc($result);
    $prenotazioni = array();
    while ($prenotazione !== null) {
        //print_r($prenotazione);
        $id_volo = mysqli_real_escape_string($conn, $prenotazione['id']);
        $query = "SELECT * FROM Tratte WHERE volo = '$id_volo' AND direzione = 'andata' ORDER BY progressivo ASC";
        $sub_result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $tratta = mysqli_fetch_assoc($sub_result);
        $tratte_andata = array();
        while ($tratta !== null) {
            $tratta['partenza'] = [
                "iataCode" => $tratta['origine'],
                "city" => getCityAndCountry($tratta['origine'])["city"],
                "country" => getCityAndCountry($tratta['origine'])["country"],
                "at" => $tratta['data_partenza']
            ];
            $tratta['arrivo'] = [
                "iataCode" => $tratta['destinazione'],
                "city" => getCityAndCountry($tratta['destinazione'])["city"],
                "country" => getCityAndCountry($tratta['destinazione'])["country"],
                "at" => $tratta['data_arrivo']
            ];
            $tratte_andata[] = $tratta;
            $tratta = mysqli_fetch_assoc($sub_result);
        }
        $prenotazione['andata']['tratte'] = $tratte_andata;

        $query = "SELECT * FROM Tratte WHERE volo = '$id_volo' AND direzione = 'ritorno' ORDER BY progressivo ASC";
        $sub_result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $tratta = mysqli_fetch_assoc($sub_result);
        $tratte_ritorno = array();
        while ($tratta !== null) {
            $tratta['partenza'] = [
                "iataCode" => $tratta['origine'],
                "city" => getCityAndCountry($tratta['origine'])["city"],
                "country" => getCityAndCountry($tratta['origine'])["country"],
                "at" => $tratta['data_partenza']
            ];
            $tratta['arrivo'] = [
                "iataCode" => $tratta['destinazione'],
                "city" => getCityAndCountry($tratta['destinazione'])["city"],
                "country" => getCityAndCountry($tratta['destinazione'])["country"],
                "at" => $tratta['data_arrivo']
            ];
            $tratte_ritorno[] = $tratta;
            $tratta = mysqli_fetch_assoc($sub_result);
        }
        $prenotazione['ritorno']['tratte'] = $tratte_ritorno;
        $prenotazioni[] = $prenotazione;
        $prenotazione = mysqli_fetch_assoc($result);
    }
    return $prenotazioni;
}

function getCityAndCountry($iataCode)
{
    global $conn;
    $iataCode = mysqli_real_escape_string($conn, $iataCode);
    $query = "SELECT iata_code,city,country FROM aeroporti WHERE iata_code = '$iataCode'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    return $row;
}

$flights = flightRequest();

if (count($error) === 0) {

    header("Content-Type: application/json");
    echo json_encode($flights);

} else {
    http_response_code($http_code);
    header("Content-Type: application/json");
    echo json_encode($error);
}

?>