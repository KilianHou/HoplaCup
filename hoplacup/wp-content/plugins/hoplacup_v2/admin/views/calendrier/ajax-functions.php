<?php

function ajax_calendar_action() {
	error_log('Function ajax_calendar_action triggered.');

	$datetime = isset($_POST['datetime']) ? sanitize_text_field($_POST['datetime']) : '';
	$terrain_id = isset($_POST['terrain_id']) ? sanitize_text_field($_POST['terrain_id']) : '';
	$match_id = isset($_POST['match_id']) ? sanitize_text_field($_POST['match_id']) : '';
	global $wpdb;
	$matchs = $wpdb->prefix . "matchs";

	$result = $wpdb->update(
		$matchs,
		array(
			'Terrains_id' => $terrain_id,
			'Temps' => $datetime
		),
		array('ID' => $match_id)
	);

	if ($result === false) {
		wp_send_json_error(array('message' => 'Échec de la mise à jour dans la base de données.'));
		return;
	}


	wp_send_json_success(array(
		'message' => 'Données mises à jour avec succès !',
		'terrain_id' => $terrain_id,
		'datetime' => $datetime
	));

}