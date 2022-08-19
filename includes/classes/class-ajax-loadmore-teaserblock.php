<?php
/**
 * Class AJAX_LoadMore_Teaserblock
 *
 * @author  Marco Di Bella
 * @package mdb-theme-ajax
 */

namespace mdb_theme_ajax;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Class for AJAX-driven display of teasers.
 *
 * @since 1.0.0
 */

class AJAX_LoadMore_Teaserblock extends AJAX_LoadMore
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
        self::$template = self::prepare_template( 'ajax-template-teaserblock' );
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
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => $params['show'],
            'order'          => 'DESC',
            'orderby'        => $params['orderby'],
            'offset'         => ($params['nextpage'] - 1) * $params['show'],
            'exclude'        => $params['exclude'],
            'cat'            => $params['cat'],
            'tag_id'         => $params['tag'],
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
            '_CLASS_'     => implode( ' ', apply_filters( 'post_class', get_post_class( $post->ID ), '', $post->ID ) ),
            '_PERMALINK_' => get_permalink( $post->ID ),
            '_LINKTITLE_' => __( 'More', 'mdb-theme-ajax' ),
            '_IMAGE_'     => get_the_post_thumbnail( $post->ID, 'medium' ),
            '_TITLE_'     => get_the_title( $post->ID ),
        );

        return $replacements;
    }
}

add_action( 'wp_ajax_teaserblock', array( 'mdb_theme_ajax\AJAX_LoadMore_Teaserblock', 'handle_AJAX' ) );
add_action( 'wp_ajax_nopriv_teaserblock', array( 'mdb_theme_ajax\AJAX_LoadMore_Teaserblock', 'handle_AJAX' ) );


AJAX_LoadMore_Teaserblock::__constructStatic();
