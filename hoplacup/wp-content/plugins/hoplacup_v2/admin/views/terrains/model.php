<?php

global $wpdb;

// Noms des tables
$terrains_table = $wpdb->prefix . 'terrains';
$matchs_table = $wpdb->prefix . 'matchs';

// CREATE - Ajouter un terrain
if (isset($_POST['submitTerrain'])) {
    $nomTerrain = sanitize_text_field($_POST['nomTerrain']);
    if (!empty($nomTerrain)) {
        $wpdb->insert($terrains_table, array('Nom' => $nomTerrain));
        if ($wpdb->last_error) {
            $message = 'Erreur : ' . $wpdb->last_error;
            $message_type = 'notice-error';
        } else {
            $message = 'Le terrain a été ajouté avec succès.';
            $message_type = 'notice-success';
        }
    } else {
        $message = 'Erreur : Le champ Nom est requis.';
        $message_type = 'notice-error';
    }
}

// UPDATE - Mettre à jour un terrain
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nomTerrain = sanitize_text_field($_POST['nomTerrain']);
    if (!empty($nomTerrain)) {
        $wpdb->update($terrains_table, array('Nom' => $nomTerrain), array('ID' => $id));
        if ($wpdb->last_error) {
            $message = 'Erreur : ' . $wpdb->last_error;
            $message_type = 'notice-error';
        } else {
            $message = 'Le terrain a été mis à jour avec succès.';
            $message_type = 'notice-success';
        }
    } else {
        $message = 'Erreur : Le champ Nom est requis pour la mise à jour.';
        $message_type = 'notice-error';
    }
}

//DELETE des terrains
if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $wpdb->delete(
        $terrains_table,
        array('id' => $del_id),
        array('%d')
    );
}

// ASSIGN - Lier un terrain à un match
if (isset($_POST['terrain_id']) && isset($_POST['match_id'])) {
    $match_id = intval($_POST['match_id']);
    $terrain_id = intval($_POST['terrain_id']);

    if ($match_id && $terrain_id) {
        $wpdb->update(
            $matchs_table,
            array('Terrains_id' => $terrain_id),
            array('id' => $match_id)
        );

        if ($wpdb->last_error) {
            $message = 'Erreur : ' . $wpdb->last_error;
            $message_type = 'notice-error';
        } else {
            $message = 'Le terrain a été assigné au match avec succès.';
            $message_type = 'notice-success';
            echo '<script type="text/javascript">window.location.reload();</script>';
        }
    } else {
        $message = 'Erreur : Match ou terrain invalide.';
        $message_type = 'notice-error';
    }
}

// READ - Récupérer la liste des terrains
$terrainResults = $wpdb->get_results("SELECT ID, Nom FROM $terrains_table");
if (!$terrainResults) {
    $terrainResults = [];
}

// READ - Récupérer la liste des matchs
$matchResults = $wpdb->get_results("SELECT id, Temps, id_eq1, id_eq2, Terrains_id FROM $matchs_table");
if (!$matchResults) {
    $matchResults = [];
}

?>
