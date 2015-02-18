<?php
/**
 * Single Post Template: One Column (Standard Layout)
 * Template Name: One Column (Standard Layout)
 * Description: Shows the post but does not load any sidebars.
 */
add_filter( 'body_class', function( $classes ) {
	$classes[] = 'normal';
	return $classes;
} );

get_header();

$about_pg_id = 2212;
$members_pg_id = 234260;
$content_class = 'span8';

//is this a page or a post in the projects post type
if ( is_page() || is_singular( 'pauinn_project' ) ) {

	// should we show a menu? let's find out.
	$show_menu = '';
	$ancestors = get_post_ancestors( $post );

	// bascially all child pages of the about or members pages + all the posts in the projects post type get the side menu
	if ( is_page( $about_pg_id ) || in_array( $about_pg_id , $ancestors) )
		$show_menu = 'about';
	if ( is_page( $members_pg_id ) || in_array( $members_pg_id , $ancestors) )
		$show_menu = 'members';
	if ( is_singular( 'pauinn_project' ) )
		$show_menu = 'projects';

	// yep, we should show a menu, modify the layout appropriately
	if ( $show_menu != '' ) {
		$content_class = 'span10 has-menu';
		echo '<div class="internal-subnav span2">';
	}

	// if this is the about page or a child, get that page tree
	if ( $show_menu == 'about' ) {
		echo '<h3><a href="' . get_permalink( $about_pg_id ) . '">About</a></h3>';
		echo '<ul>';
		wp_list_pages('title_li=&child_of=' . $about_pg_id . '&echo=1');
		echo '</ul>';

	// else if this is the for members page or a child, get THAT page tree
	} else if ( $show_menu == 'members' ) {
		echo '<h3><a href="' . get_permalink( $members_pg_id ) . '">For Members</a></h3>';
		echo '<ul>';
		wp_list_pages('title_li=&child_of=' . $members_pg_id . '&echo=1');
		echo '</ul>';

	// project pages show a list of projects, add the current_page_item class if necessary for consistency
	} else if ( $show_menu == 'projects' ) {
		echo '<h3>Projects</h3>';
		$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false ) );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		    echo '<ul>';
		    foreach ( $terms as $term ) {
			    $term_link = '/project/' . $term->slug;
			    if ( is_single( $term->name ) ) {
					echo '<li class="current_page_item"><a href="' . $term_link . '">' . $term->name . '</a></li>';
				} else {
		    		echo '<li><a href="' . $term_link . '">' . $term->name . '</a></li>';
		    	}
		    }
		    echo '</ul>';
		}
	}

	// close the menu div
	if ( $show_menu != '' ) {
		echo '</div>';
	}
}

if ( is_page( 'press' ) || is_page( 'news' ) ) $content_class .= ' stories';
?>


<div id="content" class="<?php echo $content_class; ?>" role="main">
	<?php
		while ( have_posts() ) : the_post();

			$partial = ( is_page() ) ? 'page' : 'single';

			if ( is_singular( 'pauinn_project' ) ) {

				get_template_part( 'partials/content', 'page' );

				get_template_part( 'partials/content', 'projects' );

			} else if ( $partial === 'single' ) {

				get_template_part( 'partials/content', $partial );

				if ( is_active_sidebar( 'article-bottom' ) ) {

					do_action( 'largo_before_post_bottom_widget_area' );

					echo '<div class="article-bottom">';
					dynamic_sidebar( 'article-bottom' );
					echo '</div>';

					do_action( 'largo_after_post_bottom_widget_area' );

				}

				do_action(' largo_before_comments' );

				comments_template( '', true );

				do_action( 'largo_after_comments' );

			} else if ( is_page( 'press' ) ) {

				get_template_part( 'partials/content', 'press' );

			} else if ( is_page ( 'news' ) ) {

				get_template_part( 'partials/content', 'news' );

			} else  {

				get_template_part( 'partials/content', $partial );

			}

		endwhile;
	?>
</div>

<?php do_action( 'largo_after_content' ); ?>

<?php get_footer();
