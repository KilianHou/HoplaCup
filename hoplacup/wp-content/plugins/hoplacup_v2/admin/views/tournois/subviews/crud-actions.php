<?php

// Chargez WordPress Core
require_once('../../../../../../../wp-load.php');

// Bulk nonce name (nom du token généré par la classe WP_List_Table pour les traitements en lots)
$nonce_name = 'bulk-settings_page_hoplacup-v2-settings';

// Nom par défaut pour un nouveau tournoi
$default_name = "Nouveau tournoi";


/**********************************************************
 * 
 * ADD a tournoi
 * Créer un nouveau Tournoi
 * 
 */
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    if (isset($_GET['_wpnonce'])) {
        $nonce = $_GET['_wpnonce'];
        if (wp_verify_nonce($nonce, 'add-tournoi')) {
            global $wpdb;
            $inserted = $wpdb->insert(
                $wpdb->prefix . 'tournois',
                array(
                    'Nom' => $default_name,
                )
            );
            if ($inserted !== false) {
                $new_tournoi_id = $wpdb->insert_id;
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=' . $new_tournoi_id));
                exit;
            } else {
                // erreur, affiche la liste
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois'));
                exit;
            }
        }
    }
}


/**********************************************************
 * 
 * UPDATE a tournoi
 * Sauvegarde le formulaire d'édition d'un tournoi
 * 
 */
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    // Vérifier la validité du nonce
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'edit-tournoi_' . $_POST['tournoi_id'])) {
        // Récupérer les données du formulaire et les nettoyer
        $tournoi_id = isset($_POST['tournoi_id']) ? intval($_POST['tournoi_id']) : 0;
        $tournoi_name = isset($_POST['tournoi_name']) ? sanitize_text_field($_POST['tournoi_name']) : '';
        $tournoi_image_id = isset($_POST['tournoi_image_id']) ? intval($_POST['tournoi_image_id']) : 0;

        global $wpdb;
        $updated = $wpdb->update(
            $wpdb->prefix . 'tournois',
            array(
                'Nom' => $tournoi_name,
                //'image_id' => $tournoi_image_id
            ),
            array('id' => $tournoi_id),
            array(
                '%s',
                //'%d'
            ),
            array('%d')
        );

        if ($updated !== false) {
            if (isset($_POST['save_redirect']) && $_POST['save_redirect'] == 'save_and_quit') {
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois'));
            } else {
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=' . $tournoi_id));
            }
            exit;
        } else {
            // Gérer l'erreur de mise à jour
            echo 'Erreur lors de la mise à jour de la tournoi.';
            die;
        }
    } else {
        // Nonce invalide
        echo 'Nonce invalide.';
        die;
    }
}


/**********************************************************
 * 
 * DELETE One Tournoi
 * Suppression d'un seul tournoi
 * 
 */
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (isset($_GET['id']) && isset($_GET['_wpnonce'])) {
        $tournoi_id = $_GET['id'];
        $nonce = $_GET['_wpnonce'];
        if (wp_verify_nonce($nonce, 'delete_tournoi_' . $tournoi_id)) {

            global $wpdb;
            // Récupérer les IDs de toutes les poules associées au tournoi
            $poule_ids = $wpdb->get_col($wpdb->prepare("
                SELECT ID FROM {$wpdb->prefix}poules
                WHERE Tournoi_id = %d
            ", $tournoi_id));
            // Supprimer les matchs
            foreach ($poule_ids as $poule_id) {
                $wpdb->delete($wpdb->prefix . 'matchs', array('Poules_ID' => $poule_id));
            }
            // Supprimer les associations equipes_poules
            foreach ($poule_ids as $poule_id) {
                $wpdb->delete($wpdb->prefix . 'poules_equipes', array('Poules_ID' => $poule_id));
            }
            // TODO : supprimer les associations terrains/matchs
            // ..
            // Enfin, supprimer le tournoi lui-même
            $wpdb->delete($wpdb->prefix . 'tournois', array('id' => $tournoi_id));
        }
    }
    // Rediriger après la suppression
    wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois'));
    exit;
}


/**********************************************************
 * 
 * Bulk DELETE
 * Suppression en lot
 * 
 */
if (isset($_POST['action']) && $_POST['action'] && isset($_POST['action2']) && $_POST['action2']) {

    // Vérifie le token (nonce)
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], $nonce_name)) {
        echo "Erreur token, nonce invalide";
        die;
    }

    $ids = isset($_POST['tournoi']) ? $_POST['tournoi'] : array();

    if (!empty($ids)) {
        global $wpdb;

        foreach ($ids as $tournoi_id) {
            // Récupérer les IDs de toutes les poules associées au tournoi
            $poule_ids = $wpdb->get_col($wpdb->prepare("
                SELECT ID FROM {$wpdb->prefix}poules
                WHERE Tournoi_id = %d
            ", $tournoi_id));

            // Supprimer les matchs
            foreach ($poule_ids as $poule_id) {
                $wpdb->delete($wpdb->prefix . 'matchs', array('Poules_ID' => $poule_id));
            }
            // Supprimer les associations equipes_poules
            foreach ($poule_ids as $poule_id) {
                $wpdb->delete($wpdb->prefix . 'poules_equipes', array('Poules_ID' => $poule_id));
            }

            // todo : supprimer les associations terrains/matchs
            // ..

            // Enfin, supprimer le tournoi lui-même
            $wpdb->delete($wpdb->prefix . 'tournois', array('id' => $tournoi_id));
        }
    }
}
if (isset($_GET['action']) && $_GET['action'] === 'archive') {
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'archive_tournoi_' . $_GET['id'])) {
        wp_die('Non autorisé.');
    }

    global $wpdb;
    $table_tournois = $wpdb->prefix . 'tournois';
    $tournoi_id = intval($_GET['id']);

    $wpdb->update(
        $table_tournois,
        array('archivé' => 1), // Mark as archived
        array('id' => $tournoi_id),
        array('%d'),
        array('%d')
    );

    wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois'));
    exit;
}


wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois'));
exit;
