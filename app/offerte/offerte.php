<!DOCTYPE html>
<html>

<?php include '../head.php'; ?>

<body>
    <?php include '../navbar/navbar.php'; ?>
    <header id="introduction">
        <div id="overlay">
            <h1 id="title">Offerte di voli per <?php echo $_GET["luogo"]?></h1>
            <p>
                Da qui potrai cercare e selezionare offerte interessanti per volare verso <?php echo $_GET["luogo"]?> che abbiamo trovato per te!
            </p>
        </div>
    </header>
    <section>
        <form id="search-form">
            <label for="origin">Partenza da</label>
            <input type="text" name="origin" id="origin" placeholder="Inserisci la città di partenza">
            <label for="destination">Destinazione</label>
            <input type="text" name="destination" id="ritorno" value="<?php echo $_GET['luogo']?>" placeholder="Inserisci la città di destinazione">
            <label for="departureDate">Data di partenza</label>
            <input type="date" name="departureDate" id="departureDate">
            <label for="returnDate">Data di ritorno</label>
            <input type="date" name="returnDate" id="returnDate">
            <button id="search-button">Cerca</button>
        </form>
        <?php include '../error-display/error-display.php'; ?>
    </section>
    <section>
        <div id="result" class="hidden">
            <div class="result-content">
            </div>
    </section>

    <?php include '../loader/loader.php'; ?>

    <?php include '../footer/footer.php'; ?>
</body>

</html>