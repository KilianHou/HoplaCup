<?php

/**
 * Creation des tables Plugin V2
 */

global $wpdb;

$charset_collate = $wpdb->get_charset_collate();

// Préfixes des tables
$terrains = $wpdb->prefix . 'terrains';
$clubs = $wpdb->prefix . 'clubs';
$divisions = $wpdb->prefix . 'divisions';
$poules = $wpdb->prefix . 'poules';
$equipes = $wpdb->prefix . 'equipes';
$matchs = $wpdb->prefix . 'matchs';
$poulesEquipes = $wpdb->prefix . 'poules_equipes';
$tournois = $wpdb->prefix . 'tournois';
$transfertPhases = $wpdb->prefix . 'transferts_phases';
$matchsTransfertPhases = $wpdb->prefix . 'matchs_transferts_phases';
$phases = $wpdb->prefix . 'phases';
$tournoisTerrains = $wpdb->prefix . 'tournois_terrains';



// Structure des tables
$structure_table_tournois = "CREATE TABLE IF NOT EXISTS $tournois (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Nom` VARCHAR(255) NOT NULL,
    `Archivé` TINYINT(1) NOT NULL DEFAULT 0,
    `Duree_matchs` INT  NOT NULL DEFAULT 20,
    `Date_debut` DATETIME,
    `Date_fin` DATETIME,
    `Heure_fin` TIME,
    PRIMARY KEY (`ID`)
) $charset_collate;";

$structure_table_terrains = "CREATE TABLE IF NOT EXISTS $terrains (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Nom` VARCHAR(255),
    PRIMARY KEY (`ID`)
) $charset_collate;";

$structure_table_clubs = "CREATE TABLE IF NOT EXISTS $clubs (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Nom` VARCHAR(255),
    `Logo` VARCHAR(255),
    `Ville` VARCHAR(255),
    `Pays` VARCHAR(255),
    `Contact` TEXT,
    PRIMARY KEY (`ID`)
) $charset_collate;";

$structure_table_divisions = "CREATE TABLE IF NOT EXISTS $divisions (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Division` VARCHAR(255),
    PRIMARY KEY (`ID`)
) $charset_collate;";

$structure_table_poules = "CREATE TABLE IF NOT EXISTS $poules (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Nom` VARCHAR(255),
    `Phase_id` int NOT NULL,
    `Tournoi_id` int NOT NULL,
    `Type` VARCHAR(255),
    PRIMARY KEY (`ID`),
    CONSTRAINT fk_tournoi FOREIGN KEY (`Tournoi_id`) REFERENCES {$wpdb->prefix}tournois(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_phase FOREIGN KEY (`Phase_id`) REFERENCES {$wpdb->prefix}phases(`ID`) ON DELETE CASCADE

) $charset_collate;";

$structure_table_phases = "CREATE TABLE IF NOT EXISTS $phases (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Nom` VARCHAR(255),
    `Tournoi_id` INT NOT NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT fk_tournoi_phases FOREIGN KEY (`Tournoi_id`) REFERENCES {$wpdb->prefix}tournois(`ID`) ON DELETE CASCADE
) $charset_collate;";

$structure_table_equipes = "CREATE TABLE IF NOT EXISTS $equipes (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Nom` VARCHAR(255) NOT NULL,
    `Clubs_id` int NOT NULL,
    `Divisions_id` int NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT fk_clubs FOREIGN KEY (`Clubs_id`) REFERENCES {$wpdb->prefix}clubs(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_divisions FOREIGN KEY (`Divisions_id`) REFERENCES {$wpdb->prefix}divisions(`ID`) ON DELETE SET NULL
) $charset_collate;";

$structure_table_transferts_phases = "CREATE TABLE IF NOT EXISTS $transfertPhases (
    `ID` int NOT NULL AUTO_INCREMENT,
    `id_poule_origin` int NULL,
    `classement_origin` tinyint NOT NULL,
    `id_poule_destination` int NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT fk_poule_origin FOREIGN KEY (`id_poule_origin`) REFERENCES {$wpdb->prefix}poules(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_poule_destination FOREIGN KEY (`id_poule_destination`) REFERENCES {$wpdb->prefix}poules(`ID`) ON DELETE CASCADE
) $charset_collate;";

// 2 liaison par match -> ces deux liaisons représentent les 2 futures équipes qui vont jouer lors de la prochaine phase
// cela nous permet de gérer des matchs sans connaitre les futures équipes qui le joueront (exemple : 1er A vs 2eme B)
$structure_table_matchs_transferts_phases = "CREATE TABLE IF NOT EXISTS $matchsTransfertPhases (
    `id_transfert` int NOT NULL,
    `id_match` int NOT NULL,
    CONSTRAINT fk_id_transfert FOREIGN KEY (`id_transfert`) REFERENCES {$wpdb->prefix}transferts_phases(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_id_match FOREIGN KEY (`id_match`) REFERENCES {$wpdb->prefix}matchs(`ID`) ON DELETE CASCADE
) $charset_collate;";

$structure_table_matchs = "CREATE TABLE IF NOT EXISTS $matchs (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Poules_ID` int,
    `Temps` int,
    `Terrains_id` int NULL,
    `Id_eq1` int NULL,
    `Id_eq2` int NULL,
    `Score_e1` int,
    `Score_e2` int,
    `Fairplay_e1` int,
    `Fairplay_e2` int,
    `Horaire_depart` DATETIME NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT fk_poules FOREIGN KEY (`Poules_ID`) REFERENCES {$wpdb->prefix}poules(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_terrains FOREIGN KEY (`Terrains_id`) REFERENCES {$wpdb->prefix}terrains(`ID`),
    CONSTRAINT fk_id_eq1 FOREIGN KEY (`Id_eq1`) REFERENCES {$wpdb->prefix}equipes(`ID`),
    CONSTRAINT fk_id_eq2 FOREIGN KEY (`Id_eq2`) REFERENCES {$wpdb->prefix}equipes(`ID`)
) $charset_collate;";

$structure_table_poules_equipes = "CREATE TABLE IF NOT EXISTS $poulesEquipes (
    `Equipes_ID` int NOT NULL,
    `Poules_ID` int NOT NULL,
    `Classement` tinyint,
    CONSTRAINT fk_poules_equipes_poules FOREIGN KEY (`Poules_ID`) REFERENCES {$wpdb->prefix}poules(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_poules_equipes_equipes FOREIGN KEY (`Equipes_ID`) REFERENCES {$wpdb->prefix}equipes(`ID`)
) $charset_collate;";

$structure_table_points = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}points (
    `ID` int NOT NULL AUTO_INCREMENT,
    `Tournoi_ID` int NOT NULL,
    `Points_Victoire` int NOT NULL DEFAULT 3,
    `Points_Égalité` int NOT NULL DEFAULT 1,
    `Points_Défaite` int NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`),
    CONSTRAINT fk_points_tournois FOREIGN KEY (`Tournoi_ID`) REFERENCES {$wpdb->prefix}tournois(`ID`) ON DELETE CASCADE
) $charset_collate;";

$structure_table_fairplay = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fairplay (
    `ID` int NOT NULL AUTO_INCREMENT,
    `equipe_ID` int NOT NULL,
    `Tournoi_ID` int NOT NULL,
    `Points_Fairplay` int NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`),
    Constraint fk_fairplay_equipe FOREIGN KEY (`equipe_ID`) REFERENCES {$wpdb->prefix}equipes(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_fairplay_tournois FOREIGN KEY (`Tournoi_ID`) REFERENCES {$wpdb->prefix}tournois(`ID`) ON DELETE CASCADE
) $charset_collate;";

$structure_table_tournois_terrains = "CREATE TABLE IF NOT EXISTS $tournoisTerrains (
    `ID` int NOT NULL AUTO_INCREMENT,
    `tournoi_id` INT NOT NULL,
    `terrain_id` INT NOT NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT fk_tournois_terrains_tournois FOREIGN KEY (`tournoi_id`) REFERENCES {$wpdb->prefix}tournois(`ID`) ON DELETE CASCADE,
    CONSTRAINT fk_tournois_terrains_terrains FOREIGN KEY (`terrain_id`) REFERENCES {$wpdb->prefix}terrains(`ID`) ON DELETE CASCADE
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($structure_table_tournois);
dbDelta($structure_table_terrains);
dbDelta($structure_table_clubs);
dbDelta($structure_table_divisions);
dbDelta($structure_table_phases);
dbDelta($structure_table_poules);
dbDelta($structure_table_equipes);
dbDelta($structure_table_matchs);
dbDelta($structure_table_poules_equipes);
dbDelta($structure_table_transferts_phases);
dbDelta($structure_table_matchs_transferts_phases);
dbDelta($structure_table_points);
dbDelta($structure_table_tournois_terrains);
dbDelta($structure_table_fairplay);

$sql_file_path = plugin_dir_path(__FILE__) . 'values.sql';

if (file_exists($sql_file_path)) {
    $sql_queries = file_get_contents($sql_file_path);
    // Sépare les requêtes en plusieurs requêtes
    $sql_queries = explode(';', $sql_queries);

    // Execution de chaque requête individuellement
    foreach ($sql_queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $result = $wpdb->query($query);
        }
    }
}

// wp_die();