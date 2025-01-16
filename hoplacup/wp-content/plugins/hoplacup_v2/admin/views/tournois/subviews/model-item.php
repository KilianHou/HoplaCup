<?php

// steps complétés
// 0 -> non traité
// 1 -> todo/en cours
// 2 -> done
$step1 = 0;
$step2 = 0;
$step3 = 0;
$step4 = 0;
$step5 = 0;


$tournoi_id   = isset($_GET['id']) ? intval($_GET['id']) : 0;

global $wpdb;

$phase_init_query = $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d LIMIT 1", $tournoi_id);
$phase_init_ID = $wpdb->get_var($phase_init_query);

// UPDATE le nom d'un tournoi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_tournoi_name') {
    $tournoi_id = intval($_POST['tournoiId']);
    $nouveau_nom = sanitize_text_field($_POST['nomTournoi']);

    if (!empty($tournoi_id) && !empty($nouveau_nom)) {
        global $wpdb; 

        $table_name = $wpdb->prefix . 'tournois';

        $updated = $wpdb->update(
            $table_name,
            ['Nom' => $nouveau_nom], 
            ['ID' => $tournoi_id],
            ['%s'],
            ['%d']  
        );

        if ($updated !== false) {
            echo "<div class='notice notice-success'>Le nom du tournoi a été mis à jour avec succès.</div>";
        } else {
            echo "<div class='notice notice-error'>Erreur : Impossible de mettre à jour le nom du tournoi.</div>";
        }
    } else {
        echo "<div class='notice notice-error'>Erreur : Tous les champs sont requis.</div>";
    }
}

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
if (!isset($tournoi->ID)) {
    echo "L'élément n'existe pas";
    wp_die();
}




/**
 * 
 * CHECK STEP 1
 * 
 * Vérifie si le tournoi contient déjà au moins une poule avec au moins deux équipes
 */

$equipesNumber = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(pe.Equipes_ID)
        FROM {$wpdb->prefix}poules as p
        JOIN {$wpdb->prefix}poules_equipes as pe ON p.id = pe.Poules_ID
        WHERE Tournoi_id = %d
        LIMIT 2
    ", $tournoi_id));

$datesTournoi = $wpdb->get_row($wpdb->prepare("
        SELECT Date_debut, Date_fin 
        FROM {$wpdb->prefix}tournois WHERE id = %d
        ", $tournoi_id)
);

$terrain_associated = $wpdb->get_var(
    $wpdb->prepare("
        SELECT COUNT(*) 
        FROM {$wpdb->prefix}tournois_terrains 
        WHERE tournoi_id = %d
    ", $tournoi_id)
);

if ($equipesNumber > 1 && $terrain_associated && $datesTournoi->Date_debut && $datesTournoi->Date_fin) {
    $step1 = 1; // etape1 done
    $step2 = 1;
} else {
    $step1 = 1; // etape1 todo
    $step2 = 0;
}

/**
 * 
 * CHECK STEP 2
 * 
 * Vérifie si le tournoi contient déjà des matchs
 */

if($step2 == 1){
    $matches = $wpdb->get_var(
        $wpdb->prepare("
            SELECT COUNT(m.id) 
            FROM {$wpdb->prefix}matchs as m 
            JOIN {$wpdb->prefix}poules as p ON m.Poules_ID = p.id
            JOIN {$wpdb->prefix}tournois as t ON p.Tournoi_id = t.id
            WHERE t.id = %d
        ", $tournoi_id)
    );

    if($matches){
        $step1 = 1; // etape1 done
        $step2 = 1; // etape2 done
        $step3 = 1;
        $step4 = 1;
        $step5 = 1;

    } else{
        $step1 = 1;
        $step2 = 1; // etape1 todo
        $step3 = 0;
    }
}



/**
 * CHECK STEP 3
 * 
 * Vérifie si tous les transferts d'équipes à travers les phases du tournoi est complet
 * si soit id_poule_destination, soit id_match_destination est défini, le mapping est complet
 */

// global $wpdb;

// $query = $wpdb->prepare("
//     SELECT COUNT(*) as total,
//            SUM(CASE WHEN tp.id_poule_destination IS NOT NULL OR tp.id_match_destination IS NOT NULL THEN 1 ELSE 0 END) as valid_count
//     FROM {$wpdb->prefix}transferts_phases tp
//     LEFT JOIN {$wpdb->prefix}poules p_origin ON tp.id_poule_origin = p_origin.ID
//     LEFT JOIN {$wpdb->prefix}matchs m_origin ON tp.id_match_origin = m_origin.ID
//     LEFT JOIN {$wpdb->prefix}poules p_match ON m_origin.Poules_ID = p_match.ID
//     WHERE p_origin.Tournoi_id = %d OR p_match.Tournoi_id = %d
// ", $tournoi_id, $tournoi_id);

// $result = $wpdb->get_row($query);

// if ($result->total == $result->valid_count) {
//     $step3 = 2; // etape3 done
// } else {
// }

/**
 * 
 * CHECK STEP 3
 * 
 * Vérifie si le tournoi contient une heure de début et de fin
 */

 if ($step3 == 1) {
    $result = $wpdb->get_row(
        $wpdb->prepare("
            SELECT Heure_fin
            FROM {$wpdb->prefix}tournois
            WHERE ID = %d
        ", $tournoi_id)
    );

    if ($result && $result->Heure_fin) {
        $step4 = 1; 
        $step5 = 1; 
    } else {
        $step4 = 0;
        $step5 = 0;
    }
}
?>
