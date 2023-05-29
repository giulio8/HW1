<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once 'user.php';

$username = mysqli_real_escape_string($conn, $_GET['username']);

 if (usernameExists($username)) {
    echo json_encode(array('exists' => true, 'message' => 'Username già utilizzato'));
} else {
    echo json_encode(array('exists' => false, 'message' => 'Username disponibile'));
}

?>