<?php

    global $wpdb;

    $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Noms des tables
    $terrains_table = $wpdb->prefix . 'terrains';
    $tournois_terrains_table = $wpdb->prefix . 'tournois_terrains';
    $matchs_table = $wpdb->prefix . 'matchs';
    $equipes_table = $wpdb->prefix . 'equipes';
    $poules_table = $wpdb->prefix . 'poules';
    $matchs_transferts_phases = $wpdb->prefix . 'matchs_transferts_phases';
    $transferts_phases = $wpdb->prefix . 'transferts_phases';

    $terrains = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT t.id, t.nom
            FROM $terrains_table AS t
            INNER JOIN $tournois_terrains_table AS tt ON t.id = tt.terrain_id
            WHERE tt.tournoi_id = %d
            ",
            $tournoi_id
        )
    );

 // Traitement pour assigner un terrain à un match
if (isset($_POST['terrain_id']) && isset($_POST['match_id'])) {
    $match_id = intval($_POST['match_id']);
    $terrain_id = intval($_POST['terrain_id']);
    $tournoi_id = intval($tournoi_id); // Assurez-vous que $tournoi_id est défini dans le contexte

    if ($match_id && $terrain_id && $tournoi_id) {
        // Récupérer les informations du match actuel
        $current_match = $wpdb->get_row($wpdb->prepare(
            "SELECT Horaire_depart, Temps FROM $matchs_table WHERE ID = %d",
            $match_id
        ));

        if ($current_match) {
            $horaire_depart = strtotime($current_match->Horaire_depart);
            $horaire_fin = $horaire_depart + ($current_match->Temps * 60);

            // Vérifier les conflits avec d'autres matchs sur le même terrain et dans le même tournoi
            $conflicting_match = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) 
                 FROM $matchs_table AS m
                 JOIN $tournois_terrains_table AS tt ON tt.terrain_id = m.Terrains_id
                 WHERE tt.Tournoi_id = %d
                 AND m.Terrains_id = %d 
                 AND m.ID != %d
                 AND (
                     (UNIX_TIMESTAMP(m.Horaire_depart) < %d AND (UNIX_TIMESTAMP(m.Horaire_depart) + (m.Temps * 60)) > %d) OR
                     (%d < (UNIX_TIMESTAMP(m.Horaire_depart) + (m.Temps * 60)) AND %d > UNIX_TIMESTAMP(m.Horaire_depart))
                 )",
                $tournoi_id, $terrain_id, $match_id,
                $horaire_fin, $horaire_depart, // Le match actuel chevauche un autre match
                $horaire_depart, $horaire_fin  // Un autre match chevauche le match actuel
            ));

            if ($conflicting_match > 0) {
                echo "<div class='notice notice-error'><p>Un autre match est déjà prévu sur ce terrain pendant cet horaire pour le même tournoi.</p></div>";
                echo '<script type="text/javascript">window.location.reload();</script>';
                exit;
            }

            // Si aucun conflit, mettre à jour le terrain du match
            $wpdb->update(
                $matchs_table,
                ['Terrains_id' => $terrain_id],
                ['ID' => $match_id]
            );

            if ($wpdb->last_error) {
                echo json_encode(['success' => false, 'message' => 'Erreur : ' . $wpdb->last_error]);
            } else {
                echo "<div class='notice notice-success'><p>Le terrain a été assigné au match avec succès.</p></div>";
                echo '<script type="text/javascript">window.location.reload();</script>';
            }
        } else {
            echo "<div class='notice notice-error'><p>Erreur : Impossible de récupérer l'horaire ou la durée du match.</p></div>";
        }
    } else {
        echo "<div class='notice notice-error'><p>Terrain ou tournoi invalide.</p></div>";
    }
    exit;
}


    // Traitement pour désassocier un terrain d'un match
    if (isset($_POST['remove_terrain']) && isset($_POST['match_id'])) {
        $match_id = intval($_POST['match_id']);

        if ($match_id) {
            $wpdb->update(
                $matchs_table,
                array('Terrains_id' => null),
                array('id' => $match_id)
            );

            if ($wpdb->last_error) {
                $message = 'Erreur : ' . $wpdb->last_error;
                $message_type = 'notice-error';
            } else {
                $message = 'Le terrain a été désassocié du match avec succès.';
                $message_type = 'notice-success';
                echo '<script type="text/javascript">window.location.reload();</script>';
            }
        } else {
            $message = 'Erreur : Match invalide.';
            $message_type = 'notice-error';
        }
    }
    if (isset($_POST['modifier_horaire'])) {
        // Récupérer l'ID du match et l'horaire de départ
        $match_id = isset($_POST['match_id']) ? intval($_POST['match_id']) : 0;
        $nouvel_horaire = isset($_POST['horaire_depart']) ? sanitize_text_field($_POST['horaire_depart']) : '';
    
        if ($match_id && $nouvel_horaire) {
            global $wpdb;

            $table_matchs = $wpdb->prefix . 'matchs';
            $wpdb->update(
                $table_matchs,
                ['Horaire_depart' => $nouvel_horaire],
                ['ID' => $match_id],
                ['%s'],
                ['%d']
            );
        }
    }


    
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifierHeureDebut'])) {

    global $wpdb;

    $start_date = sanitize_text_field($_POST['heureDebut']);
    $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($tournoi_id <= 0) {
        echo "<div class='notice notice-error'><p>Erreur : ID du tournoi invalide.</p></div>";
        return;
    }

    // Préparation de la mise à jour dans la base de données.
    $table_tournois = $wpdb->prefix . 'tournois'; 
    $result = $wpdb->update(
        $table_tournois,
        array(
            'Date_debut' => $start_date,
        ),
        array('id' => $tournoi_id),
        array('%s'), 
        array('%d') 
    );

    // Vérifiez si la mise à jour a réussi.
    if ($result !== false) { 
        if ($result > 0) {
            echo "<div class='notice notice-success'><p>L'heure a été mise à jour avec succès.</p></div>";
        } else {
            echo "<div class='notice notice-error'><p>Aucune modification détectée.</p></div>";
        }
    } else {
        echo "<div class='notice notice-error'><p>Une erreur est survenue lors de la mise à jour.</p></div>";
    }
}


    function generer_horaires_tournoi($tournoi_id) {
        global $wpdb;
    
        $table_tournois = $wpdb->prefix . 'tournois';
        $tournoi = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT Date_debut, Heure_fin, Duree_matchs 
                 FROM $table_tournois 
                 WHERE ID = %d", 
                 $tournoi_id
            ), 
            ARRAY_A
        );
    
        if (!$tournoi) {
            echo "<div class='notice notice-error'><p>Tournoi introuvable.</p></div>";
            return;
        }
    
        $date_debut = new DateTime($tournoi['Date_debut']);
        $heure_fin = new DateTime($tournoi['Heure_fin']);
        $duree_match = intval($tournoi['Duree_matchs']); 
    
        $table_poules = $wpdb->prefix . 'poules';
        $table_matchs = $wpdb->prefix . 'matchs';
    
        $matchs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT m.ID, m.Poules_ID 
                 FROM $table_matchs m
                 INNER JOIN $table_poules p ON m.Poules_ID = p.ID
                 WHERE p.Tournoi_ID = %d", 
                 $tournoi_id
            )
        );
    
        if (empty($matchs)) {
            echo "<div class='notice notice-error'><p>Aucun match trouvé pour ce tournoi.</p></div>";
            return;
        }
    
        $current_time = clone $date_debut;
    
        foreach ($matchs as $match) {
            $horaire_depart = $current_time->format('Y-m-d H:i:s');
        
            $wpdb->update(
                $table_matchs,
                ['horaire_depart' => $horaire_depart],
                ['ID' => $match->ID],
                ['%s'],
                ['%d']
            );
        
            $current_time->add(new DateInterval('PT' . $duree_match . 'M'));
        
            if ($current_time->format('H:i:s') > $heure_fin->format('H:i:s')) {
                $date_debut->modify('+1 day');
        
                $current_time = clone $date_debut;
            }
        }
        
    
        echo "<div class='notice notice-success'><p>Les horaires ont été générés avec succès.</p></div>";
    }
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generer_horaires') {
        $tournoi_id = isset($_POST['tournoi_id']) ? intval($_POST['tournoi_id']) : 0;
        if ($tournoi_id > 0) {
            generer_horaires_tournoi($tournoi_id);
        } else {
            echo "<div class='notice notice-error'><p>ID du tournoi invalide.</p></div>";
        }
    }

    $matchsToDisplay = $wpdb->get_results(
        $wpdb->prepare("
            SELECT 
                m.id AS id,
                m.Temps AS duree,
                eq1.Nom AS equipe1, 
                eq2.Nom AS equipe2, 
                t.Nom AS terrain,
                t.id AS terrainId,
                p.Nom AS poule,
                m.horaire_depart,
                tp1.id_poule_origin AS id_poule_origin1,
                tp1.id_poule_destination AS id_poule_destination1,
                tp1.classement_origin AS classement_origin1,
                p_origin1.Nom AS poule_origin_nom1,
                tp2.id_poule_origin AS id_poule_origin2,
                tp2.id_poule_destination AS id_poule_destination2,
                tp2.classement_origin AS classement_origin2,
                p_origin2.Nom AS poule_origin_nom2
            FROM $matchs_table AS m
            LEFT JOIN $equipes_table AS eq1 ON m.Id_eq1 = eq1.id
            LEFT JOIN $equipes_table AS eq2 ON m.Id_eq2 = eq2.id
            LEFT JOIN $terrains_table AS t ON m.Terrains_id = t.id
            JOIN $poules_table AS p ON m.Poules_ID = p.id
            LEFT JOIN $matchs_transferts_phases AS mtp1 ON mtp1.id_match = m.id
            LEFT JOIN $transferts_phases AS tp1 ON mtp1.id_transfert = tp1.ID
            LEFT JOIN $poules_table AS p_origin1 ON tp1.id_poule_origin = p_origin1.id
            LEFT JOIN $matchs_transferts_phases AS mtp2 ON mtp2.id_match = m.id AND mtp2.id_transfert != tp1.ID
            LEFT JOIN $transferts_phases AS tp2 ON mtp2.id_transfert = tp2.ID
            LEFT JOIN $poules_table AS p_origin2 ON tp2.id_poule_origin = p_origin2.id
            WHERE p.Tournoi_id = %d
            GROUP BY m.id
        ", $tournoi_id)
    );    

if (isset($_POST['action']) && $_POST['action'] === 'update_end_time') {
    $tournoiId = isset($_POST['tournoiId']) ? intval($_POST['tournoiId']) : 0;
    $heureFin = isset($_POST['heureFin']) ? sanitize_text_field($_POST['heureFin']) : null;

    if ($tournoiId > 0 && $heureFin) {
        global $wpdb;
        $table_tournois = $wpdb->prefix . 'tournois';

        $updated = $wpdb->update(
            $table_tournois,
            ['Heure_fin' => $heureFin],
            ['ID' => $tournoiId],
            ['%s'], // Format pour Heure_fin
            ['%d']  // Format pour l'ID
        );

        if ($updated !== false) {
            echo '<div class="notice notice-success">L\'heure de fin a été mise à jour avec succès.</div>';
        } else {
            echo '<div class="notice notice-error">Une erreur est survenue lors de la mise à jour de l\'heure de fin.</div>';
        }
    }
}

?>