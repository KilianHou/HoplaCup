<?php

function load_jquery_ui_admin()
{
    // Enregistrer jQuery UI dans l'administration
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');


}
add_action('admin_enqueue_scripts', 'load_jquery_ui_admin');

$view_scripts = array(
    array(
        'name' => 'sweetalert2',
        'url' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11',
        'dependencies' => array('jquery'),
        'version' => null,
        'in_footer' => true,
        'localize' => array( // permet de passer des variables php à scripts.js
            'object_name' => 'hoplacup_v2_ajax_obj',
            'data' => array(
                'ajax_url' => admin_url('admin-ajax.php'),
            )
        )
    )
);

/**
 * Vue item
 */
if (isset($_GET['subview']) && $_GET['subview'] == 'item') {
    /**
     * config step view
     */
    $step = $_GET['configstep'] ?? '';
    if (in_array($step, [1, 2, 3, 4, 5, 6])) {
        $this->set_model(plugin_dir_path(__FILE__) . "step$step/model.php");
        $this->set_view_html(plugin_dir_path(__FILE__) . "step$step/html.php");
        $this->add_cssfile(plugin_dir_url(__FILE__) . "step$step/styles.css");
        $this->create_localize_data();
        $this->add_jsfile(plugin_dir_url(__FILE__) . "step$step/scripts.js", array('localize' => $this->get_localize_data()));
        $this->add_scripts($view_scripts);
    }
    /**
     * home item view
     */
    else {
        $this->set_model(plugin_dir_path(__FILE__) . "subviews/model-item.php");
        $this->set_view_html(plugin_dir_path(__FILE__) . "subviews/html-item.php");
        $this->add_cssfile(plugin_dir_url(__FILE__) . "subviews/styles-item.css");
    }
}
/**
 * Vue liste
 */
else {
    $this->set_model(plugin_dir_path(__FILE__) . 'subviews/model-list.php');
    $this->set_view_html(plugin_dir_path(__FILE__) . 'subviews/html-list.php');
    $this->add_cssfile(plugin_dir_url(__FILE__) . 'subviews/styles-list.css');
}