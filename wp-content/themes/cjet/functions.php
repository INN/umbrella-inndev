<?php

// Include the Largo metabox API
require_once( get_template_directory() . '/largo-apis.php' );

// Includes
$includes = array(
	'/inc/metaboxes.php',
	'/inc/attachments.php',
	'/inc/sidebars.php',
	'/lib/file-un-attach/file-unattach.php',
	'/homepages/homepage.php'
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


// Custom script
function cjet_enqueue() {
	wp_enqueue_script( 'cjet-javascript', get_stylesheet_directory_uri() . '/js/cjet.js' );
}
add_action( 'wp_enqueue_scripts', 'cjet_enqueue', 11 );


// Typekit
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/cui8tby.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );

// Breadcrumbs
function cjet_breadcrumbs() {
	if ( function_exists( 'bcn_display' ) && !is_home() ) {
		echo '<div id="breadcrumbs">';
		bcn_display();
		echo '</div>';
	}
}
add_action( 'largo_main_top', 'cjet_breadcrumbs' );

add_theme_support( 'custom-header' );


// Add excerpts to pages
function cjet_init() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'cjet_init' );

// Add extra theme options
function cjet_theme_options( $options ) {

	$options[] = array(
		'name' 	=> __('INN Learn', 'cjet'),
		'type' 	=> 'heading');

	$options[] = array(
		'desc' 	=> __('Enter a description of the topics section that appears on the homepage.', 'cjet'),
		'id' 	=> 'cjet_guides_intro',
		'std' 	=> '',
		'type' 	=> 'textarea');

	return $options;
}
add_filter('largo_options', 'cjet_theme_options');

function add_after_largo_header(){

	echo '<div class="cjet-header-grid"><h5>Guides and Resources for<br/>Nonprofit News Organizations</h5></div>';
	echo '<div class="cjet-header-grid">';

	if ( ! is_search() ) {
		?>
            <div id="header-search">
                <form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="input-append">
                        <input type="text" placeholder="<?php _e('SEARCH...', 'largo'); ?>" class="input-medium appendedInputButton search-query" value="" name="s" />
                    </div>
                </form>
            </div>
        <?php
	}

	if ( SHOW_MAIN_NAV === TRUE ) {
		get_template_part( 'partials/nav', 'main' );
	}

	echo '</div>';

}
add_action( 'largo_header_after_largo_header', 'add_after_largo_header');

/**
 * Enable shortcodes in Custom HTML Widget
 *
 * @link https://github.com/INN/umbrella-inndev/issues/68#issuecomment-497853084
 * @link https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/widgets/class-wp-widget-custom-html.php#L158
 */
add_filter( 'widget_custom_html_content', 'shortcode_unautop');
add_filter( 'widget_custom_html_content', 'do_shortcode');

/**
 * Load WordPress Dashicons on Guides template
 */
function load_dashicons_front_end() {
	
	if( is_page_template( 'guides.php' ) ){
		wp_enqueue_style( 'dashicons' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
