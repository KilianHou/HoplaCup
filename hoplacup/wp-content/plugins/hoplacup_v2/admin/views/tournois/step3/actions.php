<?php

// Chargez WordPress Core
require_once('../../../../../../../wp-load.php');

$nonce_name = 'tournoi_step_4_hoplacup-v2-settings';

/**
 * Vérifications d'accès
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'acces refusé';
    wp_die();
}

if (!isset($_POST['_wpnonce'])) {
    echo 'acces refusé';
    wp_die();
}

if (!wp_verify_nonce($_POST['_wpnonce'], $nonce_name)) {
    echo 'nonce invalide';
    wp_die();
}

if (!isset($_POST['action'])) {
    echo 'action manquante';
    wp_die();
}

global $wpdb;

/**
 * Si l'action est "save_matchs"
 */
if (($_POST['action']) === 'save_matchs') {

    $table_name = "{$wpdb->prefix}matchs";
    $tournoi_id = (int) $_POST['tournoi_id'];

    foreach ($_POST['matchs'] as $match_id => $match_data) {
        $terrain_id = (int) $match_data['terrain'];
        $horaire = sanitize_text_field($match_data['horaire']);

        // Validation simple des données
        if ($terrain_id && $horaire) {
            $data = array(
                'Terrains_ID' => $terrain_id,
                'Temps' => $horaire,
            );
            $where = array(
                'ID' => $match_id,
                'Tournoi_id' => $tournoi_id
            );

            $wpdb->update($table_name, $data, $where);
        }
    }

    // Message de succès
    $message = "Configuration des matchs sauvegardée";
    set_transient('step4_success_message', $message, 2);
    wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&configstep=4&id=' . $tournoi_id));
    exit;
}
?>
