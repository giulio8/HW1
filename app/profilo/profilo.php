<!DOCTYPE html>
<html>

<?php include '../head.php'; ?>

<body>
    <?php include '../navbar/navbar.php'; ?>
    <header id="introduction">
        <div id="overlay">
            <h1 id="title">Profilo</h1>
            <p>
                Dettagli del tuo profilo.
            </p>
        </div>
    </header>
    <section>
    <form name='user' disabled method='post' enctype="multipart/form-data" autocomplete="off">
                <div class="input">
                    <label for='firstname'>Nome</label>
                    <input type='text' name='firstname' <?php if(isset($_POST["firstname"])){echo "value=".$_POST["firstname"];} ?>>
                    <div><img src="./assets/close.svg"/></div>
                </div>
                <div class="username">
                    <label for='lastname'>Cognome</label>
                    <input type='text' name='lastname' <?php if(isset($_POST["username"])){echo "value=".$_POST["lastname"];} ?>>
                    <div><img src="./assets/close.svg"/></div>
                </div>
                <div class="birthdate">
                    <label for='birthdate'>Data di nascita</label>
                    <input type='date' name='birthdate' <?php if(isset($_POST["birthdate"])){echo "value=".$_POST["birthdate"];} ?>>
                    <div><img src="./assets/close.svg"/></div>
                </div>
                <div class="email">
                    <label for='email'>Email</label>
                    <input type='email' name='email' <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Formato email errato</span></div>
                </div>
                <div class="username">
                    <label for='username'>Nome utente</label>
                    <input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Nome utente non disponibile</span></div>
                </div>
                <div class="password">
                    <label for='password'>Password</label>
                    <input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Inserisci almeno 8 caratteri</span></div>
                </div>
                <div class="confirm_password">
                    <label for='confirm_password'>Conferma Password</label>
                    <input type='password' name='confirm_password' <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?>>
                    <div><img src="./assets/close.svg"/><span>Le password non coincidono</span></div>
                </div>
    </section>
    <section id="post">
        <?php include '../loader/loader.php'; 
              include '../error-display/error-display.php';
        ?>
    </section>
    <section>
        <div id="modal" class="modal hidden">
            <div class="modal-content">
                <span class="close">&times;</span>
            </div>
    </section>

    <?php include '../footer/footer.php'; ?>
</body>

</html>