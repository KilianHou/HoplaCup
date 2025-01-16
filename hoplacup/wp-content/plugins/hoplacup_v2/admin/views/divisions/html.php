<!-- Cruds sur les divisions -->
<h1>Gestion des divisions</h1>

<div class="wrap">
    <table class="wp-list-table widefat striped">
        <thead>
        <tr>
            <th width="20%">ID</th>
            <th width="20%">Nom</th>
            <th width="20%">Actions</th>
        </tr>
        </thead>
        <tbody>
        <form action="" method="post">
            <tr>
                <td><input type="text" value="AUTO_GENERATED" disabled></td>
                <td><input type="text" id="nomDivision" name="nomDivision"></td>
                <td><button id="submitDivision" name="submitDivision" type="submit" class="action-btn" title="Créer une division" aria-label="Créer une division"><i class="fa-regular fa-square-plus"></i></button></td>
            </tr>
        </form>
        <?php foreach ($divisionResults as $divisionResult) : ?>
        <form method="post" action="admin.php?page=hoplacup-v2-settings&view=divisions&update">
            <tr>
                <td width='20%'><input type="text" name="id" value="<?= $divisionResult->ID ?>" readonly></td>
                <td width='20%'><input type="text" name="nomDivision" value="<?= $divisionResult->Division ?>"></td>
                <td width='20%'>
                    <button type="submit" name="update" class="action-btn" title="Mettre à jour" aria-label="Mettre à jour"><i class="fa-solid fa-pen-to-square"></i></button>
                    <a href='admin.php?page=hoplacup-v2-settings&view=divisions&del=<?= $divisionResult->ID ?>' class="delete-division" data-id="<?= $divisionResult->ID ?>">
                        <button type="button" class="action-btn delete" title="Supprimer la division" aria-label="Supprimer la division"><i class="fa-solid fa-trash"></i></button>
                    </a>
                </td>
            </tr>
        </form>
        <?php endforeach; ?>
        <script>
            jQuery(document).ready(function($) {
                $('.delete-division').on('click', function(e) {
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
        </tbody>
    </table>
</div>
