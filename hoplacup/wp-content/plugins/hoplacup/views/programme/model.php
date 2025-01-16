<?php

// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

// inclusion de la feuille de style de la vue

$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

$display_switch_division = true;

$page_title = "Programme " . $division_name;


$jour_valides = ['ven', 'sam', 'dim'];
$today_num = date('N');
$today_jour = ($today_num == 5 ? 'ven' : ($today_num == 6 ? 'sam' : ($today_num == 7 ? 'dim' : 'ven')));

$jour = $_GET['jour'] ?? $today_jour;

if (!in_array($jour, $jour_valides)) {
    $jour = 'ven';
}

$heure_actuelle = intval(current_time('G'));
$is_matin_open = ($jour != 'sam') || ($jour == 'sam' && $heure_actuelle < 13);
$is_apres_midi_open = ($jour == 'sam' && $heure_actuelle >= 13);


global $wpdb;


if ($division == 'u13') {
    $table_matchs = $wpdb->prefix . 'matchs_u13';
}
//
elseif ($division == 'u9' || $division == 'u11') {
    $table_matchs = $wpdb->prefix . 'matchs_u911';
}

if ($division === 'u13') {
	$sql = "SELECT m.*, 
	    e1.Nom_Equipe AS Nom_Equipe1, 
	    e2.Nom_Equipe AS Nom_Equipe2,
	    e1.Identifiant_Club AS Identifiant_Club1,
	    e2.Identifiant_Club AS Identifiant_Club2,
	    m.Score_Equipe1, m.Score_Equipe2
	    FROM {$table_matchs} AS m
	    LEFT JOIN {$wpdb->prefix}equipes_u13 AS e1 ON m.Equipe1 = e1.Identifiant
	    LEFT JOIN {$wpdb->prefix}equipes_u13 AS e2 ON m.Equipe2 = e2.Identifiant
	    WHERE m.Jour = %s
	    ORDER BY m.Temps ASC, m.Terrain ASC";
	$query = $wpdb->prepare($sql, $jour);
} elseif ($division == 'u11') {
	$sql = "SELECT m.*, 
	    e1.Nom_Equipe AS Nom_Equipe1, 
	    e2.Nom_Equipe AS Nom_Equipe2,
	    e1.Identifiant_Club AS Identifiant_Club1,
	    e2.Identifiant_Club AS Identifiant_Club2,
	    m.Score_Equipe1, m.Score_Equipe2
	    FROM {$table_matchs} AS m
	    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
	    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant
	    WHERE m.Jour = %s
		AND m.Division = %s
		ORDER BY m.Temps ASC, m.Terrain ASC";
	$query = $wpdb->prepare($sql, $jour, $division);
} elseif ($division == 'u9'){
	$sql = "SELECT m.*, 
	    e1.Nom_Equipe AS Nom_Equipe1, 
	    e2.Nom_Equipe AS Nom_Equipe2,
	    e1.Identifiant_Club AS Identifiant_Club1,
	    e2.Identifiant_Club AS Identifiant_Club2,
	    m.Score_Equipe1, m.Score_Equipe2
	    FROM {$table_matchs} AS m
	    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
	    LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant
	    WHERE m.Jour = %s
	    AND m.Division = %s
	    ORDER BY m.Temps ASC, m.Terrain ASC";
	$query = $wpdb->prepare($sql, $jour, $division);
}

$matches = $wpdb->get_results($query, ARRAY_A);

$matchesMatin = [];
$matchesApresMidi = [];

foreach ($matches as $match) {
    $heureMatch = intval(explode(':', $match['Horaire'])[0]);
    $key = sprintf('%02d:%s', $heureMatch, $match['Horaire']);
    if ($jour === 'sam') {
        if ($heureMatch <= 13) {
            $matchesMatin[] = $match;
        } else {
            $matchesApresMidi[] = $match;
        }
    }
}
