<?php

$filename = basename($_SERVER['PHP_SELF'], ".php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HW1</title>
    <link rel="stylesheet" href="/app/bootstrap-collapse.min.css">
    <link rel="stylesheet" href="/app/app.css">
    <link rel="stylesheet" href="/app/message-display/message-display.css">
    <link rel="stylesheet" href="/app/loader/loader.css">
    <link rel="stylesheet" href="/app/navbar/navbar.css">
    <link rel="stylesheet" href="/app/footer/footer.css">
    <link rel="stylesheet" href="/app/biglietto/biglietto.css">
    <link rel="stylesheet" href="<?php echo $filename ?>.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&family=Pacifico:wght@300;400&display=swap" rel="stylesheet">
    <script src="/app/app.js" defer="True"></script>
    <script src="<?php echo $filename ?>.js" defer="True"></script>
</head>