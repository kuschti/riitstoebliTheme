<?php
/*
Template Name: Galerieübersicht
*/
?>

<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
				    <div id="main" class="twelvecol first clearfix galleryoverview" role="main">

				    	<?php
						$args = array( 
							'post_type' => 'post', 
							'ignore_sticky_posts' => 1,
							'tax_query' => array(
				            	array( 'taxonomy' => 'post_format',
				                	'field' => 'slug',
				                  	'terms' => array('post-format-gallery')
				                )
					        ),
					        'posts_per_page' => 20,
					        'order' => 'desc',
					        'orderby' => 'rs_gallery_date',
					        'meta_key' => 'rs_gallery_date'
						);
						$loop = new WP_Query( $args );
						$gallerycount = 0;
						$gallerygridclass = "";
						
					    if (have_posts()) : while ( $loop->have_posts() ) : $loop->the_post(); ?>

					    		<?php
					    		if($gallerycount == 0) {
					    			$gallerygridclass = "first";
					    		}elseif ($gallerycount == 3) {
					    			$gallerygridclass = "last";
					    		}else {
					    			$gallerygridclass = "";
					    		} ?>
					    		<?php $gallerydate = DateTime::createFromFormat('Ymd', get_field('rs_gallery_date')); ?>
					    	
							    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix threecol '.$gallerygridclass.''); ?> role="article">
							    	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">

										<?php if ( has_post_thumbnail() ) {
											the_post_thumbnail('thumbnail'); 
										}
										else {
											$args = array(
										   		'post_type' => 'attachment',
										   		'numberposts' => 1,
										   		'post_parent' => $post->ID,
										   		'post_status' => null,
										   		'post_mime_type' => 'image',
										  	);

										  	$attachments = get_posts( $args );
										    if ( $attachments ) {
										    	foreach ($attachments as $attachment) {
										    		echo wp_get_attachment_image( $attachment->ID );
											    }
										    }

										} ?>


									    <header class="article-header">
										
										    <h1 class="h1"><?php the_title(); ?></h1>
											<h3><?php echo $gallerydate->format('d.m.Y') ?></h3>
									    </header> <!-- end article header -->

									</a>
							    </article> <!-- end article -->

							    <?php 
							    	if ($gallerycount == 3) {
							    		$gallerycount = 0; 
							    	}else {
							    		$gallerycount++;
							    	}
							    ?>
					
					    <?php endwhile; ?>	
					
					        <?php if (function_exists('bones_page_navi')) { ?>
					            <?php bones_page_navi(); ?>
					        <?php } else { ?>
					            <nav class="wp-prev-next">
					                <ul class="clearfix">
					        	        <li class="prev-link"><?php next_posts_link(__('&laquo; Older Entries', "bonestheme")) ?></li>
					        	        <li class="next-link"><?php previous_posts_link(__('Newer Entries &raquo;', "bonestheme")) ?></li>
					                </ul>
					            </nav>
					        <?php } ?>		
					
					    <?php else : ?>
					    
					        <article id="post-not-found" class="hentry clearfix">
					            <header class="article-header">
					        	    <h1><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
					        	</header>
					            <section class="entry-content">
					        	    <p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
					        	</section>
					        	<footer class="article-footer">
					        	    <p><?php _e("This is the error message in the index.php template.", "bonestheme"); ?></p>
					        	</footer>
					        </article>
					
					    <?php endif; ?>
			
				    </div> <!-- end #main -->
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>