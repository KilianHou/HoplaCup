<?php

if($simulation) {
    echo "Simulation : $simulation \n\nJour : $day \n\nHeure :$timer";
}

// screen-diapo/template.php
$aff = true;
$phase1 = ["A","B","C","D"];
$phase2 = ["E","F","G","H","I","J"];
$phaseP = ['Hopla U13','Castor U13','Hamster U13'];

$div = strtolower($division);

if ($div == 'u9'|| $div == 'u11') {
    $div = 'u9/11';
    $phase1 = ["A", "B", "C", "D", "K"];
    $phase2 = ["E", "F", "G", "H", "I", "J", "L", "M"];
    $phaseP = ['Hopla U9', 'Hopla U11', 'Castor U11', 'Hamster U11'];
}

if($day == 'Fri') {
    $phase = $phase1;
} else if ($day == 'Sat' && $timer <= '1300') {
    $phase = $phase1;
} else if ($day == 'Sat' && $timer >= '1300') {
    $phase = $phase2;
} else if ($day == 'Sun') {
    $phase = $phaseP;
} else {
    echo '<h1>Pas de match !</h1>';
    $aff = false;
}

if ($aff) {

?>

<main>

<?php
      for($i = 0; $i < count($phase); $i++) {
          $selectedPoule = explode(" ", $phase[$i])[0];
?>
    <section>
        <div style="display:flex;justify-content:space-around;">
            <img class="diapo-image" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/mulhousewp.webp" alt="Logo">
            <h1><?= $day !== 'Sun' ? strtoupper($div).' Poule ' : ' Coupe ' ?><?= $phase[$i] ?></h1>
            <img class="diapo-image diapo-hoplacup" src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/mascotte-hopla.png" alt="Logo">
        </div>

        <div style="display:flex;flex-direction:row;max-width:100%;position:relative;">

            <div class="table-diapo">
                <h2>Classement</h2>
                <?php if($day != 'Sun') { ?>
                <table class="classement">
                    <thead>
                        <tr>
                            <th class="col-xs">Position</th>
                            <th class="col-xs"></th>
                            <th>Equipes</th>
                            <th class="col-xs">J</th>
                            <th class="col-xs">Pts</th>
                            <th class="col-xs">+/-</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php                    
                        foreach($poules as $a) {
                            if($day != 'Sun' and $a['Nom_poule'] == $selectedPoule){
                    ?>
                        <tr>
                            <td class="col-xs"><?= $a['Position'] ?></td>
                            <td class="col-xs">
                                <?= !empty($a['Nom_Equipe']) ? '<img class="diapo-logo" src="' . dirname(dirname(plugin_dir_url(__FILE__))) . '/images/logos_clubs/' . $a['Identifiant_Club'] . '.webp" alt="Logo ' . $a['Nom_Equipe'] . '">' : '' ?>
                            </td>
                            <td><?= $a['Nom_Equipe'] ?></td>
                            <td class="col-xs"><?= $a['Matchs_joues'] ?></td>
                            <td class="col-xs"><?= $a['Victoires_matchs_directs'] ?></td>
                            <td class="col-xs"><?= $a['Goal_average'] ?></td>
                        </tr>
                        <?php
                            }
                        }
                    } else if ($day == 'Sun' and $div == 'u9/11') {
                ?>
                <table class="classement">
                    <thead>
                        <tr>
                            <th class="col-xs">Position</th>
                            <th>Equipes</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($classements as $c) {
                            if((($day != 'Sun' and strpos($c['Coupe'], $selectedPoule) !== false) or ($day == 'Sun' and strpos($c['Coupe'], $selectedPoule) !== false and $c['Division'] == explode(" ", $phase[$i])[1]))) {
                    ?>
                        <tr>
                            <td class="col-xs"><?= $c['Classement'] ?></td>
                            <td><?= $c['Equipe'] ?></td>
                        </tr>
                    <?php
                            }
                        }
                    } else if ($day == 'Sun' and $div == 'u13') {
                    ?>
                <table class="classement">
                    <thead>
                        <tr>
                            <th class="col-xs">Position</th>
                            <th>Equipes</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($classements as $c) {
                            if($day == 'Sun' and strpos($c['Coupe'], $selectedPoule) !== false) {
                    ?>
                        <tr>
                            <td class="col-xs"><?= $c['Classement'] ?></td>
                            <td><?= $c['Equipe'] ?></td>
                        </tr>
                    <?php
                            }
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="table-diapo">
                <h2>Matchs</h2>
                <table class="programme">
                    <thead>
                        <tr>
                            <th class="horaire">Horaire</th>
                            <th>Terrain</th>
                            <th colspan="7">Matchs</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($matches as $b) {
                            if($b['date'] == $day) {
                                if(($day !== 'Sun' and $b['Poule'] == $selectedPoule) or ($day == 'Sun' and $div == 'u13' and strpos($b['Phase'], $selectedPoule) !== false) or ($day == 'Sun' and strpos($b['Phase'], $selectedPoule) !== false and $b['Division'] == explode(" ", $phase[$i])[1])) {
                    ?>
                                    <tr>
                                        <td class="col-s"><?= substr($b['time'],0,2).":".substr($b['time'],2) ?></td>
                                        <td class="col-s"><?= $b['Terrain'] ?></td>
                                        <td>
                                            <?= !empty($b['Nom_Equipe1']) ? '<img class="diapo-logo" src="' . dirname(dirname(plugin_dir_url(__FILE__))) . '/images/logos_clubs/' . $b['Identifiant_Club1'] . '.webp" alt="Logo ' . $b['Nom_Equipe1'] . '">' : '' ?>
                                        </td>
                                        <td><?= $b['Nom_Equipe1'] ?></td><td><?= $b['Score_Equipe1'] ?><?= $b['Tab_Equipe1']!=null? '('.$b['Tab_Equipe1'].')':'' ?></td><td> - </td><td><?= $b['Score_Equipe2'] ?><?= $b['Tab_Equipe2']!=null?'('.$b['Tab_Equipe2'].')':'' ?></td><td><?= $b['Nom_Equipe2'] ?></td>
                                        <td>
                                            <?= !empty($b['Nom_Equipe2']) ? '<img class="diapo-logo" src="' . dirname(dirname(plugin_dir_url(__FILE__))) . '/images/logos_clubs/' . $b['Identifiant_Club2'] . '.webp" alt="Logo ' . $b['Nom_Equipe2'] . '">' : '' ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
           </div>
        </div>
    </section>
<?php   } ?>
    <footer class="center">
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_institutionnels/Qr_code_hoplacup.png" alt="Qr code hoplacup" width="200" height="200">
    </footer>
</main>
<?php
}
?>

<script>

    var currentIndex = 0;
    var images = document.querySelectorAll('section');
    images[currentIndex].style.display = 'block';

    function nextSlide() {
        images[currentIndex].style.display = 'none';
        currentIndex = (currentIndex + 1) % images.length;
        images[currentIndex].style.display = 'block';
    }
    
    setInterval(nextSlide, 10000);

</script>
