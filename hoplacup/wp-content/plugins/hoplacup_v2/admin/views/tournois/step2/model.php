<?php
    // Récupérer l'ID du tournoi
    $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $phaseId = isset($_GET['phaseId']) ? intval($_GET['phaseId']) : null;
    $equipes = [];

    global $wpdb;

    $table_matchs = $wpdb->prefix . 'matchs';
    $table_transferts_phases = $wpdb->prefix . 'transferts_phases';
    $table_matchs_transferts_phases = $wpdb->prefix . 'matchs_transferts_phases';
    $table_poules = $wpdb->prefix . 'poules';

    // Fonction pour récupérer les informations du tournoi
    function get_tournoi($tournoi_id)
    {
        global $wpdb;
        $table_tournoi = $wpdb->prefix . 'tournois';
        $query = $wpdb->prepare("SELECT * FROM $table_tournoi WHERE id = %d", $tournoi_id);
        return $wpdb->get_row($query);
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

    if (isset($_POST['ajouterClassementMatch']) && isset($_POST['phaseId'])) {
        $phase_id = intval($_POST['phaseId']);
    
        $total_teams = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(pe.Equipes_id) 
             FROM {$wpdb->prefix}poules_equipes AS pe
             INNER JOIN {$wpdb->prefix}poules AS p ON pe.Poules_id = p.ID
             WHERE p.Tournoi_id = %d",
            $tournoi_id
        ));
    
        if ($total_teams && $total_teams > 1) {
            $num_matches = intval($total_teams / 2);
    
            // Générer les noms des poules (ex: 1/2, 3/4, 5/6, ...)
            $classement_poules = [];
            for ($i = 1; $i <= $num_matches; $i++) {
                $place1 = ($i * 2) - 1;
                $place2 = $i * 2;
                $classement_poules[] = "$place1/$place2";
            }
    
            foreach ($classement_poules as $nom_poule) {
                $wpdb->insert($table_poules, [
                    'Nom' => $nom_poule,
                    'Phase_id' => $phase_id,
                    'Type' => 'Classement',
                    'Tournoi_id' => $tournoi_id
                ]);
    
                if ($wpdb->last_error) {
                    echo "<div class='notice notice-error'><p>Erreur lors de l'ajout de la poule '$nom_poule' : " . $wpdb->last_error . "</p></div>";
                }
            }
    
            echo "<div class='notice notice-success'><p>Les matchs de classement ont été ajoutés avec succès.</p></div>";
        } else {
            echo "<div class='notice notice-error'><p>Impossible d'ajouter des matchs de classement : le tournoi doit avoir au moins 2 équipes associées.</p></div>";
        }
    }    

    // Récupérer le tournoi
    $tournoi = get_tournoi($tournoi_id);
    if (!$tournoi) {
        echo "L'élément n'existe pas.";
        wp_die();
    }

    if (isset($_POST['modifierPhase'])) {
        $phase_id = intval($_GET['phaseId']);
        $nouveau_nom_phase = sanitize_text_field($_POST['nomPhase']);

        $update_query = $wpdb->prepare(
            "UPDATE {$wpdb->prefix}phases SET Nom = %s WHERE ID = %d",
            $nouveau_nom_phase,
            $phase_id
        );

        if ($wpdb->query($update_query)) {
            echo "<div class='notice notice-success'><p>Nom de la phase mis à jour avec succès !</p></div>";
        } else {
            echo "<div class='notice notice-error'><p>Erreur lors de la mise à jour du nom de la phase.</p></div>";
        }
    }

    function get_positions_from_previous_phase($current_phase_id, $tournoi_id) {
        global $wpdb;
    
        // Récupérer toutes les phases pour le tournoi
        $phases = $wpdb->get_results($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}phases WHERE Tournoi_id = %d ORDER BY id ASC",
            $tournoi_id
        ));
    
        // Trouver l'index de la phase actuelle
        $current_index = null;
        foreach ($phases as $index => $phase) {
            if ($phase->id == $current_phase_id) {
                $current_index = $index;
                break;
            }
        }
    
        // Vérifier si une phase précédente existe
        if ($current_index === null || $current_index === 0) {
            return [];
        }
    
        $previous_phase_id = $phases[$current_index - 1]->id;
    
        // Récupérer les poules et leur nombre d'équipes pour la phase précédente
        $results = $wpdb->get_results($wpdb->prepare("
            SELECT 
                p.id AS poule_id, 
                p.Nom AS poule_nom, 
                COUNT(pe.Equipes_id) AS equipes_count
            FROM 
                {$wpdb->prefix}poules AS p
            LEFT JOIN 
                {$wpdb->prefix}poules_equipes AS pe ON pe.Poules_id = p.id
            WHERE 
                p.Phase_id = %d
            GROUP BY 
                p.id
            ORDER BY 
                p.id ASC",
            $previous_phase_id
        ));    
        $positions = [];
    
        foreach ($results as $result) {
            for ($position = 1; $position <= $result->equipes_count; $position++) {
                // Vérifier si l'équipe à cette position est déjà affectée
                $already_assigned = $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) 
                    FROM {$wpdb->prefix}transferts_phases 
                    WHERE id_poule_origin = %d AND classement_origin = %d AND id_poule_destination IS NOT NULL",
                    $result->poule_id,
                    $position
                ));
                
    
                // Si l'équipe est déjà assignée, la sauter
                if ($already_assigned > 0) {
                    continue;
                }
    
                // Générer le suffixe en fonction de la position
                $suffix = ($position == 1) ? "er" : "ème";
    
                $positions[] = [
                    'poule_nom' => $result->poule_nom,
                    'position' => $position . $suffix,
                    'poule_id' => $result->poule_id,
                ];
            }
        }

        if ($current_index > 1) {
            $transfer_results = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    p.id AS poule_id,
                    p.Nom AS poule_nom,
                    COUNT(tp.id_poule_destination) AS transfer_count
                FROM 
                    {$wpdb->prefix}poules AS p
                LEFT JOIN 
                    {$wpdb->prefix}transferts_phases AS tp ON tp.id_poule_destination = p.id
                WHERE 
                    p.Phase_id = %d
                GROUP BY 
                    p.id
                ORDER BY 
                    p.id ASC",
                $previous_phase_id
            ));
        
            foreach ($transfer_results as $transfer) {
                // Créer une entrée pour chaque position dans la poule
                for ($position = 1; $position <= $transfer->transfer_count; $position++) {

                    $already_assigned = $wpdb->get_var($wpdb->prepare("
                        SELECT COUNT(*) 
                        FROM {$wpdb->prefix}transferts_phases 
                        WHERE id_poule_origin = %d AND classement_origin = %d AND id_poule_destination IS NOT NULL",
                        $transfer->poule_id,
                        $position
                    ));
                    // Si l'équipe est déjà assignée, la sauter
                    if ($already_assigned > 0) {
                        continue;
                    }
                        // Ajouter le suffixe correct (1er, 2ème, etc.)
                        $suffix = ($position === 1) ? "er" : "ème";
            
                        // Ajouter la position dans la liste
                        $positions[] = [
                            'poule_nom' => $transfer->poule_nom,
                            'position' => $position . $suffix,
                            'poule_id' => $transfer->poule_id,
                        ];
                }
            }
        }
        return $positions;
    }
    

    $tournoi_name = $tournoi->Nom;

    // Vérifier si le tournoi contient déjà des matchs (indiquant qu'il est en cours de configuration)
    $matches_exist = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}matchs WHERE `Poules_ID` IN 
        (SELECT `ID` FROM {$wpdb->prefix}poules WHERE `Tournoi_id` = %d)",
        $tournoi_id
    )) > 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_phase'])) {
        $table_phases = $wpdb->prefix . 'phases';

        // Compter le nombre de phases existantes
        $total_phases = $wpdb->get_var("SELECT COUNT(*) FROM $table_phases WHERE `Tournoi_id` = $tournoi_id");

        if ($total_phases >= 10) {
            echo '<div class="notice notice-error"><p>Erreur : Vous ne pouvez pas ajouter plus de 10 phases.</p></div>';
        } else {
            // Ajouter une nouvelle phase vide
            $result = $wpdb->insert(
                $table_phases,
                ['Nom' => '', 'Tournoi_id' => $tournoi_id],
                ['%s', '%d']
            );

            if ($result === false) {
                echo '<div class="notice notice-error"><p>Erreur : Impossible d\'ajouter une nouvelle phase.</p></div>';
            } else {
                // Récupérer l'ID de la phase nouvellement créée
                $new_phase_id = $wpdb->insert_id;

                // Construire le nom de la phase
                $phase_name = 'Phase ' . ($total_phases + 1);

                // Mettre à jour le nom de la phase
                $wpdb->update(
                    $table_phases,
                    ['Nom' => $phase_name],
                    ['id' => $new_phase_id],
                    ['%s'],
                    ['%d']
                );

                echo '<div class="notice notice-success"><p>Phase ajoutée avec succès : ' . esc_html($phase_name) . '</p></div>';
            }
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
                "SELECT ID FROM {$wpdb->prefix}poules WHERE Nom = %s AND Tournoi_id = %d",
                $nomPoule,
                $tournoiId
            ));
    
            if ($existing_poule) {
                echo "<div class='notice notice-error'><p>Erreur : Une poule portant ce nom existe déjà dans ce tournoi.</p></div>";
            } else {
                // Ajouter la poule dans la base de données
                $insert_result = $wpdb->insert(
                    "{$wpdb->prefix}poules",
                    [
                        'Nom' => $nomPoule,
                        'Tournoi_id' => $tournoiId,
                        'Phase_id' => $phaseId
                    ],
                    ['%s', '%d', '%d']
                );
    
                if ($insert_result) {
                    echo "<div class='notice notice-success'><p>Poule ajoutée avec succès !</p></div>";
                } else {
                    echo "<div class='notice notice-error'><p>Erreur : L'ajout de la poule a échoué.</p></div>";
                }
            }
        }
    }
    
    

    if (isset($_POST['modifierPoule'])) {
        $poule_id = isset($_POST['poule_id']) ? intval($_POST['poule_id']) : 0;
        $nouveau_nom_poule = isset($_POST['nom_poule']) ? sanitize_text_field($_POST['nom_poule']) : '';
    
        if ($poule_id > 0 && !empty($nouveau_nom_poule)) {
            $update_query = $wpdb->prepare(
                "UPDATE {$wpdb->prefix}poules SET Nom = %s WHERE ID = %d",
                $nouveau_nom_poule,
                $poule_id
            );
    
            if ($wpdb->query($update_query)) {
                echo "<div class='notice notice-success'><p>Nom de la poule mis à jour avec succès !</p></div>";
            } else {
                echo "<div class='notice notice-error'><p>Erreur lors de la mise à jour du nom de la poule.</p></div>";
            }
        } else {
            echo "<div class='notice notice-error'><p>Erreur : Nom de la poule ou ID invalide.</p></div>";
        }
    }
    

    if (isset($_POST['modifierPhase'])) {
        $phase_id = intval($_GET['phaseId']);
        $nouveau_nom_phase = sanitize_text_field($_POST['nomPhase']);

        $update_query = $wpdb->prepare(
            "UPDATE {$wpdb->prefix}phases SET Nom = %s WHERE ID = %d",
            $nouveau_nom_phase,
            $phase_id
        );

        if ($wpdb->query($update_query)) {
            echo "<div class='notice notice-success'><p>Nom de la phase mis à jour avec succès !</p></div>";
        } 
    }

    $table_phases = $wpdb->prefix . 'phases';

    //DELETE des phases
    if (isset($_GET['del_phase'])) {
        $del_id = $_GET['del_phase'];
        $wpdb->delete(
            $table_phases,
            array('id' => $del_id),
            array('%d')
        );
    }

    // Récupérer toutes les phases
    $phases = $wpdb->get_results("SELECT id, Nom FROM {$wpdb->prefix}phases WHERE `Tournoi_id` = $tournoi_id ORDER BY id ASC");
    
    $selectedPhase = null;
    if($phaseId){
        // Chercher la phase correspondante
        foreach ($phases as $phase) {
            if ($phase->id == $phaseId) {
                $selectedPhase = $phase;
                break;
            }
        }
    }else{
        $selectedPhase = $phases[0];
        $phaseId = $selectedPhase->id;
    }

    $organizedPoules = [];

    if($phaseId){
        // Récupérer les poules d'une phase
        $poules = $wpdb->get_results(
            $wpdb->prepare("
                SELECT 
                    p.id AS id_poule, 
                    p.Nom AS nom_poule, 
                    p.Type AS type_poule,
                    GROUP_CONCAT(e.Nom SEPARATOR ',') AS equipes
                FROM 
                    {$wpdb->prefix}poules AS p
                LEFT JOIN 
                    {$wpdb->prefix}poules_equipes AS pe ON pe.Poules_id = p.id
                LEFT JOIN 
                    {$wpdb->prefix}equipes AS e ON pe.equipes_id = e.id
                WHERE 
                    p.Tournoi_id = %d
                    AND p.Phase_id = {$phaseId}
                GROUP BY 
                    p.id
            ", $tournoi_id)
        );

        // Transformer les résultats pour créer des tableaux d'équipes
        foreach ($poules as $poule) {
            $organizedPoules[] = [
                'id_poule' => $poule->id_poule,
                'nom_poule' => $poule->nom_poule,
                'type_poule' => $poule->type_poule,
                'equipes' => $poule->equipes ? explode(',', $poule->equipes) : []
            ];
        }
    }

    if(isset($_POST['matchesGeneration'])){
        $matches = $wpdb->get_var(
            $wpdb->prepare("
                SELECT COUNT(m.id) 
                FROM {$wpdb->prefix}matchs as m 
                JOIN {$wpdb->prefix}poules as p ON m.Poules_ID = p.id
                JOIN {$wpdb->prefix}tournois as t ON p.Tournoi_id = t.id
                WHERE t.id = %d
            ", $tournoi_id)
        );

        // Si les matchs sont déjà créés, on ne les recrés pas
        if($matches) echo '<script>window.location.href="?page=hoplacup-v2-settings&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=3";</script>';
        else{
            // Récupération des informations nécessaires à la création des matchs
            $poules = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT 
                        p.id AS id_poule, 
                        p.Nom AS nom_poule, 
                        GROUP_CONCAT(e.id SEPARATOR ',') AS equipes,
                        GROUP_CONCAT(tp.id SEPARATOR ',') AS transferts
                    FROM 
                        {$wpdb->prefix}poules AS p
                    LEFT JOIN 
                        {$wpdb->prefix}poules_equipes AS pe ON pe.Poules_id = p.id
                    LEFT JOIN 
                        {$wpdb->prefix}equipes AS e ON pe.equipes_id = e.id
                    LEFT JOIN
                        {$wpdb->prefix}transferts_phases AS tp ON p.id = tp.id_poule_destination
                    WHERE 
                        p.Tournoi_id = %d
                    GROUP BY 
                        p.id
                ", $tournoi_id)
            );
    
            // Utilisée dans l'ajout des matchs de transferts
            $poulesIds = [];

            // Les matchs à insérer seront tous mis dans cette variable afin de n'effectuer qu'une seule requête (en gros on les ajoutes tous en même temps)
            $matchesToSave;
            foreach($poules as $poule){
                // Pour les matchs de transferts
                array_push($poulesIds, $poule->id_poule);

                // Création d'un tableau d'équipes et de transferts
                $teams = $poule->equipes ? explode(',', $poule->equipes) : [];
                $transfers = $poule->transferts ? explode(',', $poule->transferts) : [];

                // On met les matchs à ajoutés dans $matchesToSave
                if(count($teams) != 0){
                    while(count($teams) > 1){
                        for($i=1; $i < count($teams); $i++){
                            $matchesToSave[] = $wpdb->prepare(
                                "(%d, %d, %d, %d)",
                                $poule->id_poule,
                                $tournoi->Duree_matchs,
                                $teams[0],
                                $teams[$i]
                            );
                        }
        
                        array_shift($teams);
                    }
                }else {
                    while(count($transfers) > 1){
                        for($i=1; $i < count($transfers); $i++){
                            // On ajoute des matchs sans équipes
                            $matchesToSave[] = $wpdb->prepare(
                                "(%d, %d, %s, %s)",
                                $poule->id_poule,
                                $tournoi->Duree_matchs,
                                'NULL',
                                'NULL'
                            );
                        }
        
                        array_shift($transfers);
                    }
                }
            }

            // Création de tous les matchs de $matchesToSave

            $matchesQuery = "INSERT INTO $table_matchs (Poules_ID, Temps, Id_eq1, Id_eq2) VALUES " . implode(', ', $matchesToSave);
            // Remplacement des string 'NULL' par le type NULL (la fonction prepare ne permet pas de mettre un type NULL)
            $matchesQuery = str_replace("'NULL'", 'NULL', $matchesQuery);
            $matchesResult = $wpdb->query($matchesQuery);

            // MATCHS DE TRANSFERTS

            $matchesWithoutTeams = $wpdb->get_var("
                SELECT GROUP_CONCAT(tm.ID) as ids
                FROM $table_matchs as tm
                JOIN $table_poules as p ON tm.poules_id = p.id
                WHERE id_eq1 IS NULL
                AND p.Tournoi_id = $tournoi_id
            ");
            $matchesIds = $matchesWithoutTeams ? explode(',', $matchesWithoutTeams) : [];

            $poulesIdsString = implode(',', $poulesIds);
            $nextTransfers = $wpdb->get_results("
                SELECT GROUP_CONCAT(id) AS ids, id_poule_destination
                FROM $table_transferts_phases
                WHERE id_poule_origin IN ($poulesIdsString)
                GROUP BY id_poule_destination
            ");

            $transfersToSave = [];
            
            foreach($nextTransfers as $pouleTransfer){
                $transfersIds = $pouleTransfer->ids ? explode(',', $pouleTransfer->ids) : [];

                while(count($transfersIds) > 1){
                    for($i=1; $i < count($transfersIds); $i++){
                        $transfersToSave[] = $wpdb->prepare(
                            "(%d, %d)",
                            $transfersIds[0],
                            $matchesIds[0]
                        );
                        $transfersToSave[] = $wpdb->prepare(
                            "(%d, %d)",
                            $transfersIds[$i],
                            $matchesIds[0]
                        );

                        array_shift($matchesIds);
                    }
    
                    array_shift($transfersIds);
                }
            }

            // Création de tous les matchs de transferts de $tranfersToSave

            if(count($transfersToSave) > 0){
                $transfersQuery = "INSERT INTO $table_matchs_transferts_phases (id_transfert, id_match) VALUES " . implode(', ', $transfersToSave);
                $transfersResult = $wpdb->query($transfersQuery);
            } else $transfersResult = true;

            if ($matchesResult && $transfersResult) echo '<div class="notice notice-success"><p>Matchs créés !</p></div> <script>window.location.href="?page=hoplacup-v2-settings&view=tournois&subview=item&id=' . $tournoi_id . '&configstep=3";</script>';
            else echo '<div class="notice notice-error"><p>Erreur : Les matchs de la phase initiale n\'ont pas été créés.</p></div>';
        }
    }
?>
