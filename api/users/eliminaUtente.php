<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';
// Check if the user is authenticated
if (!$userId = checkAuth()) {
    http_response_code(401);
    exit;
}

// Check if the request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$error = array();


function deleteUser()
{
    global $userId, $conn, $error;
    if (count($error) === 0) {
        // delete the user from the database
        $userId = mysqli_real_escape_string($conn, $userId);
        $query = "DELETE FROM Utenti WHERE username = '$userId'";

        if (mysqli_query($conn, $query)) {
            mysqli_close($conn);
            return true;
        } else {
            $error[] = "Errore di connessione al Database";
            return false;
        }
    }
}

if (count($error) === 0) {
    if (!deleteUser()) {
        $error[] = "Errore nella cancellazione dell'utente";
    }
}
header("Content-Type: application/json");
if (count($error) > 0) {
    http_response_code(400);
    echo json_encode($error);
} else {
    $response = array("message" => "Utente eliminato con successo");
    echo json_encode($response);
}

?>