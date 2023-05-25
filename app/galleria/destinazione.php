<?php
$title = $_GET['title'];
$description = $_GET['description'];
$image = $_GET['image'];
?>
<div class="destination">
    <div class="reveal from-left gallery-image" data-title="<?php echo $title;?>">
        <div class="buttons">
            <button class="elimina" data-title="<?php echo $title;?>">Elimina</button>
            <button class="trova-voli" data-title="<?php echo $title;?>">Trova voli</button>
        </div>
        <img src="/app/media/<?php echo $image;?>">
    </div>
    <div class="text">
        <h3 class="title reveal from-right"><?php echo $title;?></h3>
        <p class="descrizione reveal from-down"><?php echo $description;?>
        </p>
    </div>
</div>