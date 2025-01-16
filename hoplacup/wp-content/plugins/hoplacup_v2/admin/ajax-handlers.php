<?php

/**
 * Définition des fonctions ajax et de leur fichier
 * nom_fonction => path/vers/son/fichier
 */
return array(
    // nom_fonction => path/vers/son/fichier
    //poules_équipes
    'ajax_poule_equipe_remove' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    'ajax_poule_equipe_add_update' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    // tournois
    'tournois_test_function' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    'ajax_check_DateTournoi' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    //transferts_équipes
    'ajax_transfer_placeholder' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    'ajax_reset_placeholder' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    'ajax_check_transfer_phase' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    //résultats
    'ajax_save_match_result' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    'ajax_save_match_fairplay_eq1' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    'ajax_save_match_fairplay_eq2' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',
    //classement
    'ajax_update_classement' => plugin_dir_path(__FILE__) . 'views/tournois/ajax-functions.php',



);
