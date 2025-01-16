<?php

global $wpdb;

$poule_filter = isset($_GET['poules_id']) ? intval($_GET['poules_id']) : null;
$terrain_filter = isset($_GET['terrains_id']) ? intval($_GET['terrains_id']) : null;

// Récupérer la liste des terrains depuis la base de données
$terrains_table = $wpdb->prefix . 'terrains';
$tournois_terrains_table = $wpdb->prefix . 'tournois_terrains';
$matchs_table = $wpdb->prefix . 'matchs';
$equipes_table = $wpdb->prefix . 'equipes';
$poules_table = $wpdb->prefix . 'poules';
$club_table = $wpdb->prefix . 'clubs';

function get_tournoi($tournoi_id)
{
    global $wpdb;
    $table_tournoi = $wpdb->prefix . 'tournois';
    $query = $wpdb->prepare("SELECT * FROM $table_tournoi WHERE id = %d", $tournoi_id);
    return $wpdb->get_row($query);
}

$tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tournoi = get_tournoi($tournoi_id);
$tournoi_name = $tournoi->Nom;

// Récupérer la liste des poules depuis la base de données
$poules = $wpdb->get_results(
    $wpdb->prepare("
            SELECT `id`, `Nom`, `Phase_id`
            FROM $poules_table
            WHERE Tournoi_id = %d
        ", $tournoi_id)
);



// Récupérer la liste des terrains depuis la base de données
$terrains = $wpdb->get_results($wpdb->prepare("
    SELECT DISTINCT t.id, t.Nom
    FROM $matchs_table as m
    LEFT JOIN $terrains_table as t ON m.Terrains_id = t.id
    JOIN $poules_table as p ON m.Poules_ID = p.id
    WHERE p.Tournoi_id = %d
", $tournoi_id));

// Récupérer la liste des matchs depuis la base de données
$matchs_table = $wpdb->prefix . 'matchs';

function queryGenerator($tournoi_id, $poule_filter, $terrain_filter)
{
    global $wpdb;
    $matchs_table = $wpdb->prefix . 'matchs';
    $equipes_table = $wpdb->prefix . 'equipes';
    $poules_table = $wpdb->prefix . 'poules';
    $club_table = $wpdb->prefix . 'clubs';
    $terrains_table = $wpdb->prefix . 'terrains';

    $query = "
        SELECT
            m.id as id,
            m.Score_e1 as score_eq1,
            m.Score_e2 as score_eq2,
            m.Fairplay_e1 as fairplay_e1,
            m.Fairplay_e2 as fairplay_e2,
            eq1.Nom AS equipe1,
            eq1.ID AS id_equipe1,
            club1.Logo AS logo1,
            eq2.Nom AS equipe2,
            eq2.ID AS id_equipe2,
            club2.Logo AS logo2,
            t.Nom AS terrain,
            t.id AS terrainId,
            p.Nom AS poule,
            p.Tournoi_id AS tournoi_id,
            m.Horaire_depart AS horaire_depart,
            m.Temps AS temps
        FROM $matchs_table as m
        JOIN $equipes_table as eq1 ON m.Id_eq1 = eq1.id
        JOIN $club_table as club1 ON eq1.clubs_id = club1.id
        JOIN $equipes_table as eq2 ON m.Id_eq2 = eq2.id
        JOIN $club_table as club2 ON eq2.clubs_id = club2.id
        LEFT JOIN $terrains_table as t ON m.Terrains_id = t.id
        JOIN $poules_table as p ON m.Poules_ID = p.id
        WHERE p.Tournoi_id = %d
    ";

    if ($poule_filter) {
        $query .= $wpdb->prepare(" AND p.id = %d", $poule_filter);
    }

    if ($terrain_filter) {
        $query .= $wpdb->prepare(" AND t.id = %d", $terrain_filter);
    }

    return $wpdb->prepare($query, $tournoi_id);
}

$matchs = $wpdb->get_results(queryGenerator($tournoi_id, $poule_filter, $terrain_filter));

?>