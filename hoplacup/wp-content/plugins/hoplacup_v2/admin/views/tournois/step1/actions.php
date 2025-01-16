<?php
global $wpdb;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_points'])) {
    // Vérification du nonce.
    if (!isset($_POST['points_nonce']) || !wp_verify_nonce($_POST['points_nonce'], 'save_points_settings')) {
        die('Nonce non valide.');
    }

    // Récupération des données du formulaire.
    $points_win = isset($_POST['points_win']) ? intval($_POST['points_win']) : 3;
    $points_draw = isset($_POST['points_draw']) ? intval($_POST['points_draw']) : 1;
    $points_loss = isset($_POST['points_loss']) ? intval($_POST['points_loss']) : 0;
    $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($tournoi_id > 0) {
        // Vérifiez si des points existent déjà pour ce tournoi.
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->prefix}points WHERE Tournoi_ID = %d",
            $tournoi_id
        ));

        if ($existing) {
            // Met à jour les points existants.
            $wpdb->update(
                "{$wpdb->prefix}points",
                [
                    'Points_Victoire' => $points_win,
                    'Points_Égalité' => $points_draw,
                    'Points_Défaite' => $points_loss,
                ],
                ['Tournoi_ID' => $tournoi_id],
                ['%d', '%d', '%d'],
                ['%d']
            );
        } else {
            // Insère de nouveaux points pour ce tournoi.
            $wpdb->insert(
                "{$wpdb->prefix}points",
                [
                    'Tournoi_ID' => $tournoi_id,
                    'Points_Victoire' => $points_win,
                    'Points_Égalité' => $points_draw,
                    'Points_Défaite' => $points_loss,
                ],
                ['%d', '%d', '%d', '%d']
            );
        }

        // Ajouter un message de succès.
        set_transient('points_success_message', 'Les points ont été mis à jour avec succès.', 10);
    } else {
        // Ajouter un message d'erreur.
        set_transient('points_error_message', 'ID du tournoi manquant.', 10);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AjouterDates'])) {

    global $wpdb;

    // Récupération des données du formulaire.
    $start_date = sanitize_text_field($_POST['start_date']);
    $end_date = sanitize_text_field($_POST['end_date']);
    $tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Validation des données.
    if (empty($start_date) || empty($end_date)) {
        echo "<div class='notice notice-error'><p>Erreur : Les deux dates sont obligatoires.</p></div>";
        return;
    }

    if ($tournoi_id <= 0) {
        echo "<div class='notice notice-error'><p>Erreur : ID du tournoi invalide.</p></div>";
        return;
    }

    // Préparation de la mise à jour dans la base de données.
    $table_tournois = $wpdb->prefix . 'tournois'; 
    $result = $wpdb->update(
        $table_tournois,
        array(
            'Date_debut' => $start_date,
            'Date_fin' => $end_date,
        ),
        array('id' => $tournoi_id),
        array('%s', '%s'), 
        array('%d') 
    );

    // Vérifiez si la mise à jour a réussi.
    if ($result !== false) { 
        if ($result > 0) {
            echo "<div class='notice notice-success'><p>Les dates ont été mises à jour avec succès.</p></div>";
        } else {
            echo "<div class='notice notice-error'><p>Aucune modification détectée.</p></div>";
        }
    } else {
        echo "<div class='notice notice-error'><p>Une erreur est survenue lors de la mise à jour.</p></div>";
    }
}

$tournoi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($tournoi_id > 0) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'tournois';
    $tournoi = $wpdb->get_row(
        $wpdb->prepare("SELECT Date_debut, Date_fin FROM $table_name WHERE id = %d", $tournoi_id),
        ARRAY_A
    );

    // Si les données sont trouvées, utilisez-les, sinon, utilisez la date actuelle par défaut.
    if ($tournoi) {
        $value_start_date = !empty($tournoi['Date_debut']) ? date('Y-m-d', strtotime($tournoi['Date_debut'])) : date('Y-m-d');
        $value_end_date = !empty($tournoi['Date_fin']) ? date('Y-m-d', strtotime($tournoi['Date_fin'])) : date('Y-m-d', strtotime('+2 days'));
    } else {
        echo "<div class='notice notice-error'><p>Tournoi introuvable. Les dates par défaut seront utilisées.</p></div>";
        $value_start_date = date('Y-m-d');
        $value_end_date = date('Y-m-d', strtotime('+2 days'));
    }
} else {
    echo "<div class='notice notice-error'><p>ID de tournoi manquant ou invalide.</p></div>";
    $value_start_date = date('Y-m-d'); 
    $value_end_date = date('Y-m-d', strtotime('+2 days'));
}

?>