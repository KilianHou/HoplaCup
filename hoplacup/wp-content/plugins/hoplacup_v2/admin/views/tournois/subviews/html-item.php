<div class="wrap">

    <h1 class="wp-heading-inline">Éditer le tournoi <strong><?= $tournoi_name ?></strong></h1>
    <button class="edit-btn edit-tournoi-name-button" data-tournoi-id="<?= $tournoi_id ?>" title="Modifier" aria-label="Modifier">
        <i class="fa-solid fa-pen-to-square"></i>
    </button>


    <!-- Modale pour modifier le nom du tournoi -->
    <div id="edit-phase-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="close-modal" class="close">&times;</span>
            <h3>Modifier le nom du tournoi</h3>
            <form method="POST">
                <input type="hidden" name="tournoiId" id="edit-tournoi-id" value="">
                <input type="text" name="nomTournoi" id="edit-tournoi-name" value="<?= esc_attr($tournoi_name); ?>" placeholder="Nom du tournoi" required />
                <input type="hidden" name="action" value="update_tournoi_name">
                <button type="submit" name="modifierTournoi" class="button button-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <span id="infos-etapes"></span>
    <br>
    <a class="lien-retour" href="<?= esc_url(admin_url($this->settings_root . '&view=tournois')) ?>">Retour à la liste des tournois</a>
    <hr class="wp-header-end">

    <div id="cont-etapes">

        <?php

        // Initialisation des variables d'étapes si elles ne sont pas déjà définies
        $step1 = $step1 ?? 0;
        $step2 = $step2 ?? 0;
        $step3 = $step3 ?? 0;
        $step4 = $step4 ?? 0;
        $step5 = $step5 ?? 0;


        $infos_etape = array();

        $infos_etape[0]['todo'] = 'Création de la phase de poule initiale';
        $infos_etape[0]['done'] = "Les phases sont créées";
        $infos_etape[0]['url']  = $this->settings_root . "&view=tournois&subview=item&id=$tournoi_id&configstep=1&club_id=0&division_id=0&pays_nom=0&ville_nom=0&pages=1";

        $infos_etape[1]['todo'] = 'Phases suivantes et gestion des transferts des équipes';
        $infos_etape[1]['done'] = 'Modifier les associations d\'équipes/poules de phase 1';
        $infos_etape[1]['url']  = $this->settings_root . "&view=tournois&subview=item&id=$tournoi_id&configstep=2&phaseId=$phase_init_ID";

        $infos_etape[2]['todo'] = 'Gestion des matches crée lors des étapes précédentes';
        $infos_etape[2]['done'] = 'Modifier les transfert d\'équipes à travers les phases';
        $infos_etape[2]['url']  = $this->settings_root . "&view=tournois&subview=item&id=$tournoi_id&configstep=3";

        $infos_etape[3]['todo'] = 'Gestion du tournoi';
        $infos_etape[3]['done'] = 'Entrer les résultats des matches et gérer les phases';
        $infos_etape[3]['url']  = $this->settings_root . "&view=tournois&subview=item&id=$tournoi_id&configstep=4";

        $infos_etape[4]['todo'] = 'Visualisation du tournoi';
        $infos_etape[4]['done'] = 'Affichage du classement de poules et general';
        $infos_etape[4]['url']  = $this->settings_root . "&view=tournois&subview=item&id=$tournoi_id&configstep=5";

        ?>

<?php for ($i = 0; $i < count($infos_etape); $i++) : ?>
    <?php
    $status = ${"step" . ($i + 1)} == 0 ? 'uneditable' : (${"step" . ($i + 1)} == 2 ? 'done' : 'todo');

    if ($status == 'todo') {
        $step_url = $infos_etape[$i]['url'];
    } else {
        $step_url = null;
    }
    ?>

    <div class="cont-etape <?= $status ?>" onclick="window.location.href='<?= $step_url ?>'">
        <?php if ($status == 'done') : ?>
            <div class="icon-action done"></div>
        <?php else: ?>
            <div class="icon-action config"></div>
        <?php endif; ?>
        <h3 style="align-content: center; top: 1rem">Étape <?= $i + 1 ?>/<?= count($infos_etape) ?> :</h3> <!-- Changement de format du titre -->
        <div class="separateur-horizontal"></div>
        <?php if ($status == 'done') : ?>
            <p><?= $infos_etape[$i]['done'] ?></p>
        <?php else: ?>
            <p><?= $infos_etape[$i]['todo'] ?></p>
        <?php endif; ?>
    </div>

    <?php endfor; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('edit-phase-modal');
    const closeButton = document.getElementById('close-modal');
    const editButtons = document.querySelectorAll('.edit-tournoi-name-button');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const tournoiId = button.getAttribute('data-tournoi-id');
            document.getElementById('edit-tournoi-id').value = tournoiId;
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
</script>