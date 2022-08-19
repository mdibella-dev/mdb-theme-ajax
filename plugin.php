<?php
/**
 * Plugin Name:     Marco Di Bella - AJAX
 * Author:          Marco Di Bella
 * Author URI:      https://www.marcodibella.de
 * Description:     AJAX driven shortcodes and classes for displaying lectures, publications and blog articles. Intended to use with the theme mdb-theme-fse.
 * Version:         1.0.0
 * Text Domain:     mdb_theme_ajax
 * Domain Path:     /languages
 *
 * @author  Marco Di Bella
 * @package mdb_theme_ajax
 */

namespace mdb_theme_ajax;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/** Variables and definitions **/

$plugin_version = '1.1.0';
$plugin_path    = plugin_dir_path( __FILE__ );



/** Include files */

require_once( $plugin_path . '/includes/classes/class-ajax-loadmore.php' );
require_once( $plugin_path . '/includes/classes/class-ajax-loadmore-teaserblock.php' );
require_once( $plugin_path . '/includes/classes/class-ajax-loadmore-vortragsliste.php' );
require_once( $plugin_path . '/includes/classes/class-ajax-loadmore-publikationsliste.php' );

require_once( $plugin_path . '/includes/shortcodes/shortcode-publikationsliste.php' );
require_once( $plugin_path . '/includes/shortcodes/shortcode-vortragsliste.php' );
require_once( $plugin_path . '/includes/shortcodes/shortcode-teaserblock.php' );

require_once( $plugin_path . 'includes/setup.php' );
