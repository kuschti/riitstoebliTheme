<?php
/**
 * The template for displaying posts in the Gallery Post Format
 *
 * @package Meola
 * @since Meola 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
								
    <header class="article-header">
	
	    <h1 class="h1"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

    </header> <!-- end article header -->

    <section class="entry-content clearfix <?php if(!has_post_format("gallery",$post_id)) { ?> ninecol <?php } ?>">
    	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
    		<?php echo get_the_post_thumbnail( $post_id, $size, $attr ); ?>
    	</a>
    	<p>Weitere Bilder nach dem Klick</p> 
	    <?php the_excerpt(); ?>
    </section> <!-- end article section -->

    <footer class="article-footer">
    	<p class="byline vcard"><?php _e('Posted', 'bonestheme'); ?> <time class="updated" datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time(get_option('date_format')); ?></time> <?php _e('by', 'bonestheme'); ?> <span class="author"><?php the_author_posts_link(); ?></span> <span class="amp">&</span> <?php _e('filed under', 'bonestheme'); ?> <?php the_category(', '); ?>.</p>
		<p class="tags"><?php the_tags('<span class="tags-title">Tags:</span> ', ', ', ''); ?></p>

    </footer> <!-- end article footer -->
    
    <?php // comments_template(); // uncomment if you want to use them ?>

</article> <!-- end article -->