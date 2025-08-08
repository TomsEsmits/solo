<?php
/**
 * @package Expound
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php expound_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="social-share-container">
			<div class="social-share-flex">
				<div>
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2';
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-layout="button" data-size="small" data-mobile-iframe="true"><a target="_blank" href="<?php the_permalink(); ?>" class="fb-xfbml-parse-ignore">Share</a></div>		
				</div>
				<div>
					<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
				</div>
				<div>
					<a class="btn btn-default icon linkedin-btn" href="javascript:void(0)" onclick="window.open( 'http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Linkedin">
						<img src="https://www.solopcms.com/blog/wp-content/uploads/2018/12/Linkedin-Share-Button.png" width="70px"/>
					</a>
				</div>
			</div>			
		</div		
		
		
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'expound' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php expound_posted_in(); ?>
		<style>
			.social-share-flex{
				display: flex;
				padding:15px 0px;
			}
			.social-share-flex > div:first-child{
				padding-left:0px;
			}
			.social-share-flex > div{
				padding-left:15px;
			}
			.linkedin-btn{
				opacity: 0.9;
			}
			.linkedin-btn:hover{
				opacity: 1;
			}
		</style>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->