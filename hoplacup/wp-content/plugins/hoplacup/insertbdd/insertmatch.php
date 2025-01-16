<?php

require_once WP_CONTENT_DIR . '/plugins/hoplacup/includes/vendor/autoload.php';


function hoplacup_import_data_from_sheets()
{
    global $wpdb;

    // Id spreadSheet
    $spreadsheetId = '1rlqz3EgRJidmCzAVUD-CCGBkTbnI7T9MnLOBstLvXxI';

    // Ranges à récupérer
    $matchs_ranges = [ // [0] => [matches]
        // Phase 1 - Matchs vendredi
        'Aff_Programme!D12:J19', // T1 [0]
        'Aff_Programme!O12:U19', // T2 [1]
        'Aff_Programme!D24:J31', // T3 [2]
        // Phase 1 - Matchs samedi
        'Aff_Programme!D44:J55', // T1 [3] 
        'Aff_Programme!O44:U55', // T2 [4]
        'Aff_Programme!D60:J71', // T3 [5]
        // BRASSAGE - Phase 2 - Matchs samedi
        'Aff_Programme!D84:J95',  // T1 [6]
        'Aff_Programme!O84:U95',  // T2 [7]
        'Aff_Programme!D100:J111', // T3 [8]

        // Finales/playoffs à traiter
        'Aff_Programme!D122:K123', // [9] T1 Classement 5/8 Hamster Cup
        'Aff_Programme!D124:K125', // [10] T2 Classement 5/8 Hamster Cup

        'Aff_Programme!D127:K128', // [11] T3 1/2 Finale Hamster Cup
        'Aff_Programme!D132:K133', // [12] T1 1/2 Finale Hamster Cup

        'Aff_Programme!D135:K136', // [13] T2 Classement 5/8 Castor Cup
        'Aff_Programme!D137:K138', // [14] T3 Classement 5/8 Castor Cup

        'Aff_Programme!D142:K143', // [15] T1 1/2 Finale Castor Cup
        'Aff_Programme!D144:K145', // [16] T2 1/2 Finale Castor Cup

        'Aff_Programme!D147:K148', // [17] T3 Classement 5/8 Hopla Cup
        'Aff_Programme!D152:K153', // [18] T1 Classement 5/8 Hopla Cup

        'Aff_Programme!D155:K156', // [19] T2 1/2 Finale Hopla Cup
        'Aff_Programme!D157:K158', // [20] T3 1/2 Finale Hopla Cup

        'Aff_Programme!D167:K168', // [21] T1 Classement 7/8 Hamster Cup

        'Aff_Programme!D170:K171', // [22] T2 Classement 5/6 Hamster Cup

        'Aff_Programme!D173:K174', // [23] T3 Petite Finale Hamster Cup

        'Aff_Programme!D177:K178', // [24] T1 Finale Hamster Cup

        'Aff_Programme!D180:K181', // [25] T2 Classement 7/8 Castor Cup

        'Aff_Programme!D183:K184', // [26] T3 Classement 5/6 Castor Cup

        'Aff_Programme!D187:K188', // [27] T1 Petite Finale Castor Cup

        'Aff_Programme!D190:K191', // [28] T2 Finale Castor Cup

        'Aff_Programme!D193:K194', // [29] T3 Classement 7/8 Hopla Cup

        'Aff_Programme!D197:K198', // [30] T1 Classement 5/6 Hopla Cup

        'Aff_Programme!D200:K201', // [31] T3 Petite Finale Hopla Cup

        'Aff_Programme!D204:K205' // [32] T2 Finale Hopla Cup


    ];

    $poules_ranges = [ // [1] => Poules


        'Phase_1_Aff_Poules!F11:K16', // [0] classement poule A
        'Phase_1_Aff_Poules!D20:F34', // [1] matchs poule A

        'Phase_1_Aff_Poules!F61:K66', // [2] classement poule B
        'Phase_1_Aff_Poules!D70:F84', // [3] matchs poule B

        'Phase_1_Aff_Poules!F111:K116', // [4] classement poule C
        'Phase_1_Aff_Poules!D120:F134', // [5] matchs poule C

        'Phase_1_Aff_Poules!F161:K166', // [6] classement poule D
        'Phase_1_Aff_Poules!D170:F184', // [7] matchs poule D

        // CHANGEMENT DE FEUILLE - Poules brassage E F G H I J

        'Phase_2_Aff_Poules!F11:K14', // [8] classement poule E
        'Phase_2_Aff_Poules!D18:F23', // [9] matchs poule E

        'Phase_2_Aff_Poules!F41:K44', // [10] classement poule F
        'Phase_2_Aff_Poules!D48:F53', // [11] matchs poule F

        'Phase_2_Aff_Poules!F71:K74', // [12] classement poule G
        'Phase_2_Aff_Poules!D78:F83', // [13] matchs poule G

        'Phase_2_Aff_Poules!F101:K104', // [14] classement poule H
        'Phase_2_Aff_Poules!D108:F113', // [15] matchs poule H

        'Phase_2_Aff_Poules!F131:K134', // [16] classement poule I
        'Phase_2_Aff_Poules!D138:F143', // [17] matchs poule I

        'Phase_2_Aff_Poules!F161:K164', // [18] classement poule J
        'Phase_2_Aff_Poules!D168:F173', // [19] matchs poule J

    ];


    $classements_range = [ // [2] => classements

        'Class_Final_Aff!F9:G16', // [0] classement HoplaCup

        'Class_Final_Aff!F20:G27', // [1] classement CastorCup

        'Class_Final_Aff!F31:G38', // [2] classement HamsterCup

    ];

    $equipes_range = ['master!A2:B'];




    $client = new Google_Client(); // Initialisez le client Google Sheets
    $client->setApplicationName('Google Sheets PHP Example');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig(plugin_dir_path(__FILE__) . 'hoplacup-efd8edb379c1.json');
    $service = new Google_Service_Sheets($client);

    // Appel API pour les matchs et les poules
    $response = $service->spreadsheets_values->batchGet($spreadsheetId, [
        'ranges' => array_merge($matchs_ranges, $poules_ranges, $classements_range, $equipes_range)
    ]);

    // Tableaux pour stocker les données des matchs et des poules
    $matchs_data = [];
    $poules_data = [];
    $classements_data = [];
    $equipes_data = [];

    // Parcourir la réponse et séparer les données en fonction des plages
    foreach ($response['valueRanges'] as $key => $valueRange) {
        // Récupérer les données de la plage actuelle
        $data = $valueRange['values'];
        // Vérifier si la plage correspond aux matchs, aux poules ou aux classements
        if ($key < count($matchs_ranges)) {
            // Plage correspondant aux matchs
            $matchs_data[] = $data;
        } elseif ($key < count($matchs_ranges) + count($poules_ranges)) {
            // Plage correspondant aux poules
            $poules_data[] = $data;
        } elseif ($key < count($poules_ranges) + count($classements_range) + count($matchs_ranges)) {
            // Plage correspondant aux classements
            $classements_data[] = $data;
        } else {
            $equipes_data[] = $data;
        }
    }

    function remove_accents_and_lowercase($str)
    {
        // Conversion des caractères accentués en leur équivalent sans accent
        $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
        $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1', $str);
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

        // Conversion en minuscules
        $str = strtolower($str);

        return $str;
    }


    function clean_string($input)
    {
        //$lowercase = strtolower($input);
        //$transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', $lowercase);
        // Filtrer tous les caractères non alphanumériques après la translittération si iconv n'a pas la bonne configuration sur le serveur
        $cleaned = remove_accents_and_lowercase($input);
        $cleaned = str_replace([' ', '.', '-', '_', ' U13', 'U13', ' u13', 'u13'], '', $cleaned);

        return $cleaned;
    }


    $wpdb->query('START TRANSACTION');


    /**
     * Truncate matchs
     * 
     */

    $table_name = $wpdb->prefix . 'matchs_u13';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("TRUNCATE TABLE $table_name");
    }


    /**
     * Traitement de tous les matchs
     * 
     */
    $i = 0;
    foreach ($matchs_data as $values) {

        $jour = '';

        // Déterminer le jour
        if ($i < 3) {
            $jour = 'ven';
        } elseif ($i > 2 && $i < 9) {
            $jour = 'sam';
        } else {
            $jour = 'dim';
        }

        if ($jour === 'ven') {
            $date_complete = '2024-05-31';
        } elseif ($jour === 'sam') {
            $date_complete = '2024-06-01';
        } elseif ($jour === 'dim') {
            $date_complete = '2024-06-02';
        }

        // Déterminer la phase
        if ($i < 6) {
            $phase = 'poules';
        } elseif ($i > 5 && $i < 9) {
            $phase = 'brassages';
        } else {


            $phase = '';

            switch ($i) {
                case 9:
                case 10:
                    $phase = 'Classement 5/8 Hamster Cup';
                    break;
                case 11:
                case 12:
                    $phase = '1/2 Finale Hamster Cup';
                    break;
                case 13:
                case 14:
                    $phase = 'Classement 5/8 Castor Cup';
                    break;
                case 15:
                case 16:
                    $phase = '1/2 Finale Castor Cup';
                    break;
                case 17:
                case 18:
                    $phase = 'Classement 5/8 Hopla Cup';
                    break;
                case 19:
                case 20:
                    $phase = '1/2 Finale Hopla Cup';
                    break;
                case 21:
                    $phase = 'Classement 7/8 Hamster Cup';
                    break;
                case 22:
                    $phase = 'Classement 5/6 Hamster Cup';
                    break;
                case 23:
                    $phase = 'Petite Finale Hamster Cup';
                    break;
                case 24:
                    $phase = 'Finale Hamster Cup';
                    break;
                case 25:
                    $phase = 'Classement 7/8 Castor Cup';
                    break;
                case 26:
                    $phase = 'Classement 5/6 Castor Cup';
                    break;
                case 27:
                    $phase = 'Petite Finale Castor Cup';
                    break;
                case 28:
                    $phase = 'Finale Castor Cup';
                    break;
                case 29:
                    $phase = 'Classement 7/8 Hopla Cup';
                    break;
                case 30:
                    $phase = 'Classement 5/6 Hopla Cup';
                    break;
                case 31:
                    $phase = 'Petite Finale Hopla Cup';
                    break;
                case 32:
                    $phase = 'Finale Hopla Cup';
                    break;
            }
        }

        // Déterminer le terrain
        switch ($i) {
            case 0:
            case 3:
            case 6:
            case 9:
            case 12:
            case 15:
            case 18:
            case 21:
            case 24:
            case 27:
            case 30:
                $terrain = 'A';
                break;
            case 1:
            case 4:
            case 7:
            case 10:
            case 13:
            case 16:
            case 19:
            case 22:
            case 25:
            case 28:
            case 32:
                $terrain = 'B';
                break;
            case 2:
            case 5:
            case 8:
            case 11:
            case 14:
            case 17:
            case 20:
            case 23:
            case 26:
            case 29:
            case 31:
                $terrain = 'C';
                break;
        }



        // Insérer chaque ligne de valeurs dans la table WordPress

        $j = 0; // compteur matchs playoffs

        foreach ($values as $row) {

            //Déterminer la date
            $temps =  $date_complete . ' ' . $row[0];

            // Structure des tableaux de phases poules/brassage
            if ($i < 9) {
                // construction d'un identifiant pour le match
                // "sam17:15t1"
                $identifiant = $jour . $row[0] . 't' . $terrain;
                $wpdb->insert($wpdb->prefix . 'matchs_u13', [
                    'Identifiant' => $identifiant,
                    'Jour' => $jour,
                    'Horaire' => $row[0],
                    'Temps' => $temps,
                    'Terrain' => $terrain,
                    'Equipe1' => isset($row[1]) ? clean_string($row[1]) : '',
                    'Equipe2' => isset($row[3]) ? clean_string($row[3]) : '',
                    'Score_Equipe1' => isset($row[4]) && $row[4] !== '' ? $row[4] : NULL,
                    'Score_Equipe2' => isset($row[6]) && $row[6] !== '' ? $row[6] : NULL,
                    'Poule' => '', // Poule vide dans un premier temps
                    'Phase' => $phase,
                ]);
            }
            // Structure des tableaux de phases finales/classements
            else {
                // insérer une ligne sur deux (infos match / infos tirs aux buts)
                // ligne paire => infos du match
                // ligne impaire => contient les tirs au but
                if ($j % 2 === 0) {

                    // construction d'un identifiant pour le match
                    $temps =  $date_complete . ' ' . $row[1];

                    // Récupérer les infos des tirs aux buts dans
                    $tiraubutE1 = isset($values[1][4]) && $values[1][4] !== '' ? $values[1][4] : NULL;
                    $tiraubutE2 = isset($values[1][6]) && $values[1][6] !== '' ? $values[1][6] : NULL;

                    // "sam17:15t1"
                    $identifiant = $jour . $row[1] . 't' . $terrain;
                    $wpdb->insert($wpdb->prefix . 'matchs_u13', [
                        'Identifiant' => $identifiant,
                        'Jour' => $jour,
                        'Horaire' => $row[1],
                        'Temps' => $temps,
                        'Terrain' => $terrain,
                        'Equipe1' => isset($row[3]) ? clean_string($row[3]) : '',
                        'Equipe2' => isset($row[7]) ? clean_string($row[7]) : '',
                        'Score_Equipe1' => isset($row[4]) && $row[4] !== '' ? $row[4] : NULL,
                        'Score_Equipe2' => isset($row[6]) && $row[6] !== '' ? $row[6] : NULL,
                        'Tab_Equipe1' => $tiraubutE1,
                        'Tab_Equipe2' => $tiraubutE2,
                        'Poule' => NULL, // Poule vide dans un premier temps
                        'Phase' => $phase,
                    ]);
                }
                $j++;
            }
        }
        $i++;
    }


    /**
     * Truncate poules
     * 
     */
    $table_name = $wpdb->prefix . 'poules_u13';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("TRUNCATE TABLE $table_name");
    }


    /**
     * Traitement des poules A, B, C, D, - Phase 1
     * E, F, .. J - Phase 2 "Brassage"
     */
    $i = 0;
    foreach ($poules_data as $values) {


        switch ($i) {
            case 0:
            case 1:
                $poule = 'A';
                break;
            case 2:
            case 3:
                $poule = 'B';
                break;
            case 4:
            case 5:
                $poule = 'C';
                break;
            case 6:
            case 7:
                $poule = 'D';
                break;
            case 8:
            case 9:
                $poule = 'E';
                break;
            case 10:
            case 11:
                $poule = 'F';
                break;
            case 12:
            case 13:
                $poule = 'G';
                break;
            case 14:
            case 15:
                $poule = 'H';
                break;
            case 16:
            case 17:
                $poule = 'I';
                break;
            case 18:
            case 19:
                $poule = 'J';
                break;
        }



        if ($i % 2 == 0) { // tableau classement

            foreach ($values as $row) {
                $wpdb->insert($wpdb->prefix . 'poules_u13', [
                    'Nom_poule' => $poule,
                    'Position' => isset($row[0]) ? $row[0] : NULL,
                    'Equipe' => isset($row[1]) ? clean_string($row[1]) : '',
                    'Matchs_joues' => isset($row[2]) ? $row[2] : NULL,
                    'Points' => isset($row[3]) ? $row[3] : NULL,
                    'Victoires_matchs_directs' => isset($row[4]) ? $row[4] : NULL,
                    'Goal_average' => isset($row[5]) ? $row[5] : NULL,
                ]);
            }
        } else { // tableau matchs pour créer une liaison


            foreach ($values as $row) {

                $identifiant_match = $row[0] . $row[1] . str_replace('Terrain ', 't', $row[2]);

                $where = array(
                    'Identifiant' => $identifiant_match
                );
                // Données à mettre à jour
                $data = array(
                    'Poule' => $poule
                );
                // Mise à jour de la table matchs_u13
                $wpdb->update($wpdb->prefix . 'matchs_u13', $data, $where);
            }
        }

        $i++;
    }

    /**
     * Truncate classements
     * 
     */

    $table_name = $wpdb->prefix . 'classements_u13';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("TRUNCATE TABLE $table_name");
    }

    /**
     * 
     * Synchronisation des classements finaux
     * 
     */
    $i = 0;
    foreach ($classements_data as $values) {

        $coupe = '';

        switch ($i) {
            case 0:
                $coupe = 'Hopla Cup';
                break;
            case 1:
                $coupe = 'Castor Cup';
                break;
            case 2:
                $coupe = 'Hamster Cup';
                break;
        }


        foreach ($values as $row) {
            $wpdb->insert($wpdb->prefix . 'classements_u13', [
                'Coupe' => $coupe,
                'Equipe' => (isset($row[1]) && $row[1]) ? $row[1] : NULL,
                'Classement' => (isset($row[0]) && $row[0]) ? $row[0] : NULL,
            ]);
        }

        $i++;
    }



    /**
     * Truncate equipes
     * 
     */

    $table_name = $wpdb->prefix . 'equipes_u13';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("TRUNCATE TABLE $table_name");
    }


    /**
     * Ajout equipes
     * 
     */

    foreach ($equipes_data as $equipe) {
        foreach ($equipe as $row) {
            $wpdb->insert($wpdb->prefix . 'equipes_u13', [
                'Identifiant' => clean_string($row[0]),
                'Nom_Equipe' => $row[0],
                'Nom_Club' => $row[1],
                'Identifiant_Club' => clean_string($row[1]),
                'Logo_Club' => NULL
            ]);
        }
    }


    $wpdb->query('COMMIT');
}
