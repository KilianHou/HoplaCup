<?php

// Désactivation de la mise en cache
define('DONOTCACHEPAGE', true);

// inclusion de la feuille de style de la vue
$styles_head[] = plugin_dir_url(__FILE__) . 'styles.css';


global $wpdb;

$display_switch_division = true;

$matchs = array();

if ($division === 'u13') {

    // $table_name = $wpdb->prefix . 'matchs_u13';
    // $query = "SELECT * FROM $table_name WHERE poule IS NULL ORDER BY Temps ASC, terrain ASC";
    // $matchs = $wpdb->get_results($query, OBJECT);
    // $query = "SELECT * FROM $table_name WHERE poule IS NULL ORDER BY Temps ASC, terrain ASC";


    $table_name = $wpdb->prefix . 'matchs_u13';

    $sql = "SELECT m.Jour, m.Horaire, m.Terrain, m.Equipe1, m.Equipe2, 
                e1.Nom_Equipe AS Nom_Equipe1, 
                e2.Nom_Equipe AS Nom_Equipe2,
                e1.Identifiant_Club AS Identifiant_Club1,
                e2.Identifiant_Club AS Identifiant_Club2,
                    e1.Identifiant AS Identifiant_Equipe1,
                    e2.Identifiant AS Identifiant_Equipe2,
                m.Score_Equipe1,
                    m.Score_Equipe2,
                    m.Tab_Equipe1,
                    m.Tab_Equipe2,
                    m.Phase
            FROM {$table_name} AS m
            LEFT JOIN {$wpdb->prefix}equipes_u13 AS e1 ON m.Equipe1 = e1.Identifiant
            LEFT JOIN {$wpdb->prefix}equipes_u13 AS e2 ON m.Equipe2 = e2.Identifiant
            WHERE poule IS NULL
            ORDER BY Temps ASC, terrain ASC";

    $matchs = $wpdb->get_results($sql, OBJECT);
}elseif ($division === 'u11') {
    $table_name = $wpdb->prefix . 'matchs_u911';

    $sql = $wpdb->prepare("SELECT m.Jour, m.Horaire, m.Terrain, m.Equipe1, m.Equipe2, 
                            e1.Nom_Equipe AS Nom_Equipe1, 
                            e2.Nom_Equipe AS Nom_Equipe2,
                            e1.Identifiant_Club AS Identifiant_Club1,
                            e2.Identifiant_Club AS Identifiant_Club2,
                            e1.Identifiant AS Identifiant_Equipe1,
                            e2.Identifiant AS Identifiant_Equipe2,
                            m.Score_Equipe1,
                            m.Score_Equipe2,
                            m.Tab_Equipe1,
                            m.Tab_Equipe2,
                            m.Phase
            FROM {$table_name} AS m
            LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
            LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant
            WHERE poule IS NULL AND UPPER(m.Division) = %s
            ORDER BY m.Temps ASC, m.Terrain ASC", strtoupper($division));

    $matchs = $wpdb->get_results($sql, OBJECT);
}elseif ($division === 'u9') {
    $table_name = $wpdb->prefix . 'matchs_u911';

    $sql = $wpdb->prepare("SELECT m.Jour, m.Horaire, m.Terrain, m.Equipe1, m.Equipe2, 
                            e1.Nom_Equipe AS Nom_Equipe1, 
                            e2.Nom_Equipe AS Nom_Equipe2,
                            e1.Identifiant_Club AS Identifiant_Club1,
                            e2.Identifiant_Club AS Identifiant_Club2,
                            e1.Identifiant AS Identifiant_Equipe1,
                            e2.Identifiant AS Identifiant_Equipe2,
                            m.Score_Equipe1,
                            m.Score_Equipe2,
                            m.Tab_Equipe1,
                            m.Tab_Equipe2,
                            m.Phase
            FROM {$table_name} AS m
            LEFT JOIN {$wpdb->prefix}equipes_u911 AS e1 ON m.Equipe1 = e1.Identifiant
            LEFT JOIN {$wpdb->prefix}equipes_u911 AS e2 ON m.Equipe2 = e2.Identifiant
            WHERE poule IS NULL AND UPPER(m.Division) = %s
            ORDER BY m.Temps ASC, m.Terrain ASC", strtoupper($division));

    $matchs = $wpdb->get_results($sql, OBJECT);
}