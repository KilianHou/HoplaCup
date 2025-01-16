<?php

// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

global $wpdb;

$display_switch_division = true;

$classement_hopla = array();

if ($division === 'u13') {

    $table_name = $wpdb->prefix . 'classements_u13';

    $classement_castor_table = array();
    $classement_hamster_table = array();
    $classement_hopla_table = array();

    // Tableau classement Hopla Cup
    $classement_result = "
    SELECT c.*, e.Nom_Equipe AS Nom_Equipe, e.Identifiant_Club AS Identifiant_Club, e.Identifiant AS Identifiant
    FROM {$wpdb->prefix}classements_u13 AS c
    LEFT JOIN {$wpdb->prefix}equipes_u13 AS e ON c.Equipe = e.Nom_Equipe
    WHERE c.Coupe = 'Hopla Cup'
    ORDER BY c.Classement ASC
    ";
    $classement_hopla_table = $wpdb->get_results($classement_result);

    // Afficher les résultats pour le débogage
 /*   echo '<pre>Hopla Cup Results:';
    print_r($classement_hopla_table);
    echo '</pre>';*/

    // Tableau classement Castor Cup
    $classement_result = "
    SELECT c.*, e.Nom_Equipe AS Nom_Equipe, e.Identifiant_Club AS Identifiant_Club, e.Identifiant AS Identifiant
    FROM {$wpdb->prefix}classements_u13 AS c
    LEFT JOIN {$wpdb->prefix}equipes_u13 AS e ON c.Equipe = e.Nom_Equipe
    WHERE c.Coupe = 'Castor Cup'
    ORDER BY c.Classement ASC
    ";
    $classement_castor_table = $wpdb->get_results($classement_result);

    // Afficher les résultats pour le débogage
/*    echo '<pre>Castor Cup Results:';
    print_r($classement_castor_table);
    echo '</pre>';*/

    // Tableau classement Hamster Cup
    $classement_result = "
    SELECT c.*, e.Nom_Equipe AS Nom_Equipe, e.Identifiant_Club AS Identifiant_Club, e.Identifiant AS Identifiant
    FROM {$wpdb->prefix}classements_u13 AS c
    LEFT JOIN {$wpdb->prefix}equipes_u13 AS e ON c.Equipe = e.Nom_Equipe
    WHERE c.Coupe = 'Hamster Cup'
    ORDER BY c.Classement ASC
    ";
    $classement_hamster_table = $wpdb->get_results($classement_result);

    // Afficher les résultats pour le débogage
   /* echo '<pre>Hamster Cup Results:';
    print_r($classement_hamster_table);
    echo '</pre>';*/

} elseif ($division === 'u11') {

    $table_name = $wpdb->prefix . 'classements_u911';

    $classement_castor_table = array();
    $classement_hamster_table = array();
    $classement_hopla_table = array();

    // Tableau classement Hopla Cup
    $classement_result = "
    SELECT c.*, e.Nom_Equipe AS Nom_Equipe, e.Identifiant_Club AS Identifiant_Club, e.Identifiant AS Identifiant
    FROM {$wpdb->prefix}classements_u911 AS c
    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e ON c.Equipe = e.Nom_Equipe
    WHERE c.Coupe = 'Hopla Cup' AND c.Division = 'U11'
    ORDER BY c.Classement ASC
    ";
    $classement_hopla_table = $wpdb->get_results($classement_result);

    // Afficher les résultats pour le débogage
/*    echo '<pre>Hopla Cup Results (U11):';
    print_r($classement_hopla_table);
    echo '</pre>';*/

    // Tableau classement Castor Cup
    $classement_result = "
    SELECT c.*, e.Nom_Equipe AS Nom_Equipe, e.Identifiant_Club AS Identifiant_Club, e.Identifiant AS Identifiant
    FROM {$wpdb->prefix}classements_u911 AS c
    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e ON c.Equipe = e.Nom_Equipe
    WHERE c.Coupe = 'Castor Cup' AND c.Division = 'U11'
    ORDER BY c.Classement ASC
    ";
    $classement_castor_table = $wpdb->get_results($classement_result);

    // Afficher les résultats pour le débogage
 /*   echo '<pre>Castor Cup Results (U11):';
    print_r($classement_castor_table);
    echo '</pre>';*/

    // Tableau classement Hamster Cup
    $classement_result = "
    SELECT c.*, e.Nom_Equipe AS Nom_Equipe, e.Identifiant_Club AS Identifiant_Club, e.Identifiant AS Identifiant
    FROM {$wpdb->prefix}classements_u911 AS c
    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e ON c.Equipe = e.Nom_Equipe
    WHERE c.Coupe = 'Hamster Cup' AND c.Division = 'U11'
    ORDER BY c.Classement ASC
    ";
    $classement_hamster_table = $wpdb->get_results($classement_result);

    // Afficher les résultats pour le débogage
 /*   echo '<pre>Hamster Cup Results (U11):';
    print_r($classement_hamster_table);
    echo '</pre>';*/

} elseif ($division === 'u9') {

    $table_name = $wpdb->prefix . 'classements_u911';

    $classement_castor_table = array();
    $classement_hamster_table = array();
    $classement_hopla_table = array();

    // Tableau classement Hopla Cup
    $classement_result = "
    SELECT c.*, e.Nom_Equipe AS Nom_Equipe, e.Identifiant_Club AS Identifiant_Club, e.Identifiant AS Identifiant
    FROM {$wpdb->prefix}classements_u911 AS c
    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e ON c.Equipe = e.Nom_Equipe
    WHERE c.Coupe = 'Hopla Cup' AND c.Division = 'U9'
    ORDER BY c.Classement ASC
    ";
    $classement_hopla_table = $wpdb->get_results($classement_result);

    // Afficher les résultats pour le débogage
 /*   echo '<pre>Hopla Cup Results (U9):';
    print_r($classement_hopla_table);
    echo '</pre>';*/
}

?>
