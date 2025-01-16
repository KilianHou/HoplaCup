<?php


function load_view($view, $page_parameters)
{

    /**
     * Récupératon des parametres de page
     * 
     */
    $public_root_slug = $page_parameters['public_root_slug'];
    $public_root_url = $page_parameters['public_root_url'];
    $url = $page_parameters['url'];
    $url_division = $page_parameters['url_division'];
    $lang = $page_parameters['lang'];
    $division = $page_parameters['division'];
    $division_name = $page_parameters['division_name'];
    $page_title = $page_parameters['page_title'];
    $breadcrumbs = $page_parameters['breadcrumbs'];
    $display_switch_division = $page_parameters['display_switch_division'];
    $id_resource = $page_parameters['id_resource'];

    // Déclaration du type de contenu pour la vue à afficher
    header("Content-Type: text/html");

    $styles_head = array();

    require_once plugin_dir_path(__FILE__) . 'views/' . $view . '/index.php';
}
