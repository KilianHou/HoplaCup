<?php

$model = '';
$html  = '';
$view_styles = array();

/**
 * Si sous-vue item, charge vue formulaire
 */
if (isset($_GET['subview']) && $_GET['subview'] == 'item') {
    $view_styles = array(
        array(
            'name' => 'style-item',
            'url' => plugin_dir_url(__FILE__) . 'styles-item.css',
            'dependencies' => array(),
            'version' => '1.0.0',
            'media' => 'all'
        )
    );
    $model = plugin_dir_path(__FILE__) . 'model-item.php';
    $html  = plugin_dir_path(__FILE__) . 'html-item.php';
}
/**
 * Sinon charge la vue de la liste
 */
else {
    $view_styles = array(
        array(
            'name' => 'style-list',
            'url' => plugin_dir_url(__FILE__) . 'styles-list.css',
            'dependencies' => array(),
            'version' => '1.0.0',
            'media' => 'all'
        )
    );
    $model = plugin_dir_path(__FILE__) . 'model-list.php';
    $html  = plugin_dir_path(__FILE__) . 'html-list.php';
}

$this->add_styles($view_styles);
$this->set_model($model);
$this->set_view_html($html);
