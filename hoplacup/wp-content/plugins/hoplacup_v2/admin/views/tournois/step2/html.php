<div class="wrap">
    <?php
        // Requête pour récupérer l'ID de la phase initiale
        $phase_init_query = $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d LIMIT 1", $tournoi_id);
        $phase_init_ID = $wpdb->get_var($phase_init_query);

        if (isset($_GET['phaseId']) && is_numeric($_GET['phaseId'])) {
            $phase_id = intval($_GET['phaseId']);
            $phase_query = $wpdb->prepare("SELECT Nom FROM {$wpdb->prefix}phases WHERE ID = %d", $phase_id);
            $phase_nom = $wpdb->get_var($phase_query);
        }
    ?>

    <!-- Titre de la page -->

    <div class="titre">
        <h1>Tournoi <strong><?= $tournoi_name ?></strong> : Générer les phases, poules et matchs</h1>
        <a class="lien-retour" href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id)) ?>">Retour à l'accueil du tournoi</a>
    </div>

    <div class="etape_prev">
    <a class="button button-primary button-prev" href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id .'&configstep=1&club_id=0&division_id=0&pays_nom=0&ville_nom=0&pages=1')) ?>"><i class="fa-solid fa-arrow-left"></i> Étape précédente</a>
    </div>

    <!-- Container principal -->
    <div class="container-equipes">
    <div class="container-step2">
        <!-- Liste des phases (en haut, une seule colonne) -->
        <div class="container-liste-phase">
            <h3>Liste des Phases</h3>
            <form method="POST" action="">
                <button type="submit" name="add_phase" class="add-phase-button button-primary"><i class="fa-regular fa-square-plus"></i> Ajouter une phase</button>
            </form>
            <div class="phase-list">
                <?php foreach ($phases as $index => $phase): ?>
                    <?php
                        $isSelected = isset($_GET['phaseId']) && intval($_GET['phaseId']) === intval($phase->id);
                        $selectedClass = $isSelected ? 'selected-phase' : '';
                    ?>
                    <div class="phase-container <?= $selectedClass ?>">
                        <button onclick="getInformations(<?= intval($phase->id) ?>)" class="phase-item" data-id="<?= intval($phase->id) ?>">
                            <?= htmlspecialchars($phase->Nom) ?>
                        </button>
                        <?php if ($phase->id != $phase_init_ID) : ?>
                            <!-- Bouton de suppression de la phase -->
                            <a href="admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=<?= intval($tournoi_id) ?>&configstep=2&phaseId=<?= $phase_init_ID ?>&del_phase=<?= intval($phase->id) ?>" class="delete-phase" data-id="<?= intval($phase->id) ?>">
                                <button type="button" class="action-btn button-delete" title="Supprimer la phase" aria-label="Supprimer la phase">
                                    Supprimer <i class="fa-solid fa-trash delete-btn"></i>
                                </button>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section d'affichage de la phase sélectionnée -->
        <div class="container-phase">
            <div class="phase-header">
                <div class="titre-phase">
                    <h1><?= $selectedPhase ? htmlspecialchars($selectedPhase->Nom) : 'Aucune phase sélectionnée' ?></h1>
                    <button id="edit-phase-button" class="button button-primary button-phase" aria-label="Mettre à jour">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>

            <!-- Formulaire pour ajouter des poules -->
            <?php if ($selectedPhase && intval($phase_id) !== intval($phase_init_ID)) : ?>
                <form class="add-poule-form" method="POST">
                    <input type="text" name="nomPoule" placeholder="Nom de la poule" required />
                    <input type="hidden" name="phaseId" value="<?= intval($phase_id) ?>" />
                    <button class="add-poule-button button button-primary" type="submit" name="ajouterPoule">+ Poule</button>
                </form>

                <div class="classement-matches-section">
                    <label for="add-classement-match" style="margin-right: 10px;">Matchs de classement:</label>
                    <form class="add-classement-match-form" method="POST" style="display: inline;">
                        <input type="hidden" name="phaseId" value="<?= intval($phase_id) ?>" />
                        <button id="add-classement-match" class="add-classement-match-button button button-primary" type="submit" name="ajouterClassementMatch">+ Matchs de classement</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php
            $tournoiId = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $currentPhaseId = isset($_GET['phaseId']) ? intval($_GET['phaseId']) : 0;

            $firstPhase = $wpdb->get_row($wpdb->prepare("
                SELECT id 
                FROM {$wpdb->prefix}phases 
                WHERE tournoi_id = %d 
                ORDER BY id ASC 
                LIMIT 1", 
                $tournoiId
            ));

            // Stockage de l'ID de la première phase
            $firstPhaseId = $firstPhase ? intval($firstPhase->id) : 0;
            ?>

            <div class="poule-container poules-wrapper">
                <?php foreach ($organizedPoules as $poule) : ?>
                    <div class="poule" data-id="<?= intval($poule['id_poule']) ?>">
                        <input type="text" name="nomPoule" value="<?php echo htmlspecialchars($poule['nom_poule']); ?>" class="NomPoule" readonly>
                        <?php if ($poule['type_poule'] !== 'Classement') : ?>
                            <button class="action-btn edit-poule-button" data-poule-id="<?= $poule['id_poule'] ?>" title="Modifier" aria-label="Modifier">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        <?php endif; ?>
                        <?php if ($currentPhaseId != $firstPhaseId) : ?>
                            <button type="button" class="action-btn delete-poule" title="Supprimer la poule" aria-label="Supprimer la poule" data-href="admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=<?= $tournoiId ?>&configstep=2&phaseId=<?= $currentPhaseId ?>&del_poule=<?= htmlspecialchars($poule['id_poule']) ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        <?php endif; ?>
                        <div class="drop-zone" data-id="<?= intval($poule['id_poule']) ?>">
                            <?php if ($currentPhaseId === $firstPhaseId) : // Si on est sur la première phase ?>
                                <?php if (!empty($poule['equipes'])) : ?>
                                    <?php foreach ($poule['equipes'] as $equipe) : ?>
                                        <div class="equipe" data-id="<?= intval($poule['id_poule']) ?>">
                                            <?= htmlspecialchars($equipe) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p>Aucune équipe</p>
                                <?php endif; ?>
                            <?php else : // Pour les autres phases ?>
                                <?php
                                $transfert_poules = $wpdb->get_results($wpdb->prepare("
                                    SELECT 
                                        tp.classement_origin, 
                                        tp.id_poule_origin, 
                                        p.Nom AS poule_nom_origin
                                    FROM 
                                        {$wpdb->prefix}transferts_phases AS tp
                                    INNER JOIN 
                                        {$wpdb->prefix}poules AS p ON p.ID = tp.id_poule_origin
                                    WHERE 
                                        tp.id_poule_destination = %d
                                    ORDER BY tp.classement_origin ASC",
                                    $poule['id_poule']
                                ));
                                if (!empty($transfert_poules)) :
                                    foreach ($transfert_poules as $transfert) :
                                        $classement = $transfert->classement_origin == 1 ? '1er' : $transfert->classement_origin . 'ème';
                                        ?>
                                        <div class="equipe" 
                                        data-position-equipe="<?= intval($transfert->classement_origin) ?>"
                                        data-id-poule-destinaton="<?= intval($poule['id_poule']) ?>"
                                        data-id-poule-origin="<?= intval($transfert->id_poule_origin) ?>">
                                            <?= htmlspecialchars($transfert->poule_nom_origin) . " - " . $classement; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif ($currentPhaseId != $firstPhaseId) : ?>
                                    <p>Glissez une équipe ici</p>
                                <?php else : ?>
                                    <p>Aucune équipe</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Modal pour modifier une poule -->
        <div id="edit-poule-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span id="close-poule-modal" class="close">&times;</span>
                <h3>Modifier le nom de la poule</h3>
                <form method="POST">
                    <input type="hidden" id="edit-poule-id" name="poule_id" value="" />
                    <input type="text" id="edit-poule-name" name="nom_poule" placeholder="Nom de la poule" required />
                    <button type="submit" name="modifierPoule" class="button button-primary">Enregistrer</button>
                </form>
            </div>
        </div>

        <!-- Modal pour modifier le nom de la phase -->
        <div id="edit-phase-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span id="close-modal" class="close">&times;</span>
                <h3>Modifier le nom de la phase</h3>
                <form method="POST">
                    <input type="text" name="nomPhase" value="<?php echo esc_attr($phase_nom ?? ''); ?>" placeholder="Nom de la phase" required />
                    <button type="submit" name="modifierPhase" class="button button-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

   <!-- Liste des équipes -->
   <div class="liste-des-equipes">
    <h2>Liste des Équipes</h2>
    <div class="filter-form">
        <h3>Filtrer les équipes</h3>
        <?php
        // Récupérer les positions et les filtres actuels
        $positions = get_positions_from_previous_phase($phaseId, $tournoi_id) ?? [];
        $poule_filter = isset($_GET['poule']) ? intval($_GET['poule']) : 0;
        $classement_filter = isset($_GET['classement']) ? intval($_GET['classement']) : 0;

        // Construire l'URL de base pour les filtres
        $current_url = admin_url('admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=2&phaseId=' . $phaseId);
        $query_parameters = $_GET;
        unset($query_parameters['poule'], $query_parameters['classement']); // Supprimer les anciens filtres
        $base_url = add_query_arg($query_parameters, $current_url);
        ?>
        <form method="GET" action="<?= esc_url($base_url); ?>">
            <!-- Ajouter les paramètres existants -->
            <?php foreach ($query_parameters as $key => $value) : ?>
                <input type="hidden" name="<?= esc_attr($key); ?>" value="<?= esc_attr($value); ?>">
            <?php endforeach; ?>
            
            <!-- Filtre par poule -->
            <label for="poule">Poule :</label>
            <select name="poule" id="poule" onchange="this.form.submit()">
                <option value="0" <?= $poule_filter === 0 ? 'selected' : ''; ?>>Par défaut</option>
                <?php foreach ($positions as $poules) : ?>
                    <option value="<?= intval($poules['poule_id']); ?>" <?= $poule_filter === intval($poules['poule_id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($poules['poule_nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Filtre par classement -->
            <label for="classement">Classement :</label>
            <select name="classement" id="classement" onchange="this.form.submit()">
                <option value="0" <?= $classement_filter === 0 ? 'selected' : ''; ?>>Par défaut</option>
                <?php
                // Construire une liste des classements uniques
                $classement_options = [];
                foreach ($positions as $classements) {
                    $classement_value = intval($classements['position']);
                    if (!in_array($classement_value, $classement_options)) {
                        $classement_options[] = $classement_value;
                        $selected = ($classement_filter === $classement_value) ? 'selected' : '';
                        echo "<option value='$classement_value' $selected>" . htmlspecialchars($classements['position']) . "</option>";
                    }
                }
                ?>
            </select>
        </form>
    </div>

       <?php
       // Filtrer les positions en fonction des filtres sélectionnés
       $filtered_positions = array_filter($positions, function ($position) use ($poule_filter, $classement_filter) {
           $match_poule = $poule_filter === 0 || intval($position['poule_id']) === $poule_filter;
           $match_classement = $classement_filter === 0 || intval($position['position']) === $classement_filter;
           return $match_poule && $match_classement;
       });

       // Calculs pour définir la pagination à 20 équipes par page
       $teams_per_page = 20;
       $current_page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
       $total_teams = count($filtered_positions);
       $total_pages = ceil($total_teams / $teams_per_page);
       $offset = ($current_page - 1) * $teams_per_page;
       $paged_positions = array_slice($filtered_positions, $offset, $teams_per_page);

       // Construire l'URL de pagination en conservant les filtres
       $query_parameters = $_GET;
       $query_parameters['page_num'] = '';
       $base_url = add_query_arg($query_parameters, admin_url('admin.php'));
       ?>

       <?php if (!empty($paged_positions)) : ?>
           <div class="team-list">
               <?php foreach ($paged_positions as $position) : ?>
                   <div class="equipe"
                        data-position-equipe="<?= intval($position['position']); ?>"
                        data-id-poule-destinaton="<?= intval($poule['id_poule']); ?>"
                        data-id-poule-origin="<?= intval($position['poule_id']); ?>">
                       <?= htmlspecialchars($position['poule_nom']) . " - " . $position['position']; ?>
                   </div>
               <?php endforeach; ?>
           </div>
       <?php else : ?>
           <div class="team-list">
               <p>Aucune équipe disponible pour la phase précédente.</p>
           </div>
       <?php endif; ?>

       <?php if ($total_pages > 1) : ?>
           <div class="pagination" style="display: flex; justify-content: center;">
               <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                   <a style="margin: 2px;" class="button button-primary" href="<?= esc_url(add_query_arg('page_num', $i, $base_url)); ?>">
                       <button class="button button-primary" class="<?= ($i == $current_page) ? 'active' : ''; ?>"><?= $i; ?></button>
                   </a>
               <?php endfor; ?>
           </div>
       <?php endif; ?>
   </div>


<form method="POST" action="">
    <button name="matchesGeneration" class="nextStepButton button button-primary button-prev">Étape suivante <i class="fa-solid fa-arrow-right"></i></button>
</form>

<?php if ($phase_id != $phase_init_ID): ?>
    <script>
jQuery(document).ready(function ($) {
    function makeDraggable($element) {
        $element.draggable({
            revert: 'invalid',
            zIndex: 100
        });
    }

    function enterPoule($item, $dropzone) {
        var $sourceDropzone = $item.closest('.drop-zone');
        $dropzone.find('p').remove();

        $item.appendTo($dropzone).css({
            top: 0,
            left: 0,
            position: 'relative'
        });

        makeDraggable($item);

        if ($sourceDropzone.children('.equipe').length === 0) {
            $sourceDropzone.append('<p>Glissez une équipe ici</p>');
        }

        // Rafraîchissement de la page après le déplacement
        location.reload();
    }

    function returnToTeamList($item, $teamlist) {
        console.log('Returning item to the team list.');

        var $dropzone = $item.closest('.drop-zone');

        $item.appendTo($teamlist).css({
            top: 0,
            left: 0,
            position: 'relative'
        });

        makeDraggable($item);

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ajax_reset_placeholder',
                poule_origin_id: $item.data('poule-origin'),
                classement_origin: $item.data('position')
            },
            success: function (response) {
                console.log('Reset placeholder response:', response);
                // Rafraîchissement de la page après le déplacement
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error during reset placeholder:', textStatus, errorThrown);
            }
        });

        if ($dropzone.children('.equipe').length === 0) {
            $dropzone.append('<p>Glissez une équipe ici</p>');
        }
    }

    function initPlaceholderDragAndDrop() {
        $(".drop-zone").droppable({
            accept: ".equipe",
            drop: function (event, ui) {
                let pouleDestinationId = $(this).data("id"); // ID de la poule destination
                let pouleOriginId = ui.draggable.data("id-poule-origin"); // ID de la poule d'origine
                let classementOrigin = ui.draggable.data("position-equipe"); // Classement d'origine

                if (pouleDestinationId && pouleOriginId && classementOrigin) {
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'ajax_transfer_placeholder',
                            poule_origin_id: pouleOriginId,
                            classement_origin: classementOrigin,
                            poule_destination_id: pouleDestinationId
                        },
                        success: function (response) {
                            console.log('Transfer response:', response);
                            // Rafraîchissement de la page après le déplacement
                            location.reload();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error during transfer:', textStatus, errorThrown);
                        }
                    });

                    // Visuellement déplacer l'équipe
                    enterPoule(ui.draggable, $(this));
                } else {
                    console.warn('Missing required data for drop action.');
                }
            }
        });

        $(".team-list").droppable({
            accept: ".equipe",
            drop: function (event, ui) {
                let pouleDestinationId = ui.draggable.data("id-poule-destinaton");
                let classementOrigin = ui.draggable.data("position-equipe");
                let pouleOrigin = ui.draggable.data("id-poule-origin");

                console.log('Dropping back to team list:', {
                    pouleDestinationId,
                    classementOrigin,
                    pouleOrigin
                });

                if (pouleDestinationId && classementOrigin && pouleOrigin) {
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'ajax_check_transfer_phase',
                            poule_origin_id: pouleOrigin,
                            classement_origin: classementOrigin,
                            poule_destination_id: pouleDestinationId
                        },
                        success: function (response) {
                            if (response.success) {
                                console.log('Match found in transferts_phases:', response.data);
                            } else {
                                console.warn('No matching transfer phase found.');
                            }
                            // Rafraîchissement de la page après le déplacement
                            location.reload();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error in AJAX check:', textStatus, errorThrown);
                        }
                    });
                }

                returnToTeamList(ui.draggable, $(this));
            }
        });
    }

    // Initialize draggable elements and droppable zones
    makeDraggable($('.equipe'));
    initPlaceholderDragAndDrop();
});

    </script>
    <style>
        .drop-zone .equipe:hover {
            background-color: #a8a8a8;
            color: #fff;
            cursor: pointer;
        }
    </style>
<?php endif; ?>

    <script>
        function getInformations(phaseId){
            window.location = '?page=hoplacup-v2-settings&view=tournois&subview=item&id=<?= $tournoi_id ?>&configstep=2&phaseId=' + phaseId;
        }

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

    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('edit-poule-modal');
    const closeButton = document.getElementById('close-poule-modal');
    const editButtons = document.querySelectorAll('.edit-poule-button');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const pouleId = button.getAttribute('data-poule-id');
            document.getElementById('edit-poule-id').value = pouleId;
            modal.style.display = 'flex';
        });
    });

    closeButton.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

    jQuery(document).ready(function($) {
        $('.delete-phase').on('click', function(e) {
            e.preventDefault();

            var deleteUrl = $(this).attr('href');

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "ATTENTION : Les poules créées dans cette phase seront également supprimées !",
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
        $('.delete-poule').on('click', function(e) {
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


        // Génération des matchs initiaux

        // const matchesButton = document.getElementById('matchesGeneration');


    });

    </script>