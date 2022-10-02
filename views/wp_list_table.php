<?php

require_once(ABSPATH . "wp-admin/includes/class-wp-list-table.php");

/**
 * Class CUSTOM_list_Table
 */
class CustomListTable extends WP_List_Table
{
    /**
     * Prepares the list of items for displaying.
     */
    public function prepare_items()
    {
        $orderBy = isset($_GET['orderby']) ? trim($_GET['orderby']) : "";
        $order = isset($_GET['order']) ? trim($_GET['order']) : "";
        $search_term = isset($_POST['s']) ? trim($_POST['s']) : "";

        $datas = $this->list_table_data($orderBy, $order, $search_term);

        $per_page = 5;
        $current_page = $this->get_pagenum();
        $total_items = count($datas);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));
        $this->items = array_slice($datas, (($current_page - 1) * $per_page), $per_page);

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    /**
     * Wp list table bulk actions 
     */
    public function get_bulk_actions()
    {
        return array(
            'wp_delete' => __('Delete'),
            'wp_edit'   => __('Edit')
        );
    }

    /**
     * Display columns datas
     */
    public function list_table_data($orderBy = '', $order = '', $search_term = '')
    {
        global $wpdb;
        if (!empty($search_term)) {

            $condition = array(
                'relation' => 'OR',
                array(
                    'key' => 'user_name',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'user_email',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                )
            );
            $args = array(
                'meta_query' => $condition
            );

            $data_list = get_users($args);
            $id = array();
            foreach ($data_list as $datas_list) {
                $id[] = $datas_list->ID;
            }
            $id = implode(',', $id);
            $all_datas = $wpdb->get_results("SELECT * FROM `wp_list_table` WHERE 'user_name' LIKE '%$search_term%'");
        } else {
            if ($orderBy == 'date' && $order == 'asc') {
                $all_datas = $wpdb->get_results("SELECT * FROM `wp_list_table`");
            } else {
                $all_datas = $wpdb->get_results("SELECT * FROM `wp_list_table`");
            }
        }

        $records_array = array();
        if (count($all_datas) > 0) {
            foreach ($all_datas as $index => $database) {
                // $id = $database->ID;
                // $user_name = $database->food_id;
                // $user_email = $database->user_email;
                // $phone = $database->phone;
                // $image = $database->image;
                // $date = $database->date;
                $records_array[] = array(
                    "id" => $database->ID,
                    "user_name" => $database->user_name,
                    "user_email" => $database->user_email,
                    "phone" => $database->phone,
                    "image" => $database->image,
                    "date" => $database->date,
                    "status" => !empty($database->status) ? $database->status : 'pending',

                );
            }
        }
        return $records_array;
    }

    /**
     * Gets a list of all, hidden and sortable columns
     */
    public function get_hidden_columns()
    {
        return array("");
    }

    /**
     * Gets a list of sortable columns
     */
    public function get_sortable_columns()
    {
        return array(
            "date" => array("date", false)
        );
    }

    /**
     * Gets a list of columns.
     */
    public function get_columns()
    {
        $columns = array(
            "cb" => '<input type="checkbox" class=""/>',
            "id" => "ID",
            "user_name" => "Name",
            "user_email" => "Email",
            "phone" => "Phone",
            "image" => "Image",
            "date" => "Date",
            // "action" => "Action",
            "action" => "Action"
        );
        return $columns;
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
                return $item[$column_name];
                break;
            case 'user_name':
                return $item[$column_name];
                break;
            case 'user_email':
                return $item[$column_name];
                break;
            case 'phone':
                return $item[$column_name];
                break;
            case 'image':
                return $item[$column_name];
                break;
            case 'date':
                return date("m/d/Y H:i A", strtotime($item[$column_name]));
                break;
                // case 'action':
                //     return "<a href='javascript:void(0)' data-id='" . $item['id'] . "' onclick='status_update(this)' data-status='approved' style='text-decoration:none;'>Approved</a>&nbsp <a href='javascript:void(0)' data-id='" . $item['id'] . "' data-status='rejected' onclick='status_update(this)' style='text-decoration:none;'>Rejected</a>";
                //     break;
            case 'action':
                $html = '';
                if($item['status'] == "pending"){
                    $html .= "<option value='' selected='selected'>Pending</option>";
                }else{
                    $html .="<option value='" . $item['status'] . "' selected='selected'>".ucwords($item['status'])."</option>";
                }
                return "<select name='status' id='status' onchange='my_status_change(this)'>".$html."
                <option value='" . $item['id'] . "' data-id='".$item['id']."' data-status='approved'>Approved</option>
                <option value ='" . $item['id'] . "' data-id='".$item['id']."' data-status='rejected' >Rejected</option>
                </select>
                ";
                break;
            default:
                return "No List Found Value";
        }
    }

    /**
     * Deleted data wp list Table.
     */
    // public function process_bulk_action()
    // {

    //     global $wpdb;
    //     $action = $this->current_action();
    //     switch ($action) {
    //         case 'delete':
    //             $query = $wpdb->delete('wp_list_table', array('ID' => $_GET['id']));
    //             break;
    //             case 'edit':
    //                 wp_die( 'Save something' );
    //                 break;
    //         default:
    //             // do nothing or something else
    //             return;
    //             break;
    //     }
    //     return;
      
    // }

    /**
     * WP list table row actions
     */
    // public function handle_row_actions($item, $column_name, $primary)
    // {

    //     if ($primary !== $column_name) {
    //         return '';
    //     }

    //     $action = [];
    //     $action['edit'] = '<a href="#TB_inline?&width=600&height=550&inlineId=clt-content-id" class="thickbox">' . __('Edit') . '</a>';
    //     $action['delete'] = sprintf('<a href="?page=%s&action=%s&id=%s">' . __('Delete') . '</a>', $_GET['page'], 'delete', $item['id']);
    //     $action['view'] = '<a class="clt-view-post">' . __('View') . '</a>';

    //     return $this->row_actions($action);
    // }

    /**
     * WP list table row actions
     */
    function column_user_name($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="#TB_inline?&width=600&height=550&inlineId=my-content-id" class="thickbox">' . 'Edit' . '</a>', $_GET['page'], 'view', $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>', $_GET['page'], 'delete', $item['id']),
            'view' => sprintf('<a href="#TB_inline?&width=600&height=550&inlineId=my-content-id" class="thickbox">View</a>', $_GET['page'], 'view', $item['id']),

        );
        return sprintf('%1$s %2$s', $item['user_name'], $this->row_actions($actions));
    }


    /**
     * Rows check box
     */
    public function column_cb($items)
    {

        $top_checkbox = '<input type="checkbox" class="clt-selected" />';
        return $top_checkbox;
    }
}

function data_list_table()
{
    $table = new CustomListTable();
    $table->prepare_items();
    $table->process_bulk_action();
    echo "<h3 class='mt-2'>Wp List Table</h3>";
    echo "<h4 id='statusMessage'></h4>";
    echo "<form method='POST' name='form_search_clt' action='" . $_SERVER['PHP_SELF'] . "?page=clt-list-table'>";
    $table->search_box("Search", "search_user_name");
    echo "</form>";
    $table->display();
}
data_list_table();
