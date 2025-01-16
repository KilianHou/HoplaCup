<?php

// slugs supplémentaires :
// if(isset($url_segments[0]) && $url_segments[0]) { ... } ;


include_once(plugin_dir_path(__FILE__) . 'model.php');

$view_template = plugin_dir_path(__FILE__) . 'template.php';

require_once(dirname(dirname(plugin_dir_path(__FILE__))) . '/template-parts/html.php');
