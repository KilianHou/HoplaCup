<div class="wrap">

    <h1>Tournoi <strong><?= $tournoi_name ?></strong> : Définir les transferts d'équipes à travers les phases</h1>

    <?php
    $success_message = get_transient('step3_success_message');
    if ($success_message) {
        $duration = get_option('_transient_timeout_step3_success_message');
        echo '<div class="updated-message" data-duration="' . human_time_diff(time(), $duration) . '"><div class="inner"><p>' . esc_html($success_message) . '</p></div></div>';
        delete_transient('step3_success_message');
    }
    $error_message = get_transient('step3_error_message');
    if ($error_message) {
        $duration = get_option('_transient_timeout_step3_error_message');
        echo '<div class="error-message" data-duration="' . human_time_diff(time(), $duration) . '"><div class="inner"><p>' . esc_html($error_message) . '</p></div></div>';
        delete_transient('step3_error_message');
    }
    ?>

    <a class="lien-retour" href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id)) ?>">Retour à l'accueil du tournoi</a>

    <form id="tournoi-form" method="post" action="<?= plugin_dir_url(__FILE__) . 'actions.php' ?>">

        <?php wp_nonce_field('tournoi_step_3_hoplacup-v2-settings'); ?>
        <input type="hidden" id="action-config-tournoi" name="action" value="save_transferts">
        <input type="hidden" name="tournoi_id" value="<?= $tournoi_id ?>">

        <section id="cont-phases">
            <?php
            $compte_phases = 1;
            foreach ($phases as $phase => $infos_phase) :
                /**
                 * Phases de poules (avant les playoffs)
                 */
                if ($phase < 100) :
            ?>
                    <div class="phase">
                        <h2>Phase <?= $phase ?></h2>
                        <div class="cont-poules">
                            <?php foreach ($infos_phase['poules'] as $key => $poule) : ?>
                                <div class="poule">
                                    <h4><?= $poule['nompoule'] ?></h4>
                                    <?php
                                    /**
                                     * L'admin doit  pouvoir configurer à partir de la phase 2
                                     */
                                    if ($phase > 1) : ?>
                                        <?php for ($i = 0; $i < $poule['nombreEquipes']; $i++) :

                                            $selected = ''; // par défaut aucune option est selected

                                            /**
                                             * Construire un name pour chaque champ select
                                             * 
                                             * ex. : "phase2_idPoule_$i"
                                             * le $i ici sert juste à donner un name unique
                                             */
                                        ?>
                                            <select name="phase<?= $phase ?>_<?= $poule['idpoule'] ?>_<?= $i ?>">

                                                <option value="">...</option> <!-- Option par défaut vide -->

                                                <?php
                                                /**
                                                 * Construction des options des selects: 
                                                 * "Nom poule précédente"+"index_equipes"
                                                 * => A1, A2, A3 ... C1, C2, C3
                                                 */
                                                foreach ($phases[$compte_phases - 1]['poules'] as $key => $prev_poule) :

                                                    // Récupérer l'ID et le nom de chaque poule de phase précédente
                                                    $nom_prev_poule = $prev_poule['nompoule'];
                                                    $id_prev_poule  = $prev_poule['idpoule'];

                                                    // Boucle sur le nombre d'équipes de la poule précédente
                                                    for ($nEquipe = 1; $nEquipe < (int)$prev_poule['nombreEquipes'] + 1; $nEquipe++) :

                                                        // nom de clé d'identification ex. : 1_156 (1er de poule qui a id 156)
                                                        $key_rank_idpoule = $nEquipe . '_' . $id_prev_poule;

                                                        // option selected false par défaut
                                                        $enableSelect = false;

                                                        // si une valeur est déjà renseignée ($poules_maps), préselectionner l'option,
                                                        // mais uniquement si $selected ne contient encore rien pour ce <select>
                                                        if (isset($poules_maps[$key_rank_idpoule]) && $poules_maps[$key_rank_idpoule] == $poule['idpoule'] && $selected == "") {

                                                            // premier selected détecté, appliquer le selected pour l'option courante
                                                            $enableSelect = true;
                                                            $selected = 'selected=selected';

                                                            // Retirer l'information d'association du tableau $poules_maps
                                                            // pour ne pas qu'elle soit réappliquée dans le prochain select
                                                            unset($poules_maps[$key_rank_idpoule]);
                                                        }
                                                ?>
                                                        <option value="<?= $key_rank_idpoule ?>" <?= $enableSelect ? $selected : '' ?>><?= $nom_prev_poule . $nEquipe ?></option>
                                                <?php
                                                    endfor;
                                                endforeach;
                                                ?>
                                            </select>
                                        <?php endfor ?>
                                    <?php endif ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
            <?php endif;
                $compte_phases++;
            endforeach ?>

            <?php
            /**
             * Les "phases" au-delà de 100 sont les playoffs, leurs numéros correspondent aux 3 niveaux de coupes
             * 
             * Phase 101 => Hopla Cup
             * Phase 102 => Castor Cup
             * Phase 103 => Hamster Cup
             */
            $coupes = array();
            $coupes[101] = 'Hopla Cup';
            $coupes[102] = 'Castor Cup';
            $coupes[103] = 'Hamster Cup';
            /**
             * Obtenir la toute dernière clé de phase
             */
            end($phases); // get la toute dernière phase (qui devrait être > 100 : les coupes de playoffs)
            $last_phase = key($phases); // get sa clé (101, 102 ...)
            /**
             * Obtenir la dernière clé de phase de poule (celle juste avant les playoffs)
             */
            $key_phase_before_playoff = null;
            foreach ($phases as $key => $value) {
                if ($key < 100) {
                    $key_phase_before_playoff = $key;
                }
            }
            /**
             * Si au moins une coupe de playoff est définie,
             * Afficher la colonne des playoffs
             */
            if (isset($phases[101])) : ?>

                <div class="phase phase-playoffs">
                    <h2>Playoffs</h2>
                    <?php
                    /**
                     * Les phases > 100 correspondent à des coupes de playoffs
                     * pour chaque [101, (102, 103, ...)]
                     */
                    for ($i = 101; $i < $last_phase + 1; $i++) :
                    ?>
                        <div class="coupe">
                            <ul class="classements-playoffs">
                                <?php
                                /**
                                 * Les "poules" de playoffs correspondent aux étapes
                                 * de classements de playoffs (ex. classement 5/6, 1/2 finale etc.)
                                 * 
                                 * Seuls les deux premiers classements nécessitent d'être configurés
                                 * 
                                 * (ex. : 1/2 finale, et 5/6/7/8 (ou directement 5/6 si que 6 equipes dans la coupe))
                                 * Le reste peut et devrait être déterminé automatiquement (gagnants 1/2 finales vont en finale etc.)
                                 * 
                                 * (Les poules de classements sont triées dans model.php)
                                 */

                                for ($index_poule = 0; $index_poule < 2; $index_poule++) :
                                    if (isset($phases[$i]['poules'][$index_poule])) :
                                ?>
                                        <li>
                                            <h5><?= $phases[$i]['poules'][$index_poule]['nompoule'] ?></h5>
                                            <ul class="matchs-playoffs">
                                                <?php
                                                /**
                                                 * Afficher les matchs, et leurs selects pour transférer les équipes de
                                                 * la dernière phase de poules (ex. : F1 va en 1/2 finale, ou en 5/6, ou en 5/6/7/8)
                                                 */
                                                $match_index = 0;
                                                foreach ($phases[$i]['poules'][$index_poule]['matchs'] as $match => $match_id) : ?>
                                                    <li>
                                                        <?php $selected = ''; // par défaut aucune option est selected 
                                                        ?>
                                                        <select name="phase<?= $phase ?>_<?= $match_id ?>_a">
                                                            <option value="">...</option> <!-- Option par défaut vide -->
                                                            <?php
                                                            /**
                                                             * Afficher un match et select l'équipe 1 à y transférer
                                                             * récupérer les dernières poules de phases de poules pour les selects
                                                             */
                                                            foreach ($phases[$key_phase_before_playoff]['poules'] as $key => $prev_poule) :
                                                                $nom_prev_poule = $prev_poule['nompoule'];
                                                                $id_prev_poule = $prev_poule['idpoule'];
                                                                for ($nEquipe = 1; $nEquipe < (int)$prev_poule['nombreEquipes'] + 1; $nEquipe++) :

                                                                    // nom de clé d'identification ex. : 1_156 (1er de poule qui a id 156)
                                                                    $key_rank_idpoule = $nEquipe . '_' . $id_prev_poule;

                                                                    // option selected false par défaut
                                                                    $enableSelect = false;

                                                                    // si une valeur est déjà renseignée ($matchs_maps), préselectionner l'option
                                                                    // uniquement si $selected ne contient encore rien pour ce select/options
                                                                    if (isset($matchs_maps[$key_rank_idpoule]) && $matchs_maps[$key_rank_idpoule] == $match_id && $selected == "") {

                                                                        // premier selected détecté, appliquer le selected pour l'option courante
                                                                        $enableSelect = true;
                                                                        $selected = 'selected=selected';

                                                                        // Retirer l'information d'association du tableau $matchs_maps
                                                                        // pour ne pas qu'elle soit réappliquée dans le prochain select
                                                                        unset($matchs_maps[$key_rank_idpoule]);
                                                                    }
                                                            ?>
                                                                    <option value="<?= $key_rank_idpoule ?>" <?= $enableSelect ? $selected : '' ?>><?= $nom_prev_poule . $nEquipe ?></option>
                                                            <?php endfor;
                                                            endforeach ?>
                                                        </select> Vs

                                                        <?php $selected = ''; // par défaut aucune option est selected 
                                                        ?>

                                                        <select name="phase<?= $phase ?>_<?= $match_id ?>_b">
                                                            <option value="">...</option> <!-- Option par défaut vide -->
                                                            <?php
                                                            /**
                                                             * Afficher un match et select l'équipe 2 à y transférer
                                                             * récupérer les dernières poules de phases de poules pour les selects
                                                             */
                                                            foreach ($phases[$key_phase_before_playoff]['poules'] as $key => $prev_poule) :

                                                                $nom_prev_poule = $prev_poule['nompoule'];
                                                                $id_prev_poule = $prev_poule['idpoule'];
                                                                for ($nEquipe = 1; $nEquipe < (int)$prev_poule['nombreEquipes'] + 1; $nEquipe++) :

                                                                    // nom de clé d'identification ex. : 1_156 (1er de poule qui a id 156)
                                                                    $key_rank_idpoule = $nEquipe . '_' . $id_prev_poule;

                                                                    // option selected false par défaut
                                                                    $enableSelect = false;

                                                                    // si une valeur est déjà renseignée ($matchs_maps), préselectionner l'option
                                                                    // uniquement si $selected ne contient encore rien pour ce select/options
                                                                    if (isset($matchs_maps[$key_rank_idpoule]) && $matchs_maps[$key_rank_idpoule] == $match_id && $selected == "") {

                                                                        // premier selected détecté, appliquer le selected pour l'option courante
                                                                        $enableSelect = true;
                                                                        $selected = 'selected=selected';

                                                                        // Retirer l'information d'association du tableau $matchs_maps
                                                                        // pour ne pas qu'elle soit réappliquée dans le prochain select
                                                                        unset($matchs_maps[$key_rank_idpoule]);
                                                                    }
                                                            ?>
                                                                    <option value="<?= $key_rank_idpoule ?>" <?= $enableSelect ? $selected : '' ?>><?= $nom_prev_poule . $nEquipe ?></option>
                                                            <?php endfor;
                                                            endforeach ?>
                                                        </select>
                                                    </li>
                                                <?php $match_index++;
                                                endforeach ?>
                                            </ul>
                                        </li>
                                <?php
                                    endif;
                                endfor ?>
                            </ul>
                        </div>
                    <?php endfor ?>
                </div>
            <?php endif ?>
        </section>
        <div id="cont-submit">
            <?php submit_button('Sauvegarder les transferts', 'primary', 'submit-tournoi'); ?>
        </div>
    </form>
</div>


<script>
    /**
     * Message sauvegarde
     */
    jQuery(document).ready(function($) {
        // Vérifie s'il y a un message de succès
        if ($('.updated-message').length) {
            let duration = parseInt($('.updated-message').data('duration')) * 1000
            setTimeout(function() {
                $('.updated-message').fadeOut('slow')
            }, duration)
        }
        // Vérifie s'il y a un message d'erreur de sauvegarde
        if ($('.error-message').length) {
            let duration = parseInt($('.error-message').data('duration')) * 1000
            setTimeout(function() {
                $('.error-message').fadeOut('slow')
            }, duration)
        }
    })
</script>