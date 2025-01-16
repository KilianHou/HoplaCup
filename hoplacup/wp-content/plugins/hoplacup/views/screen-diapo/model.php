<?php

require_once(dirname(dirname(plugin_dir_path(__FILE__))) . '/template-parts/times.php');

// screen-diapo/model.php
// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

$display_switch_division = true;

global $wpdb;

$div = strtolower($division);

if ($div == 'u9'|| $div == 'u11') {
    $div = 'u911';
    if ($day == 'Fri') {
        header("refresh:50");
    } else if ($day == 'Sat' && $timer <= '1300') {
        header("refresh:50");
    } else if ($day == 'Sat' && $timer >= '1300') {
        header("refresh:80");
    } else if ($day == 'Sun') {
        header("refresh:40");
    }
} else if ($div == 'u13') {
    if ($day == 'Fri') {
        header("refresh:40");
    } else if ($day == 'Sat' && $timer <= '1300') {
        header("refresh:40");
    } else if ($day == 'Sat' && $timer >= '1300') {
        header("refresh:60");
    } else if ($day == 'Sun') {
        header("refresh:30");
    }
}

$poules = $wpdb->get_results("SELECT *, e.Nom_Equipe AS Nom_Equipe FROM {$wpdb->prefix}poules_$div AS poules LEFT JOIN {$wpdb->prefix}equipes_$div AS e ON Equipe = e.Identifiant ORDER BY poules.ID ASC;", ARRAY_A);

$matches_u911 = $wpdb->get_results("SELECT 
    m.Poule,
    m.Phase,
    m.Terrain,
    m.Division,
    e1.Division AS Division1,
    e2.Division AS Division2,
    m.Score_Equipe1,
    m.Score_Equipe2,
    m.Tab_Equipe1,
    m.Tab_Equipe2,
    e1.Identifiant_Club AS Identifiant_Club1,
    e2.Identifiant_Club AS Identifiant_Club2,
    e1.Nom_Equipe AS Nom_Equipe1,
    e2.Nom_Equipe AS Nom_Equipe2,
    DATE_FORMAT(m.Temps, '%H%i') AS time,
    DATE_FORMAT(m.Temps, '%a') AS date 
FROM 
    {$wpdb->prefix}matchs_u911 m 
LEFT JOIN 
    {$wpdb->prefix}equipes_u911 AS e1 ON Equipe1 = e1.Identifiant 
LEFT JOIN 
    {$wpdb->prefix}equipes_u911 AS e2 ON Equipe2 = e2.Identifiant 
ORDER BY 
    date ASC, time ASC, Terrain ASC;", ARRAY_A);

$matches_u13 = $wpdb->get_results("SELECT 
    m.Poule,
    m.Phase,
    m.Terrain,
    m.Score_Equipe1,
    m.Score_Equipe2,
    m.Tab_Equipe1,
    m.Tab_Equipe2,
    e1.Identifiant_Club AS Identifiant_Club1,
    e2.Identifiant_Club AS Identifiant_Club2,
    e1.Nom_Equipe AS Nom_Equipe1,
    e2.Nom_Equipe AS Nom_Equipe2,
    DATE_FORMAT(m.Temps, '%H%i') AS time,
    DATE_FORMAT(m.Temps, '%a') AS date 
FROM 
    {$wpdb->prefix}matchs_u13 m 
LEFT JOIN 
    {$wpdb->prefix}equipes_u13 AS e1 ON Equipe1 = e1.Identifiant 
LEFT JOIN 
    {$wpdb->prefix}equipes_u13 AS e2 ON Equipe2 = e2.Identifiant 
ORDER BY 
    date ASC, time ASC, Terrain ASC;", ARRAY_A);


$classements = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}classements_$div", ARRAY_A);

$matches = $wpdb->get_results("SELECT *, Phase, Poule, Terrain, e1.Identifiant_Club AS Identifiant_Club1, e2.Identifiant_Club AS Identifiant_Club2, e1.Nom_Equipe AS Nom_Equipe1, e2.Nom_Equipe AS Nom_Equipe2, DATE_FORMAT(Temps, '%H%i') AS time, DATE_FORMAT(Temps, '%a') AS date FROM {$wpdb->prefix}matchs_$div LEFT JOIN {$wpdb->prefix}equipes_$div AS e1 ON Equipe1 = e1.Identifiant LEFT JOIN {$wpdb->prefix}equipes_$div AS e2 ON Equipe2 = e2.Identifiant ORDER BY date ASC, time ASC, Terrain ASC; ", ARRAY_A);

$classements = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}classements_$div", ARRAY_A);
