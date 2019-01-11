<?php
// ########################################################
// CPT Custom Post Types
// ########################################################

// https://codex.wordpress.org/Function_Reference/register_post_type#Example

function grc_custom_post_types() {

    // press-releases CPT
    $labels = array(
        'name'               => 'Press Releases',
        'singular_name'      => 'Press Release',
        'menu_name'          => 'Press Releases',
        'name_admin_bar'     => 'Press Releases',
        'add_new'            => 'Add New Press Release',
        'add_new_item'       => 'Add New Press Release',
        'new_item'           => 'New Press Release',
        'edit_item'          => 'Edit Press Release',
        'view_item'          => 'View Press Release',
        'all_items'          => 'All Press Releases',
        'search_items'       => 'Search Press Releases',
        'parent_item_colon'  => 'Parent Press Releases:',
        'not_found'          => 'No Press Release found.',
        'not_found_in_trash' => 'No Press Release found in Bin.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-id-alt', //this icon is from: https://developer.wordpress.org/resource/dashicons/
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'press-releases' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'show_in_rest'		 => true, // enable Gutenberg Editor for CPT
        'taxonomies'         => array( 
                                    // 'category',
                                    'post_tag' 
                                ) // comment these lines if you want your custom taxonomy to work indipendently from standard posts
    );
    register_post_type( 'press-releases', $args );

    // add here another CPT just duplicating and adapting the above code
}

add_action( 'init', 'grc_custom_post_types' );



/********************************************************/
// Custom Taxonomies
/********************************************************/

//https://codex.wordpress.org/Function_Reference/register_taxonomy#Example

function grc_custom_taxonomies() {

    // Press Releases Types
    $labels = array(
        'name'              => 'Press Releases Types',
        'singular_name'     => 'Press Releases Type',
        'search_items'      => 'Search Press Releases Types',
        'all_items'         => 'All Press Releases Types',
        'parent_item'       => 'Parent Press Releases Types',
        'parent_item_colon' => 'Parent Press Releases Types:',
        'edit_item'         => 'Edit Press Releases Type',
        'update_item'       => 'Refresh Press Releases Type',
        'add_new_item'      => 'Add New Press Releases Type',
        'new_item_name'     => 'Name New Press Releases Type',
        'menu_name'         => 'Press Releases Types',
    );

    $args = array(
        'hierarchical'      => true, // set to be like categories
        //'hierarchical'      => false, // set to be like tags
        // https://codex.wordpress.org/Function_Reference/register_taxonomy#Parameters
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'press-releases-types' ),
    );

    register_taxonomy( 'press-releases-types', array( 'press-releases' ), $args );

    // add here another Custom Taxonomy just duplicating and adapting the above code

}

add_action( 'init', 'grc_custom_taxonomies' );



/********************************************************/
// Flush rewrite rules to add "press-releases-types" as a permalink slug
/********************************************************/

function my_rewrite_flush() {
    grc_custom_post_types();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'my_rewrite_flush' );

// https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation



// ########################################################
// Make Archives.php Include Custom Post Types
// ########################################################

// this snippet can be pasted within the custom plugin created to output the CPT,
// assuming we are creating a plugin instead of embedding the CPT into functions.php

function namespace_add_custom_types($query) {
    if (is_category() || is_tag() && empty($query->query_vars['suppress_filters'])) {
        $query->set('post_type', array(
            'post',
            'nav_menu_item',
            'press-releases'
        ));
        return $query;
    }
}

add_filter( 'pre_get_posts', 'namespace_add_custom_types' );

// http://css-tricks.com/snippets/wordpress/make-archives-php-include-custom-post-types/


// ########################################################
// Custom messages in the admin editor notifications bar,
// just for CPT, not CT
// ########################################################
function custom_post_type_update_messages( $messages ) {
    global $post;

    $post_ID = $post->ID;
    $post_type = get_post_type( $post_ID );

    $obj = get_post_type_object( $post_type );
    $singular = $obj->labels->singular_name;

    $messages[$post_type] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf( __( '%s Updated. <a href="%s" target="_blank">View %s</a>' ), esc_attr( $singular ), esc_url( get_permalink( $post_ID ) ), strtolower( $singular ) ),
        2 => __( 'Custom field updated.', 'grc-wp-starter-theme-2018' ),
        3 => __( 'Custom field deleted.', 'grc-wp-starter-theme-2018' ),
        4 => sprintf( __( '%s Updated.', 'grc-wp-starter-theme-2018' ), esc_attr( $singular ) ),
        5 => isset( $_GET['revision']) ? sprintf( __('%2$s restored to revision from %1$s', 'grc-wp-starter-theme-2018' ), wp_post_revision_title( (int) $_GET['revision'], false ), esc_attr( $singular ) ) : false,
        6 => sprintf( __( '%s Published. <a href="%s">View %s</a>'), $singular, esc_url( get_permalink( $post_ID ) ), strtolower( $singular ) ),
        7 => sprintf( __( '%s Saved.', 'grc-wp-starter-theme-2018' ), esc_attr( $singular ) ),
        8 => sprintf( __( '%s Submitted. <a href="%s" target="_blank">Preview %s</a>'), $singular, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), strtolower( $singular ) ),
        9 => sprintf( __( '%s Scheduled for: <strong>%s</strong>. <a href="%s" target="_blank">Preview %s</a>' ), $singular, date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ), strtolower( $singular ) ),
        10 => sprintf( __( '%s Draft Updated. <a href="%s" target="_blank">Preview %s</a>'), $singular, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), strtolower( $singular ) )
    );

    return $messages;
}

add_filter( 'post_updated_messages', 'custom_post_type_update_messages' );

// http://thomasmaxson.com/update-messages-for-custom-post-types/


// ####################################################
// Example of custom loop to filter CPT and CT
// ####################################################

 //    global $wp_query, $wp_the_query;

 //    $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

 //    $wp_query = new WP_Query( array(
 //        'paged'             => get_query_var('paged') ? get_query_var('paged') : 1,
 //        'post_type'         => 'press-releases',
 //        'tax_query' => array(
 //                             array(
 //                                'taxonomy' => 'press-releases-types',   // 'custom-taxonomy-name'
 //                                'field'    => 'slug',                // it is what it is: 'slug'
 //                                'terms'    => 'reports',             // 'custom-slug-name'
 //                                )
 //                            ),
 //        'post_status'       => 'publish',
 //        'posts_per_page'    => 2,   // show n posts
 //        //'taxonomy'          => 'press-releases-types',
 //        //'term'              => $term->slug,
 //        //'term'              => 'reports',
 //        //'cat'               => 77, // include this category from the posts list
 //        // other parameters here: http://codex.wordpress.org/Class_Reference/WP_Query#Parameters
 //    ) );

 //    // reset the main query
 //    $wp_query = $wp_the_query;
?>
