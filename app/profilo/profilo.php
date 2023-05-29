<?php

require_once '../auth.php';

// Check if the user is logged in, otherwise redirect to login page
if (checkAuth() === 0) {
    header("Location: ../login/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<?php include '../head.php'; ?>

<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="content">
        <header id="introduction">
            <div id="overlay">
                <h1 id="title">Profilo</h1>
                <p>
                    Dettagli del tuo profilo.
                </p>
            </div>
        </header>
        <div class="sub-header">
                <h2 class="subtitle">Modifica i tuoi dati</h2>
                <button class="app-dismiss-button" id="logout">Disconnettiti</button>
            </div>
        <section class="app-flex-centered">
            <form name='user' id="user-form" method='post' enctype="multipart/form-data" autocomplete="off">
                <div id="username-input" class="input">
                    <label for='username'>Nome utente</label>
                    <input type='text' name='username' disabled>
                </div>
                <div id="firstname-input" class="input">
                    <label for='firstname'>Nome</label>
                    <div class="buttons-wrapper">
                        <div class="buttons hidden" data-id="firstname">
                            <img class="icon edit" src="../assets/edit-info.png"></img>
                            <img class="icon cancel hidden" src="../assets/cancel.png"></img>
                            <img class="icon save hidden" src="../assets/save.png"></img>
                        </div>
                    </div>
                    <input type='text' name='firstname' disabled>
                </div>
                <div id="lastname-input" class="input">
                    <label for='lastname'>Cognome</label>
                    <div class="buttons-wrapper">
                        <div class="buttons hidden" data-id="lastname">
                            <img class="icon edit" src="../assets/edit-info.png"></img>
                            <img class="icon cancel hidden" src="../assets/cancel.png"></img>
                            <img class="icon save hidden" src="../assets/save.png"></img>
                        </div>
                    </div>
                    <input type='text' name='lastname' disabled>
                </div>
                <div id="birthdate-input" class="input">
                    <label for='birthdate'>Data di nascita</label>
                    <div class="buttons-wrapper">
                        <div class="buttons hidden" data-id="birthdate">
                            <img class="icon edit" src="../assets/edit-info.png"></img>
                            <img class="icon cancel hidden" src="../assets/cancel.png"></img>
                            <img class="icon save hidden" src="../assets/save.png"></img>
                        </div>
                    </div>
                    <input type='date' name='birthdate' disabled>
                </div>
                <div id="email-input" class="input">
                    <label for='email'>Email</label>
                    <div class="buttons-wrapper">
                        <div class="buttons hidden" data-id="email">
                            <img class="icon edit" src="../assets/edit-info.png"></img>
                            <img class="icon cancel hidden" src="../assets/cancel.png"></img>
                            <img class="icon save hidden" src="../assets/save.png"></img>
                        </div>
                    </div>
                    <input type='email' name='email' disabled>
                </div>
                </fieldset>
                <button id="submit-button" class="hidden" type="submit">Salva</button>
            </form>
            <?php include '../message-display/message-display.php'; ?>
        </section>
        <section id="post">
            <?php include '../loader/loader.php'; ?>
        </section>
        <section>
        <div id="modal-logout" class="app-modal hidden">
                <div class="modal-content">
                    <p>Sei sicuro di voler uscire?</p>
                        <button id="confirm-button" class="app-button">Conferma</button>
                        <button id="close-button" class="app-dismiss-button">Annulla</button>
                </div>
        </section>

    </div>
    <?php include '../footer/footer.php'; ?>
</body>

</html>