<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/auth.php';

if (checkAuth()) {
    header("Location: ../home/home.php");
    exit;
}

// Verifica l'esistenza di dati POST
if ((!empty($_POST["username"])) && (!empty($_POST["password"])) && (!empty($_POST["confirm-password"])) && (!empty($_POST["firstname"])) && (!empty($_POST["lastname"])) && (!empty($_POST["birthdate"])) && (!empty($_POST["email"]))) {

    $error = array();

    # USERNAME
    // Check if the username is valid
    if (!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])) {
        $error[] = "Username non valido";
    } else {
        include $_SERVER['DOCUMENT_ROOT'] . "/api/users/user.php";
        try {
            if (usernameExists($_POST['username'])) {
                $error[] = "Username già utilizzato";
            }
        } catch (Exception $e) {
            $error[] = $e->getMessage();
        }
    }

    if (strlen($_POST["password"]) < 8) {
        $error[] = "Lunghezza password insufficiente";
    }

    if (strcmp($_POST["password"], $_POST["confirm-password"]) != 0) {
        $error[] = "Le password non coincidono";
    }

    # EMAIL
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = "Email non valida";
    } else {
        $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
        $res = mysqli_query($conn, "SELECT email FROM utenti WHERE email = '$email'");
        if (mysqli_num_rows($res) > 0) {
            $error[] = "Email già utilizzata";
        }
    }

    # inserimento nel database
    if (count($error) == 0) {

        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $password = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO utenti(username, password, firstname, lastname, birthdate, email) VALUES('$username', '$password', '$firstname', '$lastname', '$birthdate', '$email')";

        if (mysqli_query($conn, $query) or die(mysqli_error($conn))) {
            $_SESSION["username"] = $_POST["username"];
            mysqli_close($conn);
            header("Location: ../home/home.php");
            exit;
        } else {
            $error[] = "Errore di connessione al Database";
        }
    }

    mysqli_close($conn);
} else if (isset($_POST["username"])) {
    $error = array("Riempi tutti i campi");
}

?>


<html>

<?php include "../head.php"; ?>

<body>
    <script>
        let errors = new Set();
        <?php
        foreach ($error as $err) {
            echo "errors.add(\"$err\");";
        }
        ?>
        </script>
    <div class="content">
        <section class="app-flex-centered">
            <div id="form-box">
                <div id="logo-container">
                    <div class="app-logo-box">
                        <h1 id="title">FlightBook</h1>
                    </div>
                </div>
                <h1>Registrati</h1>
                <?php include "../message-display/message-display.php"; ?>
                <form id="signup-form" name='signup' method='post' enctype="multipart/form-data" autocomplete="off">
                    <div class="input-container">
                        <div id="firstname-input" class="input">
                            <label for='firstname'>Nome</label>
                            <input type='text' name='firstname' <?php if (isset($_POST["firstname"])) {
                                echo "value=" . $_POST["firstname"];
                            } ?>>
                        </div>
                        <div id="lastname-input" class="input">
                            <label for='lastname'>Cognome</label>
                            <input type='text' name='lastname' <?php if (isset($_POST["username"])) {
                                echo "value=" . $_POST["lastname"];
                            } ?>>
                        </div>
                        <div id="birthdate-input" class="input">
                            <label for='birthdate'>Data di nascita</label>
                            <input type='date' name='birthdate' <?php if (isset($_POST["birthdate"])) {
                                echo "value=" . $_POST["birthdate"];
                            } ?>>
                        </div>
                        <div id="email-input" class="input">
                            <label for='email'>Email</label>
                            <input type='email' name='email' <?php if (isset($_POST["email"])) {
                                echo "value=" . $_POST["email"];
                            } ?>>
                        </div>
                        <div id="username-input" class="input">
                            <label for='username'>Nome utente</label>
                            <input type='text' name='username' <?php if (isset($_POST["username"])) {
                                echo "value=" . $_POST["username"];
                            } ?>>

                        </div>
                        <div>
                            <div id="password-input" class="input">
                                <label for='password'>Password</label>
                                <input type='password' name='password' <?php if (isset($_POST["password"])) {
                                    echo "value=" . $_POST["password"];
                                } ?>>
                            </div>
                            <div id="confirm-password-input" class="input">
                                <label for='confirm-password'>Conferma Password</label>
                                <input type='password' name='confirm-password' <?php if (isset($_POST["confirm-password"])) {
                                    echo "value=" . $_POST["confirm-password"];
                                } ?>>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="submit" class="app-button">Registrati</button>
                </form>
            </div>
            <div class="signin">Hai un account? <a href="/app/login/login.php">Accedi</a>
        </section>

    </div>
</body>


</html>