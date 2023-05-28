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
        <div id="menu">
            <div class="small-line-menu"></div>
            <div class="small-line-menu"></div>
            <div class="small-line-menu"></div>
        </div>
        <header id="introduction">
            <div id="overlay">
                <h1 id="title">Diario di viaggio</h1>
                <p>
                    Qui potrai condividere con il mondo le tue avventure, e viaggiare con
                    la mente in mondi nuovi dando un'occhiata alle foto postate
                    dagli altri.
                </p>
            </div>
        </header>
        <h1>Welcome
            <?php echo $_SESSION['username']; ?>!
        </h1>
        <p>You have successfully logged in.</p>
        <a href="/app/logout.php">Logout</a>
        <section>
            <h2 class="subtitle">Galleria</h2>
            <p id="gallery"></p>
        </section>
        <section id="post">
            <h2 class="subtitle">Posta le tue foto</h2>
            <form id="post-image-form" name="postImage">
                <label for="img">Scegli una foto da postare!</label>
                <input type="file" name="image" accept="image/png, image/jpeg">
                <label for="title">Scegli un titolo</label>
                <input type="text" name="title">
                <label for="description">Inserisci una descrizione</label>
                <input type="text" name="description">
                <button id="post-button">Posta</button>
            </form>


            <?php include '../loader/loader.php'; ?>
            <!--error div-->
            <div id="error" class="hidden">
                <p>Errore nell'aggiunta della foto al tuo album di viaggio</p>
            </div>
        </section>
        <section>
            <div id="modal" class="modal hidden">
                <div class="modal-content">
                    <span class="close">&times;</span>
                </div>
        </section>

    </div>
    <?php include '../footer/footer.php'; ?>
</body>

</html>