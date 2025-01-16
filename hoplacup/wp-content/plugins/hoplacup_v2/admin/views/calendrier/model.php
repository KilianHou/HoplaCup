<?php

global $wpdb;

$terrains = $wpdb->prefix . "terrains";
$matchs_table = $wpdb->prefix . 'matchs';
$equipes_table = $wpdb->prefix . 'equipes';
$poules_table = $wpdb->prefix . 'poules';

$query = "
    SELECT 
        m.*,
        eq1.Nom AS nom_equipe1,
        eq2.Nom AS nom_equipe2,
        poule.Nom AS nom_poule
    FROM 
        $matchs_table m
    LEFT JOIN 
        $equipes_table eq1 ON m.id_eq1 = eq1.ID
    LEFT JOIN 
        $equipes_table eq2 ON m.id_eq2 = eq2.ID
    LEFT JOIN
        $poules_table poule ON m.Poules_ID = poule.ID
    WHERE 
        m.Terrains_id IS NULL	
";
$matchToSort = $wpdb->get_results($query);

$query2 = "
    SELECT 
        m.*,
        eq1.Nom AS nom_equipe1,
        eq2.Nom AS nom_equipe2,
        poule.Nom AS nom_poule
    FROM 
        $matchs_table m
    LEFT JOIN 
        $equipes_table eq1 ON m.id_eq1 = eq1.ID
    LEFT JOIN 
        $equipes_table eq2 ON m.id_eq2 = eq2.ID
	LEFT JOIN
		$poules_table poule ON m.Poules_ID = poule.ID
";

$matchSorted = $wpdb->get_results($query2);
$pouleResults = $wpdb->get_results("SELECT * FROM $poules_table");
$terrainResults = $wpdb->get_results("SELECT ID, Nom FROM $terrains");
//$matchId = $wpdb->get_results("SELECT Id FROM $matchs where terrainId = '$terrainId'");

