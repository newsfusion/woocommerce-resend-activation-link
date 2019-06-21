<?php
/*
Plugin Name: Resend Activation Links for WooCommerce Germanized
Plugin URI:  https://github.com/newsfusion/woocommerce-resend-activation-link
Description: See unactivated users and send activation email again with one click. Go to Admin Panel -> Users -> All Users
Version:  1.0
Author:  Peter Schael
Author URI: https://github.com/newsfusion
*/

//add new Table Headers on Admin Panel: Users -> All Users
function modify_user_table( $column ) {
    $column['isactivated'] = 'User activated?';
    $column['resend'] = 'Resend link';
    return $column;
}
add_filter( 'manage_users_columns', 'modify_user_table' );

//add new columns in users table
function modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'isactivated' :
            return ( get_user_meta( $user_id, '_woocommerce_activation', true ) ? 'no' : 'yes' );
        case 'resend' :
            return get_user_meta( $user_id, '_woocommerce_activation', true ) ?
                '<a class="wc-gzd-resend-activation-link button button-secondary" href='
                . wp_nonce_url( add_query_arg(  array( 'gzd-resend-activation' => 'yes' ), self_admin_url( 'user-edit.php?user_id=' . $user_id ) ) , 'resend-activation-link' )
            . '> Resend activation link </a>' : ''; //generate link for resending activation email
        default:

    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'modify_user_table_row', 10, 3 );

