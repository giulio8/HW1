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

<?php include '../head.php';
?>

<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="content">
        <header id="introduction">
            <div id="overlay">
                <h1 id="title">Offerte di voli
                    <?php if (isset($_GET["luogo"]))
                        echo 'per ' . $_GET['luogo']; ?>
                </h1>
                <p>
                    Da qui potrai cercare e selezionare offerte interessanti per volare
                    <?php
                    if (isset($_GET["luogo"])) {
                        echo "verso " . $_GET['luogo'] . " che abbiamo trovato per te!";
                    } else {
                        echo "dove vuoi.";
                    } ?>
                </p>
            </div>
        </header>
        <section>
            <form id="search-form">
                <div id="origin-input" class="input">
                    <label for="origin">Partenza da</label>
                    <input type="text" name="origin" id="origin" placeholder="Luogo di partenza">
                </div>
                <div id="destination-input" class="input">
                    <label for="destination">Destinazione</label>
                    <input type="text" name="destination" id="ritorno" <?php if (isset($_GET["luogo"]))
                        echo 'value="' . $_GET['luogo'] . '"'; ?> placeholder="Luogo di destinazione">
                </div>
                <div id="departureDate-input" class="input">
                    <label for="departureDate">Data di partenza</label>
                    <input type="date" name="departureDate" id="departureDate">
                </div>
                <div id="returnDate-input" class="input">
                    <label for="returnDate">Data di ritorno</label>
                    <input type="date" name="returnDate" id="returnDate">
                </div>
                <button id="search-button" class="app-button">Cerca</button>
            </form>
            <?php include '../message-display/message-display.php'; ?>
        </section>
        <button id="back-button" class="app-dismiss-button hidden">Torna indietro</button>
        <section>
            <div id="result" class="hidden">
                <div class="result-content">
                </div>
            </div>
        </section>

        <?php include '../loader/loader.php'; ?>

    </div>
    <?php include '../footer/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>