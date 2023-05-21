<?php
    require_once '../auth.php';
    require_once '../../api/connection.php';

    if (checkAuth()) {
        header("Location: ../home/home.php");
        exit;
    }   

    // Verifica l'esistenza di dati POST
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"]) && !empty($_POST["firstname"]) && !empty($_POST["lastname"]) && !empty($_POST["birthdate"]) && !empty($_POST["email"]))
    {
        $error = array();

        
        # USERNAME
        // Check if the username is valid
        if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])) {
            $error[] = "Username non valido";
        } else {
            include "/api/users/username_exists.php";
            if (usernameExists($_POST['username'])) {
                $error[] = "Username già utilizzato";
            }
        }
      
        if (strlen($_POST["password"]) < 2) {
            $error[] = "Lunghezza password insufficiente";
        } 
       
        if (strcmp($_POST["password"], $_POST["confirm_password"]) != 0) {
            $error[] = "Le password non coincidono";
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
    }
    else if (isset($_POST["username"])) {
        $error = array("Riempi tutti i campi");
    }

?>


<html>
    <head>
        <link rel='stylesheet' href='signup.css'>
        <script src='signup.js' defer="true"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="favicon.png">
        <meta charset="utf-8">

        <title>Iscriviti - Musity modifica lunghezza password</title>
    </head>
    <body>
        <div id="logo">
            Musity
        </div>
        <main>
        <section class="main_left">
        </section>
        <section class="main_right">
            <h1>Iscriviti gratuitamente per conoscere la tua musica preferita</h1>
            <form name='signup' method='post' enctype="multipart/form-data" autocomplete="off">
                <div class="username">
                    <label for='firstname'>Nome</label>
                    <input type='text' name='firstname' <?php if(isset($_POST["firstname"])){echo "value=".$_POST["firstname"];} ?>>
                    <div><img src="./assets/close.svg"/></div>
                </div>
                <div class="username">
                    <label for='lastname'>Cognome</label>
                    <input type='text' name='lastname' <?php if(isset($_POST["username"])){echo "value=".$_POST["lastname"];} ?>>
                    <div><img src="./assets/close.svg"/></div>
                </div>
                <div class="birthdate">
                    <label for='birthdate'>Data di nascita</label>
                    <input type='date' name='birthdate' <?php if(isset($_POST["birthdate"])){echo "value=".$_POST["birthdate"];} ?>>
                    <div><img src="./assets/close.svg"/></div>
                </div>
                <div class="email">
                    <label for='email'>Email</label>
                    <input type='email' name='email' <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Formato email errato</span></div>
                </div>
                <div class="username">
                    <label for='username'>Nome utente</label>
                    <input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Nome utente non disponibile</span></div>
                </div>
                <div class="password">
                    <label for='password'>Password</label>
                    <input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Inserisci almeno 8 caratteri</span></div>
                </div>
                <div class="confirm_password">
                    <label for='confirm_password'>Conferma Password</label>
                    <input type='password' name='confirm_password' <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Le password non coincidono</span></div>
                </div>
                <?php if(isset($error)) {
                    foreach($error as $err) {
                        echo "<div class='errorj'><img src='./assets/close.svg'/><span>".$err."</span></div>";
                    }
                } ?>
                <div class="submit">
                    <input type='submit' value="Registrati" id="submit">
                </div>
            </form>
            <div class="signup">Hai un account? <a href="/app/login/login.php">Accedi</a>
        </section>
        </main>
    </body>
</html>