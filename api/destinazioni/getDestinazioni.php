<?php
/*******************************************************
        Resituisce tutte le destinazioni postate dall'utente
********************************************************/
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';
if (!$userid = checkAuth()) {
        http_response_code(401);
        exit;
    };

$username = mysqli_real_escape_string($conn, $userid);

$query = "SELECT titolo, descrizione, immagine from Destinazioni WHERE utente = '$username'";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$array = array();
$array['count'] = mysqli_num_rows($res);
$array['data'] = $res->fetch_all(MYSQLI_ASSOC);

header("Content-Type: application/json");
echo json_encode($array);

?>