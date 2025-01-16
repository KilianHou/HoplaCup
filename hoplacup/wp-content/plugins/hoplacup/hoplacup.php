<?php

/*
Plugin Name: Hopla Cup
Description: Plugin personnalisé pour récupérer périodiquement les données depuis Google Sheets et les insérer dans la base de données.
Version: 1.0
*/

$public_root_slug = 'tournois';

// Routing des pages vue front
require_once plugin_dir_path(__FILE__) . 'router-front.php';
require_once plugin_dir_path(__FILE__) . '/insertbdd/insertmatch.php';
require_once plugin_dir_path(__FILE__) . '/insertbdd/insertmatch911.php';
require_once plugin_dir_path(__FILE__) . '/includes/database/create-tables.php';
require_once plugin_dir_path(__FILE__) . '/includes/refresh-functions.php';
require_once plugin_dir_path(__FILE__) . '/admin/admin-functions.php';

register_activation_hook(__FILE__, 'create_tables');
register_deactivation_hook(__FILE__, 'clear_refresh_cron');
