<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/auth.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/connection.php";
// Start the session
session_start();

// Check if the user is already logged in
if (checkAuth()) {
    echo "da login a home";
    header('Location: ../home/home.php');
    exit;
}

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Inserisci il tuo username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Validate password 
    if(empty(trim($_POST["password"]))){
        $password_err = "Inserisci la tua password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Check for errors before sending the data to the server
    if(empty($username_err) && empty($password_err)){
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
        $error = "Username e/o password errati.";
    }
    else if (isset($_POST["username"]) || isset($_POST["password"])) {
        // If only one of the two is set
        $error = "Inserisci username e password.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css" />
</head>
<body>
    <div class="content">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> ">
        <div>
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <span class="error"><?php echo $username_err; ?></span>
        </div>    
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span class="error"><?php echo $password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
    <a href="/app/signup/signup.php">Non sei ancora registrato? Registrati!</a>
    <?php if (isset($error)) {
                    echo "<p class='error'>$error</p>";
                }
    ?>
</body>
</html>
