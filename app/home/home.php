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
        <section id="introduction">
            <div class="overlay">
                <div class="app-logo-box">
                    <h1 id="title">FlightBook</h1>
                </div>
                <p id="slogan">Il mondo è un libro, e chi non viaggia ne conosce solo una pagina.</p>
            </div>
        </section>
    </div>
    <?php include '../footer/footer.php'; ?>
</body>

</html>