<?php
/**
 * Template Name: Single Post

 * @package Expound
 */
	
	get_header('new'); 
?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v5.0"></script>

<?php while ( have_posts() ) : the_post(); 
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
 ?>

<div class="new-wrapper grid-display" id="single-post-container">
	<div>
		<div class="single-post-header" style="background: url('<?php echo $featured_img_url; ?>');" >
		
		</div>
		<div class="single-post-content">
			<div class="single-post-date">
				<!--<?php the_author(); ?>/<?php echo get_the_date(); ?>-->
			</div>
			<h1><?php echo the_title(); ?></h1>
			<?php get_template_part( 'template-parts/vehicle-search-form' ); ?>
			<?php the_static_block( 'home-page-logos' ); ?>
			<?php echo the_content(); ?>
			
		</div>
		<div class="fb-comments" data-href="<?php echo get_permalink(); ?>" data-width="100%" data-numposts="5"></div>
		

	</div>
	<div>
		<div class="sidebar-newsletter">
			<div class="sidebar-title">Sign up for the latest offers, news updates and more.</div>
			<p>Subscribe to our newsletter</p>
			<form action="https://solopcms.us10.list-manage.com/subscribe/post?u=698d95faa5ca718c8be1d2cd6&id=6cd3875ece" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
					<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_698d95faa5ca718c8be1d2cd6_6cd3875ece" tabindex="-1" value=""></div>
					<div class="clear">
						<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
					</div>
				</div>
			</form>

		</div>
		<div id="related-products">
			<div class="related-products-title">Related Products</div>
			<?php
			$products = Mage::getModel('catalog/product')->getCollection();
			$products->addAttributeToSelect(array('name', 'thumbnail', 'price')); //feel free to add any other attribues you need.
			Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
			Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products); 
			$products->getSelect()->order('RAND()');
			$products->getSelect()->limit(4);
			foreach ($products as $product)  : ?>

			  <div class="related-product">
				<div class="related-product-img">
				  <a href="<?php echo $product->getProductUrl()?>"><img src="<?php echo Mage::helper('catalog/image')->init($product, 'thumbnail')->resize(100, 80)?>" alt="<?php echo $product->getName(); ?>"></a>
				</div>

				<div class="related-product-title">
				  <a href="<?php echo $product->getProductUrl(); ?>"><?php echo $product->getName(); ?></a>
				</div>

				<div class="related-product-price"><?php echo Mage::app()->getStore()->getCurrentCurrency()->format($product->getFinalPrice());?></div>

				<div class="related-product-add-cart">
				  <a href="<?php echo $product->getProductUrl(); ?>">SEE DETAILS</a>
				</div>
			  </div>
			<?php 
			endforeach;?>
		</div>
	</div>
	<div id="related-posts">
		<h2>Related Posts</h2>
		<div class="related-posts-container">
			<?php
				$related = new WP_Query(
					array(
						'category__in'   => wp_get_post_categories( $post->ID ),
						'posts_per_page' => 3,
						'post__not_in'   => array( $post->ID )
					)
				);

				if( $related->have_posts() ) { 
					while( $related->have_posts() ) { 
						$related->the_post(); 
						$short_title = substr(get_the_title(), 0, 40) . '...';
						$short_excerpt = substr(get_the_excerpt(), 0, 100) . '...';
// 						$post_link = get_post_permalink($post->ID, true);
 						$post_link = get_permalink($post->ID);
						?>
						<div class="related-post-box">
							<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
							<div class="related-post-img" style="background: url('<?php echo $image[0]; ?>');">
							
							</div>
							<div class="related-post-date">
								<?php echo get_the_date(); ?>
							</div>
							<div class="related-post-excerpt">
								<h3><?php echo $short_title; ?></h3>
								<?php echo $short_excerpt; ?>
							</div>
							<a class="read-more" href="<?php echo $post_link; ?>">Read more  &#8594;</a>
						</div>
				<?php
					}
					wp_reset_postdata();
				}
			?>
		</div>
	</div>
</div>
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>