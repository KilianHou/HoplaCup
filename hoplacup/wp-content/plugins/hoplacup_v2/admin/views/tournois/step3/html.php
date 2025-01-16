<?php
// Requête pour récupérer l'ID de la phase initiale
$phase_init_query = $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d LIMIT 1", $tournoi_id);
$phase_init_ID = $wpdb->get_var($phase_init_query);
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Configurer les matchs</h1>
    <span id="infos-etapes"></span>
    <br>
    <a class="lien-retour" href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id)) ?>">Retour à l'accueil du tournoi</a>
    <hr class="wp-header-end">

    <div class="lien-etape-prev-container">
        <div class="lien-etape lien-etape-prev">
        <a href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=2&phaseId='. $phase_init_ID .'')) ?>"><i class="fa-solid fa-arrow-left"></i> Étape précédente</a>
        </div>
    </div>

    <?php
        global $wpdb;
        $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $table_tournois = $wpdb->prefix . 'tournois';

        $tournoi = $wpdb->get_row($wpdb->prepare("SELECT Date_debut, Heure_fin FROM $table_tournois WHERE ID = %d", $tournoi_id), ARRAY_A);

        $dateDebut = isset($tournoi['Date_debut']) ? date('Y-m-d\TH:i', strtotime($tournoi['Date_debut'])) : '';
        $heureFin = isset($tournoi['Heure_fin']) ? date('H:i', strtotime($tournoi['Heure_fin'])) : '';
    ?>

    <h1>Veuillez saisir une  de début du tournoi : <?php echo esc_attr($tournoi['Date_debut']); ?><button class="edit-btn edit-tournoi-name-button" data-tournoi-id="<?= $tournoi_id ?>" title="Modifier" aria-label="Modifier">
        <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </h1>
    <h1>
        Heure de début du match le plus tard : 
        <?php echo esc_attr($heureFin); ?>
        <button class="edit-btn edit-tournoi-end-time-button" data-tournoi-id="<?= $tournoi_id ?>" title="Modifier" aria-label="Modifier">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </h1>
    <?php
        global $wpdb;
        $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $table_tournois = $wpdb->prefix . 'tournois';

        // Récupérer les informations du tournoi
        $tournoi = $wpdb->get_row($wpdb->prepare("SELECT Date_debut, Heure_fin, Duree_matchs FROM $table_tournois WHERE ID = %d", $tournoi_id), ARRAY_A);
        // Vérifiez si heure_fin existe
        if (isset($tournoi['Heure_fin']) && !empty($tournoi['Heure_fin'])) {
            ?>
            <form method="POST">
                <input type="hidden" name="action" value="generer_horaires">
                <input type="hidden" name="tournoi_id" value="<?php echo esc_attr($tournoi_id); ?>">
                <button type="submit" class="button button-primary">Générer les horaires</button>
            </form>
            <?php
        }
    ?>

    <!-- Modale pour modifier l'heure de fin du tournoi -->
    <div id="edit-end-time-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="close-end-time-modal" class="close">&times;</span>
            <h3>Définir l'heure du dernier match</h3>
            <form method="POST">
                <input type="hidden" name="tournoiId" id="edit-tournoi-end-id" value="">
                <input type="time" name="heureFin" id="edit-end-time" required />
                <input type="hidden" name="action" value="update_end_time">
                <button type="submit" name="modifierHeureFin" class="button button-primary">Enregistrer</button>
            </form>
        </div>
    </div>
   
    <!-- Modale pour modifier l'heure de depart du tournoi -->
    <div id="edit-phase-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="close-modal" class="close">&times;</span>
            <h3>Définir l'heure de début du tournoi</h3>
            <form method="POST">
                <input type="hidden" name="tournoiId" id="edit-tournoi-id" value="">
                <input type="datetime-local" name="heureDebut" id="edit-start-time" value="<?= esc_attr($dateDebut); ?>" required />
                <input type="hidden" name="action" value="update_start_time">
                <button type="submit" name="modifierHeureDebut" class="button button-primary">Enregistrer</button>
            </form>

        </div>
    </div>


    <div id="cont-etapes">
        <p>
            <span class="dashicons dashicons-info-outline"></span>
            Reliez les terrains aux matchs existants.
        </p>

        <!-- Liste des matchs -->
        <h2>Liste des matchs</h2>
        <?php if (!empty($matchsToDisplay)) : ?>
            <table class="wp-list-table widefat striped" id="matches-table">
                <thead>
                    <tr>
                        <th>Équipe 1</th>
                        <th>Équipe 2</th>
                        <th>Durée</th>
                        <th>Poule</th>
                        <th>Horaire de départ</th>
                        <th>Terrain associé</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matchsToDisplay as $match) :?>
                     
                        <tr class="match-item">
                            <td><?= esc_html($match->equipe1 ?? $match->poule_origin_nom1 .' ' . $match->classement_origin1 . ($match->classement_origin1 == 1 ? 'er' : 'eme')) ?></td>
                            <td><?= esc_html($match->equipe2 ?? $match->poule_origin_nom2 .' ' . $match->classement_origin2 . ($match->classement_origin2 == 1 ? 'er' : 'eme')) ?></td>
                            <td><?= esc_html($match->duree . " minutes" ?? 'N/A') ?></td>
                            <td><?= esc_html($match->poule ?? 'N/A') ?></td>
                            <td>
                                <form method="POST" action="<?= esc_url(add_query_arg('match_id', $match->id, $_SERVER['REQUEST_URI'])) ?>">
                                    <input type="datetime-local" name="horaire_depart" value="<?= esc_attr($match->horaire_depart) ?>" required>
                                    <input type="hidden" name="match_id" value="<?= esc_html($match->id ?? '') ?>">
                                    <input type="hidden" name="tournoi_id" value="<?= esc_html($match->Tournoi_id ?? '') ?>">
                                    <input type="hidden" name="page" id="page-number" value="<?= isset($_GET['page']) ? esc_attr($_GET['page']) : 1 ?>">
                                    <button type="submit" name="modifier_horaire" class="button">Modifier l'horaire</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <select name="terrain_id" onchange="this.form.submit()">
                                        <option value="" disabled selected>-- Sélectionner un terrain --</option>
                                        <?php foreach ($terrains as $terrain) : ?>
                                            <option value="<?= esc_html($terrain->id) ?>" <?= (isset($match->terrainId) && $match->terrainId == $terrain->id) ? 'selected' : '' ?>>
                                                <?= esc_html($terrain->nom) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="match_id" value="<?= esc_html($match->id ?? '') ?>">
                                    <input type="hidden" name="tournoi_id" value="<?= esc_html($match->Tournoi_id ?? '') ?>">
                                </form>
                            </td>
                            <td>
                                <form method="post" action="" style="display: inline;">
                                    <input type="hidden" name="match_id" value="<?= esc_html($match->id ?? '') ?>">
                                    <input type="hidden" name="tournoi_id" value="<?= esc_html($match->Tournoi_id ?? '') ?>">
                                    <input type="hidden" name="remove_terrain" value="1">
                                    <button type="submit" class="button">Désassocier</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Aucun match disponible.</p>
        <?php endif; ?>
    </div>
</div>

 
</div>

<!-- Pagination -->
<div class="pagination" id="pagination-container"></div>

<!-- Étape suivante -->
<div class="lien-etape-next-container">
    <div class="lien-etape lien-etape-next">
        <a href="<?= esc_url(admin_url($this->settings_root . '&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=4')) ?>">
            Étape suivante <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

<script>
 document.addEventListener('DOMContentLoaded', function () {
    const tabLinks = document.querySelectorAll('.tab-links li');
    const tabContents = document.querySelectorAll('.tab-content .tab');

    // Retrieve the active tab from localStorage
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

            // Remove active class from all tabs and links
            tabLinks.forEach(link => link.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to the clicked tab and corresponding content
            this.classList.add('active');
            const target = this.querySelector('a').getAttribute('href').substring(1);
            document.getElementById(target).classList.add('active');

            // Save the active tab to localStorage
            localStorage.setItem('activeTab', target);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
        const editStartBtn = document.querySelector('.edit-tournoi-name-button');
    const editStartModal = document.getElementById('edit-phase-modal');
    const closeStartModal = document.getElementById('close-modal');

    editStartBtn.addEventListener('click', function () {
        editStartModal.style.display = 'flex';
    });
    closeStartModal.addEventListener('click', function () {
        editStartModal.style.display = 'none';
    });
    
    const editEndBtn = document.querySelector('.edit-tournoi-end-time-button');
    const editEndModal = document.getElementById('edit-end-time-modal');
    const closeEndModal = document.getElementById('close-end-time-modal');

    editEndBtn.addEventListener('click', function () {
        const tournoiId = this.getAttribute('data-tournoi-id');
        document.getElementById('edit-tournoi-end-id').value = tournoiId;
        editEndModal.style.display = 'flex';
    });
    closeEndModal.addEventListener('click', function () {
        editEndModal.style.display = 'none';
    });

    window.addEventListener('click', function (e) {
        if (e.target === editStartModal) editStartModal.style.display = 'none';
        if (e.target === editEndModal) editEndModal.style.display = 'none';
    });

});

document.addEventListener('DOMContentLoaded', function () {
    const matchesPerPage = 10;
    const matches = document.querySelectorAll('.match-item');
    const totalMatches = matches.length;
    const totalPages = Math.ceil(totalMatches / matchesPerPage);
    const maxVisibleButtons = 3;
    const paginationContainer = document.getElementById('pagination-container');
    const storedPage = localStorage.getItem('currentPage');
    let currentPage = storedPage ? parseInt(storedPage) : 1;

    function showPage(pageNumber) {
        currentPage = pageNumber;
        localStorage.setItem('currentPage', currentPage);
        const start = (pageNumber - 1) * matchesPerPage;
        const end = start + matchesPerPage;

        matches.forEach((match, index) => {
            match.style.display = index >= start && index < end ? 'table-row' : 'none';
        });

        updatePagination(pageNumber);
    }

    function updatePagination(pageNumber) {
        paginationContainer.innerHTML = '';

        const prevArrow = document.createElement('button');
        prevArrow.textContent = '«';
        prevArrow.disabled = pageNumber === 1;
        prevArrow.onclick = () => showPage(pageNumber - 1);
        paginationContainer.appendChild(prevArrow);

        const startPage = Math.max(1, pageNumber - Math.floor(maxVisibleButtons / 2));
        const endPage = Math.min(totalPages, startPage + maxVisibleButtons - 1);

        for (let i = startPage; i <= endPage; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.classList.add('pagination-button');
            if (i === pageNumber) button.classList.add('current');
            button.onclick = () => showPage(i);
            paginationContainer.appendChild(button);
        }

        const nextArrow = document.createElement('button');
        nextArrow.textContent = '»';
        nextArrow.disabled = pageNumber === totalPages;
        nextArrow.onclick = () => showPage(pageNumber + 1);
        paginationContainer.appendChild(nextArrow);
    }

    showPage(currentPage);

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            localStorage.setItem('currentPage', currentPage);
        });
    });
});

</script>

<style>
.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination-button {
    padding: 5px 10px;
    margin: 0 5px;
    border: 1px solid #ccc;
    cursor: pointer;
}

.pagination-button.current {
    background-color: #0073aa;
    color: white;
    border-color: #0073aa;
}

.pagination-button:hover {
    background-color: #ccc;
}

button[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
