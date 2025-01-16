<?php

// Chargez WordPress Core
require_once('../../../../../../wp-load.php');

// Bulk nonce name (nom du token généré par la classe WP_List_Table pour les traitements en lots)
$nonce_name = 'bulk-settings_page_hoplacup-v2-settings';

// Nom par défaut pour une nouvelle division
$default_name = "";


/**********************************************************
 * 
 * ADD
 * Ajout d'une division
 * 
 */
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    if (isset($_GET['_wpnonce'])) {
        $nonce = $_GET['_wpnonce'];
        if (wp_verify_nonce($nonce, 'add-division')) {
            global $wpdb;
            $inserted = $wpdb->insert(
                $wpdb->prefix . 'divisions',
                array(
                    'Division' => $default_name,
                )
            );
            if ($inserted !== false) {
                $new_division_id = $wpdb->insert_id;
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=divisions&subview=item&id=' . $new_division_id));
                exit;
            } else {
                // erreur, affiche la liste
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=divisions'));
                exit;
            }
        }
    }
}


/**********************************************************
 * 
 * UPDATE
 * Sauvegarde le formulaire d'édition d'une division
 * 
 */
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    // Vérifier la validité du nonce
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'edit-division_' . $_POST['division_id'])) {
        // Récupérer les données du formulaire et les nettoyer
        $division_id = isset($_POST['division_id']) ? intval($_POST['division_id']) : 0;
        $division_name = isset($_POST['division_name']) ? sanitize_text_field($_POST['division_name']) : '';
        $division_image_id = isset($_POST['division_image_id']) ? intval($_POST['division_image_id']) : 0;

        global $wpdb;
        $updated = $wpdb->update(
            $wpdb->prefix . 'divisions',
            array(
                'Division' => $division_name,
                //'image_id' => $division_image_id
            ),
            array('id' => $division_id),
            array(
                '%s',
                //'%d'
            ),
            array('%d')
        );

        if ($updated !== false) {
            if (isset($_POST['save_redirect']) && $_POST['save_redirect'] == 'save_and_quit') {
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=divisions'));
            } else {
                wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=divisions&subview=item&id=' . $division_id));
            }
            exit;
        } else {
            // Gérer l'erreur de mise à jour
            echo 'Erreur lors de la mise à jour de la division.';
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
 * DELETE One
 * Suppression d'un seul élément
 * 
 */
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (isset($_GET['id']) && isset($_GET['_wpnonce'])) {
        $division_id = $_GET['id'];
        $nonce = $_GET['_wpnonce'];
        if (wp_verify_nonce($nonce, 'delete_division_' . $division_id)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'divisions';
            $wpdb->delete($table_name, array('id' => $division_id));
        }
    }
    wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=divisions'));
    exit;
}


/**********************************************************
 * 
 * Bulk DELETE
 * Suppression en lot
 * 
 */
if (isset($_POST['action']) && $_POST['action'] && isset($_POST['action2']) && $_POST['action2']) {

    // Verifie le token (nonce)
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], $nonce_name)) {
        echo "Erreur token, nonce invalide";
        die;
    }

    $ids = isset($_POST['division']) ? $_POST['division'] : array();

    if (!empty($ids)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'divisions';
        foreach ($ids as $id) {
            $wpdb->delete($table_name, array('id' => $id));
        }
    }
}



wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=divisions'));
exit;
