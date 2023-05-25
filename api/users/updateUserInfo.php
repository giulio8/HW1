<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';

if (!$userid = checkAuth()) {
    http_response_code(401);
    exit;
}

// Verifica l'esistenza di almeno un dato POST
if (!(empty($_POST["firstname"]) && empty($_POST["lastname"]) && empty($_POST["birthdate"]) && empty($_POST["email"]))) {
    $error = array();

    # inserimento nel database, dei soli campi che sono stati modificati
    $data = array();
    if (!empty($_POST["firstname"])) {
        $data["firstname"] = mysqli_real_escape_string($conn, $_POST["firstname"]);
    }
    if (!empty($_POST["lastname"])) {
        $data["lastname"] = mysqli_real_escape_string($conn, $_POST["lastname"]);
    }
    if (!empty($_POST["birthdate"])) {
        $data["birthdate"] = mysqli_real_escape_string($conn, $_POST["birthdate"]);
    }
    if (!empty($_POST["email"])) {
        $data["email"] = mysqli_real_escape_string($conn, $_POST["email"]);
    }
    // build the query

    $query = "UPDATE Utenti SET ";
    foreach ($data as $key => $value) {
        $query .= "$key = '$value', ";
    }
    $query = substr($query, 0, -2);
    $query .= "WHERE username = '$userid'";

    if (mysqli_query($conn, $query) or die(mysqli_error($conn))) {
        mysqli_close($conn);
    } else {
        $error[] = "Errore di connessione al Database";
    }

} else {
    $error = array("Riempi tutti i campi");
}

header("Content-Type: application/json");
if (count($error) > 0) {
    http_response_code(400);
    echo json_encode($error);
} else {
    $response = array("message" => "Informazioni aggiornate con successo");
    echo json_encode($response);
}

?>