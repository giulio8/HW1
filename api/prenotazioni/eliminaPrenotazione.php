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
if (empty($_POST['id'])) {
    $error[] = "Dati insufficienti";
}

// Set the variables for the query
$id = mysqli_real_escape_string($conn, $_POST['id']);


if (count($error) === 0) {
    // Check if the user is the owner of the booking
    $id = mysqli_real_escape_string($conn, $id);
    $username = mysqli_real_escape_string($conn, $userid);
    $query = "SELECT * FROM Prenotazioni WHERE id = '$id'";
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($row['utente'] === $username) {
            // Delete the image from the database
            $query = "DELETE FROM Prenotazioni WHERE id = '$id'";
            if (mysqli_query($conn, $query)) {
                mysqli_close($conn);
            } else {
                $error[] = "Errore di connessione al Database";
            }

        } else {
            $error[] = "Non sei autorizzato a cancellare questa prenotazione";
        }
    } else {
        $error[] = "Non è stata trovata la prenotazione da cancellare, riprovare più tardi";
    }
}

header("Content-Type: application/json");
if (count($error) > 0) {
    http_response_code(400);
    echo json_encode($error);
} else {
    $response = array("message" => "Prenotazione eliminata con successo");
    echo json_encode($response);
}

?>