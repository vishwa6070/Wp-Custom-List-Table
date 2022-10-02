<?php

/*
 * Plugin name: WP Custom List Table
 * Description: This is simple plugin for WP_Custom_List_Table learning
 * Author: Vishwkarma
 */




add_action('admin_menu', 'clt_list_table_menu');

function clt_list_table_menu()
{
    add_menu_page(
        'CLT List Table', //page_title.
        'CLT List Table', //menu_title.
        'manage_options', //capability.
        'clt-list-table', //menu_slug. 
        'clt_list_table_cf', //callback function.
        'dashicons-tickets-alt', //dashicon.
        2 //position.
    );
}

function clt_list_table_cf()
{

    ob_start();
    include_once plugin_dir_path(__FILE__) . 'views/wp_list_table.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
}

function wp_custom_admin_assets()
{
    if(isset($_GET['page']) && $_GET['page'] == "clt-list-table"){
        wp_enqueue_style('bootstarp', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', array());
        wp_enqueue_style('style.css', plugin_dir_url(__FILE__) . 'assets/css/style.css', array());
        wp_enqueue_script('bootstarp_js', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array());
        wp_enqueue_script('jquery-slim-min-js', plugin_dir_url(__FILE__) . 'assets/js/jquery-3.3.1.slim.min.js', array());
        wp_enqueue_script('jquery-min', plugin_dir_url(__FILE__) . 'assets/js/jquery.min.js', array());
        wp_enqueue_script('ajax-js', plugin_dir_url(__FILE__) . 'assets/js/ajax.js', array());
        wp_enqueue_script('bootstrap-min-js', plugin_dir_url(__FILE__) . 'assets/js/popper.min.js', array());
        wp_enqueue_script('sweetalert', plugin_dir_url(__FILE__) . 'assets/js/sweetalert.min.js', array());
    }
}
add_action('admin_enqueue_scripts', 'wp_custom_admin_assets');


function clt_create_edit_popup()
{
    add_thickbox();
?>
    <div id="clt-content-id" style="display:none;">
        <p>
            This is my hidden content! It will appear in ThickBox when the link is clicked.
        </p>
    </div>
<?php
}
add_action('in_admin_footer', 'clt_create_edit_popup');


function status_update(){
    global $wpdb;
    $id = $_POST['id'];
    $status = $_POST['status'];
    $wpdb->update('wp_list_table',
    array('status'=>$status),
    array('ID'=>$id));
    echo json_encode(['code'=>200,"message"=>"Status updated successfully."]);
    die;
}
add_action('wp_ajax_update_status','status_update');
