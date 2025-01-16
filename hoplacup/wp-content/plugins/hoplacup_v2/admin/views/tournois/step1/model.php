<?php
global $wpdb;

$equipes = $wpdb->prefix . 'equipes';
$clubs = $wpdb->prefix . 'clubs';
$divisions = $wpdb->prefix . 'divisions';
$poules_equipes = $wpdb->prefix . 'poules_equipes';
$poules = $wpdb->prefix . 'poules';
$table_points = $wpdb->prefix . 'points';

$club_filter = isset($_GET['club_id']) ? intval($_GET['club_id']) : null;
$division_filter = isset($_GET['division_id']) ? intval($_GET['division_id']) : null;
$pays_filter = isset($_GET['pays_nom']) ? $_GET['pays_nom'] : null;
$ville_filter = isset($_GET['ville_nom']) ? $_GET['ville_nom'] : null;
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc';
$tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$current_page = isset($_GET['pages']) ? intval($_GET['pages']) : 1;

if ($tournoi_id > 0) {
    // Vérifier si des valeurs par défaut existent déjà pour ce tournoi
    $points_exist = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(*) FROM $table_points WHERE Tournoi_ID = %d", $tournoi_id)
    );

    // Si aucune valeur n'existe, insérer les valeurs par défaut
    if (!$points_exist) {
        $wpdb->insert(
            $table_points,
            [
                'Tournoi_ID' => $tournoi_id,
                'Points_Victoire' => 3, // Valeur par défaut pour une victoire
                'Points_Égalité' => 1, // Valeur par défaut pour une égalité
                'Points_Défaite' => 0, // Valeur par défaut pour une défaite
            ],
            ['%d', '%d', '%d', '%d']
        );
    }
}
if ($tournoi_id > 0) {
    // Vérifier si une phase avec l'ID 1 existe pour le tournoi
    $phase_exist = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d", $tournoi_id)
    );

    // Si la phase avec l'ID 1 n'existe pas, l'ajouter
    if (!$phase_exist) {
        $wpdb->insert(
            "{$wpdb->prefix}phases",
            [
                'Nom' => 'Phase initiale',
                'Tournoi_id' => $tournoi_id
            ],
            ['%s']
        );
    }
}

// CONFIGURER LA DURÉE DES MATCHS

$duree_match = $wpdb->get_var($wpdb->prepare(
    "SELECT Duree_matchs FROM {$wpdb->prefix}tournois WHERE ID = %d",
    $tournoi_id
));

if (!$duree_match) {
    $duree_match = 20; // Valeur par défaut de 20 minutes
}

if (isset($_POST['save_match_duration'])) {
    // Vérification de sécurité
    if (!isset($_POST['match_duration_nonce']) || !wp_verify_nonce($_POST['match_duration_nonce'], 'save_match_duration')) {
        wp_die('Échec de la vérification de sécurité');
    }

    $duree_match = intval($_POST['duree_match']);
    if ($duree_match > 0) {
        $updated = $wpdb->update(
            "{$wpdb->prefix}tournois",
            ['Duree_matchs' => $duree_match],
            ['ID' => $tournoi_id],
            ['%d'],
            ['%d']
        );

        // Vérifier si la mise à jour a réussi
        if ($updated !== false) {
            echo "<div class='notice notice-success'><p>Durée des matchs mise à jour avec succès !</p></div>";
        } else {
            echo "<div class='notice notice-error'><p>Erreur : Mise à jour de la durée des matchs a échoué. Vérifiez les paramètres de la base de données.</p></div>";
        }
    } else {
        echo "<div class='notice notice-error'><p>Erreur : Durée des matchs invalide.</p></div>";
    }
}


if (isset($_POST['save_match_duration'])) {
    // Vérification de sécurité
    if (!isset($_POST['match_duration_nonce']) || !wp_verify_nonce($_POST['match_duration_nonce'], 'save_match_duration')) {
        wp_die('Échec de la vérification de sécurité');
    }

    $duree_match = intval($_POST['duree_match']);
    if ($duree_match > 0) {
        $wpdb->update(
            "{$wpdb->prefix}tournois",
            ['Duree_matchs' => $duree_match],
            ['ID' => $tournoi_id],
            ['%d'],
            ['%d']
        );
        echo "<div class='notice notice-success'><p>Durée des matchs mise à jour avec succès !</p></div>";
    } else {
        echo "<div class='notice notice-error'><p>Erreur : Durée des matchs invalide.</p></div>";
    }
}

// Fetch existing poule-equipe relations
$pouleEquipeRelations = $wpdb->get_results("
    SELECT Equipes_ID, Poules_ID
    FROM $poules_equipes as pe
    JOIN $poules as p ON pe.Poules_ID = p.id
    WHERE p.Tournoi_id = $tournoi_id
");

$equipesInPoules = [];
foreach ($pouleEquipeRelations as $relation) {
    $equipesInPoules[$relation->Poules_ID][] = $relation->Equipes_ID;
}

// Include the teams already in the drop zones
$teamsInDropZones = array_merge(...array_values($equipesInPoules));

function queryGenerator($equipes, $clubs, $divisions, $teamsInDropZones, $club_filter = null, $division_filter = null, $sort_order = 'asc', $pays_filter = null, $ville_filter = null): string {
    $query = "SELECT e.id, e.Nom AS NomEquipe, c.Nom AS NomClub, d.Division AS NomDivision 
          FROM $equipes AS e 
          INNER JOIN $clubs AS c ON e.Clubs_id = c.id 
          LEFT JOIN $divisions AS d ON e.Divisions_id = d.id";

    if ($club_filter !== null && $club_filter != "0" || $division_filter !== null && $division_filter != "0" || $pays_filter !== null && $pays_filter != "0" || $ville_filter !== null && $ville_filter != "0") {
        $conditions = [];
        if ($club_filter) {
            $conditions[] = "e.Clubs_id = $club_filter";
        }
        if ($division_filter) {
            $conditions[] = "e.Divisions_id = $division_filter";
        }
        if ($pays_filter) {
            $conditions[] = "c.Pays LIKE '%$pays_filter%'";
        }
        if ($ville_filter) {
            $conditions[] = "c.Ville LIKE '%$ville_filter%'";
        }
        if (!empty($conditions)) {
            $query .= " WHERE (" . implode(" AND ", $conditions) . ")";
        }

        if (!empty($teamsInDropZones)) {
            $teamsInDropZones = implode(',', array_map('intval', $teamsInDropZones));
            $query .= !empty($conditions) ? " OR e.id IN ($teamsInDropZones)" : " WHERE e.id IN ($teamsInDropZones)";
        }
    }

    if ($sort_order === 'asc') {
        $query .= " ORDER BY e.Nom ASC";  // Croissant (A-Z)
    } elseif ($sort_order === 'desc') {
        $query .= " ORDER BY e.Nom DESC";  // Décroissant (Z-A)
    }
    return $query;
}


$query = queryGenerator($equipes, $clubs, $divisions, $teamsInDropZones, $club_filter, $division_filter, $sort_order,$pays_filter, $ville_filter);

$equipeResults = $wpdb->get_results($query);
$clubsList = $wpdb->get_results("SELECT id, Nom FROM $clubs");
$divisionsList = $wpdb->get_results("SELECT id, Division FROM $divisions");

$countriesList = $wpdb->get_col("SELECT DISTINCT Pays FROM {$wpdb->prefix}clubs");
$citiesList = $wpdb->get_col("SELECT DISTINCT Ville FROM {$wpdb->prefix}clubs");

$tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$teams_per_pages = 18;
$total_teams = count($equipeResults);
$total_pages = ceil($total_teams / $teams_per_pages);
$start_index = ($current_page - 1) * $teams_per_pages;
$pagninated_teams = array_slice($equipeResults, $start_index, $teams_per_pages);

// Filtrer les équipes disponibles
$equipesDisponibles = array_filter($equipeResults, function($equipe) use ($teamsInDropZones) {
    return !in_array($equipe->id, $teamsInDropZones);
});

// Calculer les équipes restantes et paginer
$total_teams = count($equipesDisponibles);
$total_pages = ceil($total_teams / $teams_per_pages);
$start_index = ($current_page - 1) * $teams_per_pages;
$pagninated_teams = array_slice($equipesDisponibles, $start_index, $teams_per_pages);

// Si la page est vide après filtration, ajuster vers une page valide
if (empty($pagninated_teams) && $current_page > 1) {
    $current_page--;
    $start_index = ($current_page - 1) * $teams_per_pages;
    $pagninated_teams = array_slice($equipesDisponibles, $start_index, $teams_per_pages);
}

//DELETE des poules
if (isset($_GET['del_poule'])) {
    $del_id = $_GET['del_poule'];
    $wpdb->delete(
        "{$wpdb->prefix}poules",
        array('ID' => $del_id),
        array('%d')
    );
}

// Mettre à jour le nom de la poule
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nomPoule = $_POST['nomPoule'];
    if (empty($nomPoule)) {
        echo "<div class='notice notice-error'><p>Erreur : Le nom de la poule ne peut pas être vide.</p></div>";
    } else{
        $wpdb->update(
            "{$wpdb->prefix}poules",
            array(
                'Nom' => $nomPoule,
            ),
            array('ID' => $id)
        );
    }
}

//DELETE des terrains dans tournois_terrains
if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $wpdb->delete(
        "{$wpdb->prefix}tournois_terrains",
        array('terrain_id' => $del_id),
        array('%d')
    );
}

function get_poules($tournoi_id){
    global $wpdb;

    // Récupérer la phase "phase initiale" pour le tournoi
    $phase_query = $wpdb->prepare(
                "SELECT ID FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d LIMIT 1", $tournoi_id
    );
    $phase_id = $wpdb->get_var($phase_query);

    // Si la phase existe, récupérer les poules associées à cette phase
    if ($phase_id) {
        $query = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}poules WHERE Phase_id = %d AND Tournoi_id = %d",
            $phase_id, $tournoi_id
        );
        $poules = $wpdb->get_results($query);

        return $poules ? $poules : false;
    } else {
        return false;
    }
}

if (isset($_POST['ajouterPoule'])) {
    $nomPoule = trim($_POST['nomPoule']);
    $tournoiId = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (empty($nomPoule) || $tournoiId === null) {
        echo "<div class='notice notice-error'><p>Erreur : Le nom de la poule est requis.</p></div>";
    } else {
        // Vérifier si une poule avec le même nom existe déjà pour le tournoi
        $existing_poule = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}poules WHERE Nom = %s AND Tournoi_id = %d",
            $nomPoule,
            $tournoiId
        ));

        if ($existing_poule > 0) {
            echo "<div class='notice notice-error'><p>Erreur : Une poule portant ce nom existe déjà.</p></div>";
        } else {
            // Récupérer l'ID de la phase "phase initiale"
            $phase_query = $wpdb->prepare(
                "SELECT ID FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d LIMIT 1", $tournoiId
            );
            $phase_id = $wpdb->get_var($phase_query);

            if ($phase_id) {
                try {
                    $wpdb->insert(
                        $wpdb->prefix . 'poules',
                        [
                            'Nom' => $nomPoule,
                            'Phase_id' => $phase_id,
                            'Tournoi_id' => $tournoiId
                        ],
                        ['%s', '%d', '%d']
                    );
                    echo "<div class='notice notice-success'><p>Poule ajoutée avec succès !</p></div>";
                } catch (Exception $e) {
                    echo "<div class='notice notice-error'><p>Erreur : " . esc_html($e->getMessage()) . "</p></div>";
                }
            } else {
                echo "<div class='notice notice-error'><p>Erreur : La phase 'phase initiale' n'existe pas pour ce tournoi.</p></div>";
            }
        }
    }
}



$poules = get_poules($tournoi_id);

$tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($tournoi_id > 0) {
    // Récupération des points actuels pour le tournoi.
    $points = $wpdb->get_row($wpdb->prepare(
        "SELECT Points_Victoire, Points_Égalité, Points_Défaite 
         FROM $table_points
         WHERE Tournoi_ID = %d",
        $tournoi_id
    ));

    // Si des points existent, les assigner aux variables.
    $points_win = $points ? $points->Points_Victoire : 3; // Par défaut 3.
    $points_draw = $points ? $points->Points_Égalité : 1; // Par défaut 1.
    $points_loss = $points ? $points->Points_Défaite : 0; // Par défaut 0.
} else {
    $points_win = 3; // Par défaut 3.
    $points_draw = 1; // Par défaut 1.
    $points_loss = 0; // Par défaut 0.
}

if (isset($_POST['modifierPhase'])) {
    $nouveau_nom_phase = sanitize_text_field($_POST['nomPhase']);

    // Mettre à jour le nom de la phase
    $update_query = $wpdb->prepare(
        "UPDATE {$wpdb->prefix}phases SET Nom = %s WHERE Tournoi_id = %d LIMIT 1",
        $nouveau_nom_phase,
        $tournoi_id
    );

    if ($wpdb->query($update_query)) {
        echo "<div class='notice notice-success'><p>Nom de la phase mis à jour avec succès !</p></div>";
    } else {
        echo "<div class='notice notice-error'><p>Erreur lors de la mise à jour du nom de la phase.</p></div>";
    }
}

// Fonction pour supprimer DEL de l'url
function remove_query_param($url, $key)
{
    $url_parts = parse_url($url);
    parse_str($url_parts['query'] ?? '', $query_params);
    unset($query_params[$key]); // Supprime le paramètre spécifié
    $new_query = http_build_query($query_params);
    return $url_parts['path'] . ($new_query ? '?' . $new_query : '');
}

$current_url = remove_query_param($_SERVER['REQUEST_URI'], 'del');

function get_terrain(){
    global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}terrains";
        $terrains = $wpdb->get_results($query);

        return $terrains ? $terrains : false;
}
$terrains = get_terrain();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouterTerrain'])) {
    global $wpdb;

    // Récupération et validation des données
    $terrain_id = isset($_POST['terrain_id']) ? intval($_POST['terrain_id']) : 0;
    $tournoi_id = isset($_POST['tournoi_id']) ? intval($_POST['tournoi_id']) : 0;

    if ($terrain_id > 0 && $tournoi_id > 0) {
        // Table associée
        $table_tournois_terrains = $wpdb->prefix . 'tournois_terrains';

        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_tournois_terrains WHERE tournoi_id = %d AND terrain_id = %d",
            $tournoi_id,
            $terrain_id
        ));
        
        if ($exists) {
            echo '<div class="notice notice-error">Ce terrain est déjà associé à ce tournoi.</div>';
        } else {
        // Insertion dans la table
        $insert_result = $wpdb->insert(
            $table_tournois_terrains,
            [
                'tournoi_id' => $tournoi_id,
                'terrain_id' => $terrain_id
            ],
            [
                '%d','%d' 
            ]
        );
        }

        if ($insert_result) {
            echo '<div class="notice notice-success">Terrain associé avec succès au tournoi.</div>';
        } else {
            echo '<div class="notice notice-error">Erreur lors de l\'association du terrain au tournoi.</div>';
        }
    } else {
        echo '<div class="notice notice-error">Données invalides. Veuillez réessayer.</div>';
    }
}

function get_terrains_tournoi($tournoi_id){
    global $wpdb;
        $query = $wpdb->prepare(
            "SELECT * 
            FROM {$wpdb->prefix}tournois_terrains tt
            JOIN {$wpdb->prefix}terrains t ON tt.terrain_id = t.ID
            WHERE tt.tournoi_id = %d", 
            $tournoi_id
        );
        
        // Exécuter la requête et récupérer les résultats
        $terrains_associes = $wpdb->get_results($query);

        return $terrains_associes ?: [];
}
?>