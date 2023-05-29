<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';
// Check if the user is authenticated
if (!$userid = checkAuth()) {
    http_response_code(401);
    exit;
}

// Check if the request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$error = array();

// Check if the request fields are filled
if (empty($_POST['flight'])) {
    $http_code = 400;
    $error[] = "Dati insufficienti";
}

// Set the variables for the query
$flight = json_decode($_POST['flight'], true);
$data_prenotazione = date("Y-m-d");
$origine = $flight["andata"]["tratte"][0]["partenza"]["iataCode"];
$destinazione = $flight["andata"]["tratte"][count($flight["andata"]["tratte"]) - 1]["arrivo"]["iataCode"];
$prezzo = $flight["prezzo"];
$valuta = $flight["valuta"];
$data_partenza = $flight["andata"]["tratte"][0]["partenza"]["at"];
$data_arrivo = $flight["andata"]["tratte"][count($flight["andata"]["tratte"]) - 1]["arrivo"]["at"];
$compagnia = $flight["compagnia"];



if (!isset($http_code)) $http_code = 200;

function saveFlightRequest($utente, $data_prenotazione, $origine, $destinazione, $prezzo, $valuta, $data_partenza, $data_arrivo, $compagnia)
{
    global $conn, $error, $flight;
    mysqli_begin_transaction($conn);
    $id = uniqid();
    $utente = mysqli_real_escape_string($conn, $utente);
    $data_prenotazione = mysqli_real_escape_string($conn, $data_prenotazione);
    $origine = mysqli_real_escape_string($conn, $origine);
    $destinazione = mysqli_real_escape_string($conn, $destinazione);
    $prezzo = mysqli_real_escape_string($conn, $prezzo);
    $data_partenza = mysqli_real_escape_string($conn, $data_partenza);
    $data_arrivo = mysqli_real_escape_string($conn, $data_arrivo);
    $compagnia = mysqli_real_escape_string($conn, $compagnia);
    try {
    $query = "INSERT INTO Prenotazioni (id, prezzo, valuta, origine, destinazione, data_partenza, data_arrivo,
    utente, data_prenotazione, compagnia) VALUES ('$id', '$prezzo', '$valuta', '$origine',
      '$destinazione', '$data_partenza', '$data_arrivo', '$utente', '$data_prenotazione', '$compagnia')";
    mysqli_query($conn, $query) or die(mysqli_error($conn));
        foreach ($flight["andata"]["tratte"] as $i => $tratta) {
            $id_tratta = uniqid();
            $origine = mysqli_real_escape_string($conn, $tratta["partenza"]["iataCode"]);
            $destinazione = mysqli_real_escape_string($conn, $tratta["arrivo"]["iataCode"]);
            $data_partenza = mysqli_real_escape_string($conn, $tratta["partenza"]["at"]);
            $data_arrivo = mysqli_real_escape_string($conn, $tratta["arrivo"]["at"]);

            $query = "INSERT INTO Tratte (id, origine, destinazione, volo, data_partenza, data_arrivo, progressivo, direzione) VALUES ('$id_tratta', '$origine', '$destinazione', '$id', '$data_partenza', '$data_arrivo', '$i', 'andata')";
            mysqli_query($conn, $query) or die(mysqli_error($conn));
        }
        if (isset($flight["ritorno"])) {
            foreach ($flight["ritorno"]["tratte"] as $i => $tratta) {
                $id_tratta = uniqid();
                $origine = mysqli_real_escape_string($conn, $tratta["partenza"]["iataCode"]);
                $destinazione = mysqli_real_escape_string($conn, $tratta["arrivo"]["iataCode"]);
                $data_partenza = mysqli_real_escape_string($conn, $tratta["partenza"]["at"]);
                $data_arrivo = mysqli_real_escape_string($conn, $tratta["arrivo"]["at"]);

                $query = "INSERT INTO Tratte (id, origine, destinazione, volo, data_partenza, data_arrivo, progressivo, direzione) VALUES ('$id_tratta', '$origine', '$destinazione', '$id', '$data_partenza', '$data_arrivo', '$i', 'ritorno')";
                mysqli_query($conn, $query) or die(mysqli_error($conn));
            }
        }
        return mysqli_commit($conn);
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conn);
        $error[] = "Errore nella query: " . $e->getMessage();
    }
}


if (saveFlightRequest($userId, $data_prenotazione, $origine, $destinazione, $prezzo, $valuta, $data_partenza, $data_arrivo, $compagnia) === false) {
    $http_code = 500;
    $error[] = "Errore nella richiesta dei voli";
} else {
    $http_code = 200;
    $response = array("message" => "Prenotazione effettuata con successo");
}

http_response_code($http_code);
if (isset($error) && !empty($error)) {
    echo json_encode($error);
} else {
    echo json_encode($response);
}

?>