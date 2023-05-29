<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';

    $email = mysqli_real_escape_string($conn, $_GET['email']);
    function emailExists($email) {
        global $conn;
        $email = mysqli_real_escape_string($conn, $email);
        $query = "SELECT email FROM utenti WHERE email = '$email'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if (mysqli_num_rows($res) > 0) {
            return true;
        }
        return false;
    }

    /* if (emailExists($email)) {
        echo json_encode(array('exists' => true, 'message' => 'Email già utilizzata'));
    } else {
        echo json_encode(array('exists' => false, 'message' => 'Email disponibile'));
    } */

?>