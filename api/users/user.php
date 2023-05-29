<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';

function usernameExists($username)
{
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT username FROM utenti WHERE username = '$username'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    if ($res === false) {
        throw new Exception("Errore: " . mysqli_error($conn));
    } else {
        if (mysqli_num_rows($res) > 0) {
            return true;
        }
        return false;
    }
}
?>