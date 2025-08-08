	load_posts = function( category ){ 			
		
		var data_obj = {
			action: "get_wp_posts",
			nonce: $global['nonce']
		}
		
		if (typeof category !== 'undefined' && category !== 'All Categories') {
			data_obj.category = category;
		}
		
		jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : $global['ajaxurl'],
				data : 
					data_obj,
				success: function(response) {
					
					display_posts(response, true);
					//console.log(response);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					console.log("Status: " + textStatus); console.log("Error: " + errorThrown); 
				}  
				
			}
		);
		load_more_posts(category);
	}
	
	
	filter_posts = function(category){
		jQuery('.main-blog-top select').on("change", function( event ){
			event.preventDefault();
			var selectedCategory = jQuery(this).children("option:selected").val();
			jQuery('.main-blog-posts').html('');
			load_posts( selectedCategory );
		} );
	}
	
	display_posts = function(response, featured){
		jQuery.each( response, function( i, value ) {
			if(featured == true && i == 0){
				var post_content = [
					'<div class="main-blog-post latest-post" style="background: url(' + value.featured_image +');">',
						'<div class="last-post-data">',
							'<div><h2>' + value.trimmed_post_title + '</h2></div>',
							'<a href="' + value.permalink + '" class="read-more">Read more  &#8594;</a>',
						'</div>',
					'</div>'
				].join( "" );
			}else{
				var post_content = [
					'<div class="main-blog-post">',
						'<div class="blog-post-img"><div style="background: url(' + value.featured_image +');">',
						'<a href="' + value.permalink +'"><span class="fill-link-span"></span></a>',
						'</div></div>',
						'<div class="blog-post-data">',
							'<div class="blog-post-date">'+ value.date + '</div>',
							'<div><a href="' + value.permalink + '">' + value.trimmed_post_title + '</a></div>',
							'<p>' + value.post_excerpt +'</p>',
							'<a href="' + value.permalink + '" class="read-more">Read more  &#8594;</a>',
						'</div>',
					'</div>'
				].join( "" );
			}
			jQuery('.main-blog-posts').append(post_content);
			//console.log(value);
		});
	}
	load_more_posts = function(category){
		if ( jQuery( '.load-more-posts .load-more' ).length > 0 ) {
            jQuery( '.load-more-posts .load-more' ).off( 'click' ).on( 'click', function( e ) {
				e.preventDefault();
				var page = jQuery( '.load-more-posts .load-more' ).attr('data-page');
				console.log(page);
				var data_obj = {
					action: "get_wp_posts",
					nonce: $global['nonce'],
					page: page
				}
				
				if (typeof category !== 'undefined' && category !== 'All Categories') {
					data_obj.category = category;
				}
				jQuery.ajax({
						type : "POST",
						dataType : "json",
						url : $global['ajaxurl'],
						data : 
							data_obj,
						success: function(response) {
							
							display_posts(response, false);

						},
						error: function(XMLHttpRequest, textStatus, errorThrown) { 
							console.log("Status: " + textStatus); console.log("Error: " + errorThrown); 
						}  
						
					}
				);
				page++;
				jQuery( '.load-more-posts .load-more' ).attr('data-page', page);
				
			});
		}
		
	}

jQuery(document).ready(function(){
	load_posts();
	filter_posts();
});

/* external links in new tab */

;(function () {
	documentReady(function () {
		const links = document.links
		for (let i = 0, linksLength = links.length; i < linksLength; i++) {
			if (links[i].hostname !== window.location.hostname) {
				links[i].target = '_blank'
			}
		}
	})

	function documentReady(fn) {
		if (document.readyState !== 'loading') {
			fn()
		} else {
			document.addEventListener('DOMContentLoaded', fn)
		}
	}
})()
