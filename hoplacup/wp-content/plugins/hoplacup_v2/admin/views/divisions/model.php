<?php
    global $wpdb;

    $divisions = $wpdb->prefix . 'divisions';

    // CREATE de division
    if (isset($_POST['submitDivision'])) {
        $nomDivision = trim($_POST['nomDivision']);
        if (empty($nomDivision)) {
            echo "<div class='notice notice-error'><p>Erreur : Le nom de la division ne peut pas être vide.</p></div>";
        } else {
            $result = $wpdb->insert($divisions, array('Division' => $nomDivision));
            if ($result) {
                echo "<div class='notice notice-success'><p>La division a été ajoutée avec succès.</p></div>";
            }
        }
    }

    // UPDATE des divisions
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nomDivision = $_POST['nomDivision'];
        if (empty($nomDivision)) {
            echo "<div class='notice notice-error'><p>Erreur : Le nom de la division ne peut pas être vide.</p></div>";
        } else {
            $result = $wpdb->update(
                $divisions,
                array('Division' => $nomDivision),
                array('id' => $id)
            );
            if ($result !== false) {
                echo "<div class='notice notice-success'><p>La division a été modifiée avec succès.</p></div>";
            }
        }
    }

    // DELETE des divisions
    if (isset($_GET['del'])) {
        $del_id = $_GET['del'];
        $wpdb->delete(
            $divisions,
            array('id' => $del_id),
            array('%d')
        );

        // Afficher uniquement le message de succès
        echo "<div class='notice notice-success'><p>La division a été supprimée avec succès.</p></div>";
    }

    // Affiche la liste des divisions
    $divisionResults = $wpdb->get_results("SELECT * FROM $divisions");
?>
