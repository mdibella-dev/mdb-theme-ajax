<?php
/**
 * The shortcode [vortragsliste].
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 */


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

function mdb_ajax__shortcode_vortragsliste( $atts, $content = null )
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
        'post_type'      => 'vortrag',
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

    if( 'true' === $paged ) :
        $params['maxpage'] = (int) ceil( $max / $params['show'] );
    endif;


    // Generate id to allow multiple instances of the shortcode
    $id = sprintf( 'loadmore_%1$s', random_int( 1000, 9999 ) );


    // Get the content of the first 'page'
    $ajax = AJAX_LoadMore_Vortragsliste::render_dynamic_content( $params );

    if( ! empty( $ajax ) ) :

        // Correct information for the following page
        if( ( 'true' === $paged ) and ( 1 !== $params['maxpage'] ) ):
            $params['nextpage'] = 2;
        endif;


        // Generate 'data' values
        $data = '';

        foreach( $params as $data_key => $data_value ) :
            $data .= sprintf(
                ' data-%1$s="%2$s"',
                $data_key,
                $data_value
            );
        endforeach;


        // Start rendering
        ob_start();
?>
<div id="<?php echo $id; ?>" class="loadmore <?php echo $params['action']; ?>" <?php echo $data; ?>>
    <div class="loadmore-content">
        <?php echo $ajax; ?>
    </div>
        <?php
        // Show LoadMore button if required
        if( ( 'true' === $paged ) ) :
        ?>
    <div class="loadmore-action-wrapper">
        <div class="loadmore-spinner">
            <div class="sk-bounce">
                <div class="sk-bounce-dot"></div>
                <div class="sk-bounce-dot"></div>
            </div>
        </div>
        <button class="button loadmore-button" data-parentid="<?php echo $id; ?>" target="_self"><?php echo __( 'Mehr laden', 'mdb_ajax' ); ?></button>
    </div>
        <?php
        endif;
        ?>
</div>
<?php
        $output = ob_get_contents();
        ob_end_clean();
    endif;

    return $output;
}

add_shortcode( 'vortragsliste', 'mdb_ajax__shortcode_vortragsliste' );
