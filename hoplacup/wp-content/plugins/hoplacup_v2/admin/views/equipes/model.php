<?php

global $wpdb;

$equipes = $wpdb->prefix . 'equipes';
$clubs = $wpdb->prefix . 'clubs';
$divisions = $wpdb->prefix . 'divisions';

//CREATE d'équipes
if (isset($_POST['submitEquipe'])) {
    if (empty($_POST['nomClub'])) {
        echo "<div class='notice notice-error'><p>Erreur : L'équipe doit avoir un club</p></div>";
    } else {
        try {
            $nomEquipe = $_POST['nomEquipe'];
            $clubId = $_POST['nomClub'];
            $divisionId = $_POST['nomDivision'];
            $wpdb->insert($equipes, array('Nom' => $nomEquipe, 'Clubs_id' => $clubId, 'Divisions_id' => $divisionId));
            echo "<div class='notice notice-success'><p>Equipe ajoutée avec succès</p></div>";
        } catch (Exception $e) {
            echo "<div class='notice notice-error'><p>Erreur : Une erreur est survenue</p></div>";
        }
    }
}


//UPDATE des équipes
if (isset($_POST['update'])) {
	$id = intval($_POST['id']);
	$nomEquipe = sanitize_text_field($_POST['nomEquipe']);
	$clubId = intval($_POST['nomClub']);
	$divisionId = intval($_POST['nomDivision']);
	$wpdb->update(
		$equipes, // Nom de la table
		array(
			'Nom' => $nomEquipe,
			'Clubs_id' => $clubId,
			'Divisions_id' => $divisionId
		),
		array('id' => $id)
	);
}


//DELETE des équipes
if (isset($_GET['del'])) {
	$del_id = $_GET['del'];
	$wpdb->delete(
		$equipes,
		array('id' => $del_id),
		array('%d')
	);
}


//READ la liste des équipes
$equipeResults = $wpdb->get_results("
    SELECT e.id, e.Nom AS NomEquipe, c.Nom AS NomClub,c.Logo AS LogoClub , d.Division AS NomDivision
    FROM $equipes AS e
    INNER JOIN $clubs AS c ON e.Clubs_id = c.id
    LEFT JOIN $divisions AS d ON e.Divisions_id = d.id
");


//Affiche la liste des divisions
$divisionResults = $wpdb->get_results("SELECT * FROM $divisions");

// Récupérer les noms des clubs pour le select
$clubsSelect = $wpdb->get_results("SELECT id, Nom FROM $clubs");
// Récupérer les noms des divisions pour le select
$divisionsSelect = $wpdb->get_results("SELECT id, Division FROM $divisions");

?>