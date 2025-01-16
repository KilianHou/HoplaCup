<?php

$baselink = $breadcrumbs['division']['link'];

if($simulation) {
    $on = $simulation ? 'On' : '';
    echo "<br><p style='background-color:yellow;color:red;text-align:center;' >Simulation: $on \nJour: $day \nHeure: $timer \n display: $displayDate</p>";
}

$count = 0;

?>

<main class="container">

    <h3 class="rouge edition"> <?= EDITION2024 ?></h3>

    <h2 style="font-size: 250%;"><?= MATCH_Á_VENIR ?>  <?= date('j', strtotime($displayDate)) . ' ' . constant(date('F', strtotime($displayDate))) . ' ' . date('Y', strtotime($displayDate)) ?></h2>

    <!-- //<?= PROGRAMME_DU_JOUR ?>  -->

    <table>
                        <thead>
<!--                            --><?php //echo($currentDate) ?>
<!--                            --><?php //echo($currentTime) ?>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($matchs_du_jour  as $match) :
    	                        $static_logo1 = $match->Identifiant_Club1 . '.webp'; $static_logo2 = $match->Identifiant_Club2 . '.webp';
                            ?>
                            <tr>
                                <th class="jourHoraire" colspan="7" style="text-align:center;"><?= $match->Horaire ?> <?= TERRAIN ?> <?= $match->Terrain ?></th>
                            </tr>
                            <tr>
                                <td class="logoEquipe">
                                    <picture>
                                        <?= !empty($match->Nom_Equipe1) ? "<img src='". dirname(dirname(plugin_dir_url(__FILE__))) ."/images/logos_clubs/". $static_logo1 ."' alt='Logo ". $match->Nom_Equipe1 ."'>" : "" ?>
                                    </picture>
                                </td>
                                <td class="nomEquipe nomEquipe1">
                                    <a href="<?= $baselink ?>/equipes/<?= $match->Equipe1 ?>">
                                        <?= $match->Nom_Equipe1 ?>
                                    </a>
                                </td>
                                <td class="separateur1">-</td>
                                <td class= "nomEquipe nomEquipe2">
                                    <a href="<?= $baselink ?>/equipes/<?= $match->Equipe2 ?>">
                                        <?= $match->Nom_Equipe2 ?>
                                    </a>
                                </td>
                                <td class="logoEquipe">
                                    <picture>
                                        <?= !empty($match->Nom_Equipe2) ? "<img src='". dirname(dirname(plugin_dir_url(__FILE__))) ."/images/logos_clubs/". $static_logo2 ."' alt='Logo ". $match->Nom_Equipe2 ."'>" : "" ?>
                                    </picture>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>


    <h2><a class="voirequipe" href="<?= $url . '/equipes' ?>"> <?= VOIREQUIPE ?> </a></h2>

    <div class="schedule-container">
        <div class="day-card">
            <div class="calendar">
                <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/calendar.png" alt="Calendar Icon">
            </div>
            <div class="planning">
                <h2><?= VENDREDI ?></h2>
                <a href="<?= $baselink ?>/programme/?jour=ven" class="btn"><?= PLANNING ?></a>
            </div>
        </div>
        <div class="day-card">
            <div class="calendar">
                <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/calendar.png" alt="Calendar Icon">
            </div>
            <div class="planning">
                <h2><?= SAMEDI ?></h2>
                <a href="<?= $baselink ?>/programme/?jour=sam" class="btn"><?= PLANNING ?></a>
            </div>
        </div>
        <div class="day-card">
            <div class="calendar">
                <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/calendar.png" alt="Calendar Icon">
            </div>
            <div class="planning">
                <h2><?= DIMANCHE ?></h2>
                <a href="<?= $baselink ?>/playoffs" class="btn"><?= PLANNING ?></a>
            </div>
        </div>

    </div>


</main>
<div class="logos">
        <div class="logos-slide">
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/3DEclairage.jpg" alt="logosponsor3DEclairage" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/AT2E.png" alt="logoAT2E" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Audebert.jpg" alt="logoAudebert" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Cegelec.bmp" alt="logoCegelec" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Cérébat.jpeg" alt="logoCerebat" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/ElectricitéKoch.png" alt="logoElectricitéKoch" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/GroupeVincentz.jpg" alt="logoGroupeVincentz" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/HesingueCarrelage.jpeg" alt="logoHesingueCarrelage" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Ilconnect.png" alt="logoIlconnect" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/uha40.png" alt="logoUha40" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Boehli.png" alt="logo Boehli" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/HoplaCafe.png" alt="logo Hopla Café">
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/MoulinJenny.png" alt="logo Moulin Jenny">
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Serat.jpeg" alt="logo Serat" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Lesage.jpg" alt="logo Lesage" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Iloosinformatique.png" alt="logo Iloose informatique" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/Herrgott.png" alt="logo Herrgott" />
        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/HauserPeinture.jpg" alt="logo Hauser Peinture" />
        </div>
    </div>
    <script>
        var copy = document.querySelector(".logos-slide").cloneNode(true);
        document.querySelector('.logos').appendChild(copy);
    </script>