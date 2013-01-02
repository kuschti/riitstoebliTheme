<?php get_header(); ?>

			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">

					<section id="latestgalleries" class="twelvecol first clearfix galleryoverview">

						<?php
						$args = array( 'post_type' => 'post', 'ignore_sticky_posts' => 1, 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => array('post-format-gallery') ) ), 'posts_per_page' => 3, 'order' => 'desc', 'orderby' => 'rs_gallery_date', 'meta_key' => 'rs_gallery_date' );
						$loop = new WP_Query( $args );
						$gallerycount = 0;
						$gallerygridclass = "";


						
					    if (have_posts()) : while ( $loop->have_posts() ) : $loop->the_post(); ?>
					    		<?php if($gallerycount == 0) : ?>
					    		<div class="threecol first">
					    			<h1>Galerien</h1>
					    		</div>
					    		<?php endif; ?>

					    		<?php if ($gallerycount == 2) { $gallerygridclass = "last"; }else { $gallerygridclass = ""; } ?>
					    		<?php $gallerydate = DateTime::createFromFormat('Ymd', get_field('rs_gallery_date')); ?>
					    	
							    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix threecol '.$gallerygridclass.''); ?> role="article">
							    	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">

										<?php if ( has_post_thumbnail() ) {
											the_post_thumbnail('thumbnail'); 
										}
										else { 
											$args = array( 'post_type' => 'attachment', 'numberposts' => 1, 'post_parent' => $post->ID, 'post_status' => null, 'post_mime_type' => 'image', );

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

							    <?php if ($gallerycount == 3) { $gallerycount = 0; }else { $gallerycount++; } ?>
					
					    <?php endwhile; endif;?>	

					</section>	
			
				    <div id="main" class="eightcol first clearfix" role="main">

					    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					    
					    	<?php if(has_post_format("gallery",$post_id)) { 
					    		get_template_part( 'content', get_post_format() );
					    	} else { ?>
					
							    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
								
								    <header class="article-header">
									
									    <h1 class="h1"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
								
								    </header> <!-- end article header -->
							
								    <section class="entry-content clearfix <?php if(!has_post_format("gallery",$post_id)) { ?> ninecol <?php } ?>">
									    <?php the_content(); ?>
								    </section> <!-- end article section -->
								
								    <footer class="article-footer">
								    	<p class="byline vcard"><?php _e('Posted', 'bonestheme'); ?> <time class="updated" datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time(get_option('date_format')); ?></time> <?php _e('by', 'bonestheme'); ?> <span class="author"><?php the_author_posts_link(); ?></span> <span class="amp">&</span> <?php _e('filed under', 'bonestheme'); ?> <?php the_category(', '); ?>.</p>
		    							<p class="tags"><?php the_tags('<span class="tags-title">Tags:</span> ', ', ', ''); ?></p>

								    </footer> <!-- end article footer -->
								    
								    <?php // comments_template(); // uncomment if you want to use them ?>
							
							    </article> <!-- end article -->
							<?php } ?>	    
					
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
    
				    <?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
