<?php

// inclusion de la feuille de style de la vue
$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

// Title pour la page
$page_title = "Équipes $division_name";

// On affiche l'option por switcher entre U13 / U9-11
$display_switch_division = true;

/*

Récupération des équipes de la division

*/

$equipes = array();

global $wpdb;

if ($division == 'u13') {
    $table_equipes = $wpdb->prefix . 'equipes_u13';
    $sql = "SELECT * FROM $table_equipes  ORDER BY Nom_Club ASC";
} elseif ($division == 'u11') {
    $table_equipes = $wpdb->prefix . 'equipes_u911';
    $sql = $wpdb->prepare("SELECT * FROM $table_equipes WHERE Division = %s ORDER BY Nom_Club ASC", "u11");
} elseif ($division == 'u9') {
    $table_equipes = $wpdb->prefix . 'equipes_u911';
    $sql = $wpdb->prepare("SELECT * FROM $table_equipes WHERE Division = %s ORDER BY Nom_Club ASC", "u9");
}

if (isset($sql)) {
    $results = $wpdb->get_results($sql, OBJECT);
    if ($results) {
        $equipes = $results;
    } else {
        echo 'Aucunes équipes trouvées.';
    }
}
