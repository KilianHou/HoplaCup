<?php

/**
 * Generation de l'url de creation avec son token (nonce)
 * 
 */
$nonce       = wp_create_nonce('add-tournoi'); // creation du token
$actions_url = plugin_dir_url(__FILE__) . 'crud-actions.php';

$add_url = add_query_arg(
    array(
        'action' => 'add',
        '_wpnonce' => $nonce
    ),
    $actions_url
);

?>

<div class="wrap">

    <h1 class="wp-heading-inline">Liste des Tournois</h1>
    <a href="<?= esc_url($add_url) ?>" class="page-title-action">
        Créer un tournoi
    </a>
    <a href="#" id="view-archived-tournois" class="page-title-action">
        Voir les tournois archivés
    </a>
    
    <hr class="wp-header-end">

    <form id="tournois-list" method="post" action="<?= plugin_dir_url(__FILE__) . 'crud-actions.php' ?>">
        <?php display_tournois_list_table(); ?>
    </form>

    <?php
    global $wpdb;
    $tournois_table = $wpdb->prefix . 'tournois';

    $query = $wpdb->prepare(
        "SELECT * FROM $tournois_table WHERE archivé = %d",
        1
    );
    
    $tournois_archives = $wpdb->get_results($query);


    ?>
  <div id="archived-tournois-container" style="display: none; margin-top: 20px;">
    <h2>Tournois Archivés</h2>
    <table id="archived-tournois-table" style="width: 50%; border-collapse: collapse; margin-top: 10px; margin-left: 0;">
        <thead>
            <tr style="background-color: #f1f1f1; border-bottom: 2px solid #ccc;">
                <th style="width: 20%; padding: 6px; text-align: left; border: 1px solid #ddd;">ID</th>
                <th style="width: 80%; padding: 6px; text-align: left; border: 1px solid #ddd;">Nom</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tournois_archives)): ?>
                <?php foreach ($tournois_archives as $tournoi): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 6px; border: 1px solid #ddd;"><?php echo esc_html($tournoi->ID); ?></td>
                        <td style="padding: 6px; border: 1px solid #ddd;"><?php echo esc_html($tournoi->Nom); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="padding: 6px; text-align: center; border: 1px solid #ddd;">Aucun tournoi archivé trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    #archived-tournois-table th,
    #archived-tournois-table td {
        padding: 6px; /* Reduce padding */
        text-align: left;
        border: 1px solid #ddd;
        font-size: 14px; /* Compact font size */
    }

    #archived-tournois-table th {
        background-color: #f1f1f1;
        font-size: 14px; /* Compact header font size */
    }

    #archived-tournois-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    #archived-tournois-table tr:hover {
        background-color: #f0f0f0;
    }
</style>

<script>
    jQuery(document).ready(function($) {
        // Toggle visibility of archived tournaments table
        $('#view-archived-tournois').on('click', function(e) {
            e.preventDefault();
            $('#archived-tournois-container').toggle();
        });

         /**
         * Double-avertissement de suppression de tournoi
         */
        $('.delete-link').on('click', function(e) {
            e.preventDefault()
            let link = $(this)
            if (confirm("Êtes-vous sûr de vouloir supprimer ce tournoi ?\nCela supprimera tous ses matchs, scores, et associations d\'équipes.")) {
                if (confirm("Attention, cette action est irréversible !\nToutes les informations liées à ce match seront perdues.")) {
                    window.location.href = link.attr('href')
                }
            }
        })

        /**
         * Double-avertissement de suppression de tournois en lots
         */
        $('#doaction').on('click', function(event) {
            let selectedAction = $('#bulk-action-selector-top').val()
            if (selectedAction === 'delete') {
                event.preventDefault()
                if (confirm("Êtes-vous sûr de vouloir supprimer les tournois sélectionnés ?\nToutes ces données seront perdues")) {
                    if (confirm("Cette action est irréversible. Êtes-vous vraiment sûr ?")) {
                        $('#tournois-list').submit()
                    }
                }
            }
        })

    });

</script>
