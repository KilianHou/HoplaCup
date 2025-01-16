<?php

$baselink = $breadcrumbs['division']['link'];

?>


<main class="container">

    <h1>Playoffs <?= $division_name ?></h1>

    <?php

    if (!isset($matchs[0])) {

        echo '<p>Aucune donnée</p>';
    } //
    else {

        $phase_match   = '';
        $current_class = '';
        $i = 0;

        foreach ($matchs as $match) {

            $class = "";

            /**
             * Categorie du match (hopla, castor, hamster)
             * 
             */
            if (strpos($match->Phase, 'Castor') !== false) {
                $class = 'castor';
            } //
            elseif (strpos($match->Phase, 'Hamster') !== false) {
                $class = 'hamster';
            } //
            else {
                $class = "hopla";
            }

            /**
             * Si changement de classe, ouvrir une <section>
             * 
             */
            if ($class != $current_class) {
                echo ($i > 0) ? "</section>" : "";
                echo "<section class=\"batch $class\">";
                $current_class = $class;
            }

            /**
             * Si changement de type de classement, mettre le titre
             * 
             */
            if ($match->Phase !== $phase_match) {
                echo "<h4 class=\"$class\">".constant($match->Phase)."</h4>";
                $phase_match = $match->Phase;
            }
    ?>
            <div class="match-container match-<?= $class ?>">

                <p><?= constant($match->Jour) ?> - <?= str_replace(':', 'h', $match->Horaire) ?> - <?=TERRAIN?> <?= $match->Terrain ?></p>

                <div class="logo">
                    <?php if ($match->Identifiant_Club1) : ?>
                        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $match->Identifiant_Club1 ?>.webp" alt="Logo <?= $match->Nom_Equipe1 ?>">
                    <?php endif ?>
                </div>

                <div class="details">

                    <div class="cont-score">
                        <div class="equipe">
                            <a href="<?= $baselink ?>/equipes/<?= $match->Identifiant_Equipe1 ?>"><?= $match->Nom_Equipe1 ?></a>
                        </div>
                        <div class="score">
                            <?= $match->Score_Equipe1 ?>
                        </div>
                        <div class="separateur">
                            -
                        </div>
                        <div class="score">
                            <?= $match->Score_Equipe2 ?>
                        </div>
                        <div class="equipe equipe2">
                            <a href="<?= $baselink ?>/equipes/<?= $match->Identifiant_Equipe2 ?>"><?= $match->Nom_Equipe2 ?></a>
                        </div>
                    </div>
                    <div class="cont-tir-au-but">
                        <div class="equipe">
                            <?= ($match->Tab_Equipe1 != '') ? constant('TIRSAUBUT')  : '' ?>
                        </div>
                        <div class="score">
                            <?= $match->Tab_Equipe1 ?>
                        </div>
                        <div class="separateur">
                            -
                        </div>
                        <div class="score">
                            <?= $match->Tab_Equipe2 ?>
                        </div>
                        <div class="equipe equipe2">
                            <?= ($match->Tab_Equipe2 != '') ? constant('TIRSAUBUT') : '' ?>
                        </div>
                    </div>

                </div>

                <div class="logo">
                    <?php if ($match->Identifiant_Club2) : ?>
                        <img src="<?= dirname(dirname(plugin_dir_url(__FILE__))) ?>/images/logos_clubs/<?= $match->Identifiant_Club2 ?>.webp" alt="Logo <?= $match->Nom_Equipe2 ?>">
                    <?php endif ?>
                </div>

            </div>

        <?php
            $i++;
        } ?>
        </section>
    <?php } ?>







</main>