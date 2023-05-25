<!DOCTYPE html>
<html>

<?php include '../head.php'; ?>

<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="content">
        <header id="introduction">
            <div id="overlay">
                <h1 id="title">Voli prenotati</h1>
                <p>
                    In questa sezione trovi tutti i voli che hai prenotato con noi. Puoi vedere
                    i dettagli del volo, e se vuoi puoi anche cancellare la prenotazione.
                </p>
            </div>
        </header>
        <section>
            <h2 class="subtitle">Prenotazioni</h2>
            <p id="prenotazioni"></p>
        </section>
        <section id="post">
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