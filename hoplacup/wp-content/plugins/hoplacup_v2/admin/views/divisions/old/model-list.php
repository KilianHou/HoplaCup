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
    function count_divisions()
    {
        global $wpdb;
        $table_divisions = $wpdb->prefix . 'divisions';
        $query = "SELECT COUNT(*) FROM $table_divisions";
        $total_count = $wpdb->get_var($query);
        return $total_count;
    }




    /**
     * Fonction de requête de la liste des divisions
     */
    function get_divisions($per_page = 2, $page_number = 1)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'divisions';
        $offset = ($page_number - 1) * $per_page;
        //$query = "SELECT id, Division, image_id FROM $table_name LIMIT $offset, $per_page";
        $query = "SELECT id, Division FROM $table_name LIMIT $offset, $per_page";
        $results = $wpdb->get_results($query);
        return $results;
    }

    /**
     * 
     * Déclaration de la classe fille pour générer le tableau divisions
     * 
     * NONCE : WP_List_Table génère et insère à l'affichage du tableau un token (nonce)
     * le nom d'action de ce token, est exploité pour les traitements en lots (dans actions.php)
     * il est généré sous cette forme : 'bulk-settings_page_hoplacup-v2-settings'
     *
     */
    class Divisions_List_Table extends WP_List_Table
    {

        //pagination
        private $per_page = 5;

        /**
         * Définition des colonnes tu tableau
         */
        function get_columns()
        {
            return array(
                'cb'   => '<input type="checkbox" />',
                'id' => 'ID',
                'name' => 'Nom',
                'image'  => 'Image',
                'edit' => 'Editer',
                'delete' => "Supprimer",
            );
        }

        /**
         * Préparation des éléments à afficher
         */
        function prepare_items()
        {
            $columns = $this->get_columns();
            $this->_column_headers = array($columns, array(), array());
            // Paramétrage pagination
            $total_items = count_divisions();
            $divisions = get_divisions($this->per_page, $this->get_pagenum());
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page'    => $this->per_page,
            ));
            $this->items = $divisions;
        }

        /**
         * Génère la colonne de cases à cocher pour les traitements en lots
         */
        function column_cb($item)
        {
            return sprintf(
                '<input type="checkbox" name="division[]" value="%s" />',
                $item->id
            );
        }

        /**
         * Génère les contenus des cellules
         */
        function column_default($item, $column_name)
        {
            switch ($column_name) {

                case 'name':
                    $edit_url = admin_url('admin.php?page=hoplacup-v2-settings&view=divisions&subview=item&id=' . $item->id);
                    return '<a href="' . esc_url($edit_url) . '">' . $item->Division . '</a>';
                    break;

                case 'id':
                    return $item->Division;

                case 'edit':
                    $edit_url = admin_url('admin.php?page=hoplacup-v2-settings&view=divisions&subview=item&id=' . $item->id);
                    return '<a href="' . esc_url($edit_url) . '">Éditer</a>';

                    // case 'image':
                    //     $image_url = wp_get_attachment_image_url($item->image_id, 'thumbnail');
                    //     if ($image_url) {
                    //         return '<img src="' . esc_url($image_url) . '" alt="Image" style="max-width: 100px;">';
                    //     } else {
                    //         return 'Pas d\'image';
                    //     }

                case 'delete':
                    $nonce = wp_create_nonce('delete_division_' . $item->id);
                    $actions_url = plugin_dir_url(__FILE__) . 'actions.php';
                    $delete_url = add_query_arg(
                        array(
                            'action' => 'delete',
                            'id' => $item->id,
                            '_wpnonce' => $nonce
                        ),
                        $actions_url
                    );
                    return '<a href="' . esc_url($delete_url) . '">Supprimer</a>';

                default:
                    return '';
            }
        }

        /**
         * Définition des actions traitables en lots
         */
        function get_bulk_actions()
        {
            $actions = array(
                'delete' => 'Supprimer'
            );
            return $actions;
        }
    }

    /**
     * Foncion à appeler dans le template pour générer le html du tableau
     */
    function display_divisions_list_table()
    {
        $divisions_list_table = new Divisions_List_Table();
        $divisions_list_table->prepare_items();
        $divisions_list_table->display();
    }
