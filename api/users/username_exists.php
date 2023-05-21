<?php
    require_once '../connection.php';

    $username = mysqli_real_escape_string($conn, $_GET['username']);
    function usernameExists($username) {
        global $conn;
        $username = mysqli_real_escape_string($conn, $username);
        $query = "SELECT username FROM utenti WHERE username = '$username'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if (mysqli_num_rows($res) > 0) {
            return true;
        }
        return false;
    }

    if (usernameExists($username)) {
        echo json_encode(array('exists' => true, 'message' => 'Username già utilizzato'));
    } else {
        echo json_encode(array('exists' => false, 'message' => 'Username disponibile'));
    }

?>