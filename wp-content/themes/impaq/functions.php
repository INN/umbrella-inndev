<?php

// Includes
$includes = array(
	'/homepages/homepage.php'
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}

// Typekit
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/cui8tby.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Add impaq branding
function impaq_header() {
	get_template_part( 'partials/impaq-header' );
}
add_action( 'largo_main_top', 'impaq_header' );