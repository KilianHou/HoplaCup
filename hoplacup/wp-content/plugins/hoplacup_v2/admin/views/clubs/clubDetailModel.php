<?php
    global $wpdb;

    $clubs = $wpdb->prefix . 'clubs';
    $teams = $wpdb->prefix . 'equipes';
    $divisions = $wpdb->prefix . 'divisions';
    $equipes = $wpdb->prefix . 'equipes';

    $clubId = $_GET['clubId'];

    $clubInfos = $wpdb->get_row(
        $wpdb->prepare("
            SELECT id, Nom, Logo, Ville, Pays, Contact 
            FROM $clubs 
            WHERE id = %d
        ", $clubId)
    );
    
    $teamsInfos = $wpdb->get_results(
        $wpdb->prepare("
            SELECT e.id, e.Nom, e.Divisions_id, d.Division
            FROM $teams as e
            JOIN $divisions as d ON e.Divisions_id = d.id
            WHERE clubs_id = %d
        ", $clubId)
    );



    // MODIFIER UN CLUB
    if (isset($_POST['updateClub'])) {
        // Récupération des données du formulaire
        $nomClub = sanitize_text_field($_POST['nomClub']);
        $villeClub = sanitize_text_field($_POST['villeClub']);
        $paysClub = sanitize_text_field($_POST['paysClub']);
        $contactClub = sanitize_textarea_field($_POST['contactClub']);

        if (empty($clubId) || empty($nomClub)) {
            echo "L'identifiant du club et son nom sont requis pour effectuer une mise à jour.";
            return;
        }

        $ImageUpload = new ImageUpload();
        $logoLink = $clubInfos->Logo;
        if (isset($_FILES['logoClub'])) {
            $logo = $_FILES['logoClub'];
            if($logo['name']) $logoLink = $ImageUpload->upload_images($logo);
        }

        $result = $wpdb->update(
            $clubs,
            array(
                'Nom' => $nomClub,
                'Ville' => $villeClub,
                'Pays' => $paysClub,
                'Contact' => $contactClub,
                'Logo' => $logoLink
            ),
            array('id' => $clubId)
        );

        if ($result === false) {
            echo "Une erreur est survenue lors de la mise à jour du club.";
        } else {
            if($logoLink != $clubInfos->Logo) $ImageUpload->delete_file_by_url($clubInfos->Logo);
            echo '<script>window.location.reload();</script>';
        }
    }

    // MODIFIER UNE ÉQUIPE
    if (isset($_POST['updateTeam'])) {
        $id = intval($_POST['id']);
        $nomEquipe = sanitize_text_field($_POST['nomEquipe']);
        $clubId = intval($_POST['nomClub']);
        $divisionId = intval($_POST['nomDivision']);

        if(!isset($id) || !isset($nomEquipe) || !isset($clubId) || !isset($divisionId)){
            echo "Il faut l'identifiant de l'équipe, son nom, l'identifiant de son club et de sa division pour pouvoir modifier une équipe.";
            return;
        }

        $wpdb->update(
            $equipes,
            array(
                'Nom' => $nomEquipe,
                'Clubs_id' => $clubId,
                'Divisions_id' => $divisionId
            ),
            array('id' => $id)
        );

        echo '<script>window.location.reload();</script>';
    }


    // SUPPRIMER UN CLUB
    if (isset($_GET['delete']) && $_GET['delete'] == 'club') {
        if (!isset($clubId) || $clubId === 0) {
            echo "Erreur : L'identifiant du club est manquant ou invalide.";
            return;
        }
        
        $wpdb->suppress_errors(true);

        // Suppress errors using `@` and try-catch for additional safety
        try {
            $clubDeleted = @$wpdb->delete(
                $clubs,
                array('id' => $clubId),
                array('%d')
            );
    
            if ($clubDeleted === false) {
                // If deletion failed, handle gracefully
                echo "<script>
            jQuery(document).ready(function($) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Ce club ne peut pas être supprimé car il est lié à un tournoi en cours.',
                });
            });
        </script>";
            } else {
                // Successful deletion
                $fileHandler = new ImageUpload();
                $fileHandler->delete_file_by_url($clubInfos->Logo);
    
                echo '<script>window.location.href="?page=hoplacup-v2-settings&view=clubs";</script>';
            }
        } catch (Exception $e) {
            // Additional error handling for unexpected exceptions
            echo "Hello";
        }
    }
    

    // SUPPRIMER UNE ÉQUIPE
    if (isset($_GET['delete']) && $_GET['delete'] == 'team') {
        $id = intval($_POST['id']);
    
        if (!isset($id) || $id === 0) {
            echo "Erreur : L'identifiant de l'équipe est manquant ou invalide.";
            return;
        }
    
        $wpdb->delete(
            $equipes,
            array('id' => $id)
        );

        echo "<script>window.location.href=\"?page=hoplacup-v2-settings&view=clubs&clubId=$clubId\";</script>";
    }

    //CREATE d'équipes
    if (isset($_POST['submitEquipe'])) {
        $nomEquipe = trim($_POST['nomEquipe']);
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
            echo '<script>window.location.reload();</script>';
        } catch (Exception $e) {
            echo "<div class='notice notice-error'><p>Erreur : Une erreur est survenue</p></div>";
        }
    }

    // Récupérer les informations des divisions et clubs nécessaires pour le select
    $divisionsSelect = $wpdb->get_results("SELECT id, Division FROM $divisions");
    $clubsSelect = $wpdb->get_results("SELECT id, Nom FROM $clubs");
?>