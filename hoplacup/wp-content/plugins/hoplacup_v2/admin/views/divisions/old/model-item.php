<?php


function get_division($division_id)
{
    global $wpdb;

    $table_division = $wpdb->prefix . 'divisions';
    $query = $wpdb->prepare("SELECT * FROM $table_division WHERE id = %d", $division_id);
    $division = $wpdb->get_row($query);

    if ($division) {
        return $division;
    } else {
        return false;
    }
}

// utilitaires pour l'interface champ images
wp_enqueue_media();
