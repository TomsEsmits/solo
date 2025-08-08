<?php
/**
 * Template Name: Main Blog

 * @package Expound
 */

get_header('new'); ?>

<div class="new-wrapper flex-display">
	<div class="main-blog-top">
		<div><h1 class="main-blog-top-title">Check Out Our Latest Posts</h1></div>
		<div>
			<div>
				<div>
					<span>View</span>
					<div class="select-container">
						<select>
							<option value="All Categories">All Categories</option>
							
							<?php  
								$all_categories = get_categories(); 
								
								foreach ($all_categories as $category){
									if($category ->category_count > 1  ){
										echo '<option value="' . $category -> name . '">' . $category -> name . '</option>';
									}
								};
							?>
						</select>
					</div>
				</div>
			</div>
			<?php  get_search_form(); ?>
		</div>
	</div>
	<div class="main-blog-posts">
		
	</div>
	<div class="load-more-posts">
		<a href="#" data-page="2" class="load-more">Load more posts</a>
	</div>
	<div id="post">
	</div>
</div>
<script>

	jQuery(document).ready(function(){
		jQuery('.main-blog-top form img').on('click', function(){
			jQuery('.main-blog-top #searchsubmit').click();
		})
	});	
</script>
<?php get_footer(); ?>