<?php
    if (!empty($clubInfos)) {
            ?>
            <a href="?page=hoplacup-v2-settings&view=clubs" class="back-button">Retour</a>
            <div id="club-card" class="club-card">
                <h1><?= esc_html($clubInfos->Nom); ?></h1>
                <div class="club-details">
                    <div class="club-logo">
                        <img src="<?= esc_url($clubInfos->Logo) ?: 'https://pbs.twimg.com/media/EXfWi8ZXQAAdHIa.jpg'; ?>" alt="Logo de <?= esc_html($clubInfos->Nom); ?>">
                    </div>
                    <ul>
                        <li><strong>Ville :</strong> <?= esc_html($clubInfos->Ville); ?></li>
                        <li><strong>Pays :</strong> <?= esc_html($clubInfos->Pays); ?></li>
                        <li>
                            <strong>Contacts :</strong><br>
                            <span class="contact-details"><?= nl2br(esc_html($clubInfos->Contact)); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Section des équipes -->
            <div class="team-list">
                <div class="team-list-header">
                    <h2>Équipes du club</h2>
                    <button id="openCreateTeamModal" class="addTeamButton"><i class="fa-regular fa-square-plus"></i> Ajouter une équipe</button>
                </div>
                <div class="team-grid">
                    <?php
                    if (!empty($teamsInfos)) {
                        foreach ($teamsInfos as $team) {
                            ?>
                            <div class="team-card" data-teamId="<?= $team->id ?>" data-nom="<?= esc_attr($team->Nom) ?>" data-divisionId="<?= esc_attr($team->Divisions_id) ?>">
                                <h3><?= esc_html($team->Nom); ?></h3>
                                <p><strong>Division :</strong> <?= esc_html($team->Division); ?></p>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>Aucune équipe disponible pour ce club.</p>";
                    }
                    ?>
                </div>
            </div>
            <?php
    } else {
        echo "<p>Aucune information disponible pour ce club.</p>";
    }
?>

<!-- Modal de modification/suppression du club -->
<div id="editClubModal" class="modal">
    <div class="modal-content">
        <span id="closeClubModal" class="close">&times;</span>
        <h2>Modifier un club</h2>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="nomClub">Nom du club *</label>

            <input value="<?= esc_attr($clubInfos->Nom) ?>" type="text" id="nomClub" name="nomClub" maxlength="30" required>

            <label for="villeClub">Ville</label>
            <input value="<?= esc_attr($clubInfos->Ville) ?>" type="text" id="villeClub" maxlength="30" name="villeClub">

            <label for="paysClub">Pays</label>

            <select id="paysClub" name="paysClub" data-selected-country="<?= esc_attr($clubInfos->Pays); ?>" required>
                <option value="">Chargement...</option>
            </select>

            <label for="contactClub">Contacts</label>
            <textarea id="contactClub" name="contactClub" rows="5" maxlength="500" placeholder="Entrez plusieurs contacts ici, un par ligne..." style="width: 100%;"><?= esc_textarea($clubInfos->Contact) ?></textarea>

            <label for="logoClub">Logo du club</label>
            <div class="file-input-container">
                <label for="logoClub" class="file-input-label">Sélectionner un logo</label>
                <input type="file" id="logoClub" name="logoClub" accept="image/*" class="file-input">
            </div>
            <small class="file-input-help">Formats acceptés : .jpg, .jpeg, .png, .gif. Taille maximale : 2 Mo.</small>

            <div id="previewContainer" class="preview-container">
                <img
                    data-default-logo="<?= esc_url($clubInfos->Logo ?: 'https://pbs.twimg.com/media/EXfWi8ZXQAAdHIa.jpg') ?>"
                    id="imagePreview"
                    src=""
                    alt="Aperçu du logo"
                    class="image-preview"
                    style="display: none;"
                >
            </div>

            <button class="update" name="updateClub" type="submit">Enregistrer</button>
            <button id="deleteClub" class="delete" name="deleteClub" type="submit">Supprimer</button>
        </form>
    </div>
</div>-

<!-- Modal de modification/suppression d'une équipe -->
<div id="editTeamModal" class="modal">
    <div class="modal-content">
        <span id="closeTeamModal" class="close">&times;</span>
        <h2>Modifier une équipe</h2>
        <form id="teamEditForm" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" id="teamId" name="id">

            <label for="nomEquipe">Nom de l'équipe *</label>
            <input type="text" id="nomEquipe" name="nomEquipe" maxlength="30" required>

            <label for="nomClub">Club de l'équipe *</label>
            <select id="nomClub" name="nomClub" required>
                <?php foreach ($clubsSelect as $clubSelect) : ?>
                    <option value="<?= $clubSelect->id ?>"
                        <?= ($clubSelect->id == $clubId) ? 'selected' : '' ?>>
                        <?= $clubSelect->Nom ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nomDivision">Division de l'équipe *</label>
            <select id="nomDivision" name="nomDivision" required>
                <?php foreach ($divisionsSelect as $divisionSelect) : ?>
                    <option value="<?= $divisionSelect->id ?>">
                        <?= $divisionSelect->Division ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="update" name="updateTeam" type="submit">Enregistrer</button>
            <button id="deleteTeam" class="delete" name="deleteTeam" type="submit">Supprimer</button>
        </form>
    </div>
</div>

<!-- Modal de création d'une équipe -->
<div id="createTeamModal" class="modal">
    <div class="modal-content">
        <span id="closeCreateTeamModal" class="close">&times;</span>
        <h2>Créer une nouvelle équipe</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="nomEquipe">Nom de l'équipe *</label>
            <input type="text" id="nomEquipe" name="nomEquipe" maxlength="30" required>

            <label for="nomDivision">Division de l'équipe *</label>
            <select id="nomDivision" name="nomDivision">
		            <?php foreach ($divisionsSelect as $divisionSelect) : ?>
                        <option value="<?= $divisionSelect->id ?>"><?= $divisionSelect->Division ?></option>
		            <?php endforeach; ?>
                </select>
            <button id="submitEquipe" class="update" name="submitEquipe" type="submit">Créer</button>
        </form>
    </div>
</div>



<script>
    // Club
    const clubModal = document.getElementById("editClubModal"),
        closeClubModal = document.getElementById("closeClubModal"),
        clubCard = document.getElementById("club-card")

    // Équipe
    const teamModal = document.getElementById("editTeamModal"),
        closeTeamModal = document.getElementById("closeTeamModal"),
        teamCards = document.querySelectorAll(".team-card"),
        teamIdInput = document.getElementById("teamId"),
        nomTeamInput = document.getElementById("nomEquipe"),
        divisionTeamInput = document.getElementById("nomDivision");

    const createTeamModal = document.getElementById("createTeamModal"),
        openCreateTeamModalBtn = document.getElementById("openCreateTeamModal"),
        closeCreateTeamModal = document.getElementById("closeCreateTeamModal");


    clubCard.addEventListener("click", function () {
        clubModal.style.display = "block";
    });
    openCreateTeamModalBtn.addEventListener("click", () => {
        createTeamModal.style.display = "block";
    });

    // Fonction pour ouvrir la modal et pré-remplir les données de l'équipe
    teamCards.forEach(card => {
        card.addEventListener("click", function () {
            const teamId = this.getAttribute("data-teamId"),
                nom = this.getAttribute("data-nom"),
                division = this.getAttribute("data-divisionId");
                
            teamIdInput.value = teamId;
            nomTeamInput.value = nom;
            divisionTeamInput.value = division;

            teamModal.style.display = "block";
        });
    });

    // Fermer la modal
    closeClubModal.addEventListener("click", function () {
        clubModal.style.display = "none";
    });
    closeTeamModal.addEventListener("click", function () {
        teamModal.style.display = "none";
    });
    closeCreateTeamModal.addEventListener("click", () => {
        createTeamModal.style.display = "none";
    });

    // Fermer la modal si on clique à l'extérieur
    window.addEventListener("click", function (event) {
        switch(event.target){
            case clubModal :
                clubModal.style.display = "none";
            break
            case teamModal :
                teamModal.style.display = "none";
            break
            case createTeamModal :
                createTeamModal.style.display = "none";
            break
        }
    });

    // Modal de confirmation de suppression d'un club
    jQuery(document).ready(function ($) {
        $('#deleteClub').on('click', function (e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Toutes les équipes liées à ce club seront également supprimées. Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.attr('action', '?page=hoplacup-v2-settings&view=clubs&clubId=<?= esc_attr($clubId) ?>&delete=club');
                    form.submit()
                }
            });
        })

        $('#deleteTeam').on('click', function (e) {
            e.preventDefault();
            const form = $(this).closest('form');

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
                    form.attr('action', '?page=hoplacup-v2-settings&view=clubs&clubId=<?= esc_attr($clubId) ?>&delete=team');
                    form.submit()
                }
            });
        })
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('paysClub');

        // Charger les pays
        fetchCountries(selectElement);
    });

    // LOGO
    const logoInput = document.getElementById('logoClub');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const previewText = document.getElementById('previewText');
    const defaultLogo = imagePreview.dataset.defaultLogo;

    imagePreview.setAttribute('src', defaultLogo);

    logoInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.addEventListener('load', function () {
                imagePreview.setAttribute('src', this.result);
            });

            reader.readAsDataURL(file);
        } else {
            imagePreview.setAttribute('src', defaultLogo);
        }
    });
</script>