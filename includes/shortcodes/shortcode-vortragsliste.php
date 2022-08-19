<?php
/**
 * The shortcode [vortragsliste].
 *
 * @author  Marco Di Bella
 * @package mdb-theme-ajax
 */

namespace mdb_theme_ajax;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Generates a list of lectures.
 *
 * @since  1.0.0
 * @param  array  $atts       The parameters of the shortcode.
 * @param  string $content    The content encapsulated by the shortcode.
 * @return string             The output.
 */

function shortcode_vortragsliste( $atts, $content = null )
{
    // Set variables
    $output = '';
    $params = array();


    // Read parameters
    extract( shortcode_atts( array(
        'paged'   => 'false',
        'show'    => '',
        'orderby' => 'publish_date',
        ),
        $atts
    ) );


    // Get the total number of items
    $max = sizeof( get_posts( array(
        'post_type'      => 'lecture',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'orderby'        => $orderby,
    ) ) );


    // Set LoadMore parameters
    $params['show']     = empty( $show )? get_option( 'posts_per_page' ) : (int) $show;
    $params['action']   = 'vortragsliste';
    $params['orderby']  = $orderby;
    $params['maxpage']  = 1;
    $params['nextpage'] = 1;
    $params['paged']    = strtolower( $paged );
    $params['id']       = sprintf( 'loadmore_%1$s', random_int( 1000, 9999 ) );

    if( 'true' === $params['paged'] ) :
        $params['maxpage'] = (int) ceil( $max / $params['show'] );
    endif;


    // Get the content of the first 'page'
    $ajax = AJAX_LoadMore_Vortragsliste::render_dynamic_content( $params );

    return AJAX_LoadMore::prepare_output( $params, $ajax );
}

add_shortcode( 'vortragsliste', 'mdb_theme_ajax\shortcode_vortragsliste' );
