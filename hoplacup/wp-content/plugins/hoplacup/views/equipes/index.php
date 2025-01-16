<?php

// Si slug supplémentaire, il devrait s'agir de l'ID
if (isset($id_resource) && $id_resource) {

    $id_equipe = $id_resource;

    $url .= $id_equipe;

    include_once(plugin_dir_path(__FILE__) . 'model-single.php');

    $breadcrumbs[] = ['title' => $id_equipe, 'link' => $url];

    $view_template = plugin_dir_path(__FILE__) . 'template-single.php';
}


// Sinon afficher la vue en liste
else {

    include_once(plugin_dir_path(__FILE__) . 'model-list.php');

    $view_template = plugin_dir_path(__FILE__) . 'template-list.php';
}


require_once(dirname(dirname(plugin_dir_path(__FILE__))) . '/template-parts/html.php');
