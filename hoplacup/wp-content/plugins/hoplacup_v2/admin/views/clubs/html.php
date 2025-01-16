<div class="head">
    <h1>Gestion des clubs</h1>
    <div>
        <button id="openCreateClubModal" type="submit" class="modal-button" title="Créer un club"><i class="fa-regular fa-square-plus"></i> Créer un club</button>
        <button id="openCreateTeamModal" class="modal-button"><i class="fa-regular fa-square-plus"></i> Ajouter une équipe</button>
    </div>
</div>

<div class="club-filter">
    <form id="filter-form" method="GET" action="">
        <input type="hidden" name="page" value="hoplacup-v2-settings">
        <input type="hidden" name="view" value="clubs">

        <div class="filter-item">
            <label for="filter-name">Nom du club :</label>
            <div class="filter-field">
                <input 
                    type="text" 
                    name="filter-name" 
                    id="filter-name" 
                    value="<?= isset($_GET['filter-name']) ? esc_attr($_GET['filter-name']) : '' ?>" 
                    onkeyup="handleKeyPress(event)" 
                >
                <button type="button" class="reset-button" onclick="resetField('filter-name')"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <div class="filter-item">
            <label for="filter-city">Ville :</label>
            <div class="filter-field">
                <input 
                    type="text" 
                    name="filter-city" 
                    id="filter-city" 
                    value="<?= isset($_GET['filter-city']) ? esc_attr($_GET['filter-city']) : '' ?>" 
                    onkeyup="handleKeyPress(event)" 
                >
                <button type="button" class="reset-button" onclick="resetField('filter-city')"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <div class="filter-item">
            <label for="filter-country">Pays :</label>
            <div class="filter-field">
                <input 
                    type="text" 
                    name="filter-country" 
                    id="filter-country" 
                    value="<?= isset($_GET['filter-country']) ? esc_attr($_GET['filter-country']) : '' ?>" 
                    onkeyup="handleKeyPress(event)" 
                >
                <button type="button" class="reset-button" onclick="resetField('filter-country')"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
    </form>
</div>



<div class="wrap">
    <div class="clubs-list">
        <?php foreach ($clubResults as $clubResult) : ?>
            <a href="?page=hoplacup-v2-settings&view=clubs&clubId=<?= esc_attr($clubResult->ID); ?>" class="club-link">
                <div class="club-item">
                    <div class="club-info">
                        <div class="club-name"><?= esc_html($clubResult->Nom); ?></div>
                        <div class="club-logo">
                            <img 
                                src="<?= esc_url($clubResult->Logo) ?: 'https://pbs.twimg.com/media/EXfWi8ZXQAAdHIa.jpg'; ?>"
                                alt="Image du club"
                                class="club-image"
                            />
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal de création d'un club -->
<div id="createClubModal" class="modal">
    <div class="modal-content">
        <span id="closeClubModal" class="close">&times;</span>
        <h2>Créer un nouveau club</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="nomClub">Nom du club *</label>
            <input type="text" id="nomClub" name="nomClub" maxlength="30" required>

            <label for="villeClub">Ville</label>
            <input type="text" id="villeClub" maxlength="30" name="villeClub">

            <label for="paysClub">Pays :</label>
            <select id="paysClub" name="paysClub">
                <option value="">Chargement...</option>
            </select>

            <label for="contactClub">Contacts</label>
            <textarea id="contactClub" name="contactClub" rows="5" maxlength="500" placeholder="Entrez plusieurs contacts ici, un par ligne..." style="width: 100%;"></textarea>

            <label for="logoClub">Logo du club</label>
            <div class="file-input-container">
                <label for="logoClub" class="file-input-label">Sélectionner un logo</label>
                <input type="file" id="logoClub" name="logoClub" accept="image/*" class="file-input">
            </div>
            <small class="file-input-help">Formats acceptés : .jpg, .jpeg, .png, .gif. Taille maximale : 2 Mo.</small>

            <div id="previewContainer" class="preview-container">
                <img id="imagePreview" src="" alt="Aperçu du logo" class="image-preview" style="display: none;">
                <small id="previewText" class="preview-text">Aucun fichier sélectionné</small>
            </div>

            <button id="submitClub" name="submitClub" type="submit">Créer</button>
        </form>
    </div>
</div>

<!-- Modal de création d'une équipe -->
<div id="createTeamModal" class="modal">
    <div class="modal-content">
        <span id="closeTeamModal" class="close">&times;</span>
        <h2>Créer une nouvelle équipe</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="nomEquipe">Nom de l'équipe *</label>
            <input type="text" id="nomEquipe" name="nomEquipe" maxlength="30" required>

            <label for="nomClub">Club de l'équipe *</label>
            <select id="nomClub" name="nomClub">
                <?php foreach ($clubResults as $club) : ?>
                    <option value="<?= $club->ID ?>"><?= $club->Nom ?></option>
                <?php endforeach; ?>
            </select>

            <label for="nomDivision">Division de l'équipe *</label>
            <select id="nomDivision" name="nomDivision">
		            <?php foreach ($divisionsSelect as $divisionSelect) : ?>
                        <option value="<?= $divisionSelect->id ?>"><?= $divisionSelect->Division ?></option>
		            <?php endforeach; ?>
                </select>
            <button id="submitEquipe" name="submitEquipe" type="submit">Créer</button>
        </form>
    </div>
</div>

<script>
    // club
    const clubModal = document.getElementById("createClubModal"),
        openCreateClubModalBtn = document.getElementById("openCreateClubModal"),
        closeClubModalBtn = document.getElementById("closeClubModal");
    // team
    const teamModal = document.getElementById("createTeamModal"),
        openCreateTeamModalBtn = document.getElementById("openCreateTeamModal"),
        closeTeamModalBtn = document.getElementById("closeTeamModal");

    // Ouvrir la modal
    openCreateClubModalBtn.addEventListener("click", () => {
        clubModal.style.display = "block";
    });
    openCreateTeamModalBtn.addEventListener("click", () => {
        teamModal.style.display = "block";
    });

    // Fermer la modal
    closeClubModalBtn.addEventListener("click", () => {
        clubModal.style.display = "none";
    });
    closeTeamModalBtn.addEventListener("click", () => {
        teamModal.style.display = "none";
    });

    // Fermer la modal en cliquant à l'extérieur
    window.addEventListener("click", (event) => {
        switch(event.target){
            case clubModal :
                clubModal.style.display = "none";
            break
            case teamModal :
                teamModal.style.display = "none";
            break
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('paysClub');

        // Charger les pays
        fetchCountries(selectElement);
    });

    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            // Soumet le formulaire si la touche "Entrée" est pressée
            event.preventDefault();
            document.getElementById('filter-form').submit();
        } else {
        }
    }

    function resetField(fieldId) {
        const field = document.getElementById(fieldId);
        field.value = '';
    }

    // LOGO
    const logoInput = document.getElementById('logoClub');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const previewText = document.getElementById('previewText');

    logoInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.addEventListener('load', function () {
                imagePreview.setAttribute('src', this.result);
                imagePreview.style.display = 'block';
                previewText.style.display = 'none';
            });

            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
            previewText.style.display = 'block';
            previewText.textContent = 'Aucun fichier sélectionné';
        }
    });
</script>