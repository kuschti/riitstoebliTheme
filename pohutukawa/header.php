<?php
/**
 * The themes header file.
 *
 * @package WordPress
 * @subpackage Pohutukawa
 * @since Pohutukawa 1.0
 */
?><!DOCTYPE html>
<!--[if lte IE 8]>
<html class="ie" <?php language_attributes(); ?>>
<![endif]-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'pohutukawa' ), max( $paged, $page ) );
?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php 
	$options = get_option('pohutukawa_theme_options');
	if( $options['custom_favicon'] != '' ) : ?>
<link rel="shortcut icon" type="image/ico" href="<?php echo $options['custom_favicon']; ?>" />
<?php endif  ?>

<!-- HTML5 enabling script for IE7+8 -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php
	wp_enqueue_script('jquery');
	if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
	wp_head();
?>

</head>

<body <?php body_class(); ?>>

	<div id="wrap">
		<header id="header">
			<div id="branding">
				<hgroup id="site-title">
					<?php $options = get_option('pohutukawa_theme_options');
						if( $options['custom_logo'] != '' ) : ?>
						<a href="<?php echo home_url( '/' ); ?>" class="logo"><img src="<?php echo $options['custom_logo']; ?>" alt="<?php bloginfo('name'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
					<?php else: ?>
						<h1><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
						<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>		
					<?php endif  ?>
				</hgroup><!-- end #site-title -->

				<?php if (has_nav_menu( 'optional' ) ) {
					wp_nav_menu( array('theme_location' => 'optional', 'container' => 'nav' , 'container_class' => 'optional-nav', 'depth' => 1 ));} 
				?>
			</div><!-- end #branding -->
		
			<?php if ( get_header_image() ) : ?>
				<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" class="header-image" /><!-- end header-image -->
			<?php endif; // end check for header image ?>

			<div class="mobile-nav">
				<a href="#main-nav" class="menu-btn"><?php _e('Menu', 'pohutukawa') ?></a>
				<div class="search">
					<?php get_search_form(); ?>
				</div><!-- end .search -->
			</div><!-- end .mobile-nav -->
		</header><!-- end #header -->

	<div id="container" class="clearfix">