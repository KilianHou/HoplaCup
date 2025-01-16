<?php

$view_styles = array(
    array(
        'name' => 'style',
        'url' => plugin_dir_url(__FILE__) . 'styles.css',
        'dependencies' => array(),
        'version' => '1.0.0',
        'media' => 'all'
    )
);
$this->add_styles($view_styles);


// Charge path du model
$this->set_model(plugin_dir_path(__FILE__) . 'model.php');

// Charge path du html de la view
$this->set_view_html(plugin_dir_path(__FILE__) . 'html.php');
