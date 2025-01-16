<?php

// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';

global $wpdb;

$display_switch_division = true;

$currentDate = date('Y-m-d');
$currentTime = current_time('mysql');

$defaultDate = '2024-05-31';
$displayDate = $currentDate;

// Récupération des matchs à venir U13

if ($division === 'u13') {

    $table_name = $wpdb->prefix . 'matchs_u13';
    $query = $wpdb->prepare(
        "SELECT m.Jour, m.Horaire, m.Terrain,m.Equipe1, m.Equipe2, 
				e1.Nom_Equipe AS Nom_Equipe1, 
				e2.Nom_Equipe AS Nom_Equipe2,
				e1.Identifiant_Club AS Identifiant_Club1,
				e2.Identifiant_Club AS Identifiant_Club2,
				m.Score_Equipe1, m.Score_Equipe2
			FROM {$table_name} AS m
			LEFT JOIN {$wpdb->prefix}equipes_u13 AS e1 ON m.Equipe1 = e1.Identifiant
			LEFT JOIN {$wpdb->prefix}equipes_u13 AS e2 ON m.Equipe2 = e2.Identifiant 
            WHERE TIME(m.Temps) > %s - INTERVAL 20 MINUTE 
            AND DATE(m.Temps) LIKE %s 
			ORDER BY Temps ASC, Terrain ASC
			LIMIT 12",
	    $currentTime,
        $displayDate . '%'
    );

    $matchs_du_jour = $wpdb->get_results($query);

    if (empty($matchs_du_jour)) {
        $query = $wpdb->prepare(
            "SELECT m.Jour, m.Horaire, m.Terrain,m.Equipe1, m.Equipe2,
						e1.Nom_Equipe AS Nom_Equipe1, 
						e2.Nom_Equipe AS Nom_Equipe2,
						e1.Identifiant_Club AS Identifiant_Club1,
						e2.Identifiant_Club AS Identifiant_Club2,
						m.Score_Equipe1, m.Score_Equipe2
					FROM {$table_name} AS m
					LEFT JOIN {$wpdb->prefix}equipes_u13 AS e1 ON m.Equipe1 = e1.Identifiant
					LEFT JOIN {$wpdb->prefix}equipes_u13 AS e2 ON m.Equipe2 = e2.Identifiant 
					WHERE TIME(m.Temps) > %s - INTERVAL 20 MINUTE 
            		AND DATE(m.Temps) LIKE %s 
					ORDER BY Temps ASC, Terrain ASC
					LIMIT 12",
            $currentTime,
	        $defaultDate . '%'
        );
        $matchs_du_jour = $wpdb->get_results($query);
        $displayDate = $defaultDate;
    }
} elseif ($division === 'u11') {

	$table_name = $wpdb->prefix . 'matchs_u911';
	$query = $wpdb->prepare(
		"SELECT m.Jour, m.Horaire, m.Terrain,m.Equipe1, m.Equipe2, 
				e1.Nom_Equipe AS Nom_Equipe1, 
				e2.Nom_Equipe AS Nom_Equipe2,
				e1.Identifiant_Club AS Identifiant_Club1,
				e2.Identifiant_Club AS Identifiant_Club2,
				m.Score_Equipe1, m.Score_Equipe2
			FROM {$table_name} AS m
			LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
			LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant 
			WHERE TIME(m.Temps) > %s - INTERVAL 20 MINUTE 
            AND DATE(m.Temps) LIKE %s 
			AND m.Division LIKE %s
			ORDER BY Temps ASC, Terrain ASC
			LIMIT 12",
		$currentTime,
		$displayDate . '%',
		"U11"
	);

	$matchs_du_jour = $wpdb->get_results($query);

	if (empty($matchs_du_jour)) {
		$query = $wpdb->prepare(
			"SELECT m.Jour, m.Horaire, m.Terrain,m.Equipe1, m.Equipe2,
						e1.Nom_Equipe AS Nom_Equipe1, 
						e2.Nom_Equipe AS Nom_Equipe2,
						e1.Identifiant_Club AS Identifiant_Club1,
						e2.Identifiant_Club AS Identifiant_Club2,
						m.Score_Equipe1, m.Score_Equipe2
					FROM {$table_name} AS m
					LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
					LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant 
					WHERE TIME(m.Temps) > %s - INTERVAL 20 MINUTE 
					AND DATE(m.Temps) LIKE %s 
					AND m.Division LIKE %s
					ORDER BY Temps ASC, Terrain ASC
					LIMIT 12",
			$currentTime,
			$defaultDate . '%',
			"U11"
		);
		$matchs_du_jour = $wpdb->get_results( $query );
		$displayDate = $defaultDate;
	}
}elseif ($division === 'u9') {

	$table_name = $wpdb->prefix . 'matchs_u911';
	$query = $wpdb->prepare(
		"SELECT m.Jour, m.Horaire, m.Terrain,m.Equipe1, m.Equipe2, 
				e1.Nom_Equipe AS Nom_Equipe1, 
				e2.Nom_Equipe AS Nom_Equipe2,
				e1.Identifiant_Club AS Identifiant_Club1,
				e2.Identifiant_Club AS Identifiant_Club2,
				m.Score_Equipe1, m.Score_Equipe2
			FROM {$table_name} AS m
			LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
			LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant 
			WHERE TIME(m.Temps) > %s - INTERVAL 20 MINUTE 
            AND DATE(m.Temps) LIKE %s 
			AND m.Division LIKE %s
			ORDER BY Temps ASC, Terrain ASC
			LIMIT 12",
		$currentTime,
		$displayDate . '%',
		"U9"
	);

	$matchs_du_jour = $wpdb->get_results($query);

	if (empty($matchs_du_jour)) {
		$query = $wpdb->prepare(
			"SELECT m.Jour, m.Horaire, m.Terrain,m.Equipe1, m.Equipe2,
						e1.Nom_Equipe AS Nom_Equipe1, 
						e2.Nom_Equipe AS Nom_Equipe2,
						e1.Identifiant_Club AS Identifiant_Club1,
						e2.Identifiant_Club AS Identifiant_Club2,
						m.Score_Equipe1, m.Score_Equipe2
					FROM {$table_name} AS m
					LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
					LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant 
					WHERE TIME(m.Temps) > %s - INTERVAL 20 MINUTE 
            		AND DATE(m.Temps) LIKE %s 
					AND m.Division LIKE %s
					ORDER BY Temps ASC, Terrain ASC
					LIMIT 12",
			$currentTime,
			$defaultDate . '%',
			"U9"
		);
		$matchs_du_jour = $wpdb->get_results( $query );
		$displayDate = $defaultDate;
	}
} else {
    $matchs_du_jour = array();
}
