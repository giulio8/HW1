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
                <h1 id="title">Voli prenotati</h1>
                <p>
                    In questa sezione trovi tutti i voli che hai prenotato con noi. Puoi vedere
                    i dettagli del volo, e se vuoi puoi anche cancellare la prenotazione.
                </p>
            </div>
        </header>
        <section>
            <div id="result" class="hidden">
                <div class="result-content">
                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>