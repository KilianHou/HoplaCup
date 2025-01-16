<?php

// inclusion de la feuille de style de la vue
$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

// Title pour la page
$page_title = "Équipes $division_name";

// On affiche l'option pour switcher entre U13 / U9-11
$display_switch_division = true;

/*
Récupération des équipes de la division
*/

$equipes = array();

global $wpdb;

$table_equipes = $wpdb->prefix . 'equipes';
$table_divisions = $wpdb->prefix . 'divisions';
$table_clubs = $wpdb->prefix . 'clubs';
$table_postmeta = $wpdb->prefix . 'postmeta';

if ($division == 'u13') {
    $division_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $table_divisions WHERE Division = %s", 'u13'));
} elseif ($division == 'u11') {
    $division_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $table_divisions WHERE Division = %s", 'u11'));
} elseif ($division == 'u9') {
    $division_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $table_divisions WHERE Division = %s", 'u9'));
}

if (isset($division_id)) {
    $sql = $wpdb->prepare("
        SELECT 
            e.*, 
            c.Nom AS Nom_Club, 
            pm.meta_value AS Logo
        FROM 
            $table_equipes e 
        LEFT JOIN 
            $table_clubs c ON e.Clubs_id = c.ID 
        LEFT JOIN 
            $table_postmeta pm ON c.Logo = pm.post_id
        WHERE 
            e.Divisions_id = %d 
        AND 
            pm.meta_key = '_wp_attached_file'
        ORDER BY 
            c.Nom ASC", $division_id);

    $results = $wpdb->get_results($sql, OBJECT);
    if ($results) {
        $equipes = $results;
    } else {
        echo 'Aucune équipe trouvée.';
    }
} else {
    echo 'Division non trouvée.';
}

?>
