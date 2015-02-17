<?php
/**
 * The template for displaying argolinks posts on archive/search pages
 */
$custom = get_post_custom( $post->ID );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	<h3><?php echo ( isset( $custom["argo_link_url"][0] ) ) ? '<a href="' . $custom["argo_link_url"][0] . '">' . get_the_title() . '</a>' : get_the_title(); ?></h3>
	<?php
		if ( isset( $custom["argo_link_description"][0] ) )
			echo '<p class="description">' . $custom["argo_link_description"][0] . '</p>';
		if ( isset($custom["argo_link_source"][0] ) && ( $custom["argo_link_source"][0] != '' ) ) {
			echo '<p class="source">';
			echo ( isset( $custom["argo_link_url"][0] ) ) ? '<a href="' . $custom["argo_link_url"][0] . '">' . $custom["argo_link_source"][0] . '</a>' : $custom["argo_link_source"][0];
		    echo ' <span class="date">(' . get_the_time('F j, Y') . ')</span></p>';
		}
	?>
</article>
