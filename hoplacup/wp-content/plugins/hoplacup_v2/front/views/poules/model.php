<?php

// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

// inclusion de la feuille de style de la vue
$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

$display_switch_division = true;


if (isset($_GET['q'])) {
    $selectedPoule = $_GET['q'];
} else {
    $selectedPoule = ($division == 'u9') ? 'K' : 'A';
}

$table_suffixe = '';

switch ($division) {
    case 'u13':
        $table_suffixe = 'u13';
        break;
    case 'u9':
    case 'u11':
        $table_suffixe = 'u911';
        break;
}



global $wpdb;



/**
 * Select les poules
 */
$sql_poules = "SELECT *,
    e.Nom_Equipe AS Nom_Equipe
    FROM {$wpdb->prefix}poules_$table_suffixe AS poules
    LEFT JOIN {$wpdb->prefix}equipes_$table_suffixe AS e
    ON Equipe = e.Identifiant";
if ($table_suffixe == 'u911') {
    $sql_poules .= " WHERE poules.Division = '$division'";
}
$sql_poules .= " ORDER BY poules.ID ASC;";

$poules = $wpdb->get_results($sql_poules, ARRAY_A);




/**
 * Select les matchs avec liaisons vers equipes
 */
$sql_matches = "SELECT *, Poule, Terrain,
    e1.Identifiant_Club AS Identifiant_Club1,
    e2.Identifiant_Club AS Identifiant_Club2,
    e1.Nom_Equipe AS Nom_Equipe1,
    e2.Nom_Equipe AS Nom_Equipe2,
    DATE_FORMAT(Temps, '%H%i') AS time,
    DATE_FORMAT(Temps, '%a') AS date
    FROM {$wpdb->prefix}matchs_$table_suffixe
    LEFT JOIN {$wpdb->prefix}equipes_$table_suffixe AS e1
    ON Equipe1 = e1.Identifiant
    LEFT JOIN {$wpdb->prefix}equipes_$table_suffixe AS e2
    ON Equipe2 = e2.Identifiant";
if ($table_suffixe == 'u911') {
    $sql_matches .= " WHERE {$wpdb->prefix}matchs_u911.Division = '$division'";
}

$sql_matches .= " ORDER BY date ASC, time ASC, Terrain ASC; ";

$matches = $wpdb->get_results($sql_matches, ARRAY_A);
