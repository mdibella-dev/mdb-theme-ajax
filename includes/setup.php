<?php
/**
 * Functions to activate, initiate and deactivate the plugin.
 *
 * @author  Marco Di Bella
 * @package mdb_theme_ajax
 */

namespace mdb_theme_ajax;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * The activation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_activation()
{
    // Do something!
}

register_activation_hook( __FILE__, 'mdb_theme_ajax\plugin_activation' );



/**
 * The deactivation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_deactivation()
{
    // Do something!
}

register_deactivation_hook( __FILE__, 'mdb_theme_ajax\plugin_deactivation' );



/**
 * Load the frontend scripts and styles.
 *
 * @since 1.0.0
 */

function enqueue_scripts()
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

add_action( 'wp_enqueue_scripts', 'mdb_theme_ajax\enqueue_scripts', 9999 );



/**
 * The init function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_init()
{
    // Load text domain
    load_plugin_textdomain( 'mdb_theme_ajax', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'mdb_theme_ajax\plugin_init' );
