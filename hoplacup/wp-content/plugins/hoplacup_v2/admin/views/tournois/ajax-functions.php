<?php

/**
 * 
 * Fonctions ajax
 * 
 */

function tournois_test_function()
{
    // Vérifie le nonce
    check_ajax_referer('hoplacup_v2_nonce', 'nonce');

    $message = $_POST['message']; // Récupère le contenu de message depuis $_POST
    wp_send_json_success('Return test 1 avec le message : ' . $message);
    wp_die();
}

function ajax_check_DateTournoi() {
    if (!isset($_POST['tournoi_id'])) {
        wp_send_json_error(array('status' => 'error', 'message' => 'Missing parameters.'));
    }

    global $wpdb; // Declare the global $wpdb variable

    $tournoi_id = intval($_POST['tournoi_id']);
    $query = "SELECT Date_debut, Date_fin FROM {$wpdb->prefix}tournois WHERE id = %d";
    $dates = $wpdb->get_row($wpdb->prepare($query, $tournoi_id));

    if (is_null($dates->Date_debut) || is_null($dates->Date_fin)) {
        wp_send_json_error(array('status' => 'error', 'message' => 'One or both dates are null'));
    } else {
        wp_send_json_success(array('status' => 'success', 'message' => 'Both dates exist'));
    }
}

function ajax_save_match_result() {
    if (!isset($_POST['match_id']) || !isset($_POST['score_eq1']) || !isset($_POST['score_eq2'])) {
        wp_send_json_error(array('status' => 'error', 'message' => 'Missing parameters.'));
    }
    $match_id = intval($_POST['match_id']);
    $score_eq1 = intval($_POST['score_eq1']);
    $score_eq2 = intval($_POST['score_eq2']);

    global $wpdb;

    $result = $wpdb->update(
        "{$wpdb->prefix}matchs",
        array(
            'Score_e1' => $score_eq1,
            'Score_e2' => $score_eq2
        ),
        array(
            'id' => $match_id
        ),
        array(
            '%d',
            '%d'
        ),
        array(
            '%d'
        )
    );

    if ($result !== false) {
        wp_send_json_success(array('status' => 'success', 'message' => 'Match results saved successfully.'));
    } else {
        wp_send_json_error(array('status' => 'error', 'message' => 'Error saving match results.'));
    }
}

function ajax_poule_equipe_remove() {
    // Validate input data
    if (!isset($_POST['equipe_id'])) {
        wp_send_json_error(array('status' => 'error', 'message' => 'Missing parameters.'));
    }
    $equipe_id = intval($_POST['equipe_id']);
    echo ("ID de l'équipe : " . $equipe_id . "\n");

    global $wpdb;

    // Check if the equipe_id already exists in the poules_equipes table
    $existing_entry = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}poules_equipes WHERE Equipes_ID = %d",
            $equipe_id
        )
    );

    if ($existing_entry) {
        // Update the existing entry
        $wpdb->delete(
            "{$wpdb->prefix}poules_equipes",
            array(
                'Equipes_ID' => $equipe_id
            )
        );
        wp_send_json_success(array('status' => 'success', 'message' => "L'équipe a été enlever de la poule avec succès."));
    } else {
        wp_send_json_error(array('status' => 'error', 'message' => "L'équipe n'est pas dans la poule."));
    }
}

function ajax_poule_equipe_add_update() {
    // Validate input data
    if (!isset($_POST['equipe_id']) || !isset($_POST['poule_id'])) {
        wp_send_json_error(array('status' => 'error', 'message' => 'Missing parameters.'));
    }

    $equipe_id = intval($_POST['equipe_id']);
    $poule_id = intval($_POST['poule_id']);

    echo ("ID de l'équipe : " . $equipe_id . "\n");
    echo ("ID de la poule : " . $poule_id . "\n");

    global $wpdb;

    // Check if the equipe_id already exists in the poules_equipes table
    $existing_entry = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}poules_equipes WHERE Equipes_ID = %d",
            $equipe_id
        )
    );

    if ($existing_entry) {
        // Update the existing entry
        $wpdb->update(
            "{$wpdb->prefix}poules_equipes",
            array(
                'Poules_ID' => $poule_id
            ),
            array(
                'Equipes_ID' => $equipe_id
            )
        );
    } else {
        // Insert a new entry
        $wpdb->insert(
            "{$wpdb->prefix}poules_equipes",
            array(
                'Equipes_ID' => $equipe_id,
                'Poules_ID' => $poule_id
            ),
            array(
                '%d',
                '%d'
            )
        );
    }

    wp_send_json_success(array('status' => 'success', 'message' => 'Equipe updated/inserted successfully.'));
}

function ajax_transfer_placeholder() {
    // Vérifier la présence des données nécessaires
    if (!isset($_POST['poule_origin_id']) || !isset($_POST['classement_origin']) || !isset($_POST['poule_destination_id'])) {
        wp_send_json_error(array('status' => 'error', 'message' => 'Missing parameters.'));
    }

    $poule_origin_id = intval($_POST['poule_origin_id']);
    $classement_origin = intval($_POST['classement_origin']);
    $poule_destination_id = intval($_POST['poule_destination_id']);

    global $wpdb;

    // Vérifier si une entrée existe déjà pour ce classement
    $existing_entry = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}transferts_phases WHERE id_poule_origin = %d AND classement_origin = %d",
            $poule_origin_id,
            $classement_origin
        )
    );

    if ($existing_entry) {
        // Mettre à jour l'entrée existante
        $wpdb->update(
            "{$wpdb->prefix}transferts_phases",
            array('id_poule_destination' => $poule_destination_id),
            array(
                'id_poule_origin' => $poule_origin_id,
                'classement_origin' => $classement_origin
            ),
            array('%d'),
            array('%d', '%d')
        );
    } else {
        // Créer une nouvelle entrée
        $wpdb->insert(
            "{$wpdb->prefix}transferts_phases",
            array(
                'id_poule_origin' => $poule_origin_id,
                'classement_origin' => $classement_origin,
                'id_poule_destination' => $poule_destination_id
            ),
            array('%d', '%d', '%d')
        );
    }

    wp_send_json_success(array('status' => 'success', 'message' => 'Transfer successfully recorded.'));
}

function ajax_reset_placeholder() {
    global $wpdb;

    // Vérifiez les données envoyées
    $poule_origin_id = intval($_POST['poule_origin_id']);
    $classement_origin = intval($_POST['classement_origin']);

    if ($poule_origin_id > 0 && $classement_origin > 0) {
        // Supprimer l'enregistrement correspondant dans la table intermédiaire
        $deleted = $wpdb->delete(
            "{$wpdb->prefix}transferts_phases",
            [
                'id_poule_origin' => $poule_origin_id,
                'classement_origin' => $classement_origin
            ],
            ['%d', '%d']
        );

        if ($deleted !== false) {
            wp_send_json_success("Enregistrement supprimé.");
        } else {
            wp_send_json_error("Erreur lors de la suppression.");
        }
    } else {
        wp_send_json_error("Paramètres invalides.");
    }
}

function ajax_check_transfer_phase() {
    global $wpdb;

    // Récupération des données transmises via AJAX
    $poule_destination_id = isset($_POST['poule_destination_id']) ? intval($_POST['poule_destination_id']) : 0;
    $classement_origin = isset($_POST['classement_origin']) ? intval($_POST['classement_origin']) : 0;
    $poule_origin_id = isset($_POST['poule_origin_id']) ? intval($_POST['poule_origin_id']) : 0;


    $table_name = $wpdb->prefix . 'transferts_phases';
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name 
         WHERE id_poule_destination = %d 
           AND classement_origin = %d 
           AND id_poule_origin = %d",
        $poule_destination_id, $classement_origin, $poule_origin_id
    ));

    if ($exists) {
        $deleted = $wpdb->delete(
            $table_name,
            [
                'id_poule_destination' => $poule_destination_id,
                'classement_origin' => $classement_origin,
                'id_poule_origin' => $poule_origin_id,
            ],
            ['%d', '%d', '%d']
        );

        if ($deleted) {
            wp_send_json_success('Entry successfully deleted.');
        } else {
            wp_send_json_error('Error deleting entry.');
        }
    } else {
        wp_send_json_error('No matching entry found.');
    }
}

function ajax_save_match_fairplay_eq1() {
    if (!isset($_POST['match_id']) || !isset($_POST['fairplay_eq1'])) {
        wp_send_json_error(array('status' => 'error', 'message' => 'Missing parameters.'));
    }
    $match_id = intval($_POST['match_id']);
    $fairplay_eq1 = intval($_POST['fairplay_eq1']);
    $id_equipe1 = intval($_POST['id_equipe1']);
    $tournoi_id = intval($_POST['tournoi_id']);


    global $wpdb;

    $result = $wpdb->update(
        "{$wpdb->prefix}matchs",
        array(
            'Fairplay_e1' => $fairplay_eq1,
        ),
        array(
            'id' => $match_id
        ),
        array(
            '%d',
            '%d'
        ),
        array(
            '%d'
        )
    );

    // Vérifier et mettre à jour ou insérer dans la table "fairplay"
$existing_points = $wpdb->get_var($wpdb->prepare(
    "SELECT Points_Fairplay FROM {$wpdb->prefix}fairplay WHERE Equipe_id = %d AND Tournoi_id = %d",
    $id_equipe1,
    $tournoi_id
));

if ($existing_points !== null) {
    // Si l'équipe existe, ajouter les points
    $wpdb->update(
        "{$wpdb->prefix}fairplay",
        array(
            'Points_Fairplay' => $existing_points + $fairplay_eq1,
        ),
        array(
            'Equipe_id' => $id_equipe1,
            'Tournoi_id' => $tournoi_id,
        ),
        array(
            '%d',
        ),
        array(
            '%d',
            '%d',
        )
    );
} else {
    // Si l'équipe n'existe pas, insérer une nouvelle ligne
    $wpdb->insert(
        "{$wpdb->prefix}fairplay",
        array(
            'Equipe_id' => $id_equipe1,
            'Tournoi_id' => $tournoi_id,
            'Points_Fairplay' => $fairplay_eq1,
        ),
        array(
            '%d',
            '%d',
            '%d',
        )
    );
}

    if ($result !== false) {
        wp_send_json_success(array('status' => 'success', 'message' => 'Fairplay equip 1 saved successfully.'));
    } else {
        wp_send_json_error(array('status' => 'error', 'message' => 'Error saving fairplay for equip 1.'));
    }
}

function ajax_save_match_fairplay_eq2() {
    if (!isset($_POST['match_id']) || !isset($_POST['fairplay_eq2'])) {
        wp_send_json_error(array('status' => 'error', 'message' => 'Missing parameters.'));
    }
    $match_id = intval($_POST['match_id']);
    $fairplay_eq2 = intval($_POST['fairplay_eq2']);
    $id_equipe2 = intval($_POST['id_equipe2']);
    $tournoi_id = intval($_POST['tournoi_id']);

    global $wpdb;

    $result = $wpdb->update(
        "{$wpdb->prefix}matchs",
        array(
            'Fairplay_e2' => $fairplay_eq2,
        ),
        array(
            'id' => $match_id
        ),
        array(
            '%d',
            '%d'
        ),
        array(
            '%d'
        )
    );

        // Vérifier et mettre à jour ou insérer dans la table "fairplay"
$existing_points = $wpdb->get_var($wpdb->prepare(
    "SELECT Points_Fairplay FROM {$wpdb->prefix}fairplay WHERE Equipe_id = %d AND Tournoi_id = %d",
    $id_equipe2,
    $tournoi_id
));

if ($existing_points !== null) {
    // Si l'équipe existe, ajouter les points
    $wpdb->update(
        "{$wpdb->prefix}fairplay",
        array(
            'Points_Fairplay' => $existing_points + $fairplay_eq2,
        ),
        array(
            'Equipe_id' => $id_equipe2,
            'Tournoi_id' => $tournoi_id,
        ),
        array(
            '%d',
        ),
        array(
            '%d',
            '%d',
        )
    );
} else {
    // Si l'équipe n'existe pas, insérer une nouvelle ligne
    $wpdb->insert(
        "{$wpdb->prefix}fairplay",
        array(
            'Equipe_id' => $id_equipe2,
            'Tournoi_id' => $tournoi_id,
            'Points_Fairplay' => $fairplay_eq2,
        ),
        array(
            '%d',
            '%d',
            '%d',
        )
    );
}

    if ($result !== false) {
        wp_send_json_success(array('status' => 'success', 'message' => 'Fairplay equip 2 saved successfully.'));
    } else {
        wp_send_json_error(array('status' => 'error', 'message' => 'Error saving fairplay for equip 1.'));
    }
}

function ajax_update_classement() {
    global $wpdb;

    $tournoi_id = intval($_POST['tournoi_id']);

    $poules_table = $wpdb->prefix . 'poules';
    $equipes_table = $wpdb->prefix . 'equipes';
    $poules_equipes_table = $wpdb->prefix . 'poules_equipes';
    $fairplay_table = $wpdb->prefix . 'fairplay';

    $points = $wpdb->get_row(
        $wpdb->prepare("SELECT Points_Victoire, Points_Égalité, Points_Défaite 
                        FROM {$wpdb->prefix}points 
                        WHERE Tournoi_ID = %d", $tournoi_id)
    );



    $points_victoire = $points->Points_Victoire ?? 3;
    $points_egalite = $points->Points_Égalité ?? 1;
    $points_defaite = $points->Points_Défaite ?? 0;

    $poules = [];
    $scores = [];

    $query = $wpdb->prepare(
        "SELECT 
            p.ID AS poule_id, 
            p.Nom AS poule_nom,
            ph.Nom AS phase_nom,
            e.Nom AS equipe_nom,
            pe.Classement AS classement
        FROM {$wpdb->prefix}poules p
        LEFT JOIN {$wpdb->prefix}phases ph ON p.Phase_id = ph.ID 
        LEFT JOIN {$wpdb->prefix}poules_equipes pe ON p.ID = pe.Poules_ID
        LEFT JOIN {$wpdb->prefix}equipes e ON pe.Equipes_ID = e.ID
        WHERE p.Tournoi_id = %d
        ORDER BY p.Nom, pe.Classement ASC, e.Nom",
        $tournoi_id
    );

    $results = $wpdb->get_results($query);

    // Organisation des données avec phase
    foreach ($results as $row) {
        $poule_id = $row->poule_id;
        if (!isset($poules[$poule_id])) {
            $poules[$poule_id] = [
                'nom' => $row->poule_nom,
                'phase' => $row->phase_nom,
                'equipes' => []
            ];
        }
        if (!empty($row->equipe_nom)) {
            $poules[$poule_id]['equipes'][] = [
                'nom' => $row->equipe_nom,
                'classement' => $row->classement,
                'points' => 0,
                'buts_marques' => 0,
                'buts_encaisses' => 0,
                'difference_buts' => 0,
            ];
        }
    }

    if ($tournoi_id > 0) {
        // Requête SQL pour récupérer les données des poules, équipes, classements et points
        $query_result = $wpdb->prepare(
            "
            SELECT 
                pl.ID AS poule_id,
                pl.Nom AS poule_nom,
                ph.Nom AS phase_nom,
                e.Nom AS equipe_nom,
                e.ID AS equipe_id,
                pe.Classement AS classement,
                SUM(
                    CASE 
                        WHEN m.Score_e1 > m.Score_e2 AND m.Id_eq1 = e.ID THEN p.Points_Victoire
                        WHEN m.Score_e2 > m.Score_e1 AND m.Id_eq2 = e.ID THEN p.Points_Victoire
                        WHEN m.Score_e1 = m.Score_e2 THEN p.Points_Égalité
                        ELSE p.Points_Défaite
                    END
                ) AS points
            FROM 
                {$wpdb->prefix}poules pl
            LEFT JOIN 
                {$wpdb->prefix}poules_equipes pe ON pe.Poules_ID = pl.ID    
            LEFT JOIN 
                {$wpdb->prefix}phases ph ON pl.Phase_id = ph.ID 
            LEFT JOIN 
                {$wpdb->prefix}equipes e ON pe.Equipes_ID = e.ID
            LEFT JOIN 
                {$wpdb->prefix}matchs m ON (m.Id_eq1 = e.ID OR m.Id_eq2 = e.ID) AND m.Poules_ID = pl.ID
            LEFT JOIN 
                {$wpdb->prefix}points p ON p.Tournoi_ID = pl.Tournoi_ID
            WHERE 
                pl.Tournoi_ID = %d
            GROUP BY 
                pl.ID, e.ID
            ORDER BY 
                pl.ID, classement ASC, points DESC;
            ",
            $tournoi_id
        );

        // Exécuter la requête
        $resultats = $wpdb->get_results($query_result);

        // Organiser les résultats par poule
        $grouped_poules = [];
        foreach ($resultats as $row) {
            $grouped_poules[$row->poule_id]['nom'] = $row->poule_nom;
            $grouped_poules[$row->poule_id]['phase_nom'] = $row->phase_nom;
            $grouped_poules[$row->poule_id]['equipes'][] = [
                'equipe_id' => $row->equipe_id,
                'equipe_nom' => $row->equipe_nom,
                'classement' => $row->classement,
                'points' => $row->points,
            ];
        }

        // Calculer le classement pour chaque poule
        foreach ($grouped_poules as $poule_id => $poule_data) {
            $equipes = $poule_data['equipes'];

            // Trier les équipes de chaque poule
            usort($equipes, function ($a, $b) {
                if ($b['points'] === $a['points']) {
                    return $a['classement'] - $b['classement']; // Égalité : tri par classement initial
                }
                return $b['points'] - $a['points'];
            });

            // requête pour les buts marqués par équipe et pour le classement par poule
            $buts_marques_par_equipe = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT e.ID AS equipe_id, SUM(
                        CASE WHEN m.Id_eq1 = e.ID THEN m.Score_e1 ELSE 0 END
                    ) + SUM(
                        CASE WHEN m.Id_eq2 = e.ID THEN m.Score_e2 ELSE 0 END
                    ) AS buts_marques
                    FROM {$wpdb->prefix}equipes e
                    JOIN {$wpdb->prefix}matchs m ON e.ID = m.Id_eq1 OR e.ID = m.Id_eq2
                    JOIN {$wpdb->prefix}poules p ON m.Poules_ID = p.ID
                    WHERE p.Tournoi_ID = %d
                    GROUP BY e.ID
                ", $tournoi_id),
                ARRAY_A
            );

            $buts_par_equipe = [];
            foreach ($buts_marques_par_equipe as $buts) {
                $buts_par_equipe[$buts['equipe_id']] = $buts['buts_marques'];
            }

            // requête pour les buts encaissés
            $buts_encaisses_par_equipe = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT e.ID AS equipe_id, SUM(
                        CASE WHEN m.Id_eq1 = e.ID THEN m.Score_e2 ELSE 0 END
                    ) + SUM(
                        CASE WHEN m.Id_eq2 = e.ID THEN m.Score_e1 ELSE 0 END
                    ) AS buts_encaisses
                    FROM {$wpdb->prefix}equipes e
                    JOIN {$wpdb->prefix}matchs m ON e.ID = m.Id_eq1 OR e.ID = m.Id_eq2
                    JOIN {$wpdb->prefix}poules p ON m.Poules_ID = p.ID
                    WHERE p.Tournoi_ID = %d
                    GROUP BY e.ID
                ", $tournoi_id),
                ARRAY_A
            );

            $buts_encaisses_par_equipe_map = [];
            foreach ($buts_encaisses_par_equipe as $buts) {
                $buts_encaisses_par_equipe_map[$buts['equipe_id']] = $buts['buts_encaisses'];
            }

            foreach ($grouped_poules as &$poule) {
                foreach ($poule['equipes'] as &$equipe) {
                    $equipe_id = $equipe['equipe_id'];
                    $equipe['buts_marques'] = $buts_par_equipe[$equipe_id] ?? 0;
                    $equipe['buts_encaisses'] = $buts_encaisses_par_equipe_map[$equipe_id] ?? 0;
                    $equipe['goal_average'] = $equipe['buts_marques'] - $equipe['buts_encaisses']; // TODO : revoir ce calcul je ne suis pas sure
                }
            }
            unset($poule, $equipe);

            // Mettre à jour le classement pour chaque équipe dans la table poules_equipes
            $classement = 1;
            foreach ($equipes as $equipe) {
                $wpdb->update(
                    "{$wpdb->prefix}poules_equipes",
                    ['Classement' => $classement],
                    ['Equipes_ID' => $equipe['equipe_id'], 'Poules_ID' => $poule_id],
                    ['%d'],
                    ['%d', '%d']
                );
                $classement++;
            }
        }
    }

    $matches = $wpdb->get_results(
        $wpdb->prepare("SELECT Id_eq1, Id_eq2, Score_e1, Score_e2 
                        FROM {$wpdb->prefix}matchs 
                        WHERE Poules_ID IN (
                            SELECT ID FROM {$wpdb->prefix}poules WHERE Tournoi_ID = %d
                        )", $tournoi_id)
    );

    foreach ($matches as $match) {
        $equipe1 = $match->Id_eq1;
        $equipe2 = $match->Id_eq2;
        $score1 = $match->Score_e1;
        $score2 = $match->Score_e2;

        if (!isset($scores[$equipe1])) $scores[$equipe1] = 0;
        if (!isset($scores[$equipe2])) $scores[$equipe2] = 0;

        if ($score1 === null || $score2 === null) {

        } elseif ($score1 > $score2) {
            $scores[$equipe1] += $points_victoire;
            $scores[$equipe2] += $points_defaite;
        } elseif ($score1 < $score2) {
            $scores[$equipe2] += $points_victoire;
            $scores[$equipe1] += $points_defaite;
        } else {
            $scores[$equipe1] += $points_egalite;
            $scores[$equipe2] += $points_egalite;
        }
    }

    $equipes = $wpdb->get_results(
        "SELECT ID, Nom FROM {$wpdb->prefix}equipes", OBJECT_K
    );

    $classement = [];
    foreach ($scores as $equipe_id => $score) {
        $classement[] = [
            'nom' => $equipes[$equipe_id]->Nom ?? 'Équipe inconnue',
            'score' => $score
        ];
    }
        usort($classement, function ($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    // Mise à jour des classements dans la table wp_poules_equipes
    foreach ($classement as $position => $equipe) {
        $equipe_nom = $equipe['nom'];
        $classement_position = $position + 1; // Les classements commencent à 1

        // Récupérer l'ID de l'équipe à partir de son nom
        $equipe_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->prefix}equipes WHERE Nom = %s",
                $equipe_nom
            )
        );

        if ($equipe_id) {
            // Mettre à jour le classement dans wp_poules_equipes
            $wpdb->update(
                $wpdb->prefix . 'poules_equipes',
                ['Classement' => $classement_position],
                ['Equipes_ID' => $equipe_id],
                ['%d'],
                ['%d']
            );
        }
    }

    // Récupérer les classements fairplay
    $classement_fairplay = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT 
                e.Nom AS equipe_nom,
                SUM(fp.Points_Fairplay) AS points
            FROM 
                {$wpdb->prefix}equipes e
            LEFT JOIN 
                {$wpdb->prefix}fairplay fp ON e.ID = fp.equipe_ID
            WHERE 
                fp.Tournoi_ID = %d
            GROUP BY 
                e.ID
            ORDER BY 
                points DESC",
            $tournoi_id
        )
    );


}