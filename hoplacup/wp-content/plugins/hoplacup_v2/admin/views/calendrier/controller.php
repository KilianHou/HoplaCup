<?php


/**
 * Utiliser variables GET_ pour spécialiser les vues (list, item, form etc ...)
 */
function load_jquery_ui_admin()
{
	// Enregistrer jQuery UI dans l'administration
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-sortable');

}
add_action('admin_enqueue_scripts', 'load_jquery_ui_admin');



/**
 * Utiliser variables GET_ pour spécialiser les vues (list, item, form etc ...)
 */

$view_styles = array(
    array(
        'name' => 'style',
        'url' => plugin_dir_url(__FILE__) . 'styles.css',
        'dependencies' => array(),
        'version' => '1.0.0',
        'media' => 'all'
    )
    // , Ajout d'autres styles au besoin
);

$this->add_styles($view_styles); // Appelle la méthode pour ajouter les styles
$this->set_model(plugin_dir_path(__FILE__) . 'model.php');
$this->set_view_html(plugin_dir_path(__FILE__) . 'html.php');
