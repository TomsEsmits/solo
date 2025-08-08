<?php
/**
 * Template Name: Blog Archive

 * @package Expound
 */
$current_category = get_the_category();
$args = array( 
			'category' => $current_category[0]->cat_ID,
			'posts_per_page' => get_option( "posts_per_page" )
			);
$myposts = get_posts( $args );


get_header('new'); ?>


<div class="new-wrapper flex-display">
	<div class="main-blog-top">
		<div><h1 class="main-blog-top-title">
			<?php 				
				echo $current_category[0]->name;
			?>
		</h1></div>
		<div>
			<div>
				<div>	
					<a href="https://www.solopcms.com/blog" class="blue-btn">Blog Homepage</a>
				</div>
			</div>
			<?php  get_search_form(); ?>
		</div>
	</div>
	<main class="blog-category-main">
		<div class="archive-blog-posts">
			<div class="categories-archive-content style="background:#000;">
				<?php if ( have_posts() ) : ?>
					<?php                
						foreach( $myposts as $post ) :  setup_postdata($post); 
						$featured_img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ));
					?>
						<div class="main-blog-post">						
							<div class="blog-post-img" style="background: url(<?php echo $featured_img_url; ?>);"></div>
							<div class="padded-blog-post-box">
								<p><?php the_date(); ?></p>
								<h3><?php the_title(); ?></h3>
								<div>
									<?php
										$excerpt = get_the_excerpt();
										echo $short_excerpt = substr($excerpt, 0, 180 ) . '...';
										
									?>
								</div>
								<p><a href="<?php the_permalink(); ?>">Read More &#8594;</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
 
					<?php _e('Sorry, no posts matched your criteria.'); ?>
				 
				<?php endif; ?>

			</div>
			<div id="cat-posts-pagination">
				<?php echo paginate_links( $args ); ?>
			</div>
		</div>
		<aside class="blog-category-aside">
			<h2>Solopcms Blog Categories</h2>
			<ul>
				<?php  
					$all_categories = get_categories(); 
					
					foreach ($all_categories as $category){
						if($category ->category_count > 1  ){
							echo '<li><a href="' . get_category_link($category->cat_ID) . '">' . $category -> name . '</a></li>';
						}
					};
				?>
			</ul>
		</aside>
	</main>	
</div>


<script>

	jQuery(document).ready(function(){
		jQuery('.main-blog-top form img').on('click', function(){
			jQuery('.main-blog-top #searchsubmit').click();
		})
	});	
</script>
<?php get_footer(); ?>