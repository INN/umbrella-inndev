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

/**
 * Set a user cookie to grant access to specific pages when a specific form
 * is submitted through Gravity Forms.
 * 
 * @param Obj $entry The form entry that was just submitted
 * @param Obj $form The current form
 */
function set_cookie_on_form_submission( $entry, $form ) {

	// uncomment to review the entry
    // echo "<pre>".print_r(￼$entry,true)."</pre>"; die;

	// loop through $form fields to find field with access_ID as its label
	foreach( $form['fields'] as $field ) {

		if( 'access_ID' == $field->label ) {

			$access_ID_field_ID = $field->id;

		}
		
	}

	// if an access_ID field is found, use its value for $from_page and set the cookie
	if( $access_ID_field_ID ) {

		$from_page = $entry[$access_ID_field_ID];

		// make sure $from_page is a valid post id before setting the cookie
		if( is_string( $from_page ) && get_post_status( $from_page ) ) {

			// set the cookie
			setcookie( 'unrestrict_'.$from_page, 1, strtotime( '+365 days' ), COOKIEPATH, COOKIE_DOMAIN, false, false);

			// redirect so we dont land on gravity forms "thank you" page
			wp_redirect( get_permalink( $from_page ) );

		}

	}

}
add_action( 'gform_after_submission', 'set_cookie_on_form_submission', 10, 2 );

/**
 * Grant access to pages that should be restricted
 * based on if a cookie is set that matches the specific post ID
 * 
 * If cookie is not set, try and find a form with a name identical to the current page
 * and display it if found.
 * 
 * @param Str $content The entire post content
 * 
 * @return Str $content The entire post content
 */
function restrict_access_to_pages_by_cookie( $content ) {

	global $post;

	// see if current post/page is supposed to be restricted
	$restrict_access = get_post_meta( $post->ID, 'cjet-content-restrict-access' )[0];

	// go ahead andd display if not supposed to be restricted
	if( ! $restrict_access || $restrict_access === 0 || is_user_logged_in() ) {

		return $content;

	}

    // check if user has submitted this pages form, if not, show only form
    if( ! isset( $_COOKIE['unrestrict_'.get_the_ID()] ) ) {

		// but wait, what if this is a child page?
		// we need to see if a cookie has been set to grant access to the parent page
		// first, grab the top-level parent of this page
		if( $post->post_parent ) {

			$ancestors = get_post_ancestors( $post->ID );
			$root = count( $ancestors ) - 1;
			$parent = $ancestors[$root];

		} else {

			$parent = $post->ID;

		}

		// if an actual parent was found (parent id doesn't match current post id),
		// let's see if a cookie for it (parent) exists
		if( $parent != $post->ID ) {

			// if a cookie is found, let's display the content
			if( isset( $_COOKIE['unrestrict_'.$parent] ) ) {

				return $content;
			
			// else if no cookie is found, let's redirect the user to the top-level parent 
			// so they can fill out the form
			} else {

				wp_redirect( get_permalink( $parent ) );

			}

		}

		// make sure the GF methods exist before we try using them
		// that way we don't see any scary 500 errors
		if( method_exists( 'RGFormsModel', 'get_form_id' ) && method_exists( 'RGForms', 'get_form' ) ) {

			// try and find a form that matches the current post title
			$form = RGFormsModel::get_form_id( $post->post_title );
			
			// if a form is found with a matching name, show it to the user
			if( $form ) {

				$form = RGForms::get_form( $form );
				return '<h4>'.__( 'The content on this '.$post->post_type.' is restricted. Please fill out the form below to gain access.', 'cjet' ).'</h4>'.$form;

			}

		}

		return '<h4>'.__( 'The content on this '.$post->post_type.' is restricted.', 'cjet' ).'</h4>';
		
    } else {

        // user has submitted this pages form in the last 365 days
        // show content
		return $content;
		
	}
	
}
add_action( 'the_content', 'restrict_access_to_pages_by_cookie' ); 
