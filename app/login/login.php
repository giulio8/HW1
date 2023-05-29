<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/auth.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/connection.php";

// Check if the user is already logged in
if (checkAuth()) {
    header('Location: ../home/home.php');
    exit;
}

$error = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $error[] = "Inserisci il tuo username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password 
    if (empty(trim($_POST["password"]))) {
        $error[] = "Inserisci la tua password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check for errors before sending the data to the server
    if (count($error) == 0) {
        // Check user data in the database
        // Prepare a select statement
        $username = mysqli_real_escape_string($conn, $username);
        $sql = "SELECT username, password FROM utenti WHERE username = '$username'";
        $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        // Check if the username exists, if yes then verify the password

        if (mysqli_num_rows($res) > 0) {
            $entry = mysqli_fetch_assoc($res);
            if (password_verify($password, $entry['password'])) {
                // Set session variables
                $_SESSION["username"] = $entry['username'];
                header("Location: ../home/home.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        // If the username doesn't exist or the password is wrong
        $error[] = "Username e/o password errati.";
    }
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
                <h1>Accedi</h1>
                <?php include "../message-display/message-display.php"; ?>
                <form id="login-form" name='login' method='post' enctype="multipart/form-data" autocomplete="off">
                    <div class="input-container">
                        <div id="username-input" class="input">
                            <label for='username'>Nome utente</label>
                            <input type='text' name='username' <?php if (isset($_POST["username"])) {
                                echo "value=" . $_POST["username"];
                            } ?>>
                        </div>
                        <div id="password-input" class="input">
                            <label for='password'>Password</label>
                            <input type='password' name='password' <?php if (isset($_POST["password"])) {
                                echo "value=" . $_POST["password"];
                            } ?>>
                        </div>
                    </div>
                    <button type="submit" id="submit" class="app-button">Accedi</button>
                </form>
            </div>
            <div class="signup">Non hai un account? <a href="/app/signup/signup.php">Registrati</a>
        </section>

    </div>
</body>


</html>