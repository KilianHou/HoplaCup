<?php
$selected_poule_id = isset($_GET['poules_id']) ? intval($_GET['poules_id']) : null;
$selected_terrain_id = isset($_GET['terrains_id']) ? intval($_GET['terrains_id']) : null; ?>

<div class="wrap">
    <div class="wrap">
        <div class="titre">
            <h1>Tournoi <strong><?= $tournoi_name ?></strong> : Gestion du tournoi</h1>
            <a class="lien-retour"
               href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id)) ?>">Retour
                à l'accueil du tournoi</a>
        </div>

        <div class="etape_prev">
            <a class="button button-primary button-prev"
               href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=3')) ?>"><i
                        class="fa-solid fa-arrow-left"></i> Étape précédente</a>
        </div>

        <!-- Tabs and Filters Container -->
        <div class="tabs-filters-container">
            <!-- Tabs -->
            <div class="tabs">
                <ul class="tab-links">
                    <li class="active"><a href="#actuel">Matchs</a></li>
                    <li><a href="#fini">Match terminée</a></li>
                </ul>
            </div>
            <!-- Filters -->
            <div class="filters">
                <form method="GET" action="">
                    <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
                    <input type="hidden" name="view" value="<?php echo esc_attr($_GET['view']); ?>">
                    <input type="hidden" name="subview" value="<?php echo esc_attr($_GET['subview']); ?>">
                    <input type="hidden" name="id" value="<?php echo esc_attr($_GET['id']); ?>">
                    <input type="hidden" name="configstep" value="<?php echo esc_attr($_GET['configstep']); ?>">
                    <select name="poules_id" id="poules_id" onchange="this.form.submit()">
                        <option value="all">Toutes les poules</option>
                        <?php foreach ($poules as $poule) : ?>
                            <option value="<?php echo esc_attr($poule->id); ?>" <?php echo $poule->id == $selected_poule_id ? 'selected' : ''; ?>>
                                <?php echo esc_html($poule->Nom); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="terrains_id" id="terrains_id" onchange="this.form.submit()">
                        <option value="all">Tous les terrains</option>
                        <?php foreach ($terrains as $terrain) : ?>
                            <option value="<?php echo esc_attr($terrain->id); ?>" <?php echo $terrain->id == $selected_terrain_id ? 'selected' : ''; ?>>
                                <?php echo esc_html($terrain->Nom); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="tab-content">
            <div id="actuel" class="tab active">
                <h2>Matchs</h2>
                <table class="wp-list-table widefat striped">
                    <thead>
                    <tr>
                        <th>Équipe 1</th>
                        <th>Fairplay</th>
                        <th>Équipe 2</th>
                        <th>Fairplay</th>
                        <th>Score équipe 1</th>
                        <th>Score équipe 2</th>
                        <th>Actions</th>
                        <th>Terrain</th>
                        <th>Poule</th>
                        <th>Horaire</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($matchs as $match) :
                        if (!(isset($match->score_eq1) && isset($match->score_eq2))) :
                            ?>
                            <tr>
                                <td>
                                    <img src="<?= esc_url($match->logo1) ?: 'https://pbs.twimg.com/media/EXfWi8ZXQAAdHIa.jpg'; ?>"
                                        alt="Logo de <?= esc_html($match->equipe1); ?>">
                                    <p><?= esc_html($match->equipe1 ?? 'N/A') ?></p>
                                </td>
                                <td>
                                    <form method="POST" class="submit-fairplay-eq1">
                                        <input type="hidden" name="match_id" value="<?= esc_attr($match->id) ?>">
                                        <input type="hidden" name="equipe1_id" value="<?= esc_attr($match->id_equipe1) ?>">
                                        <input type="hidden" name="tournoi_id" value="<?= esc_attr($match->tournoi_id) ?>">
                                        <input type="number" name="fairplay_eq1" value="<?= esc_attr($match->fairplay_e1) ?>" style="width: 50px">
                                        <button type="submit">+ Fairplay</button>
                                    </form>
                                </td>
                                <td>
                                    <img src="<?= esc_url($match->logo2) ?: 'https://pbs.twimg.com/media/EXfWi8ZXQAAdHIa.jpg'; ?>"
                                        alt="Logo de <?= esc_html($match->equipe2); ?>">
                                    <p><?= esc_html($match->equipe2 ?? 'N/A') ?></p>
                                </td>
                                <td>
                                    <form method="POST" class="submit-fairplay-eq2">
                                        <input type="hidden" name="match_id" value="<?= esc_attr($match->id) ?>">
                                        <input type="hidden" name="equipe2_id" value="<?= esc_attr($match->id_equipe2) ?>">
                                        <input type="hidden" name="tournoi_id" value="<?= esc_attr($match->tournoi_id) ?>">
                                        <input type="number" name="fairplay_eq2" value="<?= esc_attr($match->fairplay_e2) ?>" style="width: 50px">
                                        <button type="submit">+ Fairplay</button>
                                    </form>
                                </td>
                                <form method="POST" class="submit-score">
                                    <td><input type="number" name="score_eq1" value="<?= esc_attr($match->score_eq1 ?? 0) ?>" class="score-input"></td>
                                    <td><input type="number" name="score_eq2" value="<?= esc_attr($match->score_eq2 ?? 0) ?>" class="score-input"></td>
                                    <input type="hidden" name="tournoi_id" value="<?= esc_attr($match->tournoi_id) ?>">


                                <td>
                                        <input type="hidden" name="action" value="save_match_result">
                                        <input type="hidden" name="match_id" value="<?= esc_attr($match->id) ?>">
                                        <button type="submit" class="action-btn" name="save_resultat"
                                                class="button button-primary"><i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </form>
                                </td>
                                <td><?= esc_html($match->terrain ?? 'N/A') ?></td>
                                <td><?= esc_html($match->poule ?? 'N/A') ?></td>
                                <td><?= esc_html($match->horaire_depart ?? 'N/A') ?></td>
                            </tr>
                        
                        <?php
                        endif;
                    endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="fini" class="tab">
                <h2>Match terminé</h2>
                <table class="wp-list-table widefat striped">
                    <thead>
                    <tr>
                        <th>Équipe 1</th>
                        <th>Fairplay</th>
                        <th>Score</th>
                        <th>Équipe 2</th>
                        <th>Fairplay</th>
                        <th>Score</th>
                        <th>Terrain</th>
                        <th>Poule</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($matchs as $match) :
                        if ((($match->score_eq1 != null) && ($match->score_eq2 != null))) : ?>
                            <tr>
                                <td>
                                    <img src="<?= esc_url($match->logo1) ?: 'https://pbs.twimg.com/media/EXfWi8ZXQAAdHIa.jpg'; ?>"
                                         alt="Logo de <?= esc_html($match->equipe1); ?>">
                                    <p><?= esc_html($match->equipe1 ?? 'N/A') ?></p></td>
                                <td>
                                    <form method="POST" class="submit-fairplay-eq1">
                                        <input type="hidden" name="match_id" value="<?= esc_attr($match->id) ?>">
                                        <input type="hidden" name="equipe1_id" value="<?= esc_attr($match->id_equipe1) ?>">
                                        <input type="hidden" name="tournoi_id" value="<?= esc_attr($match->tournoi_id) ?>">
                                        <input type="number" name="fairplay_eq1" value="<?= esc_attr($match->fairplay_e1) ?>" style="width: 50px">
                                        <button type="submit">+ Fairplay</button>
                                    </form>
                                </td>
                                <td><input type="number" name="score_eq1"
                                           value="<?= esc_attr($match->score_eq1 ?? 0) ?>" class="score-input" readonly>
                                </td>
                                <td>
                                    <img src="<?= esc_url($match->logo2) ?: 'https://pbs.twimg.com/media/EXfWi8ZXQAAdHIa.jpg'; ?>"
                                         alt="Logo de <?= esc_html($match->equipe2); ?>">
                                    <p><?= esc_html($match->equipe2 ?? 'N/A') ?></p></td>
                                <td>
                                    <form method="POST" class="submit-fairplay-eq2">
                                        <input type="hidden" name="match_id" value="<?= esc_attr($match->id) ?>">
                                        <input type="hidden" name="equipe2_id" value="<?= esc_attr($match->id_equipe2) ?>">
                                        <input type="hidden" name="tournoi_id" value="<?= esc_attr($match->tournoi_id) ?>">
                                        <input type="number" name="fairplay_eq2" value="<?= esc_attr($match->fairplay_e2) ?>" style="width: 50px">
                                        <button type="submit">+ Fairplay</button>
                                    </form>
                                </td>
                                <td><input type="number" name="score_eq2"
                                           value="<?= esc_attr($match->score_eq2 ?? 0) ?>" class="score-input" readonly>
                                </td>
                                <td><?= esc_html($match->terrain ?? 'N/A') ?></td>
                                <td><?= esc_html($match->poule ?? 'N/A') ?></td>
                            </tr>
                        <?php endif; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="lien-etape-container">
    <div class="lien-etape">
        <a href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=5')) ?>">Étape
            suivante <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabLinks = document.querySelectorAll('.tab-links li');
        const tabContents = document.querySelectorAll('.tab-content .tab');
        
        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            tabLinks.forEach(link => link.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            document.querySelector(`.tab-links li a[href="#${activeTab}"]`).parentElement.classList.add('active');
            document.getElementById(activeTab).classList.add('active');
        } else {
            tabLinks[0].classList.add('active');
            tabContents[0].classList.add('active');
        }

        tabLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                tabLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');

                const target = this.querySelector('a').getAttribute('href').substring(1);
                tabContents.forEach(content => content.classList.remove('active'));
                document.getElementById(target).classList.add('active');

                localStorage.setItem('activeTab', target);
            });
        });
        document.querySelectorAll('.submit-score').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const score_eq1 = form.elements['score_eq1'];
                const score_eq2 = form.elements['score_eq2'];
                const match_id = form.elements['match_id'];
                const tournoi_id = form.elements['tournoi_id'];

                console.log("Score of Equipe 1:", score_eq1 ? score_eq1.value : "Not Found");
                console.log("Score of Equipe 2:", score_eq2 ? score_eq2.value : "Not Found");
                console.log("Match ID:", match_id ? match_id.value : "Not Found");
                console.log("Tournoi ID:", tournoi_id ? tournoi_id.value : "Not Found");

                jQuery(document).ready(function ($) {
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: "ATTENTION : Les résultats ne pourront pas être modifiés après validation.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, je suis sûr !',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'ajax_save_match_result',
                                    match_id: match_id.value,
                                    score_eq1: score_eq1.value,
                                    score_eq2: score_eq2.value
                                },
                                success: function (response) {
                                    console.log("Résultats du match enregistrés :", response);


                                    $.ajax({
                                        url: ajaxurl,
                                        type: 'POST',
                                        data: {
                                            action: 'ajax_update_classement',
                                            tournoi_id: tournoi_id.value
                                        },
                                        success: function (response) {
                                            console.log("Classement mis à jour :", response);

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Succès',
                                                text: 'Les résultats et le classement ont été mis à jour avec succès.',
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        },
                                        error: function (error) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Erreur',
                                                text: 'Une erreur s\'est produite lors de la mise à jour du classement.',
                                            });
                                        }
                                    });
                                },
                                error: function (error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: 'Une erreur s\'est produite lors de l\'enregistrement des résultats.',
                                    });
                                }
                            });
                        }
                    });
                });
            });
        });

        const formsFairplayEq1 = document.querySelectorAll('.submit-fairplay-eq1');
    formsFairplayEq1.forEach((form) => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const fairplay_eq1 = form.elements['fairplay_eq1'];
            const match_id = form.elements['match_id'];
            const tournoi_id = form.elements['tournoi_id'];
            const id_equipe1 = form.elements['equipe1_id'];

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ajax_save_match_fairplay_eq1',
                    match_id: match_id.value,
                    fairplay_eq1: fairplay_eq1.value,
                    tournoi_id: tournoi_id.value,
                    id_equipe1: id_equipe1.value
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Les points de fairplay ont été enregistrés avec succès.',
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur s\'est produite lors de l\'enregistrement des points de fairplay.',
                    });
                }
            });
        });
    });

    const formsFairplayEq2 = document.querySelectorAll('.submit-fairplay-eq2');
    formsFairplayEq2.forEach((form) => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const fairplay_eq2 = form.elements['fairplay_eq2'];
            const match_id = form.elements['match_id'];
            const tournoi_id = form.elements['tournoi_id'];
            const id_equipe2 = form.elements['equipe2_id'];

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ajax_save_match_fairplay_eq2',
                    match_id: match_id.value,
                    fairplay_eq2: fairplay_eq2.value,
                    tournoi_id: tournoi_id.value,
                    id_equipe2: id_equipe2.value
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Les points de fairplay ont été enregistrés avec succès.',
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur s\'est produite lors de l\'enregistrement des points de fairplay.',
                    });
                }
            });
        });
    });
    })

</script>
