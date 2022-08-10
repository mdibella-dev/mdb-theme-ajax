<?php
/**
 * Functions to activate, initiate and deactivate the plugin.
 *
 * @author  Marco Di Bella
 * @package mdb_theme_ajax
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;


/**
 * The activation function for the plugin.
 *
 * @since 1.0.0
 */

function mdb_ajax__plugin_activation()
{
    // Do something!
}

register_activation_hook( __FILE__, 'mdb_ajax__plugin_activation' );



/**
 * The deactivation function for the plugin.
 *
 * @since 1.0.0
 */

function mdb_ajax__plugin_deactivation()
{
    // Do something!
}

register_deactivation_hook( __FILE__, 'mdb_ajax__plugin_deactivation' );



/**
 * Load the frontend scripts and styles.
 *
 * @since 1.0.0
 */

function mdb_tc_enqueue_scripts()
{
    wp_enqueue_script(
        'ajax',
        plugins_url( 'assets/build/js/ajax-loadmore.min.js', dirname( __FILE__ ) ),
        'jquery',
        false,
        true
    );

    wp_localize_script(
        'ajax',
        'mdb_ajax',
        array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
    );
}

add_action( 'wp_enqueue_scripts', 'mdb_tc_enqueue_scripts', 9999 );
