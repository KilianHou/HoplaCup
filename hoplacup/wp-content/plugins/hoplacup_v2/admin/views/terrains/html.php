<!-- CRUD sur les terrains -->
<h1>Gestion des terrains</h1>

<div class="wrap">
    
    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (isset($message)) : ?>
        <div class="notice <?= $message_type ?>"><p><?= $message ?></p></div>
    <?php endif; ?>
    
    <table class="wp-list-table widefat striped">
        <thead>
        <tr>
            <th width="20%">ID</th>
            <th width="40%">Nom</th>
            <th width="20%">Actions</th>
        </tr>
        </thead>
        <tbody>
        
        <!-- Formulaire pour insérer un nouveau terrain -->
        <form action="" method="post" onsubmit="return validateForm(this);">
            <tr>
                <td><input type="text" value="AUTO_GENERATED" disabled></td>
                <td><input type="text" id="nomTerrain" name="nomTerrain" required></td>
                <td><button id="submitTerrain" name="submitTerrain" type="submit" class="action-btn" title="Créer terrain" aria-label="Créer terrain"><i class="fa-regular fa-square-plus"></i></button></td>
            </tr>
        </form>
        
        <!-- Liste des terrains existants et options de mise à jour/suppression -->
        <?php foreach ($terrainResults as $terrainResult) : ?>
            <form method="post" action="" onsubmit="return validateForm(this);">
                <tr>
                    <td width="20%"><input type="text" name="id" value="<?= $terrainResult->ID ?>" readonly></td>
                    <td width="40%"><input type="text" name="nomTerrain" value="<?= $terrainResult->Nom ?>"></td>
                    <td width="20%">
                        <button type="submit" name="update" class="action-btn" title="Mettre à jour" aria-label="Mettre à jour"><i class="fa-solid fa-pen-to-square"></i></button>
                        <a href='admin.php?page=hoplacup-v2-settings&view=terrains&del=<?= $terrainResult->ID ?>' class="delete-terrain" data-id="<?= $terrainResult->ID ?>">
                            <button type="button" class="action-btn delete" title="Supprimer le terrain" aria-label="Supprimer le terrain"><i class="fa-solid fa-trash"></i></button>
                        </a>

                    </td>
                </tr>
            </form>
        <?php endforeach; ?>
        
        </tbody>
    </table>
</div>

<script>
// Fonction de validation pour vérifier que le champ Nom n'est pas vide
function validateForm(form) {
    const nomTerrain = form.querySelector("[name='nomTerrain']");
    if (!nomTerrain.value.trim()) {
        alert("Le champ Nom est requis.");
        return false;
    }
    return true;
}
            jQuery(document).ready(function($) {
                $('.delete-terrain').on('click', function(e) {
                    e.preventDefault();

                    var deleteUrl = $(this).attr('href');

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
            });
</script>
