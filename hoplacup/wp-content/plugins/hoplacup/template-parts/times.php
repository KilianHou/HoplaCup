<?php

global $wpdb;

date_default_timezone_set("Europe/Paris");

$simulation = $wpdb->get_var("SELECT option_value
                                FROM {$wpdb->prefix}options
                                WHERE option_name = 'hoplacup_plugin_enable_date_simulation'");

if(!$simulation == 'on') {
    $day = current_time('D');
    $timer = current_time('H:i');
} else {
    $day = $wpdb->get_var("SELECT option_value
                                FROM {$wpdb->prefix}options
                                WHERE option_name = 'hoplacup_plugin_simulation_day'");

    $timer = $wpdb->get_var("SELECT option_value
                                FROM {$wpdb->prefix}options
                                WHERE option_name = 'hoplacup_plugin_simulation_time'");
}

if ($day == 'Mon' || $day == 'Thu' || $day == 'Wed' || $day == 'Tue' || ($day == 'Fri' && $timer <= '17:00')) {
    $day = 'Fri';
    $timer = '17:00';
} elseif ($day == 'Fri' && $timer >= '17:00' && $day != 'Sat' && $day != 'Sun') {
	$displayDate = date('Y-m-d');
    
	// Ajouter d'autres actions ici si nécessaire
}

if($simulation and $day == 'Sat') {
	$displayDate = '2024-06-01';
} elseif ($simulation and $day == 'Sun') {
	$displayDate = '2024-06-02';
} else {
	$displayDate = '2024-05-31';
}