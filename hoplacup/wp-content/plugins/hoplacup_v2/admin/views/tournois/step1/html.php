<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php
require_once plugin_dir_path(__FILE__) . 'actions.php';
require_once 'model.php';
$phase_init_query = $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d LIMIT 1", $tournoi_id);
$phase_init_ID = $wpdb->get_var($phase_init_query);
?>
<div class="wrap">
    <div style="margin-left: 2rem">
        <h1>Etape 1: Création de la phase de poule initiale</h1>
        <a class="lien-retour"
           href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id)) ?>">Retour
            à
            l'accueil du tournoi</a>
    </div>

    <!-- Section initialisation des dates de début et fin -->
    <section class="form-table">
    <div class="date_container">
        <h3>Sélectionnez une période</h3>
        <?php if ($tournoi): ?>
            <form id="date-form" method="post">
                <span class="date">
                    <label for="start-date">Date de début :</label>
                    <input type="date" id="start-date" name="start_date" class="date_select"
                        value="<?php echo esc_attr($value_start_date); ?>" required>
                </span>

                <span class="date">
                    <label for="end-date">Date de fin :</label>
                    <input type="date" id="end-date" name="end_date" class="date_select"
                        value="<?php echo esc_attr($value_end_date); ?>" required>
                </span>

                <button type="submit" class="button button-primary" name="AjouterDates">
                    Sauvegarder <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </form>
        <?php else: ?>
            <p>Erreur : Le tournoi spécifié est introuvable.</p>
        <?php endif; ?>
    </div>
    </section>
    <!-- Section initialisation des points -->
    <section class="form-table">
        <h3>Définir les Points par Résultat</h3>
        <form method="post" action="">
            <?php wp_nonce_field('save_points_settings', 'points_nonce'); ?>

            <div class="pointsForm" style="text-align: center">
        <span class="form-group">
            <label for="points_win">Points pour une victoire :</label>
            <input name="points_win" type="number" id="points_win" class="small-text win"
                   value="<?php echo esc_attr($points_win); ?>"/>
        </span>
                <span class="form-group">
            <label for="points_draw">Points pour un match nul :</label>
            <input name="points_draw" type="number" id="points_draw" class="small-text draw"
                   value="<?php echo esc_attr($points_draw); ?>"/>
        </span>
                <span class="form-group">
            <label for="points_loss">Points pour une défaite :</label>
            <input name="points_loss" type="number" id="points_loss" class="small-text loss"
                   value="<?php echo esc_attr($points_loss); ?>"/>
        </span>     
                </span>
                <span style="display: flex; justify-content: center;">
                    <button type="submit" name="save_points" class="button button-primary save-btn" title="Sauvegarder"
                            aria-label="Sauvegarder">
                        Sauvegarder <i class="fa-solid fa-floppy-disk"></i>
                    </button>
                </span>
            </div>
        </form>
    </section>
        
        <!-- Modale pour définir la durée des matchs -->
<div id="duration-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="close-duration-modal" class="close">&times;</span>
        <h3>Définir la durée des matchs</h3>
        <form method="POST">
            <?php wp_nonce_field('save_match_duration', 'match_duration_nonce'); ?>
            <span class="span-display" >
            <input type="number" name="duree_match" value="<?php echo esc_attr($duree_match ?? ''); ?>" placeholder="Durée en minutes" required />
            <p style="margin:0px 10px ; font-size:16px ;">Minutes.</p>
            <button type="submit" name="save_match_duration" class="button button-primary">Enregistrer</button>
            </span>
        </form>
    </div>
</div>
    

    <!-- Section ajout de terrains -->
    <section class="form-table">
        <h3>Ajouter des terrains au tournoi</h3>
        <form class="add-poule-form" method="POST" action="<?php echo esc_url($current_url); ?>">
            <select name="terrain_id" id="terrain_id" required style="width: 15%">
                <option value="" disabled selected>Choisissez un terrain</option>
                <?php foreach ($terrains as $terrain): ?>
                    <option value="<?php echo esc_html($terrain->ID); ?>">
                        <?php echo esc_html($terrain->Nom); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="tournoi_id" value="<?php echo esc_html($tournoi_id); ?>">
            <button class="add-poule-button button button-primary" type="submit" name="ajouterTerrain">+ Terrain</button>
        </form>
        <?php $terrains_associes = get_terrains_tournoi($tournoi_id) ?>
        <div class="terrain-container">
        <?php if (empty($terrains_associes)):?>
                <p>Aucun terrain n'a été associé à ce tournoi.</p>
            <?php else:?>
            <?php foreach ($terrains_associes as $terrain_associe): ?>
                <div class="terrain">
                    <?php echo esc_html($terrain_associe->Nom); ?>
                    <button type="button" class="edit-btn delete-btn terrain-btn" title="Retirer le terrain du tournoi" aria-label="Retirer le terrain du tournoi" data-id="<?= $terrain_associe->ID ?>" data-href="admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=<?= $tournoi_id ?>&configstep=1&del=<?= $terrain_associe->ID ?>&club_id=0&division_id=0&pays_nom=0&ville_nom=0&pages=1">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <h3 style="font">Durée d'un match : <span id="duree-match-affiche"><?php echo esc_html($duree_match); ?> minutes</span></h3>
    <span style="display: flex; justify-content: center; margin-top:25px ;">
                <button style="margin-bottom:20px ;" type="button" id="open-duration-modal" class="button button-primary time-btn save-btn" title="Définir la durée des matchs"
                    aria-label="Définir la durée des matchs">
                    Définir la durée des matchs <i class="fa-regular fa-clock" style="color: #ffffff;"></i>
                </button>

                </span>

<div class="poules-container">
<?php
$phase_query = $wpdb->prepare("SELECT Nom FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d LIMIT 1", $tournoi_id);
$phase_nom = $wpdb->get_var($phase_query);
?>
<h2 class="phase-initiale"><?php echo esc_html($phase_nom); ?>
    <button id="edit-phase-button" class="button button-primary button-phase">Modifier <i class="fa-solid fa-pen-to-square"></i></button>
</h2>

    <!-- Modale pour modifier le nom de la phase -->
    <div id="edit-phase-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="close-modal" class="close">&times;</span>
            <h3>Modifier le nom de la phase</h3>
            <form method="POST">
                <input type="text" name="nomPhase" value="<?php echo esc_attr($phase_nom); ?>" placeholder="Nom de la phase" required />
                <button type="submit" name="modifierPhase" class="button button-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <h3>Liste des Poules</h3>

    <div id="creation-poule">
        <form class="add-poule-form" method="POST" action="#creation-poule">
            <input type="text" name="nomPoule" placeholder="Nom de la poule" required />
            <button class="add-poule-button button button-primary" type="submit" name="ajouterPoule">+ Poule</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.hash === '#creation-poule') {
                const targetElement = document.querySelector('#creation-poule');
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>


    <div class="poules-wrapper">

        

        <?php if (empty($poules)):?>
            <p>Aucune poule n'a été créée pour ce tournoi.</p>
        <?php else:?>
        <?php foreach ($poules as $poule): ?>
            <div class="poule">
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $poule->ID ?>">
                <input type="text" name="nomPoule" value="<?php echo esc_html($poule->Nom); ?>" class="NomPoule">
                <button type="submit" name="update" class="edit-btn" title="Mettre à jour" aria-label="Mettre à jour"><i class="fa-solid fa-pen-to-square"></i></button>
                <button type="button" class="edit-btn delete-btn" title="Supprimer la poule" aria-label="Supprimer la poule" data-id="<?= $poule->ID ?>"
                data-href="admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=<?= $tournoi_id ?>&configstep=1&del_poule=<?= $poule->ID ?>&club_id=0&division_id=0&pays_nom=0&ville_nom=0&pages=1">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
                <div data-id="<?php echo htmlspecialchars($poule->ID)?>" class="drop-zone">
                    <!-- Zone pour drag-and-drop -->
                    <?php if (isset($equipesInPoules[$poule->ID])): ?>
                        <?php foreach ($equipesInPoules[$poule->ID] as $equipeId): ?>
                            <?php
                            $equipe = array_filter($equipeResults, function($e) use ($equipeId) {
                                return $e->id == $equipeId;
                            });
                            $equipe = reset($equipe);
                            ?>
                            <?php if ($equipe): ?>
                                <div data-id="<?php echo htmlspecialchars($equipe->id)?>" class="equipe">
                                    <?php echo esc_html($equipe->NomEquipe); ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Glissez une équipe ici</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="lien-etape-container">
    <div class="lien-etape">
        <a href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=2&phaseId=' . $phase_init_ID . '')) ?>" onclick="checkifPoules(event)">Étape suivante <i class="fa-solid fa-arrow-right"></i></a>
        <script>
            async function checkifPoules(event) { // Obligé de faire une fonction async pour attendre la réponse de l'ajax
                event.preventDefault();

                let checkValid = true;
                let message = '';

                await jQuery(document).ready(function ($) {
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'ajax_check_DateTournoi',
                            tournoi_id: <?php echo $tournoi_id; ?>
                        },
                        success: function (response) {
                            if (!response.success) {
                                message = "Les dates de début et/ou de fin du tournoi ne sont pas définies.";
                                checkValid = false;
                            }

                            // Vérification des terrains associés
                            var terrains = document.querySelectorAll('.terrain');
                            if (terrains.length === 0) {
                                message = "Vous devez associer au moins un terrain au tournoi pour continuer.";
                                checkValid = false;
                            }

                            // Vérification des poules et des équipes dans les poules
                            var poules = document.querySelectorAll('.poule');
                            var pouleValide = false;

                            poules.forEach(function(poule) {
                                var equipes = poule.querySelectorAll('.equipe');
                                if (equipes.length >= 2) {
                                    pouleValide = true;
                                }
                            });

                            if (!pouleValide) {
                                message = "Vous devez avoir au moins une poule avec deux équipes pour continuer.";
                                checkValid = false;
                            }

                            if (checkValid) {
                                window.location.href = event.target.href;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: message,
                                });
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error:', textStatus, errorThrown);
                        }
                    });
                });
            }
        </script>
    </div>
</div>
    <h2>Liste des Équipes</h2>
    <div class="filter-form">
        <h3>Filtrer les Équipes</h3>
        <form method="GET" action="">
            <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
            <input type="hidden" name="view" value="<?php echo esc_attr($_GET['view']); ?>">
            <input type="hidden" name="subview" value="<?php echo esc_attr($_GET['subview']); ?>">
            <input type="hidden" name="id" value="<?php echo esc_attr($_GET['id']); ?>">
            <input type="hidden" name="configstep" value="<?php echo esc_attr($_GET['configstep']); ?>">

            <label for="club_id">Filtrer par club :</label>
            <select name="club_id" id="club_id" onchange="this.form.submit()">
                <option value="0">Par défaut</option>
                <?php foreach ($clubsList as $club): ?>
                    <option value="<?php echo esc_attr($club->id); ?>" <?php selected($club_filter, $club->id); ?>>
                        <?php echo esc_html($club->Nom); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="division_id">Filtrer par division :</label>
            <select name="division_id" id="division_id" onchange="this.form.submit()">
                <option value="0">Par défaut</option>
                <?php foreach ($divisionsList as $division): ?>
                    <option value="<?php echo esc_attr($division->id); ?>" <?php selected($division_filter, $division->id); ?>>
                        <?php echo esc_html($division->Division); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="pays_nom">Filtrer par pays :</label>
            <select name="pays_nom" id="pays_nom" onchange="this.form.submit()">
                <option value="0">Par défaut</option>
                <?php foreach ($countriesList as $country): ?>
                    <option value="<?php echo esc_attr($country); ?>" <?php selected($pays_filter, $country); ?>>
                        <?php echo esc_html($country); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="ville_nom">Filtrer par ville :</label>
            <select name="ville_nom" id="ville_nom" onchange="this.form.submit()">
                <option value="0">Par défaut</option>
                <?php foreach ($citiesList as $city): ?>
                    <option value="<?php echo esc_attr($city); ?>" <?php selected($ville_filter, $city); ?>>
                        <?php echo esc_html($city); ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </form>
    </div>

    <div class="team-list">
        <?php foreach ($pagninated_teams as $equipe): ?>
            <?php if (!in_array($equipe->id, array_merge(...array_values($equipesInPoules)))): ?>
                <div data-id="<?php echo htmlspecialchars($equipe->id) ?>" class="equipe">
                    <?php echo esc_html($equipe->NomEquipe); ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

<div class="pagination">
    <form method="GET" action="">
        <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
        <input type="hidden" name="view" value="<?php echo esc_attr($_GET['view']); ?>">
        <input type="hidden" name="subview" value="<?php echo esc_attr($_GET['subview']); ?>">
        <input type="hidden" name="id" value="<?php echo esc_attr($_GET['id']); ?>">
        <input type="hidden" name="configstep" value="<?php echo esc_attr($_GET['configstep']); ?>">
        <input type="hidden" name="club_id" value="<?php echo esc_attr($_GET['club_id']); ?>">
        <input type="hidden" name="division_id" value="<?php echo esc_attr($_GET['division_id']); ?>">
        <input type="hidden" name="pays_nom" value="<?php echo esc_attr($_GET['pays_nom']); ?>">
        <input type="hidden" name="ville_nom" value="<?php echo esc_attr($_GET['ville_nom']); ?>">
        <?php if ($current_page > 1): ?>
            <button type="submit" name="pages" value="<?php echo $current_page - 1; ?>">&laquo; Previous</button>
        <?php endif; ?>

            <?php
            $range = 2; // Number of pages to show before and after the current page
            $showEllipsis = false;

            for ($i = 1; $i <= $total_pages; $i++):
                if ($i == 1 || $i == $total_pages || ($i >= $current_page - $range && $i <= $current_page + $range)) {
                    if ($showEllipsis) {
                        echo '<span>...</span>';
                        $showEllipsis = false;
                    }
                    ?>
                    <button type="submit" name="pages"
                            value="<?php echo $i; ?>" <?php if ($i == $current_page) echo 'class="active"'; ?>><?php echo $i; ?></button>
                    <?php
                } else {
                    $showEllipsis = true;
                }
            endfor;
            ?>

            <?php if ($current_page < $total_pages): ?>
                <button type="submit" name="pages" value="<?php echo $current_page + 1; ?>">Next &raquo;</button>
            <?php endif; ?>
        </form>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {

        // Rendre les selects en dropdown select 2

        $('#club_id').select2();
        $('#division_id').select2();
        $('#pays_nom').select2();
        $('#ville_nom').select2();

        // Fonction pour gérer les drag and drop

        function initDragAndDrop() {
            $(".equipe").draggable({
                revert: "invalid",
                zIndex: 100
            });

            $(".drop-zone").droppable({
                accept: ".equipe",
                drop: function (event, ui) {
                    let equipeId = ui.draggable.data("id");
                    let pouleId = $(this).data("id");

                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'ajax_poule_equipe_add_update',
                            equipe_id: equipeId,
                            poule_id: pouleId
                        },
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error:', textStatus, errorThrown);
                        }
                    });

                    enterPoule(ui.draggable, $(this));
                }
            });

            $(".team-list").droppable({
                accept: ".equipe",
                drop: function (event, ui) {

                    let equipeId = ui.draggable.data("id");

                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'ajax_poule_equipe_remove',
                            equipe_id: equipeId
                        },
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error:', jqXHR.message);
                        }
                    });


                    returnToTeamList(ui.draggable, ".team-list");
                }
            });
        }

        function enterPoule($item, $dropzone) {
            var $sourceDropzone = $item.closest('.drop-zone');
            $dropzone.find('p').remove();
            $item.appendTo($dropzone).css({
                top: 0,
                left: 0,
                position: 'relative'
            }).draggable({
                revert: "invalid",
                zIndex: 100
            });
            if ($sourceDropzone.children('.equipe').length === 0) {
                $sourceDropzone.append('<p>Glissez une équipe ici</p>');
            }
        }

        function returnToTeamList($item, $teamlist) {
            var $dropzone = $item.closest('.drop-zone');
            $item.appendTo($teamlist).css({
                top: 0,
                left: 0,
                position: 'relative'
            }).draggable({
                revert: "invalid",
                zIndex: 100
            });
            if ($dropzone.children('.equipe').length === 0) {
                $dropzone.append('<p>Glissez une équipe ici</p>');
            }
        }

        initDragAndDrop();
    });

    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('edit-phase-modal');
    const openButton = document.getElementById('edit-phase-button');
    const closeButton = document.getElementById('close-modal');

    openButton.addEventListener('click', function () {
        modal.style.display = 'flex';
    });

    closeButton.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // Fermer la modale si on clique en dehors
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

    jQuery(document).ready(function($) {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();

            var deleteUrl = $(this).data('href');
            console.log(deleteUrl)

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });

        $('.terrain-btn').on('click', function(e) {
            e.preventDefault();

            var deleteUrl = $(this).data('href');
            console.log(deleteUrl)

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action retirera le terrain de ce tournoi !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, retirer le terrain !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
        
        $('#terrain_id').select2({
        placeholder: 'Rechercher un terrain',
        allowClear: true
        });
    });

    jQuery(document).ready(function ($) {
        // Fonction pour gérer l'affichage de la modale
        const durationModal = $('#duration-modal');
        const openDurationButton = $('#open-duration-modal');
        const closeDurationButton = $('#close-duration-modal');

        openDurationButton.on('click', function () {
            durationModal.show();
        });

        closeDurationButton.on('click', function () {
            durationModal.hide();
        });

        $(window).on('click', function (event) {
            if (event.target.id === 'duration-modal') {
                durationModal.hide();
            }
        });
    });
</script>