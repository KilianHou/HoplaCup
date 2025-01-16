<?php

/**
 * Generation de l'url de creation avec son token (nonce)
 * 
 */
$nonce = wp_create_nonce('add-division'); // creation du token
$actions_url = plugin_dir_url(__FILE__) . 'actions.php';
$add_url = add_query_arg(
    array(
        'action' => 'add',
        '_wpnonce' => $nonce
    ),
    $actions_url
);
?>

<div class="wrap">

    <h1 class="wp-heading-inline">Liste des divisions</h1>
    <a href="<?= esc_url($add_url) ?>" class="page-title-action">
        Ajouter une division
    </a>
    <hr class="wp-header-end">

    <form id="divisions-list" method="post" action="<?= plugin_dir_url(__FILE__) . 'actions.php' ?>">
        <?php display_divisions_list_table(); ?>
    </form>

</div>