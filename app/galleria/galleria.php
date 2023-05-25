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
            <h2 class="subtitle">Galleria</h2>
            <p id="gallery">
            </p>
        </section>

        <section id="post">
            <h2 class="subtitle">Posta le tue foto</h2>
            <form id="post-image-form" name="postImage">
                <label for="img">Scegli una foto da postare!</label>
                <input type="file" name="image" accept="image/png, image/jpeg">
                <label for="titolo">Scegli un titolo</label>
                <input type="text" name="titolo">
                <label for="description">Inserisci una descrizione</label>
                <input type="text" name="descrizione">
                <button id="post-button">Posta</button>
            </form>


            <?php include '../loader/loader.php';
            include '../message-display/message-display.php';
            ?>
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