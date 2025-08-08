<?php 
	/* search results page */
	
	get_header('new');

?>
<style>
header.search-header{
	width: 100%;
}
.new-wrapper{
	width: 1260px;
	max-width: 100%;
	margin: 0 auto;
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
}
.main-blog-top{
	width: 100%;
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	padding-bottom: 30px;
}
.main-blog-top #searchsubmit{
	display: none;
}
.main-blog-top form input.field{
	position: relative;
	border: solid 1px #dcdbda;
	height:35px;
	border-radius: 3px;
}
.main-blog-top form input.field:focus{
	border: solid 2px #dcdbda;
}
.main-blog-top form{
	position: relative;
}
.main-blog-top form span{
	position: absolute;
	right: 10px;
	top: 7px;
}
.main-blog-top form img{
	display: inline;
	cursor: pointer;
	-webkit-transition: 0.5s ease-in-out;
    -moz-transition: 0.5s ease-in-out;
    -o-transition: 0.5s ease-in-out;
    transition: 0.5s ease-in-out;
}
.main-blog-top form img:hover{
	-moz-transform: scale(1.1);
	-webkit-transform: scale(1.1);
	-o-transform: scale(1.1);
	-ms-transform: scale(1.1);
	transform: scale(1.2);
}
.main-blog-post.latest-post{
	display: flex;
	position: relative;
	align-items: center;
	width: 100%;
	height: 330px;
	background-color: #fff;
	background-size: cover!important;
	background-position: center center!important;
}
.main-blog-post.latest-post:after{
	content: '';
	background: #000;
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	opacity: 0.3;
	
}
.last-post-data{
	color: #fff;
	padding-left: 30px;
	z-index: 9999;
}
.last-post-data h2{
	color: #fff;
	font-size: 500;
	width: 500px;
	max-width: 90%;
}
.main-blog-top > div:first-child{
	font-size: 24px;
}
.main-blog-top > div:nth-child(2){
	display: flex;
	align-items: center;
	color: #4487b8;
}
.main-blog-top .select-container{
	position:relative;	
	overflow: hidden;
	padding-right: 20px;
}
.main-blog-top .select-container:after{
	content: '';
	position: absolute;
	top: 39%;
	width: 0;
	height: 0;
	border-left: 7px solid
	transparent;
	border-right: 7px solid
	transparent;
	border-top: 7px solid
	#e8e8e8;
	clear: both;
	right: 30px;
	cursor: pointer;
	z-index: 1;
}
.main-blog-top > div:nth-child(2) > div{
	display: flex;
}
.main-blog-top > div:nth-child(2) select{
	-moz-appearance:none; /* Firefox */
    -webkit-appearance:none; /* Safari and Chrome */
    appearance:none;
	width: 140px;
	position: relative;
	font-size: 14px;
	border: none;
	line-height: 14px;
	height: 20px;
	color: #4487b8;
	font-weight: bold;
	cursor: pointer;
	z-index: 2;
	background: none;
	padding-right:30px;
}
.main-blog-top > div:nth-child(2) select option{
	overflow: hidden;
}
.main-blog-posts{
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
}
.main-blog-post{
	width: 31%;
	border: solid 1px #dcdbda;
	margin-bottom: 20px;
}
.blog-post-img{
	width: 100%;
	height: 200px;
	background-size: cover!important;
	background-position: center center!important;
}
.blog-post-data{
	padding: 0px 20px 20px 20px;
}
.blog-post-data > div:nth-child(2){
	min-height: 42px;
	font-weight: 600;
}
.blog-post-data p{
	min-height: 105px;
}
.blog-post-date{
	padding: 10px 20px 10px 0px;
}
.load-more-posts{
	margin: 0 auto;
}
.load-more-posts .load-more{
	padding: 10px 20px;
	background: #2cccff;
	color: #fff;
	display: inline-block;
	margin-bottom: 30px;
	transition: background 250ms linear;
}
.load-more-posts .load-more:hover{
	background: #4588b9;
	text-decoration: none;
}
@media(max-width: 1300px){
	.new-main{
		padding: 0px 30px;
	}
	.main-blog-post{
		width: 48%;
	}
}
@media(max-width: 960px){
	.main-blog-top > div{
		width: 100%;
	}
	.main-blog-top{
		padding-top: 30px;
	}
	.main-blog-top > div:nth-child(2){
		justify-content: space-between;
	}
	.main-blog-posts,
	.last-post-data{
		max-width: 100%;
	}
}
@media(max-width: 600px){
	.main-blog-post{
		width: 100%;
	}
}
</style>

<header class="page-header">
	<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'shape' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
</header><!-- .page-header -->

<div class="new-wrapper flex-display">

	<?php if ( have_posts() ) : ?>


		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			
			<div class="main-blog-post">
				<div class="blog-post-img" style="background: url(' <?php the_post_thumbnail_url('full'); ?>')">
				</div>
				<div class="blog-post-data">
					<div class="blog-post-date"><?php the_date(); ?> </div>
					<div><?php echo the_title(); ?></div>
					<p>
						<?php 
							$excerpt = get_the_excerpt();
							echo wp_trim_words($excerpt, 30, '...' );
						?>
					</p>
					<a href="<?php the_permalink(); ?>" class="read-more">Read more  &#8594;</a>
				</div>
			</div>
		<?php endwhile; ?>


	<?php else : ?>

		<?php echo('no results'); ?>

	<?php endif; ?>

</div><!-- #content .site-content -->

<?php
	get_footer();
?>