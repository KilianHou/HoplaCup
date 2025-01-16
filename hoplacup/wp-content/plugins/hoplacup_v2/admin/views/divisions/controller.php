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
        )
    );

    $this->add_styles($view_styles);
    $this->add_scripts($view_scripts);

    // Charge path du model
    $this->set_model(plugin_dir_path(__FILE__) . 'model.php');

    // Charge path du html de la view
    $this->set_view_html(plugin_dir_path(__FILE__) . 'html.php');
?>