<?php
require_once '../connection.php';
require_once '../../app/auth.php';
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
if (empty($_POST['titolo']) ||  empty($_POST['descrizione'])) {
    $error[] = "Dati insufficienti";
}

// Set the variables for the query
$username = mysqli_real_escape_string($conn, $userid);
$titolo = mysqli_real_escape_string($conn, $_POST['titolo']);
$descrizione = mysqli_real_escape_string($conn, $_POST['descrizione']);
$file = $_FILES['image'];

// Check if the tuple username-titolo already exists
if (!empty($_POST['titolo'])) {
    $query = "SELECT * FROM destinazioni WHERE titolo = '$titolo' AND utente = '$username'";
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) > 0) {
        $error[] = "Hai già inserito una destinazione con questo titolo";
    }
}

// Save the image in the server filesystem
$img_location = "/app/media/";

if (count($error) === 0) {
    if ($file['size'] != 0) {
        $type = exif_imagetype($file['tmp_name']);
        $allowedExt = array(IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg');
        if (isset($allowedExt[$type])) {
            if ($file['error'] === 0) {
                if ($file['size'] < 4000000) {
                    $fileNameNew = uniqid('', true) . "." . $allowedExt[$type];
                    $fileDestination = $img_location . $fileNameNew;
                    move_uploaded_file($file['tmp_name'], $fileDestination);
                } else {
                    $error[] = "L'immagine non deve avere dimensioni maggiori di 4MB";
                }
            } else {
                $error[] = "Errore nel carimento del file";
            }
        } else {
            $error[] = "I formati consentiti sono .png, .jpeg e .jpg";
        }
    } else {
        $error[] = "Non hai caricato nessuna immagine";
    }
}

if (count($error) === 0) {
    $query = "INSERT INTO destinazioni (titolo, descrizione, immagine, utente) VALUES ('$titolo', '$descrizione', '$fileNameNew', '$username')";
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
    $response = array("message" => "Immagine caricata con successo");
    echo json_encode($response);
}

?>