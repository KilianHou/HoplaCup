<?php

/**
 *
 * Fonction appelée lors de l'activation du plugin
 * Wordpress crééra automatiquement ces tables si elles n'existent pas encore dans sa BDD
 *
 */

function create_tables()
{
	global $wpdb;

	$version_db = get_option('nom_plugin_version_db'); // ?? Garder la trace de la version de la base de données
	$charset_collate = $wpdb->get_charset_collate(); // Obtenir le jeu de caractères et le collate de la base de données de WordPress


	//////////////////////////////Tables U13/////////////////////////////////////
	$matchs = $wpdb->prefix . 'matchs_u13'; // Création de la table `matchs_u13`
	$structure_table = "CREATE TABLE $matchs (
        `ID` int NOT NULL AUTO_INCREMENT,
        `Identifiant`  VARCHAR(255) NOT NULL,
        `Jour`  VARCHAR(255) NOT NULL,
        `Horaire`  VARCHAR(255) NOT NULL,
        `Temps`  DATETIME,
        `Terrain`  VARCHAR(255) NOT NULL,
        `Equipe1`  VARCHAR(255),
        `Equipe2`  VARCHAR(255),
        `Score_Equipe1`  VARCHAR(255),
        `Score_Equipe2`  VARCHAR(255),
        `Tab_Equipe1`  int,
        `Tab_Equipe2`  int,
        `Poule`  VARCHAR(255),
        `Phase`  VARCHAR(255) NOT NULL,

        PRIMARY KEY (`ID`)

    ) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table);


	$poules = $wpdb->prefix . 'poules_u13'; // Création de la table `matchs_u13`
	$structure_table = "CREATE TABLE $poules (
        `ID` int NOT NULL AUTO_INCREMENT,
        `Nom_poule` VARCHAR(255),
        `Position` int,
        `Equipe` VARCHAR(255),
        `Matchs_joues` int,
        `Points` int,
        `Victoires_matchs_directs` int,
        `Goal_average` int,
        PRIMARY KEY (`ID`)

    ) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table);


	$equipes_u13 = $wpdb->prefix . 'equipes_u13';
	$structure_table_equipes_u13 = "CREATE TABLE $equipes_u13 (
    `id` INT NOT NULL AUTO_INCREMENT,
    `Identifiant` Varchar(255) NOT NULL,
    `Nom_Equipe` VARCHAR(255) NOT NULL,
    `Nom_Club` VARCHAR(255) NOT NULL,
    `Identifiant_Club` VARCHAR(255) NOT NULL,
    `Logo_Club` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table_equipes_u13);


	$classements = $wpdb->prefix . 'classements_u13'; // Création de la table `classements_u13`
	$structure_table = "CREATE TABLE $classements (

        `ID` int NOT NULL AUTO_INCREMENT,
        `Coupe` VARCHAR(255),
        `Equipe` VARCHAR(255),
        `Classement` int,
        PRIMARY KEY (`ID`)

    ) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table);


	//////////////////////////////Tables U13/////////////////////////////////////
	$matchs911 = $wpdb->prefix . 'matchs_u911'; // Création de la table `matchs_u911`
	$structure_table = "CREATE TABLE $matchs911 (

        `ID` int NOT NULL AUTO_INCREMENT,
        `Division` VARCHAR(255) NOT NULL,
        `Identifiant`  VARCHAR(255) NOT NULL,
        `Jour`  VARCHAR(255) NOT NULL,
        `Horaire`  VARCHAR(255),
        `Temps`  DATETIME,
        `Terrain`  VARCHAR(255) NOT NULL,
        `Equipe1`  VARCHAR(255),
        `Equipe2`  VARCHAR(255),
        `Score_Equipe1`  VARCHAR(255),
        `Score_Equipe2`  VARCHAR(255),
        `Tab_Equipe1`  int,
        `Tab_Equipe2`  int,
        `Poule`  VARCHAR(255),
        `Phase`  VARCHAR(255) NOT NULL,

        PRIMARY KEY (`ID`)

    ) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table);


	$poules911 = $wpdb->prefix . 'poules_u911'; // Création de la table `poules_u911`
	$structure_table = "CREATE TABLE $poules911 (

        `ID` int NOT NULL AUTO_INCREMENT,
        `Division` VARCHAR(255) NOT NULL,
        `Nom_poule` VARCHAR(255),
        `Position` int,
        `Equipe` VARCHAR(255),
        `Matchs_joues` int,
        `Points` int,
        `Victoires_matchs_directs` int,
        `Goal_average` int,
        PRIMARY KEY (`ID`)

    ) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table);


	$equipes_u911 = $wpdb->prefix . 'equipes_u911';
	$structure_table_equipes_u911 = "CREATE TABLE $equipes_u911 (
    `id` INT NOT NULL AUTO_INCREMENT,
    `Identifiant` Varchar(255) NOT NULL,
    `Nom_Equipe` VARCHAR(255) NOT NULL,
    `Nom_Club` VARCHAR(255) NOT NULL,
    `Identifiant_Club` VARCHAR(255) NOT NULL,
    `Division` VARCHAR(255) NOT NULL,
    `Logo_Club` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table_equipes_u911);



	$classements911 = $wpdb->prefix . 'classements_u911'; // Création de la table `classements_u911`

	$structure_table = "CREATE TABLE $classements911 (

        `ID` int NOT NULL AUTO_INCREMENT,
        `Coupe` VARCHAR(255),
        `Equipe` VARCHAR(255),
        `Division` VARCHAR(255) NOT NULL,
        `Classement` int,
        PRIMARY KEY (`ID`)

    ) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($structure_table);

	// Mettre à jour la version de la base de données
	update_option('nom_plugin_version_db', '1.0');
}
