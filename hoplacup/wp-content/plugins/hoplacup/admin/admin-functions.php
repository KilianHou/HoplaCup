<?php

/**
 * Initialisation de la page Settings du plugin Hoplacup
 * Créé un lien de menu spécifique pour le plugin
 * dans le palen "Settings" de Wordpress
 *
 */

 /**
 * Initialisation de la disponibilité d'options pour le plugin
 */

 function hoplacup_plugin_options_page()
{
    add_options_page('Hoplacup Plugin Settings', 'Hoplacup Plugin', 'manage_options', 'hoplacup-plugin-settings', 'hoplacup_plugin_settings_page');
}

add_action('admin_menu', 'hoplacup_plugin_options_page');function hoplacup_register_settings()
{
    register_setting('hoplacup_plugin_options', 'hoplacup_plugin_enable_cron');
    register_setting('hoplacup_plugin_options', 'hoplacup_plugin_enable_date_simulation');
    register_setting('hoplacup_plugin_options', 'hoplacup_plugin_simulation_time');
    register_setting('hoplacup_plugin_options', 'hoplacup_plugin_simulation_day');
}add_action('admin_init', 'hoplacup_register_settings');

/**
 * Fonction qui charge la page contenant le formulaire admin dans Settings
 */

 function hoplacup_plugin_settings_page()
{
    include(plugin_dir_path(__FILE__) . '/view/settings-page.php');
}