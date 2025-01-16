<?php

/**
 * Définit les vues valides (division, equipes, etc.)
 * 
 * Chargé par controller.php pour déterminer la vue et son dossier à charger (admin/views/...)
 */
$admin_views = [
    'home',
    'divisions',
    'tournois',
    'clubs',
    'poules-equipes',
    'calendrier',
    'terrains'
];

/**
 * Views à afficher dans le menu admin settings
 */
$menu_views = [
    'home'           => 'Accueil',
    'divisions'      => 'Divisions',
    'clubs'          => 'Clubs',
    'terrains'       => 'Terrains',
    'tournois'       => 'Tournois',
    /* 'calendrier'     => 'Calendrier', */
];
