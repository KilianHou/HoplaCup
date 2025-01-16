<?php

$tournoi_id   = isset($_GET['id']) ? intval($_GET['id']) : 0;

global $wpdb;

/**
 * Récupère le tournoi
 */
function get_tournoi($tournoi_id)
{
    global $wpdb;
    $table_tournoi = $wpdb->prefix . 'tournois';
    $query = $wpdb->prepare("SELECT * FROM $table_tournoi WHERE id = %d", $tournoi_id);
    $tournoi = $wpdb->get_row($query);
    if ($tournoi) {
        return $tournoi;
    } else {
        return false;
    }
}
$tournoi      = get_tournoi($tournoi_id);
$tournoi_name = isset($tournoi->Nom) ? $tournoi->Nom : '';



// Noms des tables
$table_poules = $wpdb->prefix . 'poules';
$table_matchs = $wpdb->prefix . 'matchs';



// Requête pour sélectionner toutes les poules du tournoi avec le nombre de matchs
$query = $wpdb->prepare(
    "SELECT p.ID as poule_id, p.Nom, p.Phase_id, p.Tournoi_id, COUNT(m.ID) as nombre_matchs, GROUP_CONCAT(m.ID) as match_ids
     FROM $table_poules p
     LEFT JOIN $table_matchs m ON p.ID = m.Poules_ID
     WHERE p.Tournoi_id = %d
     GROUP BY p.ID",
    $tournoi_id
);
$results = $wpdb->get_results($query);

// Initialiser l'array des phases
$phases = [];

if (!empty($results)) {
    foreach ($results as $result) {
        $poule_id = $result->poule_id;

        // Calculer le nombre d'équipes en fonction de la phase
        if ($result->Phase > 100) {
            $nombreEquipes = $result->nombre_matchs * 2; // 2 equipes par match en phase playoffs
        } else {
            // nombreMatchs = 0.5 * nombreEquipes * (nombreEquipes - 1)
            // nombreEquipes^2 - nombreEquipes - 2 * nombreMatchs = 0
            // Utiliser la formule pour déduire le nombre d'équipes à partir du nombre de matchs de la poule : 
            $a = 1;
            $b = -1;
            $c = -2 * $result->nombre_matchs;
            $nombreEquipes = intval((-$b + sqrt($b * $b - 4 * $a * $c)) / (2 * $a));
        }

        // Ajouter les données au tableau des phases
        if (!isset($phases[$result->Phase])) {
            $phases[$result->Phase] = [];
        }

        // Ajouter l'ID du match au tableau des matchs
        $match_ids = !empty($result->match_ids) ? explode(',', $result->match_ids) : [];

        $phases[$result->Phase][] = [
            'nompoule' => $result->Nom,
            'idpoule' => $result->poule_id,
            'nombreEquipes' => $nombreEquipes,
            'matchs' => $match_ids
        ];
    }

    // Trier les phases > 100 par nombre d'équipes descendant et gérer les finales
    foreach ($phases as $phase => &$poules) {
        if ($phase > 100) {
            // Trier par nombre d'équipes en descendant
            usort($poules, function ($a, $b) {
                if ($a['nombreEquipes'] != $b['nombreEquipes']) {
                    return $b['nombreEquipes'] - $a['nombreEquipes'];
                }
                return 0;
            });

            // Regrouper les poules commençant par "finale" et "petite finale"
            $final_poules = [];
            $half_final_poules = [];
            $other_poules = [];
            foreach ($poules as $poule) {
                if (stripos($poule['nompoule'], 'finale') === 0) {
                    $final_poules[] = $poule;
                } elseif (stripos($poule['nompoule'], 'petite finale') === 0) {
                    $half_final_poules[] = $poule;
                } else {
                    $other_poules[] = $poule;
                }
            }

            // Réaffecter les poules dans l'ordre correct
            $poules = array_merge($other_poules, $half_final_poules, $final_poules);
        }
    }
}

// Calculer et ajouter le total de matchs pour chaque phase
foreach ($phases as $phase_key => &$phase_poules) {
    $total_matchs = 0;
    foreach ($phase_poules as $poule) {
        $total_matchs += count($poule['matchs']);
    }
    // Créer un tableau associatif pour chaque phase
    $phases[$phase_key] = [
        'poules' => $phase_poules, // La clé 'poules' contient les données des poules
        'total_matchs' => $total_matchs // La clé 'total_matchs' contient le nombre total de matchs
    ];
}
//echo '<pre>' . print_r($phases, true) . '</pre>';




/**
 * Poules déjà mappées
 */
$poules_maps = array();
$results = $wpdb->get_results("
    SELECT id_poule_origin, id_poule_destination, classement_origin
    FROM {$wpdb->prefix}transferts_phases
    WHERE id_poule_destination IS NOT NULL
");
foreach ($results as $result) {
    $key = $result->classement_origin . '_' . $result->id_poule_origin;
    $poules_maps[$key] = $result->id_poule_destination;
}
// echo '<pre>' . print_r($poules_maps, true) . '</pre>';

/**
 * Matchs de playoffs déjà mappés
 */
$matchs_maps = array();
$results = $wpdb->get_results("
    SELECT id_poule_origin, id_match_destination, classement_origin
    FROM {$wpdb->prefix}transferts_phases
    WHERE id_match_destination IS NOT NULL
");
foreach ($results as $result) {
    $key = $result->classement_origin . '_' . $result->id_poule_origin;
    $matchs_maps[$key] = $result->id_match_destination;
}
//echo '<pre>' . print_r($matchs_maps, true) . '</pre>';
