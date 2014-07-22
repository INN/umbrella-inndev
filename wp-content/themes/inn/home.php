<?php
/**
 * The homepage template
 */

/**
 * ======== DO NOT EDIT OR CLONE THIS FILE FOR A CHILD THEME =======
 *
 * Largo comes with a built-in homepage template system, documented in homepages/README.md
 * It's generally better to use that system than to have your child theme use its own home.php template
 */

get_header();

/*
 * Collect post IDs in each loop so we can avoid duplicating posts
 * and get the theme option to determine if this is a two column or three column layout
 */
$shown_ids = array();
$home_template = str_replace('.php', '', of_get_option( 'home_template', 'blog.php' ) );
$home_template = str_replace( get_stylesheet_directory() , "", $home_template);
$layout_class = str_replace( get_stylesheet_directory() , "", of_get_option('home_template') );
$tags = of_get_option ('tag_display');

global $largo;
$span_class = ( $largo['home_rail'] ) ? 'span8' : 'span12' ;
?>

<div id="content" class="stories <?php echo $span_class; ?> <?php echo sanitize_html_class(basename($home_template)); ?>" role="main">

	<?php if ( is_active_sidebar('homepage-left-rail') ) { ?>
	<div id="content-main" class="<?php echo $span_class; ?>">
	<?php }

	largo_load_custom_template_functions();
	get_template_part( $home_template );

	// sticky posts box if this site uses it
	if ( of_get_option( 'show_sticky_posts' ) ) {
		get_template_part( 'homepages/part', 'sticky-posts' );
	}

	// bottom section, we'll either use a two-column widget area or a single column list of recent posts
	if ( of_get_option( 'homepage_bottom') === 'widgets' ) {
		get_template_part( 'homepages/part', 'bottom-widget-area' );
	} else if ( of_get_option( 'homepage_bottom' ) === 'list' ) {
		$args = array(
			'paged'					=> $paged,
			'post_status'			=> 'publish',
			'posts_per_page'		=> 10,
			'post__not_in' 			=> $shown_ids,
			'ignore_sticky_posts' 	=> true
			);

		if ( of_get_option('num_posts_home') )
			$args['posts_per_page'] = of_get_option('num_posts_home');
		if ( of_get_option('cats_home') )
			$args['cat'] = of_get_option('cats_home');
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				//if the post is in the array of post IDs already on this page, skip it. Just a double-check
				if ( in_array( get_the_ID(), $shown_ids ) ) {
					continue;
				} else {
					$ids[] = get_the_ID();
					get_template_part( 'content', 'home' );
				}
			endwhile;
			largo_content_nav( 'nav-below' );
		} else {
			get_template_part( 'content', 'not-found' );
		}
	}

	if ( is_active_sidebar('homepage-left-rail') ) { ?>
	</div>
	<div id="left-rail" class="span4">
	<?php dynamic_sidebar( 'homepage-left-rail' ) ?>
	</div>
	<?php } ?>

</div><!-- #content-->
<?php if ($largo['home_rail']) get_sidebar(); ?>

<div class="clearfix row-fluid" id="home-bottom">
	<?php
		if ( is_active_sidebar( 'inn-home-bottom' ) )	dynamic_sidebar( 'inn-home-bottom' );
	?>
</div>
<?php get_footer(); ?>