<?php

// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

require_once(dirname(dirname(plugin_dir_path(__FILE__))) . '/template-parts/times.php');

$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

global $wpdb;

$display_switch_division = true;

// Récupération des matchs à venir U13



// Exemple d'affichage des valeurs après l'ajustement

if ($division === 'u13') {

	$table_name = $wpdb->prefix . 'matchs_u13';
	$query = $wpdb->prepare(
		"SELECT m.Jour, 
				m.Horaire, 
				m.Terrain, 
				m.Equipe1, 
				m.Equipe2, 
				e1.Nom_Equipe AS Nom_Equipe1, 
				e2.Nom_Equipe AS Nom_Equipe2, 
				e1.Identifiant_Club AS Identifiant_Club1, 
				e2.Identifiant_Club AS Identifiant_Club2, 
				m.Score_Equipe1, 
				m.Score_Equipe2, 
				DATE_FORMAT(m.Temps, '%%H:%%i') AS time, 
				DATE_FORMAT(m.Temps, '%%a') AS date 
		 FROM {$table_name} AS m 
		 LEFT JOIN {$wpdb->prefix}equipes_u13 AS e1 ON m.Equipe1 = e1.Identifiant 
		 LEFT JOIN {$wpdb->prefix}equipes_u13 AS e2 ON m.Equipe2 = e2.Identifiant 
		 WHERE DATE_FORMAT(m.Temps, '%%H:%%i') >= %s 
		 AND DATE_FORMAT(m.Temps, '%%a') = %s
		 ORDER BY DATE_FORMAT(m.Temps, '%%a') ASC, 
				  DATE_FORMAT(m.Temps, '%%H:%%i') ASC, 
				  m.Terrain ASC 
		 LIMIT 12",
		$timer, $day
	);
	
	$matchs_du_jour = $wpdb->get_results($query);
	


} elseif ($division === 'u11') {

	$table_name = $wpdb->prefix . 'matchs_u911';
	$query = $wpdb->prepare(
		"SELECT m.Jour, 
				m.Horaire, 
				m.Terrain, 
				m.Equipe1, 
				m.Equipe2, 
				e1.Nom_Equipe AS Nom_Equipe1, 
				e2.Nom_Equipe AS Nom_Equipe2, 
				e1.Identifiant_Club AS Identifiant_Club1, 
				e2.Identifiant_Club AS Identifiant_Club2, 
				m.Score_Equipe1, 
				m.Score_Equipe2, 
				DATE_FORMAT(m.Temps, '%%H:%%i') AS time, 
				DATE_FORMAT(m.Temps, '%%a') AS date 
		 FROM {$table_name} AS m 
		 LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant 
		 LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant 
		 WHERE DATE_FORMAT(m.Temps, '%%H:%%i') >= %s 
		   AND DATE_FORMAT(m.Temps, '%%a') = %s 
		   AND m.Division = 'U11' 
		 ORDER BY DATE_FORMAT(m.Temps, '%%a') ASC, 
				  DATE_FORMAT(m.Temps, '%%H:%%i') ASC, 
				  m.Terrain ASC 
		 LIMIT 12",
		$timer, 
		$day
	);
	
	$matchs_du_jour = $wpdb->get_results($query);
	

}elseif ($division === 'u9') {

	$table_name = $wpdb->prefix . 'matchs_u911';
	$query = $wpdb->prepare(
		"SELECT m.Jour, 
				m.Horaire, 
				m.Terrain, 
				m.Equipe1, 
				m.Equipe2, 
				e1.Nom_Equipe AS Nom_Equipe1, 
				e2.Nom_Equipe AS Nom_Equipe2, 
				e1.Identifiant_Club AS Identifiant_Club1, 
				e2.Identifiant_Club AS Identifiant_Club2, 
				m.Score_Equipe1, 
				m.Score_Equipe2, 
				DATE_FORMAT(m.Temps, '%%H:%%i') AS time, 
				DATE_FORMAT(m.Temps, '%%a') AS date 
		 FROM {$table_name} AS m 
		 LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant 
		 LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant 
		 WHERE DATE_FORMAT(m.Temps, '%%H:%%i') >= %s 
		   AND DATE_FORMAT(m.Temps, '%%a') = %s 
		   AND m.Division LIKE 'U9' 
		 ORDER BY DATE_FORMAT(m.Temps, '%%a') ASC, 
				  DATE_FORMAT(m.Temps, '%%H:%%i') ASC, 
				  m.Terrain ASC 
		 LIMIT 12",
		$timer, 
		$day
	);
	
	$matchs_du_jour = $wpdb->get_results($query);
	

} else {
    $matchs_du_jour = array();
}
