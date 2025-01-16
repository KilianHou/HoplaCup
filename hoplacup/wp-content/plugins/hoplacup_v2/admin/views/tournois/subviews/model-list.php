<?php

/**
 * Inclut la classe mère WP_List_Table si non présente
 */
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Retourne le nombre d'entrées
 */
function count_tournois()
{
    global $wpdb;
    $table_tournois = $wpdb->prefix . 'tournois';
    $query = "SELECT COUNT(*) FROM $table_tournois where Archivé = 0";
    $total_count = $wpdb->get_var($query);
    return $total_count;
}

/**
 * Fonction de requête de la liste des Tournois
 */
function get_tournois($per_page = 10, $page_number = 1)
{
    global $wpdb;
    $table_tournois = $wpdb->prefix . 'tournois';
    $table_poules = $wpdb->prefix . 'poules';
    $table_matchs = $wpdb->prefix . 'matchs';

    $offset = ($page_number - 1) * $per_page;
    $query = "SELECT id, Nom, archivé FROM $table_tournois where Archivé = 0 LIMIT %d, %d";
    $results = $wpdb->get_results($wpdb->prepare($query, $offset, $per_page));

    foreach ($results as $tournoi) {
        $count_query = $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_matchs WHERE `Poules_ID` IN 
            (SELECT `ID` FROM $table_poules WHERE `Tournoi_id` = %d)",
            $tournoi->id
        );
        $tournoi->match_count = $wpdb->get_var($count_query);
    }

    return $results;
}

/**
 * Déclaration de la classe fille pour générer le tableau tournois
 */
class Tournois_List_Table extends WP_List_Table
{
    private $per_page = 10;

    function get_columns()
    {
        return array(
            'cb'   => '<input type="checkbox" />',
            'id' => 'ID',
            'name' => 'Nom',
            'total-matches' => 'Matchs',
            'edit' => 'Editer',
            'delete' => "Supprimer",
            'archive' => 'Archiver', // New column for Archiver
        );
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $this->_column_headers = array($columns, array(), array());

        $total_items = count_tournois();
        $tournois = get_tournois($this->per_page, $this->get_pagenum());

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $this->per_page,
        ));
        $this->items = $tournois;
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="tournoi[]" value="%s" />',
            $item->id
        );
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'name':
                $edit_url = admin_url('admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=' . $item->id);
                return '<a href="' . esc_url($edit_url) . '">' . $item->Nom . '</a>';
                break;

            case 'total-matches':
                return $item->match_count;
                break;

            case 'id':
                return $item->id;

            case 'edit':
                $edit_url = admin_url('admin.php?page=hoplacup-v2-settings&view=tournois&subview=item&id=' . $item->id);
                return '<a href="' . esc_url($edit_url) . '">Éditer</a>';

            case 'delete':
                $nonce = wp_create_nonce('delete_tournoi_' . $item->id);
                $actions_url = plugin_dir_url(__FILE__) . 'crud-actions.php';
                $delete_url = add_query_arg(
                    array(
                        'action' => 'delete',
                        'id' => $item->id,
                        '_wpnonce' => $nonce
                    ),
                    $actions_url
                );
                return '<a href="' . esc_url($delete_url) . '" class="delete-link">Supprimer</a>';

            case 'archive':
                if ($item->archivé == 1) {
                    return '<span class="archived">Archivé</span>'; // Already archived
                } else {
                    $nonce = wp_create_nonce('archive_tournoi_' . $item->id);
                    $actions_url = plugin_dir_url(__FILE__) . 'crud-actions.php';
                    $archive_url = add_query_arg(
                        array(
                            'action' => 'archive',
                            'id' => $item->id,
                            '_wpnonce' => $nonce
                        ),
                        $actions_url
                    );
                    return '<a href="' . esc_url($archive_url) . '" class="archive-link">Archiver</a>';
                }

            default:
                return '';
        }
    }

    function get_bulk_actions()
    {
        return array(
            'delete' => 'Supprimer'
        );
    }
}

/**
 * Fonction pour afficher le tableau des tournois
 */
function display_tournois_list_table()
{
    $tournois_list_table = new Tournois_List_Table();
    $tournois_list_table->prepare_items();
    $tournois_list_table->display();
}
