<?php
require_once '../connection.php';
require_once '../../app/auth.php';
// Check if the user is authenticated
/* if (!$userid = checkAuth()) {
    http_response_code(401);
    exit;
} */$userid = "pippo1";

// Check if the request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$error = array();

// Check if the request fields are filled
if (empty($_POST['titolo'])) {
    $error[] = "Dati insufficienti";
}

// Set the variables for the query
$titolo = mysqli_real_escape_string($conn, $_POST['titolo']);


if (count($error) === 0) {
    // Check if the user is the owner of the destination and retrieve the image name
    $username = mysqli_real_escape_string($conn, $userid);
    $query = "SELECT immagine FROM destinazioni WHERE titolo = '$titolo' AND utente = '$username'";
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) > 0) {
        // Delete the image from the server filesystem
        $row = mysqli_fetch_assoc($res);
        $fileName = $row['immagine'];
        $img_location = "/app/media/";
        unlink($img_location . $fileName);
    } else {
        $error[] = "Non puoi eliminare questa destinazione";
    }
}

// Delete the destination from the database
if (count($error) === 0) {
    $query = "DELETE FROM destinazioni WHERE titolo = '$titolo' AND utente = '$username'";
    if (mysqli_query($conn, $query)) {
        mysqli_close($conn);
    } else {
        $error[] = "Errore di connessione al Database";
    }
}

header("Content-Type: application/json");
if (count($error) > 0) {
    http_response_code(400);
    echo json_encode($error);
} else {
    $response = array("message" => "Immagine eliminata con successo");
    echo json_encode($response);
}

?>