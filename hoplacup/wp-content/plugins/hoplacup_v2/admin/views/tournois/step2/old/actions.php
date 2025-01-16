<?php

// Chargez WordPress Core
require_once('../../../../../../../wp-load.php');

$nonce_name = 'tournoi_step_1_hoplacup-v2-settings';

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

if (!wp_verify_nonce($_POST['_wpnonce'], 'tournoi_step_3_hoplacup-v2-settings')) {
    echo 'nonce invalide';
    wp_die();
}

if (!isset($_POST['action'])) {
    echo 'action manquante';
    wp_die();
}



global $wpdb;


/**
 * if action "generate", 
 */
if (($_POST['action']) === 'save_transferts') {

    $table_name = "{$wpdb->prefix}transferts_phases";

    $tournoi_id = (int) $_POST['tournoi_id'];

    foreach ($_POST as $key => $value) {
        /**
         * Examiner les name des POST, et intercepter ceux qui commencent par "phase..."
         */
        if (substr($key, 0, 5) == "phase") {
            /**
             * On déduit ensuite des informations à partir du name
             * 
             * Exemple : $_POST['phase2_15_4']
             * 
             * $phase => 'phase2'
             * 
             * Si phases de poules : 
             *      $id_cible => 15
             * Si phase de playoffs :
             *      $id_cible => 15
             * 
             * Le 4 sert juste à différencier les name des selects/POST (il s'incrémente sur le nombre d'équipes de la poule)
             * 
             * 
             * => $value
             * contient le classement, et l'id de poule d'origine sous cette forme : 
             * "1_98"
             * Classement => 1
             * ID de la poule d'origine : 98
             * 
             */

            // Scinder la chaine en infos exploitables
            $parts = explode("_", $key);
            // Extraire les informations de phase, de poule et d'équipe
            $phase        = $parts[0];
            $id_cible     = (int) $parts[1];
            $numero_phase = (int) str_replace("phase", "", $phase);


            /**
             * Cas où la cible est dans une phase de poule ( < 100 )
             * 
             * Renseigner dans la table en BDD :
             * "id_poule_origin"
             * "classement_origin"
             * "id_poule_destination"
             * 
             */
            if ($numero_phase < 100) {

                $infos_value_post     = explode('_', $value); // valeur du POST

                $classement_origin    = (int)$infos_value_post[0]; // classement poule origine
                if (isset($infos_value_post[1])) { // Si id poule origine renseigné
                    $id_poule_origin  = (int)$infos_value_post[1]; //id poule origine
                }
                $id_poule_destination = (int)$id_cible;

                /**
                 * Les 3 infos sont nécessaires pour valider un update
                 */
                if (isset($id_poule_origin) && $id_poule_origin && $classement_origin && $id_poule_destination) {
                    $where = array(
                        'id_poule_origin' => $id_poule_origin,
                        'classement_origin' => $classement_origin,
                    );
                    $data = array(
                        'id_poule_destination' => $id_poule_destination,
                    );
                    $format = array('%d', '%d', '%d', '%d', '%d');
                    $wpdb->update($table_name, $data, $where, $format);
                }
            }
            /**
             * Cas où la cible est un match de playoffs ( phase > 100 )
             * 
             * Renseigner dans la table en BDD :
             * "id_poule_origin"
             * "classement_origin"
             * "id_match_destination"
             * 
             */
            else {
                $infos_value_post     = explode('_', $value); // valeur du POST

                $classement_origin    = (int)$infos_value_post[0]; // classement poule origine
                if (isset($infos_value_post[1])) { // Si id poule origine renseigné
                    $id_poule_origin  = (int)$infos_value_post[1]; //id poule origine
                }
                $id_match_destination = (int)$id_cible; // on est en playoffs, la cible est un match


                /**
                 * Les 3 infos sont nécessaires pour valider un update
                 */
                if (isset($id_poule_origin) && $id_poule_origin && $classement_origin && $id_match_destination) {
                    $where = array(
                        'id_poule_origin' => $id_poule_origin,
                        'classement_origin' => $classement_origin,
                    );
                    $data = array(
                        'id_match_destination' => $id_match_destination,
                    );
                    $format_data = array('%d'); // Format pour les colonnes mises à jour
                    $format_where = array('%d', '%d'); // Format pour les colonnes dans la clause WHERE

                    $wpdb->update($table_name, $data, $where, $format_data, $format_where);
                }
            }
        }
    }
    // Message de succès
    $message = "Configuration sauvegardée";
    set_transient('step3_success_message', $message, 2);
    wp_redirect(admin_url('admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&configstep=3&id=' . $tournoi_id));
    exit;
}
