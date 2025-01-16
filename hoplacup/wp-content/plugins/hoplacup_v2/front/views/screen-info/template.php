<?php


if($simulation) {
    echo "Simulation : $simulation \n\nJour : $day \n\nHeure :$timer";
}

// screen-info/template.php
$aff = true;
$time = '';


if($div == 'u911') {
    $div = 'u9/11';
}

if($timer <= '1300') {
    $timer_start = '0600';
    $timer_end = '1300';
} elseif ($timer >= '1300') {
    $timer_start = '1300';
    $timer_end = '2200';
}

if(($day == 'Fri') or ($day == 'Sat' ) or ($day == 'Sun')) {
    $aff = true;
} else {
    echo '<h1>Pas de match !</h1>';
    $aff = false;
}

if ($aff) {

    ?>
    <main>

        <h1><?= strtoupper($div) ?></h1>

        <table>
            <thead>
            <tr>
                <th>Horaire</th>
                <?php
                foreach($terrain as $a) {
                    ?>
                    <th colspan='7' style="border-right:2px solid #000;">Terrain <?= $a['Terrain'] ?></th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach($matches as $b) {
            if($day == $b['date'] and $b['time'] >= $timer_start and $b['time'] <= $timer_end ) {
            if ($time < $b['time']) {
            $time = $b['time'] ?>
            <tr><td><?=  substr($b['time'],0,2).":".substr($b['time'],2); ?></td>
                <?php   }
                if ($time == $b['time']) {
                    $tab = $b['Tab_Equipe1'] != null and $b['Tab_Equipe2'] != null;
                    ?>
                    <td>
                        <?= !empty($b['Nom_Equipe1']) ? '<img class="diapo-logo" src="' . dirname(dirname(plugin_dir_url(__FILE__))) . '/images/logos_clubs/' . $b['Identifiant_Club1'] . '.webp" alt="Logo ' . $b['Nom_Equipe1'] . '">' : '' ?>
                    </td>
                    <td><?= $b['Nom_Equipe1'] ?></td><td><?= $b['Score_Equipe1'] ?><?= $b['Tab_Equipe1'] != null ? '('.$b['Tab_Equipe1'].')':'' ?></td><td> - </td><td><?= $b['Score_Equipe2'] ?><?= $b['Tab_Equipe2'] != null ? '('.$b['Tab_Equipe2'].')':''?></td><td><?= $b['Nom_Equipe2'] ?></td>
                    <td style="border-right:4px solid #000;">
                        <?= !empty($b['Nom_Equipe2']) ? '<img class="diapo-logo" src="' . dirname(dirname(plugin_dir_url(__FILE__))) . '/images/logos_clubs/' . $b['Identifiant_Club2'] . '.webp" alt="Logo ' . $b['Nom_Equipe2'] . '">' : '' ?>
                    </td><!--</tr>-->
                    <?php
                }
                }
                }
                ?>
            </tbody>
        </table>
        <footer class="center">
            <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_institutionnels/Qr_code_hoplacup.png" alt="Qr code hoplacup" width="200" height="200">
        </footer>
    </main>

<?php } ?>