<?php

global $wpdb;

$equipes = $wpdb->prefix . 'equipes';
$clubs = $wpdb->prefix . 'clubs';
$pays = $wpdb->prefix . 'pays';
$contact = $wpdb->prefix . 'contact';
$divisions = $wpdb->prefix . 'divisions';

//CREATE de clubs
if (isset($_POST['submitClub'])) {
    $ImageUpload = new ImageUpload();

	$nomClub = trim($_POST['nomClub']);
	$villeClub = trim($_POST['villeClub']);
	$paysClub = trim($_POST['paysClub']);
    $contactClub = trim($_POST['contactClub']);

	if (empty($nomClub)){
        echo "<div class='notice notice-error'><p>Erreur : Le nom du club ne peut pas être vide.</p></div>";
        return;
    }
    if (strlen($nomClub) > 30 || strlen($villeClub) > 30 || strlen($paysClub) > 30 || strlen($contactClub) > 100) {
        echo "<div class='notice notice-error'><p>Erreur : Un ou plusieurs champs dépassent la longueur maximale autorisée.</p></div>";
        return;
    }

    $logoLink;
    if (isset($_FILES['logoClub'])) {
        $logo = $_FILES['logoClub'];
        $logoLink = $ImageUpload->upload_images($logo);
    }

    $wpdb->insert($clubs, array('Nom' => $nomClub, 'Ville' => $villeClub,'Pays' => $paysClub,'Contact' => $contactClub, 'Logo' => $logoLink));
}


// UPDATE des clubs
if (isset($_POST['update_club'])) {
    $id = intval($_POST['id']);
    $nomClub = trim($_POST['nomClub']);
    $villeClub = trim($_POST['villeClub']);
    $paysClub = trim($_POST['paysClub']);
    $contactClub = trim($_POST['contactClub']); // Peut être vide
    $logoClub = $_POST['division_image_id'];

    if (empty($nomClub) || empty($villeClub) || empty($paysClub)) {
        echo "<div class='notice notice-error'><p>Erreur : Tous les champs obligatoires doivent être remplis lors d'une modification (Nom, Ville, Pays).</p></div>";
    } else {
        $wpdb->update(
            $clubs,
            array(
                'Nom' => $nomClub,
                'Ville' => $villeClub,
                'Logo' => $logoClub,
                'Pays' => $paysClub,
                'Contact' => $contactClub,
            ),
            array('ID' => $id)
        );
    }
}



//DELETE des clubs
if (isset($_GET['delete_club_id'])) {
    $del_id = $_GET['delete_club_id'];
    $wpdb->delete(
        $clubs,
        array('ID' => $del_id),
        array('%d')
    );
}

$filterName = isset($_GET['filter-name']) ? trim($_GET['filter-name']) : '';
$filterCity = isset($_GET['filter-city']) ? trim($_GET['filter-city']) : '';
$filterCountry = isset($_GET['filter-country']) ? trim($_GET['filter-country']) : '';

$whereClauses = [];

if (!empty($filterName)) {
    $whereClauses[] = "Nom LIKE '%" . esc_sql($filterName) . "%'";
}

if (!empty($filterCity)) {
    $whereClauses[] = "Ville LIKE '%" . esc_sql($filterCity) . "%'";
}

if (!empty($filterCountry)) {
    $whereClauses[] = "Pays LIKE '%" . esc_sql($filterCountry) . "%'";
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = "WHERE " . implode(' AND ', $whereClauses);
}


//Affiche la liste des clubs
$clubResults = $wpdb->get_results("SELECT * FROM $clubs $whereSQL");

// utilitaires pour l'interface champ images
wp_enqueue_media();
?>

<?php

global $wpdb;

$equipes = $wpdb->prefix . 'equipes';
$clubs = $wpdb->prefix . 'clubs';
$divisions = $wpdb->prefix . 'divisions';

//CREATE d'équipes
if (isset($_POST['submitEquipe'])) {
    $nomEquipe = trim($_POST['nomEquipe']);
    $clubId = trim($_POST['nomClub']);
    $divisionId = trim($_POST['nomDivision']);

    if (empty($clubId)) {
        echo "<div class='notice notice-error'><p>Erreur : L'équipe doit avoir un club</p></div>";
        return;
    }
    if (empty($nomEquipe)){
        echo "<div class='notice notice-error'><p>Erreur : Le nom de l'équipe ne peut pas être vide.</p></div>";
        return;
    }

    try {
        $wpdb->insert($equipes, array('Nom' => $nomEquipe, 'Clubs_id' => $clubId, 'Divisions_id' => $divisionId));
        echo "<div class='notice notice-success'><p>Équipe ajoutée avec succès</p></div>";
    } catch (Exception $e) {
        echo "<div class='notice notice-error'><p>Erreur : Une erreur est survenue</p></div>";
    }
}


//UPDATE des équipes
if (isset($_POST['update_equipe'])) {
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
if (isset($_POST['delete_equipe']) && isset($_POST['delete_equipe_id'])) {
	$del_id = $_POST['delete_equipe_id'];
	$wpdb->delete(
		$equipes,
		array('id' => $del_id),
		array('%d')
	);
}

// Récupérer les noms des divisions pour le select
$divisionsSelect = $wpdb->get_results("SELECT id, Division FROM $divisions");

?>