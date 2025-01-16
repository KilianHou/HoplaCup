<?php

/************************************************************************
 *
 * Ce fichier contient les fonctions nécessaires au refresh par cron task
 * de la base de donnée avec les données du Google Sheets.
 *
 **********************************************************************/

require_once plugin_dir_path(__FILE__) . '../insertbdd/insertmatch.php';
require_once plugin_dir_path(__FILE__) . '../insertbdd/insertmatch911.php';

/**
 * Fonction qui est appelée périodiquement par la tâche Cron.
 * Elle s'occupe de remplacer le contenu existant par les données
 * qu'elle récupère depuis Google Sheets
 */
function refresh_data()
{
    // Executer les fonctions de fetch GoogleSheets / insert en BDD
    hoplacup_import_data_from_sheets(); // hoplacup > insertbb > insertmatch.php
    hoplacup_import_data_from_sheets2();
}


/**
 * Si le plugin est désactivé, cette fonction est appelée :
 * Supprimer l'option qui mémorise en BDD si la cron est active,
 * et supprime la tâche Cron
 * Cette fonction est appelée par le Hook de désactivtion du plugin
 * qui est défini dans le fichier racine hoplacup.php
 */
function clear_refresh_cron()
{
    delete_option('hoplacup_plugin_enable_cron');
    wp_clear_scheduled_hook('refresh_data_event');
}


/**
 * Cette fonction enregistre une option pour le plugin.
 * Elle utilise la fonction register_setting() de WordPress pour
 * enregistrer l'option hoplacup_plugin_enable_cron dans le groupe hoplacup_plugin_options.
 */
function register_plugin_options()
{
    register_setting('hoplacup_plugin_options', 'hoplacup_plugin_enable_cron');
}
/**
 * add_action(),  ajoute une action à l'événement admin_init, ce qui signifie
 * que la fonction register_plugin_options() sera exécutée lorsque
 * l'administration de WordPress est initialisée.
 */
add_action('admin_init', 'register_plugin_options');


/**
 * Cette fonction définit un nouvel intervalle de temps
 * de 60 secondes qui est rendu disponible pour la cron.
 * (l'intervalle de 60s n'est pas disponible par défaut, on doit donc le définir)
 */
function add_custom_cron_interval($schedules)
{
    $schedules['every_minutes'] = array(
        'interval' => 60, // 60 secondes
        'display' => __('Toutes les minutes')
    );
    return $schedules;
}
// ajoute l'intervalle à ceux disponibles par défaut
add_filter('cron_schedules', 'add_custom_cron_interval');


/**
 * Cette fonction est appelée quand l'administrateur enclenche
 * la cron (case à cocher dans les "Settings" > "HoplaCup Plugin" du panel admin)
 */
function handle_activate_refresh()
{
    refresh_data();
    if (!wp_next_scheduled('refresh_data_event')) { // si la tâche n'est pas déjà planifiée,
        // initialise et enclenche la planification qu'on nomme "refresh_data_event"
        wp_schedule_event(time(), 'every_minutes', 'refresh_data_event');
    }
}
// On associe l'évènement "refresh_data_event" à la fonction refresh_data() :
add_action('refresh_data_event', 'refresh_data');


/**
 * Fonction qui sert de "pont" entre l'action de l'administrateur
 * et la fonction d'activation de de planification
 */
function activate_refresh()
{
    handle_activate_refresh();
}
// La fonction est reliée à l'action de l'admin
add_action('admin_post_activate_refresh', 'activate_refresh');


/**
 * Cette fonction est appelée quand l'administrateur désactive
 * la cron (case décochée dans les "Settings" > "HoplaCup Plugin" du panel admin)
 */
function handle_deactivate_refresh()
{
    wp_clear_scheduled_hook('refresh_data_event');
}


/**
 * Fonction qui sert de "pont" entre l'action de l'administrateur
 * et la fonction d'activation de de planification
 */
function deactivate_refresh()
{
    handle_deactivate_refresh();
}
// La fonction est reliée à l'action de l'admin
add_action('admin_post_deactivate_refresh', 'deactivate_refresh');


/**
 * Cette fonction est appelée lors de la soumission du formulaire de paramètres du plugin.
 * Elle vérifie si le formulaire a été soumis en vérifiant si la variable $_POST['submit'] est définie.
 * Si le formulaire a été soumis, elle appelle la fonction handle_cron_toggle()
 * pour gérer la bascule de la tâche cron en fonction de l'état de la case à cocher.
 */
function handle_plugin_settings_form_submission()
{
    if (isset($_POST['submit'])) {
        handle_cron_toggle();
    }
}


/**
 * Cette ligne ajoute une action à l'initialisation de l'administration WordPress.
 * Lorsque l'administration WordPress est initialisée, la
 * fonction handle_plugin_settings_form_submission() est appelée.
 * Cette fonction est responsable de la gestion de la soumission du formulaire de paramètres du plugin.
 *
 */
add_action('admin_init', 'handle_plugin_settings_form_submission');


/**
 * Cette fonction est chargée de basculer l'état de la tâche cron
 * en fonction de l'état de la case à cocher dans le formulaire.
 * Elle vérifie si la case à cocher a été cochée en évaluant
 * la valeur de $_POST['hoplacup_plugin_enable_cron'] qui vaut soit "on" soit "" (vide).
 * Si elle est sur "on", elle appelle la fonction activate_refresh() pour activer la tâche cron.
 * Sinon, elle appelle la fonction deactivate_refresh() pour désactiver la tâche cron.
 */
function handle_cron_toggle()
{
    $enabled = isset($_POST['hoplacup_plugin_enable_cron']) && $_POST['hoplacup_plugin_enable_cron'] === 'on';
    if ($enabled) {
        activate_refresh();
    } else {
        deactivate_refresh();
    }
}
