<!DOCTYPE html>
<!--[if lt IE 7]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<?php
	// get the current url (used for rel canonical and open graph tags)
	global $current_url;
	$current_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
?>
<title>
	<?php
		global $page, $paged;
		wp_title( '|', true, 'right' );
		bloginfo( 'name' ); // Add the blog name.

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . 'Page ' . max( $paged, $page );
	?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<link rel="canonical" href="<?php echo $current_url; ?>" />
<?php if (get_option( 'blog_public') ) {
	if (is_date() || ( is_archive() &&  of_get_option( 'noindex_archives' ) ) ) { ?>
		<meta name="robots" content="noindex,follow" />
<?php }
	}
?>

<?php
	wp_enqueue_style( 'largo-stylesheet', get_template_directory_uri() . '/css/style.css' );
	wp_enqueue_script( 'largo-modernizr', get_template_directory_uri() . '/js/modernizr.custom.js' );

	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<?php echo $header_img; ?>
<div id="top"></div>
<div class="global-nav-bg">
	<div class="global-nav">
		<nav id="top-nav" class="span12">
        	<span class="visuallyhidden">
        		<a href="#main" title="Skip to content">Skip to content</a>
        	</span>
        	<?php
				$args = array(
					'theme_location' => 'global-nav',
					'depth'		 => 1,
					'container'	 => false,
				);
				wp_nav_menu($args);
			?>
        	<div class="nav-right">
        		<?php if ( of_get_option( 'show_donate_button') ) :
        			largo_donate_button();
        		endif; ?>

				<div id="header-search">
					<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div class="input-append">
							<input type="text" placeholder="SEARCH" class="input-medium appendedInputButton search-query" value="" name="s" /><button type="submit" class="search-submit btn">GO</button>
						</div>
					</form>
				</div>
				<div class="org-logo">
        			<a href="http://investigativenewsnetwork.org/" target="_blank"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/img/inn-logo-80-55.jpg" height="55" alt="INN logo" /></a>
				</div>
        	</div>
        </nav>
    </div> <!-- /.global-nav -->
</div> <!-- /.global-nav-bg -->

<div id="page" class="hfeed clearfix">

	<header id="site-header" class="clearfix">
		<?php
			$header_tag = is_home() ? 'h1' : 'h2';
			$header_class = of_get_option( 'no_header_image' ) ? 'branding' : 'visuallyhidden';
			$divider = $header_class == 'branding' ? '' : ' - ';
    		//print the text-only version of the site title
    		printf('<%1$s class="%2$s"><a href="%3$s">%4$s%5$s<span class="tagline">%6$s</span></a></%1$s>',
	    		$header_tag,
	    		$header_class,
	    		esc_url( home_url( '/' ) ),
	    		esc_attr( get_bloginfo('name') ),
	    		$divider,
	    		esc_attr( get_bloginfo('description') )
	    	);
	    	if ($header_class != 'branding') :
    	?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img class="header_img" src="" alt="" />
			</a>
		<?php endif; ?>
	</header>

	<header class="print-header">
		<p><strong><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></strong> (<?php echo $current_url ?>)</p>
	</header>

	<nav id="main-nav" class="navbar clearfix">
	  <div class="navbar-inner">
	    <div class="container">

	      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
	      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	        <div class="label">Navigation</div>
	        <div class="bars">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
	        </div>
	      </a>

	      <ul class="nav">
	        	<li class="home-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="icon-home icon-white"></i></a></li>
	        	<li class="divider-vertical"></li>
	      </ul>
	      <ul class="nav hidden-phone">
	        <?php
				$args = array(
					'theme_location' => 'navbar-categories',
					'depth'		 => 0,
					'container'	 => false,
					'items_wrap' => '%3$s',
					'menu_class' => 'nav',
					'walker'	 => new Bootstrap_Walker_Nav_Menu()
				);
				//wp_nav_menu($args);
			?>
	      </ul>

	      <!-- Everything you want hidden at 940px or less, place within here -->
	      <div class="nav-collapse">
	        <ul class="nav">
	        	<?php
					$args = array(
						'theme_location' => 'navbar-supplemental',
						'depth'		 => 1,
						'container'	 => false,
						'items_wrap' => '%3$s'
					);

					wp_nav_menu($args);
				?>
	        </ul>
	        <ul class="nav visible-phone">
	        	<li class="divider"></li>
		        <?php
					$args = array(
						'theme_location' => 'global-nav',
						'depth'		 => 1,
						'container'	 => false,
						'items_wrap' => '%3$s'
					);
					wp_nav_menu($args);
				?>
	         </ul>
	      </div>

	    </div>
	  </div>
	</nav>
	<?php if ( of_get_option( 'show_dont_miss_menu') ) : ?>
	<nav id="secondary-nav" class="clearfix">
    	<div id="topics-bar" class="span12 hidden-phone">
			<?php wp_nav_menu( array( 'theme_location' => 'dont-miss', 'container' => false, 'depth' => 1 ) ); ?>
		</div>
	</nav>
	<?php endif; ?>

<div id="main" class="row-fluid clearfix">