<?php
/**
 * Class AJAX_LoadMore_Vortragsliste
 *
 * @author  Marco Di Bella
 * @package mdb-theme-ajax
 */

namespace mdb_theme_ajax;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Class for AJAX-driven presentation of lectures.
 *
 * @since 1.0.0
 */

class AJAX_LoadMore_Vortragsliste extends AJAX_LoadMore
{
    /**
     * HTML template for dynamic output.
     *
     * @since 1.0.0
     * @var   string
     */

    private static $template = '';


    /**
     * A (static) constructor; sets the basic class parameters.
     *
     * @since 1.0.0
     */

    static function __constructStatic()
    {
        self::$template = self::prepare_template( 'ajax-template-vortragsliste' );
    }


    /**
     * Returns the (saved) HTML template for the dynamic output.
     *
     * @since  1.0.0
     * @return string    The HTML template.
     */

    static function get_template() {
        return self::$template;
    }


    /**
     * Determines the dynamically adding contributions (records).
     *
     * @since  1.0.0
     * @param  array $params    The LoadMore parameters.
     * @return array            An array of matching WP_POST objects.
     */

    static function get_posts( $params )
    {
        $posts = get_posts( array(
            'post_type'      => 'lecture',
            'post_status'    => 'publish',
            'posts_per_page' => $params['show'],
            'order'          => 'DESC',
            'orderby'        => $params['orderby'],
            'offset'         => ($params['nextpage'] - 1) * $params['show'],
        ) );

        return $posts;
    }


    /**
     * Returns the content (generated depending on the specific post) to be replaced in the HTML template.
     *
     * @since  1.0.0
     * @param  WP_POST $post    The post (aka record) to generate the replacements for.
     * @return array            An associative array containing the replacement terms.
     */

    static function get_replacements( $post )
    {
        // Create replacement terms
        $replacements = array(
            '_LOCATION_'    => get_field( 'speech-event-location', $post->ID ),
            '_TITLE_'       => get_the_title( $post->ID ),
            '_DESCRIPTION_' => get_field( 'speech-event', $post->ID ),
            '_MONTH_'       => get_the_date( 'M', $post->ID ),
            '_YEAR_'        => get_the_date( 'Y', $post->ID ),
        );

        return $replacements;
    }
}

add_action( 'wp_ajax_vortragsliste', array( 'mdb_theme_ajax\AJAX_LoadMore_Vortragsliste', 'handle_AJAX' ) );
add_action( 'wp_ajax_nopriv_vortragsliste', array( 'mdb_theme_ajax\AJAX_LoadMore_Vortragsliste', 'handle_AJAX' ) );


AJAX_LoadMore_Vortragsliste::__constructStatic();
