<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';
// Check if the user is authenticated
if (!$userid = checkAuth()) {
    http_response_code(401);
    exit;
} //echo $userid;

// Check if the request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit;
}

$error = array();

if (!isset($http_code))
    $http_code = 200;

function userRequest()
{
    global $userid, $conn;
    $utente = mysqli_real_escape_string($conn, $userid);
    $query = "SELECT username, email, firstname, lastname, birthdate FROM Utenti WHERE username = '$utente'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $utente = mysqli_fetch_assoc($result);
    if ($utente !== null) {
        $utente['age_majority'] = $utente['birthdate'] <= date('Y-m-d', strtotime('-18 years'));
        return $utente;
    } else {
        return null;
    }
}

$utente = userRequest();

if (count($error) === 0) {
    if ($utente === null) {
        $http_code = 404;
        echo json_encode(array("message" => "Utente non trovato"));
    } else {
        header("Content-Type: application/json");
        echo json_encode($utente);
    }

} else {
    http_response_code($http_code);
    header("Content-Type: application/json");
    echo json_encode($error);
}

?>