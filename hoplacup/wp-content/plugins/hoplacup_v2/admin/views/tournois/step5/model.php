<?php 
    global $wpdb;

    $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
?>