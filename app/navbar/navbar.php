<nav id="navbar">
        <img src="/app/assets/logo.png" alt="logo" id="logo">
        <div class="navbar-links-block">
                <img class="menu-toggle" src="/app/assets/main-menu.png">
                <div id="links">
                        <a href="/app/home/home.php" class='nav-link <?php if ($filename === "home") echo "selected"?>'>Home</a>
                        <a href="/app/galleria/galleria.php" class="nav-link <?php if ($filename === "galleria") echo "selected"?>">Galleria</a>
                        <a href="/app/offerte/offerte.php" class="nav-link <?php if ($filename === "offerte") echo "selected"?>">Voli</a>
                        <a href="/app/prenotazioni/prenotazioni.php" class="nav-link <?php if ($filename === "prenotazioni") echo "selected"?>">Prenotazioni</a>
                        <a href="/app/profilo/profilo.php" class="nav-link <?php if ($filename === "profilo") echo "selected"?>">Profilo</a>
                </div>
                <img id="plane" src="/app/assets/plane.png">
        </div>
</nav>
<script src="/app/navbar/navbar.js" defer="False"></script>