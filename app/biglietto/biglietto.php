<?php
$volo = json_decode($_POST['flight'], true);
$id = $volo['id'];
$prezzo = $volo['prezzo'];
$valuta = $volo['valuta'];
$andata = $volo['andata'];
$ritorno = $volo['ritorno'];
$compagnia = $volo['compagnia'];
$origine = $andata['tratte'][0]['partenza']['city'];
$destinazione = $andata['tratte'][count($andata['tratte']) - 1]['arrivo']['city'];
$n_scali = count($andata['tratte']) - 1;
?>

<div class="biglietto">
    <p class="compagnia"><?php echo $compagnia; ?></p>
    <p class="intestazione">da <span> <?php echo $origine; ?> </span> a <span><?php echo $destinazione; ?> </span></p>
    <p class="scali">Scali: <?php echo $n_scali; ?></p>
    <p class="prezzo">Prezzo totale:
        <?php $parti = explode('.', $prezzo); $int = $parti[0]; isset($parti[1]) ? $dec = $parti[1] : $dec = "00"; echo "<span class='int'>$int</span>,<span class='decimal'>$dec</span>"; ?> <span class="currency"><?php echo $valuta;?></span> <span class="info">(oneri e tasse inclusi)</span>
    </p>


    <div class="details collapse" data-id="<?php echo $id;?>">
        <hr>
        <div class="container-tratte">
            <div class="andata">
                <h2 class="title">Andata</h2>
                <?php foreach ($andata['tratte'] as $i => $tratta) {
                    $partenza = $tratta['partenza'];
                    $arrivo = $tratta['arrivo'];
                    $data_p = date_format(date_create($partenza['at']), "d-m H:i");
                    $data_a = date_format(date_create($arrivo['at']), "d-m H:i");
                echo "<div class='tratta'>
                    <h3 class='title'>Tratta ".($i+1)."</h3>
                    <p class='intestazione'>da <span>".$partenza['city']."</span> a <span>".$arrivo['city']."</span></p>
                    <p class='schedule'>partenza ".$data_p." / arrivo ".$data_a." </p>
                </div>";
                } ?>
            </div>
            <div class='ritorno'>
                <h2 class='title'>Ritorno</h2>
                <?php foreach ($ritorno['tratte'] as $i => $tratta) {
                    $partenza = $tratta['partenza'];
                    $arrivo = $tratta['arrivo'];
                    $data_p = date_format(date_create($partenza['at']), "d-m H:i");
                    $data_a = date_format(date_create($arrivo['at']), "d-m H:i");
                echo "<div class='tratta'>
                    <h3 class='title'>Tratta ".($i+1)."</h3>
                    <p class='intestazione'>da <span>".$partenza['city']."</span> a <span>".$arrivo['city']."</span></p>
                    <p class='schedule'>partenza ".$data_p." / arrivo ".$data_a." </p>
                </div>";
                } ?>
            </div>
        </div>
    </div>
    <p class='show-details' data-bs-toggle='collapse' data-bs-target=".details[data-id='<?php echo $id;?>']" aria-expanded='false'><img
            class='arrow-details' src='/app/assets/caret-down.png'><span class='text'>Mostra dettagli</span></p>
</div>