<?php

/*
Plugin Name: Hopla Cup V2
Description: Plugin personnalisé pour administrer le tournois HoplaCup.
Version: 2.0
*/

class HoplaCupV2
{
    const ADMIN_ROOT_SETTINGS_URL = 'admin.php?page=hoplacup-v2-settings';
    const PUBLIC_ROOT_SLUG = 'tournois';

    public function __construct()
    {
        register_activation_hook(__FILE__, array($this, 'plugin_activate'));

        if (is_admin()) {
            $this->handle_admin_requests();
        } else {
            $this->handle_front_requests();
        }

        $this->enqueue_scripts();
    }

    /**
     * Event appelé à l'activation du plugin
     */
    public function plugin_activate()
    {
        require_once plugin_dir_path(__FILE__) . '/admin/init-tables.php';
    }

    /**
     * Init admin
     */
    private function handle_admin_requests()
    {
        require_once(plugin_dir_path(__FILE__) . '/admin/controller.php');
        $admin_controller = new Plugin_HoplaCup_Admin_Controller();

        if (wp_doing_ajax()) {
            $admin_controller->register_ajax_handlers();
        } else {
            $admin_controller->settings_root = self::ADMIN_ROOT_SETTINGS_URL;
            $admin_controller->init_hooks();
        }
    }

    /**
     * Init front
     */
    private function handle_front_requests()
    {
        require_once plugin_dir_path(__FILE__) . 'front/router-front.php';
    }

    /**
     * Enqueue styles/scripts (Font Awesome)
     */
    private function enqueue_scripts()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style(
                'font-awesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
                array(),
                '6.0.0'
            );
        });

        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style(
                'font-awesome-admin',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
                array(),
                '6.0.0'
            );
        });
    }
}

new HoplaCupV2();
