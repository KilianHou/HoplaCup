<?php
// Récupérer l'ID de la division depuis l'URL
$division_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les détails de la division
$division = get_division($division_id);

// Vérifier si la division existe
if ($division) {
    // Récupérer le nom de la division
    $division_name = isset($division->Division) ? $division->Division : '';

    // Créer le formulaire
?>
    <div class="wrap">

        <h1 class="wp-heading-inline">Éditer la division "<?= $division_name ?>"</h1>
        <a href="admin.php?page=hoplacup-v2-settings&view=divisions">Retour à la liste</a>
        <hr class="wp-header-end">

        <form id="form-division" class="form-hoplacup" method="post" action="<?= plugin_dir_url(__FILE__) . 'actions.php' ?>">
            <?php wp_nonce_field('edit-division_' . $division_id); ?>
            <input type="hidden" name="action" value="update">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="division_name">Nom de la division</label></th>
                        <td><input type="text" id="division_name" name="division_name" value="<?php echo esc_attr($division_name); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="division_image">Image de la division</label></th>
                        <td>
                            <?php
                            $image_url = ''; // URL de l'image par défaut
                            if (isset($division->image_id)) {
                                $image_url = wp_get_attachment_image_url($division->image_id, 'thumbnail');
                            }
                            ?>
                            <input type="hidden" name="division_image_id" id="division_image_id" value="<?php echo esc_attr($division->image_id); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" id="division_image_preview" style="max-width: 100px;">
                            <button type="button" class="button" id="upload_image_button">Sélectionner une image</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="division_id" value="<?php echo esc_attr($division_id); ?>">
            <input type="hidden" name="save_redirect" id="save_redirect" value="">

            <div class="buttons-container">
                <?php submit_button('Enregistrer', 'primary', 'submit-division'); ?>
                <p class="submit"><button type=" button-primary" class="button button-primary" id="save_and_quit">Enregistrer et Quitter</button></p>
            </div>

        </form>

        <a href="admin.php?page=hoplacup-v2-settings&view=divisions">Retour à la liste</a>

        <script>
            jQuery(document).ready(function($) {

                // Lorsque le formulaire est soumis
                $('.form-hoplacup').submit(function(event) {
                    var divisionName = $('#division_name').val();
                    if (divisionName === '') {
                        event.preventDefault();
                        alert('Veuillez entrer un nom pour la division.');
                        $('#division_name').focus();
                        return false; // Empêcher la soumission du formulaire
                    }
                });

                // Save and quit
                $('#save_and_quit').click(() => {
                    $('#save_redirect').val('save_and_quit');
                    $('#form-division').submit();
                });

                // preselect champ nme
                if ($('#division_name').val() == '') {
                    $('#division_name').select()
                }


                // interface champ image
                $('#upload_image_button').click(function() {
                    var custom_uploader = wp.media({
                        title: 'Choisir une image',
                        button: {
                            text: 'Choisir'
                        },
                        multiple: false
                    });

                    custom_uploader.on('select', function() {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('#division_image_id').val(attachment.id);
                        $('#division_image_preview').attr('src', attachment.url);
                    });

                    custom_uploader.open();
                });
            });
        </script>
    </div>
<?php
} else {
    echo 'Division non trouvée.';
}
?>