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
                <h1 id="title">Galleria delle destinazioni</h1>
                <p>
                    Qui potrai condividere con il mondo le tue avventure, e viaggiare con
                    la mente in mondi nuovi dando un'occhiata alle foto postate
                    dagli altri.
                </p>
            </div>
        </header>
        <section>
            <div class="sub-header">
                <h2 class="subtitle">Galleria</h2>
                <button class="app-button" id="aggiungi">Carica nuova destinazione</button>
            </div>
            <p id="gallery">
            </p>
        </section>

        <?php include '../loader/loader.php';
        include '../message-display/message-display.php';
        ?>
        </section>
        <section>
            <div id="modal-add-dest" class="app-modal hidden">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form id="post-image-form" name="postImage">
                        <div id="img-input" class="input">
                            <label for="img">Scegli una foto da postare!</label>
                            <input type="file" name="image" accept="image/png, image/jpeg">
                        </div>
                        <div id="title-input" class="input">
                            <label for="titolo">Titolo</label>
                            <input type="text" name="titolo" id="titolo" placeholder="Luogo di destinazione">
                        </div>
                        <div id="description-input" class="input">
                            <label for="description">Inserisci una descrizione</label>
                            <input type="text" name="descrizione">
                        </div>
                        <button id="post-button" class="app-button">Posta</button>
                        <button id="close-button" class="app-dismiss-button">Annulla</button>
                    </form>
                </div>
        </section>

    </div>
    <?php include '../footer/footer.php'; ?>
</body>

</html>