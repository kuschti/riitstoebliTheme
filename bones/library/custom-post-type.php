<?php
/* Bones Custom Post Type Example
This page walks you through creating 
a custom post type and taxonomies. You
can edit this one or copy the following code 
to create another one. 

I put this in a separate file so as to 
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

// 1. Register Event Post Type

// let's create the function for the custom type
function post_type_event_init() { 
	// creating (registering) the custom type 
	register_post_type( 'event', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
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
			'rewrite'	=> array( 'slug' => 'events', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'events', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions')
	 	) /* end of options */
	); /* end of register post type */
	
} 

	// adding the function to the Wordpress init
	add_action( 'init', 'post_type_event_init');
	
	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/

// 2. Event Kategorien
	
	// now let's add custom categories (these act like categories)
    register_taxonomy( 'eventtyp', 
    	array('event'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
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
    		'rewrite' => array( 'slug' => 'eventtyp' ),
    	)
    );
// 3. Show Columns
 
    add_filter ("manage_edit-rs_events_columns", "rs_events_edit_columns");
    add_action ("manage_posts_custom_column", "rs_events_custom_columns");
     
    function rs_events_edit_columns($columns) {
     
        $columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "rs_col_ev_cat" => "Category",
            "rs_col_ev_date" => "Dates",
            "rs_col_ev_times" => "Times",
            "title" => "Event",
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
            $eventcats = get_the_terms($post->ID, "eventtyp");
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
            $startd = $custom["rs_events_startdate"][0];
            $endd = $custom["rs_events_enddate"][0];
            $startdate = date("F j, Y", $startd);
            $enddate = date("F j, Y", $endd);
            echo $startdate . '<br /><em>' . $enddate . '</em>';
        break;
        case "rs_col_ev_times":
            // - show times -
            $startt = $custom["rs_events_startdate"][0];
            $endt = $custom["rs_events_enddate"][0];
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

// 4. Show Meta-Box
 
    add_action( 'admin_init', 'rs_events_create' );
     
    function rs_events_create() {
        add_meta_box('rs_events_meta', 'Events', 'rs_events_meta', 'event');
    }
     
    function rs_events_meta () {
     
        // - grab data -
         
        global $post;
        $custom = get_post_custom($post->ID);
        $meta_sd = $custom["rs_events_startdate"][0];
        $meta_ed = $custom["rs_events_enddate"][0];
        $meta_st = $meta_sd;
        $meta_et = $meta_ed;
         
        // - grab wp time format -
         
        $date_format = get_option('date_format'); // Not required in my code
        $time_format = get_option('time_format');
         
        // - populate today if empty, 00:00 for time -
         
        if ($meta_sd == null) { $meta_sd = time(); $meta_ed = $meta_sd; $meta_st = 0; $meta_et = 0;}
         
        // - convert to pretty formats -
         
        $clean_sd = date("D, M d, Y", $meta_sd);
        $clean_ed = date("D, M d, Y", $meta_ed);
        $clean_st = date($time_format, $meta_st);
        $clean_et = date($time_format, $meta_et);
         
        // - security -
         
        echo '<input type="hidden" name="rs-events-nonce" id="rs-events-nonce" value="' .
        wp_create_nonce( 'rs-events-nonce' ) . '" />';
         
        // - output -
         
        ?>
        <div class="rs-meta">
        <ul>
            <li><label>Start Datum</label><input name="rs_events_startdate" class="rsdate" value="<?php echo $clean_sd; ?>" /></li>
            <li><label>Start Zeit</label><input name="rs_events_starttime" value="<?php echo $clean_st; ?>" /><em></em></li>
            <li><label>End Datum</label><input name="rs_events_enddate" class="rsdate" value="<?php echo $clean_ed; ?>" /></li>
            <li><label>End Zeit</label><input name="rs_events_endtime" value="<?php echo $clean_et; ?>" /><em></em></li>
        </ul>
        </div>
        <?php
    }



// 10. Add Event Post Type to Feed

    // Add a Custom Post Type to a feed
    function add_event_to_feed( $qv ) {
      if ( isset($qv['feed']) && !isset($qv['post_type']) )
        $qv['post_type'] = array('post', 'event');
      return $qv;
    }

    add_filter( 'request', 'add_event_to_feed' );   
    
    /*
    	looking for custom meta boxes?
    	check out this fantastic tool:
    	https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
    */
	

?>