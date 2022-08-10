<?php
/**
 * Class AJAX_LoadMore
 *
 * @author  Marco Di Bella 
 * @package mdb_theme_ajax
 */


defined( 'ABSPATH' ) or exit;



/**
 * Generic class for implementing AJAX-driven 'LoadMore' elements.
 *
 * @since 1.7.0
 * @todo  Integrate shortcode functionality.
 */

abstract class AJAX_LoadMore
{
    /**
     * Reads in the HTML template for dynamic output and returns it for storage.
     *
     * @since 1.7.0
     * @param string $filename    The filename of the template.
     */

    static function prepare_template( $filename )
    {
        global $plugin_path;

        $filepath = $plugin_path . '/includes/templates/' . $filename . '.php';
        $buffer   = '';

        if( true === file_exists( $filepath ) ) :

            // Read template
            $buffer = file_get_contents( $filepath );

            // Remove PHP code and excess spaces
            $buffer = trim( preg_replace( '/<\?php(.*|\n|\r)(\?>)/is', '', $buffer ) );

        endif;

        return $buffer;
    }


    /**
     * Returns the (saved) HTML template for the dynamic output.
     *
     * @since  1.7.0
     * @return string    The HTML template.
     */

    abstract static function get_template();


    /**
     * Returns the content (generated depending on the specific post) to be replaced in the HTML template.
     *
     * @since  1.7.0
     * @param  WP_POST $post    The post (aka record) to generate the replacements for.
     * @return array            An associative array containing the replacement terms.
     */

    abstract static function get_replacements( $post );


    /**
     * Determines the dynamically adding contributions (records).
     *
     * @since  1.7.0
     * @param  array $params    The LoadMore parameters.
     * @return array            An array of matching WP_POST objects.
     */

    abstract static function get_posts( $params );


    /**
     * Returns an array containing the LoadMore parameters (as key) and their default values ​​(as value).
     *
     * @since 1.7.0
     * @return array    The array.
     */

    static function get_default_params()
    {
        $default = array(
            'paged'     => 'false',
            'show'      => '',
            'exclude'   => '',
            'orderby'   => 'publish_date',
            'cat'       => 0,
            'tag'       => 0,
            'maxpage'   => 1,
            'nextpage'  => 1
        );

        return $default;
    }


    /**
     * Generates the dynamic output.
     *
     * @since  1.7.0
     * @param  array  $params    The LoadMore parameters.
     * @return string            The output.
     */

    static function render_dynamic_content( $params )
    {
        $output = '';
        $posts  = static::get_posts( $params );

        if( $posts) :

            // Loop through all posts
            foreach( $posts as $post ) :
                $buffer       = static::get_template();
                $replacements = static::get_replacements( $post );

                foreach( $replacements as $placeholder => $replacement ) :
                    $buffer = str_replace( $placeholder, $replacement, $buffer );
                endforeach;

                $output .= $buffer;
            endforeach;

            // Improve typography (when plugin wp-typography is loaded)
            if( class_exists( 'WP_Typography' ) ) :
                $output = WP_Typography::process_title( $output );
            endif;

        endif;

        return $output;
    }


    /**
     * Determines the LoadMore parameters submitted via $_POST and then calls render_dynamic_content().
     *
     * @since 1.7.0
     */

    static function handle_AJAX() {
        $default = static::get_default_params();
        $params  = array();

        foreach( $default as $key => $value ) :
            $params[$key] = $_POST[$key];
        endforeach;

        echo self::render_dynamic_content( $params );
        die();
    }
}
