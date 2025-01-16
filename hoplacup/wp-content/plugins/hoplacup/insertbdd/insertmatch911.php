<?php

require_once WP_CONTENT_DIR . '/plugins/hoplacup/includes/vendor/autoload.php';


function hoplacup_import_data_from_sheets2()
{
    global $wpdb;

    // Id spreadSheet
    $spreadsheetId = '1Ts0CO5ECkvCLTcHRa018mMtKCrzyeLZiOHx_okvR5SQ';

    // Ranges à récupérer
    $matchs2_ranges = [ // [0] => [matches]
        // Phase 1 - Matchs vendredi
        'Aff_Programme!D12:J19', // T1 [0]
        'Aff_Programme!O12:U19', // T2 [1]
        'Aff_Programme!D24:J31', // T3 [2]
        'Aff_Programme!O24:U31', // T4 [3]

        // Phase 1 - Matchs samedi
        'Aff_Programme!D44:J55', // T1 [4]
        'Aff_Programme!O44:U55', // T2 [5]
        'Aff_Programme!D60:J71', // T3 [6]
        'Aff_Programme!O60:U71', // T4 [7]


        // BRASSAGE - Phase 2 - Matchs samedi
        'Aff_Programme!D84:J94',  // T1 [8]
        'Aff_Programme!O84:U94',  // T2 [9]
        'Aff_Programme!D99:J109', // T3 [10]
        'Aff_Programme!O99:U109', // T4 [11]

        // Finales/playoffs à traiter
        'Playoffs_Aff!E8:K9', // [12] T1 1/2 Finale Hopla Cup U9
        'Playoffs_Aff!E10:K11', // [13] T2 1/2 Finale Hopla Cup U9

        'Playoffs_Aff!E13:K14', // [14] T1 Classement 5/8 Hamster Cup U11
        'Playoffs_Aff!E15:K16', // [15] T2 Classement 5/8 Hamster Cup U11

        'Playoffs_Aff!E18:K19', // [16] T3 1/2 Finale Hamster Cup U11
        'Playoffs_Aff!E20:K21', // [17] T4 1/2 Finale Hamster Cup U11

        'PLayoffs_Aff!E23:K24', // [18] T1 Classement 5/8 Castor Cup U11
        'PLayoffs_Aff!E25:K26', // [19] T2 Classement 5/8 Castor Cup U11

        'PLayoffs_Aff!E28:K29', // [20] T1 1/2 Finale Castor Cup U11
        'PLayoffs_Aff!E30:K31', // [21] T2 1/2 Finale Castor Cup U11

        'PLayoffs_Aff!E33:K34', // [22] T1 Classement 5/8 Hopla Cup U11
        'PLayoffs_Aff!E35:K36', // [23] T2 Classement 5/8 Hopla Cup U11

        'PLayoffs_Aff!E38:K39', // [24] T3 1/2 Finale Hopla Cup U11
        'PLayoffs_Aff!E40:K41', // [25] T4 1/2 Finale Hopla Cup U11

        'Playoffs_Aff!E48:K49', // [26] T3 Classement 5:6 Hopla Cup U9

        'Playoffs_Aff!E51:K52', // [27] T1 Petite Finale Hopla Cup U9

        'Playoffs_Aff!E54:K55', // [28] T2 Finale Hopla Cup U9

        'PLayoffs_Aff!E57:K58', // [29] T1 Classement 7/8 Hamster Cup U11

        'PLayoffs_Aff!E60:K61', // [30] T2 Classement 5/6 Hamster Cup U11

        'PLayoffs_Aff!E63:K64', // [31] T3 Petite Finale Hamster Cup U11

        'PLayoffs_Aff!E66:K67', // [32] T4 Finale Hamster Cup U11

        'PLayoffs_Aff!E69:K70', // [33] T1 Classement 7/8 Castor Cup U11

        'PLayoffs_Aff!E72:K73', // [34] T2 Classement 5/6 Castor Cup U11

        'PLayoffs_Aff!E75:K76', // [35] T3 Petite Finale Castor Cup U11

        'PLayoffs_Aff!E78:K79', // [36] T4 Finale Castor Cup U11

        'PLayoffs_Aff!E81:K82', // [37] T1 Classement 7/8 Hopla Cup U11

        'PLayoffs_Aff!E84:K85', // [38] T2 Classement 5/6 Hopla Cup U11

        'PLayoffs_Aff!E89:K90', // [39] T3 Petite Finale Hopla Cup U11

        'PLayoffs_Aff!E93:K94' // [40] T4 Finale Hopla Cup U11


    ];

    $poules_ranges = [ // [1] => Poules

        'Phase_1_Aff_Poules_U9!F11:K16', // [0] classement poule K U9
        'Phase_1_Aff_Poules_U9!D20:F34', // [1] matchs poule K U9

        'Phase_1_Aff_Poules_U11!F11:K16', // [2] classement poule A U11
        'Phase_1_Aff_Poules_U11!D20:F34', // [3] matchs poule A U11

        'Phase_1_Aff_Poules_U11!F61:K66', // [4] classement poule B U11
        'Phase_1_Aff_Poules_U11!D70:F84', // [5] matchs poule B U11

        'Phase_1_Aff_Poules_U11!F111:K116', // [6] classement poule C U11
        'Phase_1_Aff_Poules_U11!D120:F134', // [7] matchs poule C U11

        'Phase_1_Aff_Poules_U11!F161:K166', // [8] classement poule D U11
        'Phase_1_Aff_Poules_U11!D170:F184', // [9] matchs poule D U11

        'Phase_2_Aff_Poules_U9!F11:K13', // [10] classement poule L U9
        'Phase_2_Aff_Poules_U9!D17:F19', // [11] matchs poule L U9

        'Phase_2_Aff_Poules_U9!F37:K39', // [12] classement poule M U9
        'Phase_2_Aff_Poules_U9!D43:F45', // [13] matchs poule M U9

        'Phase_2_Aff_Poules_U11!F11:K14', // [14] classement poule E U11
        'Phase_2_Aff_Poules_U11!D18:F23', // [15] matchs poule E U11

        'Phase_2_Aff_Poules_U11!F41:K44', // [16] classement poule F U11
        'Phase_2_Aff_Poules_U11!D48:F53', // [17] matchs poule F U11

        'Phase_2_Aff_Poules_U11!F71:K74', // [18] classement poule G U11
        'Phase_2_Aff_Poules_U11!D78:F83', // [19] matchs poule G U11

        'Phase_2_Aff_Poules_U11!F101:K104', // [20] classement poule H U11
        'Phase_2_Aff_Poules_U11!D108:F113', // [21] matchs poule H U11

        'Phase_2_Aff_Poules_U11!F131:K134', // [22] classement poule I U11
        'Phase_2_Aff_Poules_U11!D138:F143', // [23] matchs poule I U11

        'Phase_2_Aff_Poules_U11!F161:K164', // [24] classement poule J U11
        'Phase_2_Aff_Poules_U11!D168:F173', // [25] matchs poule J U11

    ];



    $classements_range = [ // [2] => classements

        'Class_Final_Aff_U11!F9:G16', // [0] classement HoplaCup U11

        'Class_Final_Aff_U11!F20:G27', // [1] classement CastorCup U11

        'Class_Final_Aff_U11!F31:G38', // [2] classement HamsterCup U11

        'Class_Final_Aff_U9!F9:G14', // [3] classement HoplaCup U

    ];

    $equipes_range = ['master!A2:C'];


    $client = new Google_Client(); // Initialisez le client Google Sheets
    $client->setApplicationName('Google Sheets PHP Example');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig(plugin_dir_path(__FILE__) . 'hoplacup-efd8edb379c1.json');
    $service = new Google_Service_Sheets($client);

    // Appel API pour les matchs et les poules
    $response = $service->spreadsheets_values->batchGet($spreadsheetId, [
        'ranges' => array_merge($matchs2_ranges, $poules_ranges, $classements_range, $equipes_range)
    ]);

    // Tableaux pour stocker les données des matchs et des poules
    $matchs2_data = [];
    $poules_data = [];
    $classements_data = [];
    $equipes_data = [];



    // Parcourir la réponse et séparer les données en fonction des plages
    foreach ($response['valueRanges'] as $key => $valueRange) {
        // Récupérer les données de la plage actuelle
        $data = $valueRange['values'];
        // Vérifier si la plage correspond aux matchs, aux poules ou aux classements
        if ($key < count($matchs2_ranges)) {
            // Plage correspondant aux matchs
            $matchs2_data[] = $data;
        } elseif ($key < count($matchs2_ranges) + count($poules_ranges)) {
            // Plage correspondant aux poules
            $poules_data[] = $data;
        } elseif ($key < count($matchs2_ranges) + count($poules_ranges) + count($classements_range)) {
            // Plage correspondant aux classements
            $classements_data[] = $data;
        } else {
            $equipes_data[] = $data;
        }
    }









    function remove_accents_and_lowercase2($str)
    {
        // Conversion des caractères accentués en leur équivalent sans accent
        $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
        $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1', $str);
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

        // Conversion en minuscules
        $str = strtolower($str);

        return $str;
    }


    function clean_string2($input)
    {
        // Filtrer tous les caractères non alphanumériques après la translittération si iconv n'a pas la bonne configuration sur le serveur
        $cleaned = remove_accents_and_lowercase2($input);
        $cleaned = str_replace([' ', '.', '-', '_'], '', $cleaned);

        return $cleaned;
    }


    $wpdb->query('START TRANSACTION');


    /**
     * Truncate matchs
     *
     */

    $table_name = $wpdb->prefix . 'matchs_u911';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("TRUNCATE TABLE $table_name");
    }


    /**
     * Traitement de tous les matchs
     *
     */
    $i = 0;

    foreach ($matchs2_data as $values) {

        $jour = '';

        // Déterminer le jour
        if ($i < 4) {  // Matches vendredi (indices 0-3)
            $jour = 'ven';
        } elseif ($i > 3 && $i < 12) {  // Matches samedi (indices 4-11)
            $jour = 'sam';
        } else {  // Si `$i` est 12 ou plus, cela devrait couvrir les matches du dimanche
            $jour = 'dim';
        }

        if ($jour === 'ven') {
            $date_complete = '2024-05-31';
        } elseif ($jour === 'sam') {
            $date_complete = '2024-06-01';
        } elseif ($jour === 'dim') {
            $date_complete = '2024-06-02';
        }

        if ($i < 8) {  // Correspondant à "Phase 1 - Matchs vendredi" et "Phase 1 - Matchs samedi"
            $phase = 'poules';
        } elseif ($i >= 8 && $i < 12) {  // Correspondant à "BRASSAGE - Phase 2 - Matchs samedi"
            $phase = 'brassages';
        } else {


            $phase = '';

            switch ($i) {
                case 12:
                case 13:
                    $phase = '1/2 Finale Hopla Cup'; //u9
                    break;
                case 14:
                case 15:
                    $phase = 'Classement 5/8 Hamster Cup';
                    break;
                case 16:
                case 17:
                    $phase = '1/2 Finale Hamster Cup';
                    break;
                case 18:
                case 19:
                    $phase = 'Classement 5/8 Castor Cup';
                    break;
                case 20:
                case 21:
                    $phase = '1/2 Finale Castor Cup';
                    break;
                case 22:
                case 23:
                    $phase = 'Classement 5/8 Hopla Cup';
                    break;
                case 24:
                case 25:
                    $phase = '1/2 Finale Hopla Cup';
                    break;
                case 26:
                    $phase = 'Classement 5/6 Hopla Cup'; //u9
                    break;
                case 27:
                    $phase = 'Petite Finale Hopla Cup'; //u9
                    break;
                case 28:
                    $phase = 'Finale Hopla Cup'; //u9
                    break;
                case 29:
                    $phase = 'Classement 7/8 Hamster Cup';
                    break;
                case 30:
                    $phase = 'Classement 5/6 Hamster Cup';
                    break;
                case 31:
                    $phase = 'Petite Finale Hamster Cup';
                    break;
                case 32:
                    $phase = 'Finale Hamster Cup';
                    break;
                case 33:
                    $phase = 'Classement 7/8 Castor Cup';
                    break;
                case 34:
                    $phase = 'Classement 5/6 Castor Cup';
                    break;
                case 35:
                    $phase = 'Petite Finale Castor Cup';
                    break;
                case 36:
                    $phase = 'Finale Castor Cup';
                    break;
                case 37:
                    $phase = 'Classement 7/8 Hopla Cup';
                    break;
                case 38:
                    $phase = 'Classement 5/6 Hopla Cup';
                    break;
                case 39:
                    $phase = 'Petite Finale Hopla Cup';
                    break;
                case 40:
                    $phase = 'Finale Hopla Cup';
                    break;
            }
        }

        // Déterminer le terrain
        switch ($i) {
            // Terrain 1 les cas suivants
            case 0:
            case 4:
            case 8:
            case 12:
            case 14:
            case 18:
            case 22:
            case 27:
            case 29:
            case 33:
            case 37:
                $terrain = '1';
                break;
            // Terrain 2 dans les cas suivants
            case 1:
            case 5:
            case 9:
            case 13:
            case 15:
            case 19:
            case 23:
            case 28:
            case 30:
            case 34:
            case 38:

                $terrain = '2';
                break;
            // Terrain 3 les cas suivants
            case 2:
            case 6:
            case 10:
            case 16:
            case 20:
            case 24:
            case 26:
            case 31:
            case 35:
            case 39:

                $terrain = '3';
                break;
            // Terrain 4  dans les cas suivants
            case 3:
            case 7:
            case 11:
            case 17:
            case 21:
            case 25:
            case 32:
            case 36:
            case 40:
                $terrain = '4';
                break;
        }


        // Insérer chaque ligne de valeurs dans la table WordPress

        $j = 0; // compteur de lignes traitées pour chaque groupe de valeurs

        foreach ($values as $row) {
            // Nettoyer le tableau $row pour retirer l'élément 'Terrain'
            $cleaned_row = array_filter($row, function ($value) {
                return !preg_match('/^Terrain \d+$/', $value);
            });

            // Réindexer les clés après le filtrage
            $cleaned_row = array_values($cleaned_row);

            // Vérifier si c'est une ligne d'insertion de match ou d'informations supplémentaires

            if ($i < 12) {

                // construction d'un identifiant pour le match
                // "sam17:15t1"
                $temps =  $date_complete . ' ' . $row[0];
                $identifiant = $jour . $row[0] . 't' . $terrain;
                $wpdb->insert($wpdb->prefix . 'matchs_u911', [
                    'Identifiant' => $identifiant,
                    'Jour' => $jour,
                    'Horaire' => $row[0],
                    'Temps' => $temps,
                    'Terrain' => $terrain,
                    'Equipe1' => isset($row[1]) ? clean_string($row[1]) : '',
                    'Equipe2' => isset($row[3]) ? clean_string($row[3]) : '',
                    'Score_Equipe1' => isset($row[4]) && $row[4] !== '' ? $row[4] : NULL,
                    'Score_Equipe2' => isset($row[6]) && $row[6] !== '' ? $row[6] : NULL,
                    'Poule' => NULL, // Poule vide dans un premier temps
                    'Phase' => $phase,
                ]);
            } else {
                if ($jour === 'dim') {
                    $division = '';
                    switch ($i) {
                        case 12:
                        case 13:
                        case 26:
                        case 27:
                        case 28:
                            $division = 'U9';
                            break;

                        case 14:
                        case 15:
                        case 16:
                        case 17:
                        case 18:
                        case 19:
                        case 20:
                        case 21:
                        case 22:
                        case 23:
                        case 24:
                        case 25:
                        case 29:
                        case 30:
                        case 31:
                        case 32:
                        case 33:
                        case 34:
                        case 35:
                        case 36:
                        case 37:
                        case 38:
                        case 39:
                        case 40:
                            $division = 'U11';
                            break;
                    }
                }

                if ($j % 2 == 0) {


                    // Insérer les informations standard du match
                    $temps = $date_complete . ' ' . $cleaned_row[0];
                    $identifiant = $jour . $cleaned_row[0] . 't' . $terrain;
                    $wpdb->insert($wpdb->prefix . 'matchs_u911', [
                        'Identifiant' => $identifiant,
                        'Jour' => $jour,
                        'Horaire' => $cleaned_row[0],
                        'Temps' => $temps,
                        'Terrain' => $terrain,
                        'Equipe1' => isset($row[2]) ? clean_string2($row[2]) : '',
                        'Equipe2' => isset($row[6]) ? clean_string2($row[6]) : '',
                        'Score_Equipe1' => (isset($row[3]) && $row[3] !== '') ? $row[3] : NULL,
                        'Score_Equipe2' => (isset($row[5]) && $row[5] !== '') ? $row[5] :  NULL,
                        'Poule' => NULL,
                        'Phase' => $phase,
                        'Division' => $division,
                    ]);
                } else {
                    // Insérer des informations supplémentaires pour les tirs au but
                    $tiraubutE1 = (isset($cleaned_row[3]) && trim($cleaned_row[3]) !== '') ? $cleaned_row[3] : NULL;
                    $tiraubutE2 = (isset($cleaned_row[5]) && trim($cleaned_row[5]) !== '') ? $cleaned_row[5] : NULL;

                    $wpdb->update($wpdb->prefix . 'matchs_u911', [
                        'Tab_Equipe1' => $tiraubutE1,
                        'Tab_Equipe2' => $tiraubutE2
                    ], [
                        'Identifiant' => $identifiant
                    ]);
                }
                $j++;
            }
        }
        $i++;
    }

    /*Truncate poules*/
    $table_name = $wpdb->prefix . 'poules_u911';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("TRUNCATE TABLE $table_name");
    }

    /**
     * Traitement des poules A, B, C, D, - Phase 1
     * E, F, .. J - Phase 2 "Brassage"
     */
    $i = 0;
    foreach ($poules_data as $index => $values) {
        $poule = ''; // Variable pour stocker le nom de la poule
        $groupe = ''; // Variable pour stocker le groupe (U9, U11)

        // Déterminez le nom de la poule et le groupe en fonction de l'index
        switch ($index) {
            case 0:
            case 1:
                $poule = 'K';
                $groupe = 'U9';
                break;
            case 2:
            case 3:
                $poule = 'A';
                $groupe = 'U11';
                break;
            case 4:
            case 5:
                $poule = 'B';
                $groupe = 'U11';
                break;
            case 6:
            case 7:
                $poule = 'C';
                $groupe = 'U11';
                break;
            case 8:
            case 9:
                $poule = 'D';
                $groupe = 'U11';
                break;
            case 10:
            case 11:
                $poule = 'L';
                $groupe = 'U9';
                break;
            case 12:
            case 13:
                $poule = 'M';
                $groupe = 'U9';
                break;
            case 14:
            case 15:
                $poule = 'E';
                $groupe = 'U11';
                break;
            case 16:
            case 17:
                $poule = 'F';
                $groupe = 'U11';
                break;
            case 18:
            case 19:
                $poule = 'G';
                $groupe = 'U11';
                break;
            case 20:
            case 21:
                $poule = 'H';
                $groupe = 'U11';
                break;
            case 22:
            case 23:
                $poule = 'I';
                $groupe = 'U11';
                break;
            case 24:
            case 25:
                $poule = 'J';
                $groupe = 'U11';
                break;
        }



        if ($i % 2 == 0) { // tableau classement

            foreach ($values as $row) {
                // Si l'index est pair, traiter comme tableau de classement
                $wpdb->insert($wpdb->prefix . 'poules_u911', [
                    'Division' => $groupe,
                    'Nom_poule' => $poule,
                    'Position' => isset($row[0]) ? $row[0] : NULL,
                    'Equipe' => isset($row[1]) ? clean_string2($row[1]) : '',
                    'Matchs_joues' => isset($row[2]) ? $row[2] : NULL,
                    'Points' => isset($row[3]) ? $row[3] : NULL,
                    'Victoires_matchs_directs' => isset($row[4]) ? $row[4] : NULL,
                    'Goal_average' => isset($row[5]) ? $row[5] : NULL,
                ]);
            }
        } else {

            foreach ($values as $row) {
                // Si c'est un tableau de matchs
                $identifiant_match = $row[0] . $row[1] . str_replace('Terrain ', 't', $row[2]);
                $where = ['Identifiant' => $identifiant_match];
                $data = [
                    'Poule' => $poule,
                    'Division' => $groupe
                ];
                $wpdb->update($wpdb->prefix . 'matchs_u911', $data, $where);
            }
        }
        $i++;
    }
    /**
     * Truncate classements
     *
     */

    $table_name = $wpdb->prefix . 'classements_u911';
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
        $division = 'U11';

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
            case 3:
                $coupe = 'Hopla Cup';
                $division = 'U9';
                break;
        }


        foreach ($values as $row) {
            $wpdb->insert($wpdb->prefix . 'classements_u911', [
                'Coupe' => $coupe,
                'Division' => $division,
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

    $table_name = $wpdb->prefix . 'equipes_u911';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("TRUNCATE TABLE $table_name");
    }


    /**
     * Ajout equipes
     *
     */

    foreach ($equipes_data as $equipe) {
        foreach ($equipe as $row) {
            $wpdb->insert($wpdb->prefix . 'equipes_u911', [
                'Identifiant' => clean_string($row[0]),
                'Nom_Equipe' => $row[0],
                'Nom_Club' => $row[1],
                'Identifiant_Club' => clean_string($row[1]),
                'Division' => $row[2],
                'Logo_Club' => NULL
            ]);
        }
    }


    $wpdb->query('COMMIT');
}
