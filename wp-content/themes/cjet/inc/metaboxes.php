<?php
/**
 * Add featured image display metabox
 * (allows author to override automatic display of featured image as the "cover image" on single post page)
 */
largo_add_meta_box(
	'featured-image-display',
	'Featured Image Display',
	'featured_image_metabox_meta_box_display',
	'post',
	'side',
	'default'
);
function featured_image_metabox_meta_box_display() {
	global $post;
	$values = get_post_custom( $post->ID );
	$checked = isset($values["featured-image-display"][0]) ? 'checked="checked" ' : '';
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	?>
	<input type="checkbox" name="featured-image-display" <?php echo $checked; ?>/> Override display of featured image for this post?
	<?php
}

/**
 * largo metabox API doesn't yet support checkboxes as an input type
 * so we need a special function to save this field (for now)
 */
function save_details($post_ID = 0) {
	$post_ID = (int) $post_ID;
	$post_type = get_post_type( $post_ID );
	$post_status = get_post_status( $post_ID );

	if ( $post_type && isset( $_POST['featured-image-display'] ) && $_POST['featured-image-display'] == 'on' ) {
		update_post_meta( $post_ID, 'featured-image-display', 'false' );
	} else {
		delete_post_meta( $post_ID, 'featured-image-display' );
	}
	return $post_ID;
}
add_action('save_post', 'save_details');


/**
 * Meta field for hiding the author box
 */
largo_add_meta_box(
	'author_display',
	__('Author Display', 'cjet'),
	'cjet_author_display_control',
	'page'
);

function cjet_author_display_control() {

	global $post;

	//if this isn't a parent page, this setting is irrelevant
	$ancestors = get_post_ancestors( $post->ID );
	if ( count( $ancestors ) !== 1 ) {
		echo "<em>" . __('This setting is only relevant in the context of guide/course landing pages', 'cjet'). "</em>";
	} else {
		$value = get_post_meta( $post->ID, 'cjet_hide_author', true );
		?>
			<input type="checkbox" name="cjet_hide_author" value="1" <?php checked( $value, 1); ?> /> Hide the author bio for this guide/course
		<?php
	}
}
largo_register_meta_input( 'cjet_hide_author' );

/**
 * Adds metabox to display who the post/page was last edited by
 */
largo_add_meta_box(
	'cjet_last_edited_metabox',
	__('Last Edited', 'cjet'),
	'cjet_last_edited_metabox',
	array( 'page', 'post' )
);

function cjet_last_edited_metabox() {

	$post_type = get_post_type();
	
	// grab the name of the author who last edited the post/page
	$modified_author = get_the_modified_author();

	// grab last date the post/page was edited
	$modified_date = get_the_modified_date();

	echo sprintf(
		'<p>This %1$s was last edited by %2$s on %3$s.</p>',
		esc_html( $post_type ),
		esc_html( $modified_author ),
		esc_html( $modified_date )
	);

}

/**
 * Adds metabox to allow authors to select and view when the content was published/updated
 */
largo_add_meta_box(
	'cjet_content_date_metabox',
	__('Content Date', 'cjet'),
	'cjet_content_date_metabox',
	array( 'page', 'post' )
);

function cjet_content_date_metabox() {

	global $post;

	// grab existing content date from post meta
	$content_date = get_post_meta( $post->ID, 'cjet_content_date' )[0];
	
	echo '<p>This content was originally published on:</p>';

	echo sprintf(
		'<input type="date" id="cjet-content-date" name="cjet_content_date" value="%1$s" />',
		esc_html( $content_date )
	);

}

largo_register_meta_input( 'cjet_content_date' );

/**
 * Adds metabox to allow pages/posts to be hidden behind cookies
 */
largo_add_meta_box(
	'cjet_content_restrict_access_metabox',
	__('Restrict Access', 'cjet'),
	'cjet_content_restrict_access_metabox',
	array( 'page', 'post' )
);

function cjet_content_restrict_access_metabox() {

	global $post;

	$values = get_post_custom( $post->ID );
	$checked = isset($values["cjet-content-restrict-access"][0]) ? 'checked="checked" ' : '';
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	?>
	
	<p>Should this <?php echo get_post_type( $post->ID ); ?> be hidden behind a cookie?</p>
	<input type="checkbox" name="cjet-content-restrict-access" <?php echo $checked; ?>/>
	<?php

}

/**
 * Largo metabox API doesn't yet support checkboxes as an input type
 * so we need a special function to save this checkbox field for restricting content (for now)
 * 
 * @param int $post_ID The ID of the post we're saving to
 * 
 * @return int $post_ID The ID of the post we're saving to
 */
function save_content_restriction_checkbox( $post_ID = 0 ) {

	$post_ID = (int) $post_ID;
	$post_type = get_post_type( $post_ID );
	$post_status = get_post_status( $post_ID );

	if ( $post_type && isset( $_POST['cjet-content-restrict-access'] ) && $_POST['cjet-content-restrict-access'] == 'on' ) {
		update_post_meta( $post_ID, 'cjet-content-restrict-access', 'false' );
	} else {
		delete_post_meta( $post_ID, 'cjet-content-restrict-access' );
	}

	return $post_ID;

}
add_action( 'save_post', 'save_content_restriction_checkbox' );