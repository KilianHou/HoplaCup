<?php

/**
 * 
 * Controller admin
 * Récupère et génère la view (equipes, division etc.)
 * 
 */

require_once(ABSPATH . 'wp-includes/pluggable.php');

class Plugin_HoplaCup_Admin_Controller
{

    private $view; // stocke le nom de la view (le même que son nom de dossier)
    private $view_html; // stocke le path du template de la view
    private $model; // stocke le path du fichier de données (model) de la view
    private $styles; // stocke les feuilles de styles
    private $scripts; // stocke les scripts
    private $views = []; // views définies dans admin_views.php
    private $menu_views = []; // Array de views à afficher dans le menu
    private $localize_data; //

    public $settings_root; // Défini dans fichier racine du plugin Ex : options-general.php?page=hoplacup-v2-settings';



    public function __construct()
    {
        // si requete vers interface des settings du plugin, charge la vue
        if (isset($_GET['page']) && $_GET['page'] === 'hoplacup-v2-settings') {
            $this->init_page();
        }
    }

    /**
     * init_hooks
     * -> Ajout du lien plugin hoplacup dans le menu settings
     */
    public function init_hooks()
    {
        add_action('admin_menu', array($this, 'add_plugin_submenu'));
    }

    /**
     * Ajout du lien dans menu settings/réglages wordpress
     */

    // Regarde si permission

    function user_has_capabilities($capabilities) {
        foreach ($capabilities as $capability) {
            if (current_user_can($capability)) {
                return true;
            }
        }
        return false;
    }

    public function add_plugin_submenu()
    {
        if ($this->user_has_capabilities(['hoplacup', 'manage_options'])) // Si l'utilisateur a les droits
            add_menu_page( // Add menu
                'HoplaCup V2',
                'HoplaCup V2',
                'read',
                'hoplacup-v2-settings',
                array($this, 'render_dashboard'),
                'dashicons-sos'
            );

            foreach ($this->get_menu_views() as $menu_view => $menu_title) {
                add_submenu_page(
                    'hoplacup-v2-settings',
                    $menu_title,
                    $menu_title,
                    'read',
                    'hoplacup-v2-settings&view=' . $menu_view,
                    array($this, 'render_dashboard')
                );
            }
            remove_submenu_page('hoplacup-v2-settings', 'hoplacup-v2-settings'); // Enlève un sous-menu en double
    }

    /**
     * init_page
     */
    public function init_page()
    {
        $this->styles[] = array(
            'name' => 'global-style',
            'url' => plugin_dir_url(__FILE__) . 'assets/global.css',
            'dependencies' => array(),
            'version' => '1.0.0',
            'media' => 'all'
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
        $this->add_scripts($view_scripts);

        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles')); // Hook pour charger les styles dans le contexte de l'administration
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts')); // Hook pour charger les scripts dans le contexte de l'administration
        $this->register_views();
        $this->handle_view_request(); // Détermine la vue à afficher
    }

    /**
     * Ajoute les scriopts
     */
    public function enqueue_scripts()
    {
        if (!empty($this->scripts)) {
            foreach ($this->scripts as $script) {
                wp_enqueue_script($script['name'], $script['url'], $script['dependencies'], $script['version'], $script['in_footer']);
                if (isset($script['localize'])) {
                    $script['localize']['data']['nonce'] = wp_create_nonce('hoplacup_v2_nonce');
                    wp_localize_script($script['name'], $script['localize']['object_name'], $script['localize']['data']);
                }
            }
        }
    }

    /**
     * Ajoute les feuilles Css
     */
    public function enqueue_styles()
    {
        if (!empty($this->styles)) {
            foreach ($this->styles as $style) {
                wp_enqueue_style($style['name'], $style['url'], $style['dependencies'], $style['version'], $style['media']);
            }
        }
    }

    /**
     * Détermine la view
     */
    public function handle_view_request()
    {
        $view = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'home';
        $view = in_array($view, $this->views) ? $this->set_view($view) : $this->set_view('home');
        require_once(plugin_dir_path(__FILE__) . 'views/' . $this->get_view() . '/controller.php');
    }

    private function generate_handle_name($url)
    {
        // génère un name de fichier à partir d'un path de fichier
        return basename($url, '.' . pathinfo($url, PATHINFO_EXTENSION));
    }

    /**
     * Méthode d'ajout de feuilles de styles
     */
    public function add_styles($additional_styles)
    {
        $this->styles = isset($this->styles[0]) ? array_merge($this->styles, $additional_styles) : $additional_styles;
    }

    public function add_cssfile($url, $name = null, $version = '1.0.0', $media = 'all')
    {
        if ($name === null) {
            $name = $this->generate_handle_name($url);
        }
        $this->styles[] = array(
            'name' => $name,
            'url' => $url,
            'dependencies' => array(),
            'version' => $version,
            'media' => $media
        );
    }

    /**
     * Méthode d'ajout de scripts
     */
    public function add_scripts($additional_scripts)
    {
        $this->scripts = isset($this->scripts[0]) ? array_merge($this->scripts, $additional_scripts) : $additional_scripts;
    }

    public function add_jsfile($url, $options = array())
    {
        $default_options = array(
            'name' => $this->generate_handle_name($url),
            'dependencies' => array('jquery'),
            'version' => '1.0.0',
            'in_footer' => true,
            'localize' => null
        );
        $options = array_merge($default_options, $options);
        $script = array(
            'name' => $options['name'],
            'url' => $url,
            'dependencies' => $options['dependencies'],
            'version' => $options['version'],
            'in_footer' => $options['in_footer']
        );
        if ($options['localize']) {
            $script['localize'] = $options['localize'];
        }
        $this->scripts[] = $script;
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Set/Get view
     */
    public function set_view_html($template)
    {
        $this->view_html = $template;
    }
    public function get_view_html()
    {
        return $this->view_html;
    }

    /**
     * Render dashboard view
     */
    public function render_dashboard()
    {
        require_once($this->get_model());
        require_once(plugin_dir_path(__FILE__) . '/dashboard.php');
    }

    /**
     * Set/Get view
     */
    public function set_view($view)
    {
        $this->view = $view;
    }
    public function get_view()
    {
        return $this->view;
    }

    /**
     * Set/Get model
     */
    public function set_model($model)
    {
        $this->model = $model;
    }
    public function get_model()
    {
        return $this->model;
    }

    /**
     * Set/Get admin/menu views
     */
    public function register_views()
    {
        require_once(plugin_dir_path(__FILE__) . 'admin_views.php');
        $this->views = $admin_views;
        $this->menu_views = $menu_views;
    }
    public function get_views()
    {
        return $this->views;
    }
    public function get_menu_views()
    {
        return $this->menu_views;
    }


    /**
     * Objet localize data, necessaire aux fichiers de scripts
     * qui définissent des requêtes ajax wordpress
     */
    public function create_localize_data()
    {
        $this->localize_data = array(
            'object_name' => 'hoplacup_v2_ajax_obj',
            'data' => array(
                'ajax_url' => admin_url('admin-ajax.php'),
            )
        );
    }
    public function get_localize_data()
    {
        return $this->localize_data;
    }


    /**
     * Prise en charge des requêtes ajax
     */
    public function register_ajax_handlers()
    {
        $ajax_handlers = include(plugin_dir_path(__FILE__) . 'ajax-handlers.php'); // Inclut le fichier de définition des fonctions AJAX
        foreach ($ajax_handlers as $action => $file) {
            require_once $file; // Inclut le fichier contenant les fonctions de rappel pour l'action AJAX
            add_action("wp_ajax_$action", $action);
        }
    }
}

class ImageUpload {
    private $upload_dir;

    public function __construct() {
        $this->upload_dir = $this->get_plugin_dir() . '/hoplacup_v2/uploads'; // Définit le dossier d'upload
        $this->create_upload_dir();
    }

    private function get_plugin_dir() {
        if (defined('WP_SITEURL')) {
            $base_url = WP_SITEURL;
        } else {
            $base_url = get_option('siteurl');
        }
        return str_replace($base_url, ABSPATH, plugins_url());
    }

    private function create_upload_dir() { // Crée le dossier d'upload si il n'existe pas
        if (!file_exists($this->upload_dir)) {
            chmod($this->get_plugin_dir() . '/hoplacup_v2', 0755);
            wp_mkdir_p($this->upload_dir);
        }
    }

    private function show_error($message) { // Affiche les popups d'erreurs ?>
        <script>
            jQuery(document).ready(function($) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '<?= $message ?>',
                });
            });
        </script>
    <?php }

    public function upload_images($files) { // Upload les images
        // Permet d'avoir un id unique pour chaque fichier upload
        $file_extension = pathinfo($files['name'], PATHINFO_EXTENSION);
        
        $uniq_file_name = uniqid('img_', true) . '.' . $file_extension;

        $target_upload_file = $this->upload_dir . '/' . basename($uniq_file_name);

        if (move_uploaded_file($files['tmp_name'], $target_upload_file)) {
            $uploaded_file_url = plugins_url('uploads/' . $uniq_file_name, dirname(__FILE__));

            return $uploaded_file_url;
        } else {
            $this->show_error('Erreur lors de l\'upload du fichier');
            return false;
        }
    }

    // Suppression d'un fichier grace au uniq id
    // Si vous avez le lien du fichier -> utilisez delete_file_by_url
    public function delete_file($fileName){
        $filePath = $this->upload_dir . '/' . basename($fileName);

        if(!file_exists($filePath)){
            return [
                'success' => false,
                'message' => "Le fichier spécifié n'existe pas : $filePath"
            ];
        }

        if(unlink($filePath)){
            return [
                'success' => true,
                'message' => "Fichier supprimé avec succès : $filePath"
            ];
        }else {
            return [
                'success' => false,
                'message' => "Erreur lors de la suppression du fichier : $filePath"
            ];
        }
    }

    // Suppression d'un fichier grace à son url
    public function delete_file_by_url($fileUrl){
        $filesUrl = '/wp-content/plugins/hoplacup_v2/uploads/';

        $parsedUrl = parse_url($fileUrl);
        $fileName = str_replace($filesUrl, '', $parsedUrl['path']);

        return $this->delete_file($fileName);
    }
}
