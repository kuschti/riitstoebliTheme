<?php
/*
Plugin Name: Riitstoebli Setup
Description: riitsteoebli.ch spezifische Konfigurationen
*/

/* Start Adding Functions Below this Line */

// 1. Register Event Post Type

function ctp_rewrite_flush() {
    ctp_rs_event_init();
    cpt_rs_eventtypes_init();
    flush_rewrite_rules(false);
}
register_activation_hook( __FILE__, 'ctp_rewrite_flush' );


// let's create the function for the custom type
function ctp_rs_event_init() { 
    // creating (registering) the custom type 
    register_post_type( 'rs_events', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
        // let's now add all the options for this post type
        array('labels' => array(
            'name' => __('Events', 'bonestheme'), /* This is the Title of the Group */
            'singular_name' => __('Event', 'bonestheme'), /* This is the individual type */
            'all_items' => __('Alle Events', 'bonestheme'), /* the all items menu item */
            'add_new' => __('Neuer Event', 'bonestheme'), /* The add new menu item */
            'add_new_item' => __('Neuen Event erstellen', 'bonestheme'), /* Add New Display Title */
            'edit' => __( 'Bearbeiten', 'bonestheme' ), /* Edit Dialog */
            'edit_item' => __('Events bearbeiten', 'bonestheme'), /* Edit Display Title */
            'new_item' => __('Neuer Event', 'bonestheme'), /* New Display Title */
            'view_item' => __('Event anzeigen', 'bonestheme'), /* View Display Title */
            'search_items' => __('Event suchen', 'bonestheme'), /* Search Custom Type Title */ 
            'not_found' =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */ 
            'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
            'parent_item_colon' => ''
            ), /* end of arrays */
            'description' => __( 'This is the example custom post type', 'bonestheme' ), /* Custom Type Description */
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'show_ui' => true,
            'query_var' => true,
            'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
            'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
            'rewrite'   => array( 'slug' => 'events', 'with_front' => false ), /* you can specify its url slug */
            'has_archive' => 'events', /* you can rename the slug here */
            'capability_type' => 'post',
            'hierarchical' => false,
            /* the next one is important, it tells what's enabled in the post editor */
            'supports' => array( 'title', 'editor', 'author', 'thumbnail')
        ) /* end of options */
    ); /* end of register post type */
    
} 

// adding the function to the Wordpress init
add_action( 'init', 'ctp_rs_event_init'); 

// 2. Event Kategorien
function cpt_rs_eventtypes_init() {     
    // now let's add custom categories (these act like categories)
    register_taxonomy( 'rs_eventtypes', 
        array('rs_events'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
        array('hierarchical' => true,     /* if this is true, it acts like categories */             
            'labels' => array(
                'name' => __( 'Event Typ', 'bonestheme' ), /* name of the custom taxonomy */
                'singular_name' => __( 'Event Typ', 'bonestheme' ), /* single taxonomy name */
                'search_items' =>  __( 'Event Typen suchen', 'bonestheme' ), /* search title for taxomony */
                'all_items' => __( 'Alle Event Typen', 'bonestheme' ), /* all title for taxonomies */
                'parent_item' => __( 'Parent Custom Category', 'bonestheme' ), /* parent title for taxonomy */
                'parent_item_colon' => __( 'Parent Custom Category:', 'bonestheme' ), /* parent taxonomy title */
                'edit_item' => __( 'Event Typ bearbeiten', 'bonestheme' ), /* edit custom taxonomy title */
                'update_item' => __( 'Update Custom Category', 'bonestheme' ), /* update title for taxonomy */
                'add_new_item' => __( 'Neuer Event Typ', 'bonestheme' ), /* add new title for taxonomy */
                'new_item_name' => __( 'New Custom Category Name', 'bonestheme' ) /* name title for taxonomy */
            ),
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'events/typ' ),
        )
    );
}

add_action( 'init', 'cpt_rs_eventtypes_init');

// 3. Show Columns
 
    add_filter("manage_edit-rs_events_columns", "rs_events_edit_columns");
    add_action("manage_posts_custom_column", "rs_events_custom_columns");
     
    function rs_events_edit_columns($columns) {
     
        $columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "title" => "Event",
            "rs_col_ev_date" => "Dates",
            "rs_col_ev_times" => "Times",
            "rs_col_ev_cat" => "Category",
            "rs_col_ev_desc" => "Description",
            );
        return $columns;
    }
     
    function rs_events_custom_columns($column) {
        global $post;
        $custom = get_post_custom();
        switch ($column)
        {
        case "rs_col_ev_cat":
            // - show taxonomy terms -
            $eventcats = get_the_terms($post->ID, "rs_eventtypes");
            $eventcats_html = array();
            if ($eventcats) {
            foreach ($eventcats as $eventcat)
            array_push($eventcats_html, $eventcat->name);
            echo implode($eventcats_html, ", ");
            } else {
            _e('None', 'themeforce');;
            }
        break;
        case "tf_col_ev_date":
            // - show dates -
            $startd = $custom["rs_event_date_start"][0];
            $endd = $custom["rs_event_date_end"][0];
            $startdate = date("F j, Y", $startd);
            $enddate = date("F j, Y", $endd);
            echo $startdate . '<br /><em>' . $enddate . '</em>';
        break;
        case "rs_col_ev_times":
            // - show times -
            $startt = $custom["rs_event_time_start"][0];
            $endt = $custom["rs_event_time_start"][0];
            $time_format = get_option('time_format');
            $starttime = date($time_format, $startt);
            $endtime = date($time_format, $endt);
            echo $starttime . ' - ' .$endtime;
        break;
        case "rs_col_ev_desc";
            the_excerpt();
        break;
         
        }
    }

// 10. Add Event Post Type to Feed

    // Add a Custom Post Type to a feed
    function add_event_to_feed( $qv ) {
      if ( isset($qv['feed']) && !isset($qv['post_type']) )
        $qv['post_type'] = array('post', 'rs_events');
      return $qv;
    }

    add_filter( 'request', 'add_event_to_feed' );   
    
    /*
        looking for custom meta boxes?
        check out this fantastic tool:
        https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
    */
    
    // wp thumbnails (sizes handled in functions.php)
    add_theme_support('post-thumbnails');

    // default thumb size
    set_post_thumbnail_size(125, 125, true);

    // wp custom background (thx to @bransonwerner for update)
    add_theme_support( 'custom-background',
        array(
        'default-image' => '',  // background image default
        'default-color' => '', // background color default (dont add the #)
        'wp-head-callback' => '_custom_background_cb',
        'admin-head-callback' => '',
        'admin-preview-callback' => ''
        )
    );

    // rss thingy
    add_theme_support('automatic-feed-links');

    // to add header image support go here: http://themble.com/support/adding-header-background-image-support/

    // adding post format support
    add_theme_support( 'post-formats',
        array(
            'aside',             // title less blurb
            'gallery',           // gallery of images
            'link',              // quick link to other site
            'image',             // an image
            'quote',             // a quick quote
            'status',            // a Facebook like status update
            'video',             // video
            'chat'               // chat transcript
        )
    );

    // wp menus
    add_theme_support( 'menus' );

    // registering wp3+ menus
    register_nav_menus(
        array(
            'main-nav' => __( 'The Main Menu', 'bonestheme' ),   // main nav in header
            'footer-links' => __( 'Footer Links', 'bonestheme' ) // secondary nav in footer
        )
    );

/* Stop Adding Functions Below this Line */
?>
