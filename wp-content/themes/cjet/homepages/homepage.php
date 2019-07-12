<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class CJETHomepageLayout extends Homepage {
	var $name = 'CJET/Newstraining.org Homepage Layout';
	var $description = 'Custom homepage layout for Newstraining.org.';

	function __construct( $options=array() ) {
		$defaults = array(
			'template' => get_stylesheet_directory() . '/homepages/templates/cjet.php',
			'assets' => array(
				array('cjet', get_stylesheet_directory_uri() . '/homepages/assets/css/cjet.css', array()),
			)
		);
		$options = array_merge( $defaults, $options );
		$this->init();
		$this->load( $options );
	}
}

function inn_custom_homepage_layouts() {
	$unregister = array(
		'HomepageBlog',
		'HomepageSingle',
		'HomepageSingleWithFeatured',
		'HomepageSingleWithSeriesStories',
		'TopStories',
		'Slider',
		'LegacyThreeColumn'
	);

	foreach ($unregister as $layout) {
		unregister_homepage_layout($layout);
	}

	register_homepage_layout('CJETHomepageLayout');
}
add_action('init', 'inn_custom_homepage_layouts', 10);
