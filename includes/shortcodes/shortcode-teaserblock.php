<?php
/**
 * The shortcode [teaserblock].
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 * @package mdb_theme_ajax
 */


defined( 'ABSPATH' ) or exit;



/**
 * Generates a teaser block with the most recently published articles.
 *
 * @since  1.0.0
 * @param  array  $atts       The parameters of the shortcode.
 * @param  string $content    The content encapsulated by the shortcode.
 * @return string             The output.
 */

function mdb_ajax__shortcode_teaserblock( $atts, $content = null )
{
    // Set variables
    $output = '';
    $params = array();


    // Read parameters
    extract( shortcode_atts( array(
        'paged'   => 'false',
        'show'    => '4',
        'exclude' => '',
        'orderby' => 'publish_date',
        'cat'     => 0,
        'tag'     => 0,
        ),
        $atts
    ) );


    // Get the total number of items
    $max = sizeof( get_posts( array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'orderby'        => $orderby,
        'exclude'        => $exclude,
        'cat'            => $cat,
        'tag_id'         => $tag,
    ) ) );


    // Set LoadMore parameters
    $params['show']     = empty( $show )? get_option( 'posts_per_page' ) : (int) $show;
    $params['action']   = 'teaserblock';
    $params['exclude']  = $exclude;
    $params['orderby']  = $orderby;
    $params['cat']      = $cat;
    $params['tag']      = $tag;
    $params['maxpage']  = 1;
    $params['nextpage'] = 1;
    $params['paged']    = strtolower( $paged );

    if( 'true' === $paged ) :
        $params['show']    = get_option( 'posts_per_page' );  // always take the information from the backend.
        $params['maxpage'] = (int) ceil( $max / $params['show'] );
    endif;


    // Generate id to allow multiple instances of the shortcode
    $id = sprintf( 'loadmore_%1$s', random_int( 1000, 9999 ) );


    // Get the content of the first 'page'
    $ajax = AJAX_LoadMore_Teaserblock::render_dynamic_content( $params );

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
        if( ( 'true' === $paged ) and ( $params['nextpage'] !== $params['maxpage'] ) ) :
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

add_shortcode( 'teaserblock', 'mdb_ajax__shortcode_teaserblock' );
