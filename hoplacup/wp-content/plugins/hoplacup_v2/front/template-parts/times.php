<?php

global $wpdb;

date_default_timezone_set("Europe/Paris");

$simulation = $wpdb->get_var("SELECT option_value
                                FROM {$wpdb->prefix}options
                                WHERE option_name = 'hoplacup_plugin_enable_date_simulation'");

if($simulation != 'on') {

    $day = current_time('D');
    $timer = current_time('Hi');

} else {

    $day = $wpdb->get_var("SELECT option_value
                                FROM {$wpdb->prefix}options
                                WHERE option_name = 'hoplacup_plugin_simulation_day'");

    $timer = $wpdb->get_var("SELECT option_value
                                FROM {$wpdb->prefix}options
                                WHERE option_name = 'hoplacup_plugin_simulation_time'");

}

//echo $simulation.' '.$day.' '.$timer;
