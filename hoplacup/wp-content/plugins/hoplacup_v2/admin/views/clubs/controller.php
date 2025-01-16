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
    ),
    array(
        'name' => 'countrySelectSetter',
        'url' => plugin_dir_url(__FILE__) . 'countrySelectSetter.js',
        'dependencies' => array(),
        'version' => null,
        'in_footer' => true
    )
);

$this->add_scripts($view_scripts);



if(isset($_GET['clubId'])){
    $view_styles = array(
        array(
            'name' => 'style',
            'url' => plugin_dir_url(__FILE__) . 'clubDetailStyles.css',
            'dependencies' => array(),
            'version' => '1.0.0',
            'media' => 'all'
        )
    );

    $this->add_styles($view_styles);
    $this->set_view_html(plugin_dir_path(__FILE__) . 'clubDetailView.php');
    $this->set_model(plugin_dir_path(__FILE__) . 'clubDetailModel.php');
}
else{
    $this->add_styles($view_styles);
    // Charge path du html de la view
    $this->set_view_html(plugin_dir_path(__FILE__) . 'html.php');
    // Charge path du model
    $this->set_model(plugin_dir_path(__FILE__) . 'model.php');
}