<div class="sticky-nav-wrapper">
	<div class="sticky-nav-holder show" data-hide-at-top="<?php echo (is_front_page() || is_home()) ? 'true' : 'false'; ?>">

		<?php do_action( 'largo_before_sticky_nav_container' ); ?>

		<div class="sticky-nav-container">
			<nav id="sticky-nav" class="sticky-navbar navbar clearfix">
				<div class="container">

					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
					<a class="btn btn-navbar toggle-nav-bar" title="<?php esc_attr_e('More', 'largo'); ?>">
						<div class="bars">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</div>
					</a>

					<div class="nav-left">
						<?php
							if ( of_get_option( 'show_sitename_in_sticky_nav', 1 ) ) {
								echo '<li class="site-name"><a href="/">' . get_bloginfo('name') . '</a></li>';
							} else if ( of_get_option( 'sticky_header_logo' ) == '' ) { ?>
								<li class="home-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php largo_home_icon( 'icon-white' ); ?></a></li>
							<?php } else { ?>
								<li class="home-icon"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php largo_home_icon( 'icon-white' , 'medium' ); ?></a></li>

						<?php } ?>
					</div>
					<div class="nav-shelf">
					<ul class="nav">
						<li class="<?php echo (of_get_option('sticky_header_logo') == '' ? 'home-link' : 'home-icon' ) ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php
								if ( of_get_option( 'sticky_header_logo' ) == '' )
									largo_home_icon( 'icon-white' );
								else
									largo_home_icon( 'icon-white' , 'orig' );
								?>
							</a>
						</li>
						<?php
							if ( of_get_option( 'show_sitename_in_sticky_nav', 1 ) )
								echo '<li class="site-name"><a href="/">' . get_bloginfo('name') . '</a></li>';

							$args = array(
							'theme_location' => 'main-nav',
							'depth'		 => 0,
							'container'	 => false,
							'items_wrap' => '%3$s',
							'menu_class' => 'nav',
							'walker'	 => new Bootstrap_Walker_Nav_Menu()
							);
							largo_nav_menu($args);

							if (has_nav_menu('global-nav')) {
								$args = array(
									'theme_location' => 'global-nav',
									'depth'		 => 1,
									'container'	 => false,
									'menu_class' => 'dropdown-menu',
									'echo' => false
								);
								$global_nav = largo_nav_menu($args);

								if (!empty($global_nav)) { ?>
									<li class="menu-item-has-childen dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle"><?php
											//try to get the menu name from global-nav
											$menus = get_nav_menu_locations();
											$menu_title = wp_get_nav_menu_object($menus['global-nav'])->name;
											echo ( $menu_title ) ? $menu_title : __('About', 'largo');
											?> <b class="caret"></b>
										</a>
										<?php echo $global_nav; ?>
									</li>
								<?php } ?>
							<?php } ?>
						</ul>
					</div>
				</div>
			</nav>
		</div>
	</div>
</div>