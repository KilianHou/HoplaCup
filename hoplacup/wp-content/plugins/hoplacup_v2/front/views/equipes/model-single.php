<?php

// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

$styles_head[] = plugin_dir_url(__FILE__) . 'styles-details.css';

$page_title = "Équipes $division_name";

global $wpdb;

$poules = "";
$Equipe = "";

// Récupération de l'id_equipe du club
if ($division == 'u13') {

	$club_sql = $wpdb->prepare(
		"SELECT Identifiant_Club, Nom_Equipe FROM {$wpdb->prefix}equipes_u13 WHERE Identifiant = %s",
		$id_equipe
	);
}else{
	$club_sql = $wpdb->prepare(
		"SELECT Identifiant_Club, Nom_Equipe FROM {$wpdb->prefix}equipes_u911 WHERE Identifiant = %s",
		$id_equipe
	);
}

$Equipe = $wpdb->get_results($club_sql);


$club_identifiant = $wpdb->get_var($club_sql);

if ($division == 'u13') {
    $sql = $wpdb->prepare(
        "
        SELECT m.*,
        e1.Nom_Equipe AS Nom_Equipe1,
        e2.Nom_Equipe AS Nom_Equipe2,
        e1.Identifiant_Club AS Club_Equipe1,
        e2.Identifiant_Club AS Club_Equipe2,
        e1.Identifiant_Club AS Identifiant_Club1,
        e2.Identifiant_Club AS Identifiant_Club2
        FROM {$wpdb->prefix}matchs_u13 AS m
        LEFT JOIN {$wpdb->prefix}equipes_u13 AS e1 ON m.Equipe1 = e1.Identifiant
        LEFT JOIN {$wpdb->prefix}equipes_u13 AS e2 ON m.Equipe2 = e2.Identifiant
        WHERE Equipe1 = %s OR Equipe2 = %s
        ORDER BY m.Temps ASC

    ",
        $id_equipe,
        $id_equipe
    );
} else {
    $sql = $wpdb->prepare(
        "
        SELECT m.*, e1.Nom_Equipe AS Nom_Equipe1, e2.Nom_Equipe AS Nom_Equipe2, e1.Identifiant_Club AS Club_Equipe1, e2.Identifiant_Club AS Club_Equipe2, e1.Identifiant_Club AS Identifiant_Club1, e2.Identifiant_Club AS Identifiant_Club2
        FROM {$wpdb->prefix}matchs_u911 AS m
        LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
        LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant
        WHERE Equipe1 = %s OR Equipe2 = %s
        ORDER BY m.Temps ASC
    ",
        $id_equipe,
        $id_equipe
    );
}


// Exécuter la requête SQL
$matches = $wpdb->get_results($sql, OBJECT);
