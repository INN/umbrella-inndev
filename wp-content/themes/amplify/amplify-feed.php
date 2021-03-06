<?php
/**
 * Single Post Template: Amplify Feed
 * Template Name: Amplify Feed Template
 * Description: Shows the saved links feed that can be embedded on external sites.
 */

if( isset( $_GET['horizontal'] ) && 'true' == $_GET['horizontal'] ){
    $body_class = 'horizontal';
} else {
    $body_class = 'vertical';
}

if( isset( $_GET['show_link_descriptions'] ) && 'true' == $_GET['show_link_descriptions'] ){
    $saved_links_class = 'show-link-description';
} else {
    $saved_links_class = '';
}

if( isset( $_GET['hide_header'] ) && 'true' == $_GET['hide_header'] ) {
    $hide_header = 'hide-header';
} else {
    $hide_header = '';
}

if( isset( $_GET['rows'] ) && 2 == $_GET['rows'] ){
    $rows = 'rows-2';
} else if( isset( $_GET['rows'] ) && 3 == $_GET['rows'] ){
    $rows = 'rows-3';
} else {
    $rows = 'rows-1';
}

?>

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
    <link rel="profile" href="https://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <title>Amplify Embed Feed | INN Amplify Project</title>
    <?php wp_head(); ?>
</head>
<body class="<?php echo $body_class; ?>">

    <div class="amplify-embed-feed">

        <?php

        $term_description = 'Stories from the project';

        if( isset( $_GET['tag'] ) ){
            
            echo '<div class="tag-header '.$hide_header.'">';
            
                $term = $_GET['tag'];
                $term = get_term_by( 'slug', $term, 'lr-tags' );

                if( $term->description && ! empty( $term->description ) ){
                    $term_description = $term->description;
                }
                
                if ( function_exists ( 'largo_get_term_meta_post' ) ) {
                    
                    $term_meta_post = largo_get_term_meta_post( 'lr-tags', $term->term_id );
                    $term_meta = get_post_meta( $term_meta_post );

                    if ( ! empty( trim( $term_meta['lr_more_link'][0] ) ) ) {
                        $term_more_link = $term_meta['lr_more_link'][0];
                    }

                    $thumbnail = get_the_post_thumbnail_url( $term_meta_post, 'large' );

                    if( $term_more_link ){
                        echo '<a class="tag-more-link" href="'.$term_more_link.'" target="_blank"><img class="tag-featured-media" src="'.$thumbnail.'"></a>';
                    } else {
                        echo '<img class="tag-featured-media" src="'.$thumbnail.'">';
                    }

                }

            echo '</div>';

        }

        $query_args = array (
            'post_type'      => 'rounduplink',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );

        if( isset( $term ) ){

            $tax_query = array (
                'taxonomy' => 'lr-tags',
                'field' => 'slug',
                'terms' => $term->slug,
            );

            $query_args['tax_query'] = array( $tax_query );

        }

        $my_query = new WP_Query( $query_args );

        if ( $my_query->have_posts() ) {

            if( isset( $_GET['horizontal'] ) && 'true' == $_GET['horizontal'] ){
                echo '<div class="stories-wrapper">';
            }
            echo '<h5 class="stories-title '.$hide_header.'">'.$term_description.'</h5>';
            echo '<div class="saved-links '.$rows.'">';

            while ( $my_query->have_posts() ) : $my_query->the_post();
            $saved_link = get_post_custom( $post->ID );

            // skip roundups
            if ( get_post_type( $post ) === 'roundup' ) continue; ?>

            <div class="saved-link clearfix <?php echo $saved_links_class; ?>">
                <h5><?php
                    if ( isset( $saved_link["lr_url"][0] ) ) {
                        $output = '<a href="' . $saved_link["lr_url"][0] . '" ';
                        $output .= 'target="_blank" ';
                        $output .= '>' . get_the_title() . '</a>';
                    } else {
                        $output = get_the_title();
                    }
                    echo $output;
                    ?>
                </h5>

                <?php
                    if ( isset($saved_link["lr_source"][0] ) && !empty( $saved_link["lr_source"][0] ) ) {
                        $saved_link_source = '<p class="source">' . __('By ', 'link-roundups') . '<span>';
                        if ( !empty( $saved_link["lr_url"][0] ) ) {
                            $saved_link_source .= '<a href="' . $saved_link["lr_url"][0] . '" ';
                            $saved_link_source .= 'target="_blank" ';
                            $saved_link_source .= '>' . $saved_link["lr_source"][0] . '</a>';
                        } else {
                            $saved_link_source .= $saved_link["lr_source"][0];
                        }
                        $saved_link_source .= '</span></p>';
                        echo $saved_link_source;
                    }
                    if ( isset( $saved_link["lr_desc"][0] ) ) {
                        echo '<div class="description">';
                            echo wpautop( $saved_link["lr_desc"][0] );
                        echo '</div>';
                    }
                ?>
            </div>
            
        <?php
            endwhile;
            if( isset( $term ) ){
                if ( ! empty( trim( $term_meta['lr_gforms_id'][0] ) ) ) {
                    $term_gforms_id = $term_meta['lr_gforms_id'][0];
                    echo '<div class="submit-idea-container"><a target="_blank" class="submit-idea-btn btn" href="https://docs.google.com/forms/d/e/'.$term_gforms_id.'/viewform">Submit a story idea</a></div>';
                }
            }
            echo '</div>';
            if( isset( $_GET['horizontal'] ) && 'true' == $_GET['horizontal'] ){
                echo '</div>';
            }
        } else {
            _e( '<p class="error">You don\'t have any recent links or the link roundups plugin is not active.</p>', 'link-roundups' );
        } // end recent links
        ?>
    </div>
</body>
</html>