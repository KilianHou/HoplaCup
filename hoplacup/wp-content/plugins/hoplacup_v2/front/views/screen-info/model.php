<?php

//include_once(dirname('template-parts/times.php'));

require_once(dirname(dirname(plugin_dir_path(__FILE__))) . '/template-parts/times.php');

//get_template_part('template-parts/times.php');
// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);




$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

$display_switch_division = true;

header("refresh:60");

global $wpdb;

$div = strtolower($division);

if($div == 'u9' || $div == 'u11') {
    $div = 'u911';
}

$terrain = $wpdb->get_results("SELECT DISTINCT Terrain FROM {$wpdb->prefix}matchs_$div", ARRAY_A);

$matches = $wpdb->get_results("SELECT *, Terrain, e1.Identifiant_Club AS Identifiant_Club1, e2.Identifiant_Club AS Identifiant_Club2, e1.Nom_Equipe AS Nom_Equipe1, e2.Nom_Equipe AS Nom_Equipe2, DATE_FORMAT(Temps, '%H%i') AS time, DATE_FORMAT(Temps, '%a') AS date FROM {$wpdb->prefix}matchs_$div LEFT JOIN {$wpdb->prefix}equipes_$div AS e1 ON Equipe1 = e1.Identifiant LEFT JOIN {$wpdb->prefix}equipes_$div AS e2 ON Equipe2 = e2.Identifiant ORDER BY date ASC, time ASC, Terrain ASC; ", ARRAY_A);
