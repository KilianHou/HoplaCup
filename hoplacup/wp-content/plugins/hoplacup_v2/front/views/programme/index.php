<?php

// Parametres optionnels à récupérer :
// $parametre = $url_segments[2];

include_once(plugin_dir_path(__FILE__) . 'model.php');

$view_template = plugin_dir_path(__FILE__) . 'template.php';

require_once(dirname(dirname(plugin_dir_path(__FILE__))) . '/template-parts/html.php');
