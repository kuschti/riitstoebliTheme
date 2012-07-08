<?php
/**
 * nori functions and definitions
 *
 * @package WordPress
 * @subpackage Pohutukawa
 * @since Pohutukawa 1.0
 */

/*-----------------------------------------------------------------------------------*/
/* Make theme available for translation
/* Translations can be filed in the /languages/ directory
/*-----------------------------------------------------------------------------------*/

load_theme_textdomain( 'pohutukawa', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

/*-----------------------------------------------------------------------------------*/
/* Set the content width based on the theme's design and stylesheet.
/*-----------------------------------------------------------------------------------*/

if ( ! isset( $content_width ) )
	$content_width = 680;

/*-----------------------------------------------------------------------------------*/
/* Tell WordPress to run pohutukawa() when the 'after_setup_theme' hook is run.
/*-----------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'pohutukawa' );

if ( ! function_exists( 'pohutukawa' ) ):

/*-----------------------------------------------------------------------------------*/
/* Create the Nori Theme Options Page
/*-----------------------------------------------------------------------------------*/

require_once ( get_template_directory() . '/includes/theme-options.php' );

/*-----------------------------------------------------------------------------------*/
/* Call JavaScript Scripts for pohutukawa (Masonry layout, Fitvids for Elasic Videos and Custom)
/*-----------------------------------------------------------------------------------*/

add_action('wp_enqueue_scripts','pohutukawa_scripts_function');
	function pohutukawa_scripts_function() {
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', false, '1.0');
		wp_enqueue_script( 'placeholder', get_template_directory_uri() . '/js/jquery.placeholder.min.js', false, '1.0');
		wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', false, '1.0');
}

/*-----------------------------------------------------------------------------------*/
/* Load Google Webfonts for Headlines
/*-----------------------------------------------------------------------------------*/

 function pohutukawa_load_fonts() {
            wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=PT+Sans:700');
            wp_enqueue_style( 'googleFonts');
        }
 
    add_action('wp_print_styles', 'pohutukawa_load_fonts');

/*-----------------------------------------------------------------------------------*/
/* Sets up theme defaults and registers support for WordPress features. 
/*-----------------------------------------------------------------------------------*/

function pohutukawa() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'pohutukawa' ),
		'optional' => __( 'Optional Navigation (no sub menus supported)', 'pohutukawa' )
	) );
	
	// Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'status', 'link', 'quote', 'image', 'gallery', 'video', 'audio' ) );

	// This theme allows users to set a custom background
	add_custom_background();

// The default header text color
	define( 'HEADER_TEXTCOLOR', '222' );
	
	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to pohutukawa_header_image_width and pohutukawa_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'pohutukawa_header_image_width', 1400 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'pohutukawa_header_image_height', 440 ) );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// gets included in the site header
	 
	function header_style() {
    ?>
	 <?php if ( get_header_image() ) : ?>
	 <style type="text/css">
	 @media screen and (min-width: 1100px) {
        #header {
            background: url(<?php header_image(); ?>) no-repeat scroll;
				height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
				background-position:center;
				background-size: cover;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
        }
		}
    </style>
	 <?php endif; // end check for header image ?>
	 <?php
}

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See pohutukawa_admin_header_style(), below.
	add_custom_image_header( 'header_style', 'pohutukawa_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
			'pohutukawa' => array(
				'url' => '%s/images/headers/pohutukawa.jpg',
				'thumbnail_url' => '%s/images/headers/pohutukawa-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Pohutukawa', 'pohutukawa' )
			),
			'roadtrip' => array(
				'url' => '%s/images/headers/roadtrip.jpg',
				'thumbnail_url' => '%s/images/headers/roadtrip-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Roadtrip', 'pohutukawa' )
			),
			'poetrygarden' => array(
				'url' => '%s/images/headers/poetrygarden.jpg',
				'thumbnail_url' => '%s/images/headers/poetrygarden-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Poetry Garden', 'pohutukawa' )
			),
			'skyline' => array(
				'url' => '%s/images/headers/skyline.jpg',
				'thumbnail_url' => '%s/images/headers/skyline-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Skyline', 'pohutukawa' )
			)
	) );
}
endif;

if ( ! function_exists( 'pohutukawa_admin_header_style' ) ) :

/*-----------------------------------------------------------------------------------*/
/* Styles the header image displayed on the Appearance > Header admin panel.
/* Referenced via add_custom_image_header() in pohutukawa_setup().
/*-----------------------------------------------------------------------------------*/

function pohutukawa_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#heading {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
/*-----------------------------------------------------------------------------------*/

function pohutukawa_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'pohutukawa_page_menu_args' );

/*-----------------------------------------------------------------------------------*/
/* Sets the post excerpt length to 40 characters.
/*-----------------------------------------------------------------------------------*/

function pohutukawa_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'pohutukawa_excerpt_length' );

/*-----------------------------------------------------------------------------------*/
/* Returns a "Continue Reading" link for excerpts
/*-----------------------------------------------------------------------------------*/

function pohutukawa_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading', 'pohutukawa' ) . '</a>';
}

/*-----------------------------------------------------------------------------------*/
/* Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and pohutukawa_continue_reading_link().
/*
/* To override this in a child theme, remove the filter and add your own
/* function tied to the excerpt_more filter hook.
/*-----------------------------------------------------------------------------------*/

function pohutukawa_auto_excerpt_more( $more ) {
	return ' &hellip;' . pohutukawa_continue_reading_link();
}
add_filter( 'excerpt_more', 'pohutukawa_auto_excerpt_more' );

/*-----------------------------------------------------------------------------------*/
/* Adds a pretty "Continue Reading" link to custom post excerpts.
/*
/* To override this link in a child theme, remove the filter and add your own
/* function tied to the get_the_excerpt filter hook.
/*-----------------------------------------------------------------------------------*/

function pohutukawa_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= pohutukawa_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'pohutukawa_custom_excerpt_more' );

/*-----------------------------------------------------------------------------------*/
/* Remove inline styles printed when the gallery shortcode is used.
/*-----------------------------------------------------------------------------------*/

function pohutukawa_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'pohutukawa_remove_gallery_css' );


if ( ! function_exists( 'pohutukawa_comment' ) ) :

/*-----------------------------------------------------------------------------------*/
/* Comments template pohutukawa_comment
/*-----------------------------------------------------------------------------------*/

function pohutukawa_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">			

				<?php echo get_avatar( $comment, 40 ); ?>
		
			<div class="comment-content">
				<ul class="comment-meta">
					<li class="comment-author"><?php printf( __( '%s', 'pohutukawa' ), sprintf( '%s', get_comment_author_link() ) ); ?></li>
						<li class="comment-time"><?php _e( ' // ', 'pohutukawa' ); ?><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s &#64; %2$s', 'pohutukawa' ),
						get_comment_date('d.m.y'),
						get_comment_time() );
					?></a></li>
					<li class="comment-edit"><?php edit_comment_link( __( 'Edit', 'pohutukawa' ), ' ' );?></li>
					<li class="comment-reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'pohutukawa' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></li>
				</ul>
				
				<?php comment_text(); ?>	
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'pohutukawa' ); ?></p>
				<?php endif; ?>
			</div><!-- end .comment-content -->
		</article><!-- end .comment -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( '<span>Pingback:</span>', 'pohutukawa' ); ?> <?php comment_author_link(); ?></p>
		<p><?php edit_comment_link( __('Edit pingback', 'pohutukawa'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Register widgetized area and update sidebar with default widgets
/*-----------------------------------------------------------------------------------*/

function pohutukawa_widgets_init() {

	register_sidebar( array (
		'name' => __( 'Main Widget Area', 'pohutukawa' ),
		'id' => 'widget-area-main',
		'description' => __( 'Main widget area (below the post and pages content).', 'pohutukawa' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => __( 'Footer Widget Area 1', 'pohutukawa' ),
		'id' => 'widget-area-footer-1',
		'description' => __( 'Footer Widget Area 1 (left column in desktop view)', 'pohutukawa' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => __( 'Footer Widget Area 2', 'pohutukawa' ),
		'id' => 'widget-area-footer-2',
		'description' => __( 'Footer Widget Area 2 (middle column in desktop view)', 'pohutukawa' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => __( 'Footer Widget Area 3', 'pohutukawa' ),
		'id' => 'widget-area-footer-3',
		'description' => __( 'Footer Widget Area 3 (right column in desktop view)', 'pohutukawa' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => __( 'Social Links Footer Widget', 'pohutukawa' ),
		'id' => 'widget-area-footer-sociallinks',
		'description' => __( 'Optional widget area to include the Social Links Widget in the bottom footer area.', 'pohutukawa' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
add_action( 'init', 'pohutukawa_widgets_init' );

/*-----------------------------------------------------------------------------------*/
/* Customized pohutukawa search form
/*-----------------------------------------------------------------------------------*/

function pohutukawa_search_form( $form ) {

    $form = '	<form method="get" class="searchform" action="'.get_bloginfo('url').'">
			<input type="text" class="field s" name="s" placeholder="'. esc_attr__('Search...', 'pohutukawa') .'" />
			<input type="submit" class="searchsubmit" name="submit" value="'. esc_attr__('Submit', 'pohutukawa') .'" />
	</form>';

    return $form;
}
add_filter( 'get_search_form', 'pohutukawa_search_form' );

/**
 * Removes the default CSS style from the WP image gallery
 */
add_filter('gallery_style', create_function('$a', 'return "
<div class=\'gallery\'>";'));


/*-----------------------------------------------------------------------------------*/
/* pohutukawa Shortcodes
/*-----------------------------------------------------------------------------------*/
 
// Enable shortcodes in widget areas
add_filter( 'widget_text', 'do_shortcode' );

// Replace WP autop formatting
if (!function_exists( "pohutukawa_remove_wpautop")) {
	function pohutukawa_remove_wpautop($content) { 
		$content = do_shortcode( shortcode_unautop( $content ) ); 
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content);
		return $content;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Multi Columns Shortcodes
/* Don't forget to add "_last" behind the shortcode if it is the last column.
/*-----------------------------------------------------------------------------------*/

// Two Columns
function pohutukawa_shortcode_two_columns_one( $atts, $content = null ) {
   return '<div class="two-columns-one">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'two_columns_one', 'pohutukawa_shortcode_two_columns_one' );

function pohutukawa_shortcode_two_columns_one_last( $atts, $content = null ) {
   return '<div class="two-columns-one last">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'two_columns_one_last', 'pohutukawa_shortcode_two_columns_one_last' );

// Three Columns
function pohutukawa_shortcode_three_columns_one($atts, $content = null) {
   return '<div class="three-columns-one">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_one', 'pohutukawa_shortcode_three_columns_one' );

function pohutukawa_shortcode_three_columns_one_last($atts, $content = null) {
   return '<div class="three-columns-one last">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_one_last', 'pohutukawa_shortcode_three_columns_one_last' );

function pohutukawa_shortcode_three_columns_two($atts, $content = null) {
   return '<div class="three-columns-two">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_two', 'pohutukawa_shortcode_three_columns' );

function pohutukawa_shortcode_three_columns_two_last($atts, $content = null) {
   return '<div class="three-columns-two last">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'three_columns_two_last', 'pohutukawa_shortcode_three_columns_two_last' );

// Four Columns
function pohutukawa_shortcode_four_columns_one($atts, $content = null) {
   return '<div class="four-columns-one">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_one', 'pohutukawa_shortcode_four_columns_one' );

function pohutukawa_shortcode_four_columns_one_last($atts, $content = null) {
   return '<div class="four-columns-one last">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_one_last', 'pohutukawa_shortcode_four_columns_one_last' );

function pohutukawa_shortcode_four_columns_two($atts, $content = null) {
   return '<div class="four-columns-two">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_two', 'pohutukawa_shortcode_four_columns_two' );

function pohutukawa_shortcode_four_columns_two_last($atts, $content = null) {
   return '<div class="four-columns-two last">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_two_last', 'pohutukawa_shortcode_four_columns_two_last' );

function pohutukawa_shortcode_four_columns_three($atts, $content = null) {
   return '<div class="four-columns-three">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_three', 'pohutukawa_shortcode_four_columns_three' );

function pohutukawa_shortcode_four_columns_three_last($atts, $content = null) {
   return '<div class="four-columns-three last">' . pohutukawa_remove_wpautop($content) . '</div>';
}
add_shortcode( 'four_columns_three_last', 'pohutukawa_shortcode_four_columns_three_last' );

// Divide Text Shortcode
function pohutukawa_shortcode_divider($atts, $content = null) {
   return '<div class="divider"></div>';
}
add_shortcode( 'divider', 'pohutukawa_shortcode_divider' );

/*-----------------------------------------------------------------------------------*/
/* Text Highlight and Info Boxes Shortcodes
/*-----------------------------------------------------------------------------------*/

function pohutukawa_shortcode_white_box($atts, $content = null) {
   return '<div class="white-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'white_box', 'pohutukawa_shortcode_white_box' );

function pohutukawa_shortcode_yellow_box($atts, $content = null) {
   return '<div class="yellow-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'yellow_box', 'pohutukawa_shortcode_yellow_box' );

function pohutukawa_shortcode_red_box($atts, $content = null) {
   return '<div class="red-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'red_box', 'pohutukawa_shortcode_red_box' );

function pohutukawa_shortcode_blue_box($atts, $content = null) {
   return '<div class="blue-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'blue_box', 'pohutukawa_shortcode_blue_box' );

function pohutukawa_shortcode_green_box($atts, $content = null) {
   return '<div class="green-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'green_box', 'pohutukawa_shortcode_green_box' );

function pohutukawa_shortcode_lightgrey_box($atts, $content = null) {
   return '<div class="lightgrey-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'lightgrey_box', 'pohutukawa_shortcode_lightgrey_box' );

function pohutukawa_shortcode_grey_box($atts, $content = null) {
   return '<div class="grey-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'grey_box', 'pohutukawa_shortcode_grey_box' );

function pohutukawa_shortcode_dark_box($atts, $content = null) {
   return '<div class="dark-box">' . do_shortcode( pohutukawa_remove_wpautop($content) ) . '</div>';
}
add_shortcode( 'dark_box', 'pohutukawa_shortcode_dark_box' );

/*-----------------------------------------------------------------------------------*/
/* General Buttons Shortcodes
/*-----------------------------------------------------------------------------------*/

function pohutukawa_button( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'link'	=> '#',
    'target'	=> '',
    'color'	=> '',
    'size'	=> '',
	'form'	=> '',
	'style'	=> '',
    ), $atts));

	$color = ($color) ? ' '.$color. '-btn' : '';
	$size = ($size) ? ' '.$size. '-btn' : '';
	$form = ($form) ? ' '.$form. '-btn' : '';
	$target = ($target == 'blank') ? ' target="_blank"' : '';

	$out = '<a' .$target. ' class="standard-btn' .$color.$size.$form. '" href="' .$link. '"><span>' .do_shortcode($content). '</span></a>';

    return $out;
}
add_shortcode('button', 'pohutukawa_button');

/*-----------------------------------------------------------------------------------*/
/* Link Post Format Button Shortcode
/*-----------------------------------------------------------------------------------*/

function pohutukawa_link_format( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'link'	=> '#',
    'target'	=> '',
	 'style'	=> '',
    ), $atts));

	$target = ($target == 'blank') ? ' target="_blank"' : '';

	$out = '<a' .$target. ' class="link' .$style. '" href="' .$link. '"><span>' .do_shortcode($content). '</span></a>';

    return $out;
}
add_shortcode('link_format', 'pohutukawa_link_format');


/*-----------------------------------------------------------------------------------*/
/* Deactives the default CSS styles for the Smart Archives Reloaded plugin
/*-----------------------------------------------------------------------------------*/

add_filter('smart_archives_load_default_styles', '__return_false');

/*-----------------------------------------------------------------------------------*/
/* Include a custom Flickr Widget
/*-----------------------------------------------------------------------------------*/

class pohutukawa_flickr extends WP_Widget {

	function pohutukawa_flickr() {
		$widget_ops = array('description' => 'Show some Flickr preview images.' , 'pohutukawa');

		parent::WP_Widget(false, __('Flickr Widget', 'pohutukawa'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = $instance['title'];
		$id = $instance['id'];
		$linktext = $instance['linktext'];
		$linkurl = $instance['linkurl'];
		$number = $instance['number'];
		$type = $instance['type'];
		$sorting = $instance['sorting'];
		
		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>
            
        <div class="flickr_badge_wrapper"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=<?php echo $sorting; ?>&amp;&amp;source=<?php echo $type; ?>&amp;<?php echo $type; ?>=<?php echo $id; ?>&amp;size=m"></script>
		  <div class="clear"></div>
		  <?php if($linktext == ''){echo '';} else {echo '<div class="flickr-bottom"><a href="'.$linkurl.'" class="flickr-home" target="_blank">'.$linktext.'</a></div>';}?>
		</div><!-- end .flickr_badge_wrapper -->
	
	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$id = esc_attr($instance['id']);
		$linktext = esc_attr($instance['linktext']);
		$linkurl = esc_attr($instance['linkurl']);
		$number = esc_attr($instance['number']);
		$type = esc_attr($instance['type']);
		$sorting = esc_attr($instance['sorting']);
		?>
		
		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com" target="_blank">idGettr</a>):','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo $id; ?>" class="widefat" id="<?php echo $this->get_field_id('id'); ?>" />
        </p>
		  
		  <p>
            <label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Flickr Profile Link Text:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linktext'); ?>" value="<?php echo $linktext; ?>" class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php _e('Flickr Profile URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linkurl'); ?>" value="<?php echo $linkurl; ?>" class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" />
        </p>

       	<p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos:','pohutukawa'); ?></label>
            <select name="<?php echo $this->get_field_name('number'); ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>">
                <?php for ( $i = 1; $i <= 10; $i += 1) { ?>
                <option value="<?php echo $i; ?>" <?php if($number == $i){ echo "selected='selected'";} ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Choose user or group:','pohutukawa'); ?></label>
            <select name="<?php echo $this->get_field_name('type'); ?>" class="widefat" id="<?php echo $this->get_field_id('type'); ?>">
                <option value="user" <?php if($type == "user"){ echo "selected='selected'";} ?>><?php _e('User', 'pohutukawa'); ?></option>
                <option value="group" <?php if($type == "group"){ echo "selected='selected'";} ?>><?php _e('Group', 'pohutukawa'); ?></option>            
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sorting'); ?>"><?php _e('Show latest or random pictures:','pohutukawa'); ?></label>
            <select name="<?php echo $this->get_field_name('sorting'); ?>" class="widefat" id="<?php echo $this->get_field_id('sorting'); ?>">
                <option value="latest" <?php if($sorting == "latest"){ echo "selected='selected'";} ?>><?php _e('Latest', 'pohutukawa'); ?></option>
                <option value="random" <?php if($sorting == "random"){ echo "selected='selected'";} ?>><?php _e('Random', 'pohutukawa'); ?></option>            
            </select>
        </p>
		<?php
	}
} 

register_widget('pohutukawa_flickr');

/*-----------------------------------------------------------------------------------*/
/* Include a custom Video Widget
/*-----------------------------------------------------------------------------------*/

class pohutukawa_video extends WP_Widget {

	function pohutukawa_video() {
		$widget_ops = array('description' => 'Show a custom featured video.' , 'pohutukawa');

		parent::WP_Widget(false, __('Video Widget', 'pohutukawa'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = $instance['title'];
		$embedcode = $instance['embedcode'];
		
		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>
            
        <div class="video_widget">
		  <div class="featured-video"><?php echo $embedcode; ?></div>
		  </div><!-- end .video_widget -->
	
	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
		$title = esc_attr($instance['title']);
		$embedcode = esc_attr($instance['embedcode']);
		?>
		
		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Video embed code:','pohutukawa'); ?></label>
				<textarea name="<?php echo $this->get_field_name('embedcode'); ?>" class="widefat" rows="6" id="<?php echo $this->get_field_id('embedcode'); ?>"><?php echo( $embedcode ); ?></textarea>
        </p>

		<?php
	}
} 

register_widget('pohutukawa_video');

/*-----------------------------------------------------------------------------------*/
/* Including a custom Social Media Widget
/*-----------------------------------------------------------------------------------*/

 class pohutukawa_sociallinks extends WP_Widget {

	function pohutukawa_sociallinks() {
		$widget_ops = array('description' => 'Link to your social profiles.' , 'pohutukawa');

		parent::WP_Widget(false, __('Social Links Widget', 'pohutukawa'),$widget_ops);
	}

	function widget($args, $instance) {  
		extract( $args );
		$title = $instance['title'];
		$twitter = $instance['twitter'];
		$facebook = $instance['facebook'];
		$googleplus = $instance['googleplus'];
		$flickr = $instance['flickr'];
		$picasa = $instance['picasa'];
		$fivehundredpx = $instance['fivehundredpx'];
		$youtube = $instance['youtube'];
		$vimeo = $instance['vimeo'];
		$dribbble = $instance['dribbble'];
		$ffffound = $instance['ffffound'];
		$pinterest = $instance['pinterest'];
		$zootool = $instance['zootool'];
		$behance = $instance['behance'];
		$deviantart = $instance['deviantart'];
		$squidoo = $instance['squidoo'];
		$slideshare = $instance['slideshare'];
		$lastfm = $instance['lastfm'];
		$grooveshark = $instance['grooveshark'];
		$soundcloud = $instance['soundcloud'];
		$foursquare = $instance['foursquare'];
		$gowalla = $instance['gowalla'];
		$linkedin = $instance['linkedin'];
		$xing = $instance['xing'];
		$wordpress = $instance['wordpress'];
		$tumblr = $instance['tumblr'];
		$rss = $instance['rss'];
		$rsscomments = $instance['rsscomments'];
		$target = $instance['target'];
		
		
		echo $before_widget; ?>
		<?php if($title != '')
			echo '<h3 class="widget-title">'.$title.'</h3>'; ?>

        <ul class="sociallinks">
			<?php 
			if($twitter != '' && $target != ''){
				echo '<li><a href="'.$twitter.'" class="twitter" title="Twitter" target="_blank">Twitter</a></li>';
			} elseif($twitter != '') {
				echo '<li><a href="'.$twitter.'" class="twitter" title="Twitter">Twitter</a></li>';
			}
			?>

			<?php 
			if($facebook != '' && $target != ''){
				echo '<li><a href="'.$facebook.'" class="facebook" title="Facebook" target="_blank">Facebook</a></li>';
			} elseif($facebook != '') {
				echo '<li><a href="'.$facebook.'" class="facebook" title="Facebook">Facebook</a></li>';
			}
			?>

			<?php 
			if($googleplus != '' && $target != ''){
				echo '<li><a href="'.$googleplus.'" class="googleplus" title="Google+" target="_blank">Google+</a></li>';
			} elseif($googleplus != '') {
				echo '<li><a href="'.$googleplus.'" class="googleplus" title="Google+">Google+</a></li>';
			}
			?>

			<?php if($flickr != '' && $target != ''){
				echo '<li><a href="'.$flickr.'" class="flickr" title="Flickr" target="_blank">Flickr</a></li>';
			} elseif($flickr != '') {
				echo '<li><a href="'.$flickr.'" class="flickr" title="Flickr">Flickr</a></li>';
			}
			?>

			<?php if($picasa != '' && $target != ''){
				echo '<li><a href="'.$picasa.'" class="picasa" title="Picasa" target="_blank">Picasa</a></li>';
			} elseif($picasa != '') {
				echo '<li><a href="'.$picasa.'" class="picasa" title="Picasa">Picasa</a></li>';
			}
			?>

			<?php if($fivehundredpx != '' && $target != ''){
				echo '<li><a href="'.$fivehundredpx.'" class="fivehundredpx" title="500px" target="_blank">500px</a></li>';
			} elseif($fivehundredpx != '') {
				echo '<li><a href="'.$fivehundredpx.'" class="fivehundredpx" title="500px">500px</a></li>';
			}
			?>	

			<?php if($youtube != '' && $target != ''){
			echo '<li><a href="'.$youtube.'" class="youtube" title="YouTube" target="_blank">YouTube</a></li>';
			} elseif($youtube != '') {
				echo '<li><a href="'.$youtube.'" class="youtube" title="YouTube">YouTube</a></li>';
			}
			?>

			<?php if($vimeo != '' && $target != ''){
			echo '<li><a href="'.$vimeo.'" class="vimeo" title="Vimeo" target="_blank">Vimeo</a></li>';
			} elseif($vimeo != '') {
				echo '<li><a href="'.$vimeo.'" class="vimeo" title="Vimeo">Vimeo</a></li>';
			}
			?>

			<?php if($dribbble != '' && $target != ''){
			echo '<li><a href="'.$dribbble.'" class="dribbble" title="Dribbble" target="_blank">Dribbble</a></li>';
			} elseif($dribbble != '') {
				echo '<li><a href="'.$dribbble.'" class="dribbble" title="Dribbble">Dribbble</a></li>';
			}
			?>

			<?php if($ffffound != '' && $target != ''){
			echo '<li><a href="'.$ffffound.'" class="ffffound" title="Ffffound" target="_blank">Ffffound</a></li>';
			} elseif($ffffound != '') {
				echo '<li><a href="'.$ffffound.'" class="ffffound" title="Ffffound">Ffffound</a></li>';
			}
			?>

			<?php if($pinterest != '' && $target != ''){
			echo '<li><a href="'.$pinterest.'" class="pinterest" title="Pinterest" target="_blank">Pinterest</a></li>';
			} elseif($pinterest != '') {
				echo '<li><a href="'.$pinterest.'" class="pinterest" title="Pinterest">Pinterest</a></li>';
			}
			?>

			<?php if($zootool != '' && $target != ''){
				echo '<li><a href="'.$zootool.'" class="zootool" title="Zootool" target="_blank">Zootool</a></li>';
			} elseif($zootool != '') {
				echo '<li><a href="'.$zootool.'" class="zootool" title="Zootool">Zootool</a></li>';
			}
			?>

			<?php if($behance != '' && $target != ''){
				echo '<li><a href="'.$behance.'" class="behance" title="Behance Network" target="_blank">Behance Network</a></li>';
			} elseif($behance != '') {
				echo '<li><a href="'.$behance.'" class="behance" title="Behance Network">Behance Network</a></li>';
			}
			?>

			<?php if($deviantart != '' && $target != ''){
				echo '<li><a href="'.$deviantart.'" class="deviantart" title="deviantART" target="_blank">deviantART</a></li>';
			} elseif($deviantart != '') {
				echo '<li><a href="'.$deviantart.'" class="deviantart" title="deviantART">deviantART</a></li>';
			}
			?>

			<?php if($squidoo != '' && $target != ''){
				echo '<li><a href="'.$squidoo.'" class="squidoo" title="Squidoo" target="_blank">Squidoo</a></li>';
			} elseif($squidoo != '') {
				echo '<li><a href="'.$squidoo.'" class="squidoo" title="Squidoo">Squidoo</a></li>';
			}
			?>

			<?php if($slideshare != '' && $target != ''){
				echo '<li><a href="'.$slideshare.'" class="slideshare" title="Slideshare" target="_blank">Slideshare</a></li>';
			} elseif($slideshare != '') {
				echo '<li><a href="'.$slideshare.'" class="slideshare" title="Slideshare">Slideshare</a></li>';
			}
			?>

			<?php if($lastfm != '' && $target != ''){
				echo '<li><a href="'.$lastfm.'" class="lastfm" title="Lastfm" target="_blank">Lastfm</a></li>';
			} elseif($lastfm != '') {
				echo '<li><a href="'.$lastfm.'" class="lastfm" title="Lastfm">Lastfm</a></li>';
			}
			?>

			<?php if($grooveshark != '' && $target != ''){
				echo '<li><a href="'.$grooveshark.'" class="grooveshark" title="Grooveshark" target="_blank">Grooveshark</a></li>';
			} elseif($grooveshark != '') {
				echo '<li><a href="'.$grooveshark.'" class="grooveshark" title="Grooveshark">Grooveshark</a></li>';
			}
			?>

			<?php if($soundcloud != '' && $target != ''){
				echo '<li><a href="'.$soundcloud.'" class="soundcloud" title="Soundcloud" target="_blank">Soundcloud</a></li>';
			} elseif($soundcloud != '') {
				echo '<li><a href="'.$soundcloud.'" class="soundcloud" title="Soundcloud">Soundcloud</a></li>';
			}
			?>

			<?php if($foursquare != '' && $target != ''){
				echo '<li><a href="'.$foursquare.'" class="foursquare" title="Foursquare" target="_blank">Foursquare</a></li>';
			} elseif($foursquare != '') {
				echo '<li><a href="'.$foursquare.'" class="foursquare" title="Foursquare">Foursquare</a></li>';
			}
			?>

			<?php if($gowalla != '' && $target != ''){
				echo '<li><a href="'.$gowalla.'" class="gowalla" title="Gowalla" target="_blank">Gowalla</a></li>';
			} elseif($gowalla != '') {
				echo '<li><a href="'.$gowalla.'" class="gowalla" title="Gowalla">Gowalla</a></li>';
			}
			?>

			<?php if($linkedin != '' && $target != ''){
				echo '<li><a href="'.$linkedin.'" class="linkedin" title="LinkedIn" target="_blank">LinkedIn</a></li>';
			} elseif($linkedin != '') {
				echo '<li><a href="'.$linkedin.'" class="linkedin" title="LinkedIn">LinkedIn</a></li>';
			}
			?>

			<?php if($xing != '' && $target != ''){
				echo '<li><a href="'.$xing.'" class="xing" title="Xing" target="_blank">Xing</a></li>';
			} elseif($xing != '') {
				echo '<li><a href="'.$xing.'" class="xing" title="Xing">Xing</a></li>';
			}
			?>

			<?php if($wordpress != '' && $target != ''){
				echo '<li><a href="'.$wordpress.'" class="wordpress" title="WordPress" target="_blank">WordPress</a></li>';
			} elseif($wordpress != '') {
				echo '<li><a href="'.$wordpress.'" class="wordpress" title="WordPress">WordPress</a></li>';
			}
			?>

			<?php if($tumblr != '' && $target != ''){
				echo '<li><a href="'.$tumblr.'" class="tumblr" title="Tumblr" target="_blank">Tumblr</a></li>';
			} elseif($tumblr != '') {
				echo '<li><a href="'.$tumblr.'" class="tumblr" title="Tumblr">Tumblr</a></li>';
			}
			?>

			<?php if($rss != '' && $target != ''){
				echo '<li><a href="'.$rss.'" class="rss" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			} elseif($rss != '') {
				echo '<li><a href="'.$rss.'" class="rss" title="RSS Feed">RSS Feed</a></li>';
			}
			?>

			<?php if($rsscomments != '' && $target != ''){
				echo '<li><a href="'.$rsscomments.'" class="rsscomments" title="RSS Comments" target="_blank">RSS Comments</a></li>';
			} elseif($rsscomments != '') {
				echo '<li><a href="'.$rsscomments.'" class="rsscomments" title="RSS Comments">RSS Comments</a></li>';
			}
			?>

		</ul><!-- end .sociallinks -->

	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) { 
		$title = esc_attr($instance['title']);
		$twitter = esc_attr($instance['twitter']);
		$facebook = esc_attr($instance['facebook']);
		$googleplus = esc_attr($instance['googleplus']);
		$flickr = esc_attr($instance['flickr']);
		$picasa = esc_attr($instance['picasa']);
		$fivehundredpx = esc_attr($instance['fivehundredpx']);
		$youtube = esc_attr($instance['youtube']);
		$vimeo = esc_attr($instance['vimeo']);
		$dribbble = esc_attr($instance['dribbble']);
		$ffffound = esc_attr($instance['ffffound']);
		$pinterest = esc_attr($instance['pinterest']);
		$zootool = esc_attr($instance['zootool']);
		$behance = esc_attr($instance['behance']);
		$deviantart = esc_attr($instance['deviantart']);
		$squidoo = esc_attr($instance['squidoo']);
		$slideshare = esc_attr($instance['slideshare']);
		$lastfm = esc_attr($instance['lastfm']);
		$grooveshark = esc_attr($instance['grooveshark']);
		$soundcloud = esc_attr($instance['soundcloud']);
		$foursquare = esc_attr($instance['foursquare']);
		$gowalla = esc_attr($instance['gowalla']);
		$linkedin = esc_attr($instance['linkedin']);
		$xing = esc_attr($instance['xing']);
		$wordpress = esc_attr($instance['wordpress']);
		$tumblr = esc_attr($instance['tumblr']);
		$rss = esc_attr($instance['rss']);
		$rsscomments = esc_attr($instance['rsscomments']);
		$target = esc_attr($instance['target']);
		
		?>

		 <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php echo $twitter; ?>" class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php echo $facebook; ?>" class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google+ URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('googleplus'); ?>" value="<?php echo $googleplus; ?>" class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('flickr'); ?>" value="<?php echo $flickr; ?>" class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('picasa'); ?>"><?php _e('Picasa URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('picasa'); ?>" value="<?php echo $picasa; ?>" class="widefat" id="<?php echo $this->get_field_id('picasa'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('fivehundredpx'); ?>"><?php _e('500px URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('fivehundredpx'); ?>" value="<?php echo $fivehundredpx; ?>" class="widefat" id="<?php echo $this->get_field_id('fivehundredpx'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php echo $youtube; ?>" class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('vimeo'); ?>"><?php _e('Vimeo URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('vimeo'); ?>" value="<?php echo $vimeo; ?>" class="widefat" id="<?php echo $this->get_field_id('vimeo'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('dribbble'); ?>"><?php _e('Dribbble URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('dribbble'); ?>" value="<?php echo $dribbble; ?>" class="widefat" id="<?php echo $this->get_field_id('dribbble'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('ffffound'); ?>"><?php _e('Ffffound URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('ffffound'); ?>" value="<?php echo $ffffound; ?>" class="widefat" id="<?php echo $this->get_field_id('ffffound'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('pinterest'); ?>" value="<?php echo $pinterest; ?>" class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id('zootool'); ?>"><?php _e('Zootool URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('zootool'); ?>" value="<?php echo $zootool; ?>" class="widefat" id="<?php echo $this->get_field_id('zootool'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('behance'); ?>"><?php _e('Behance Network URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('behance'); ?>" value="<?php echo $behance; ?>" class="widefat" id="<?php echo $this->get_field_id('behance'); ?>" />
        </p>
		  
		 <p>
            <label for="<?php echo $this->get_field_id('deviantart'); ?>"><?php _e('deviantART URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('deviantart'); ?>" value="<?php echo $deviantart; ?>" class="widefat" id="<?php echo $this->get_field_id('deviantart'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('squidoo'); ?>"><?php _e('Squidoo URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('squidoo'); ?>" value="<?php echo $squidoo; ?>" class="widefat" id="<?php echo $this->get_field_id('squidoo'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('slideshare'); ?>"><?php _e('Slideshare URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('slideshare'); ?>" value="<?php echo $slideshare; ?>" class="widefat" id="<?php echo $this->get_field_id('slideshare'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('lastfm'); ?>"><?php _e('Last.fm URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('lastfm'); ?>" value="<?php echo $lastfm; ?>" class="widefat" id="<?php echo $this->get_field_id('lastfm'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('grooveshark'); ?>"><?php _e('Grooveshark URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('grooveshark'); ?>" value="<?php echo $grooveshark; ?>" class="widefat" id="<?php echo $this->get_field_id('grooveshark'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('soundcloud'); ?>"><?php _e('Soundcloud URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('soundcloud'); ?>" value="<?php echo $soundcloud; ?>" class="widefat" id="<?php echo $this->get_field_id('soundcloud'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('foursquare'); ?>"><?php _e('Foursquare URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('foursquare'); ?>" value="<?php echo $foursquare; ?>" class="widefat" id="<?php echo $this->get_field_id('foursquare'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('gowalla'); ?>"><?php _e('Gowalla URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('gowalla'); ?>" value="<?php echo $gowalla; ?>" class="widefat" id="<?php echo $this->get_field_id('gowalla'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('Linkedin URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php echo $linkedin; ?>" class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('xing'); ?>"><?php _e('Xing URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('xing'); ?>" value="<?php echo $xing; ?>" class="widefat" id="<?php echo $this->get_field_id('xing'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('wordpress'); ?>"><?php _e('WordPress URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('wordpress'); ?>" value="<?php echo $wordpress; ?>" class="widefat" id="<?php echo $this->get_field_id('wordpress'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('tumblr'); ?>"><?php _e('Tumblr URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('tumblr'); ?>" value="<?php echo $tumblr; ?>" class="widefat" id="<?php echo $this->get_field_id('tumblr'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('RSS-Feed URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('rss'); ?>" value="<?php echo $rss; ?>" class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" />
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('rsscomments'); ?>"><?php _e('RSS for Comments URL:','pohutukawa'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('rsscomments'); ?>" value="<?php echo $rsscomments; ?>" class="widefat" id="<?php echo $this->get_field_id('rsscomments'); ?>" />
        </p>
		  
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['target'], true ); ?> id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" <?php checked( $target, 'on' ); ?>> <?php _e('Open all links in a new browser tab', 'pohutukawa'); ?></input>
		</p>
       
		<?php
	}
} 

register_widget('pohutukawa_sociallinks');