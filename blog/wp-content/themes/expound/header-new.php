<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-T2KBPSD');</script>
	<!-- End Google Tag Manager -->
	    <script type="text/javascript">
        (function(i, s, o, g, r, a, m) {
            i["GoogleAnalyticsObject"] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, "script", "//www.google-analytics.com/analytics.js", "ga");

        ga('create', ' UA-46640274-1', 'www.solopcms.com');


        ga("require", "displayfeatures");
        ga('send', 'pageview');
        ga("require", "ec", "ec.js");

        //var $t_jQuery = jQuery.noConflict();
	</script>
	<?php
		$disable == true;
		$head = get_block('head', false);
		//echo $head->getCssJsHtml(); // All CSS and JS files defined in your Magento head
		//echo $head->getChildHtml();
		//echo $head->helper('core/js')->getTranslatorScript();
		echo $head->getIncludes();
		$url = "https://" . $_SERVER[ "SERVER_NAME" ] . "/";
	?>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:400,700' rel='stylesheet' type='text/css'>
	<?php wp_head(); ?>
	<?php /*
	<script type="text/javascript" src="<?php echo $url; ?>skin/frontend/rwd/default/js/bjqs.min.js"></script>
	
	<script type="text/javascript" src="<?php echo $url; ?>js/lib/jquery/jquery-1.10.2.min.js"></script>
	*/ ?>
	
	
	<link rel="stylesheet" href="<?php echo $url; ?>skin/frontend/rwd/default/css/styles.css">
	<script type="text/javascript" src="<?php echo $url; ?>skin/frontend/rwd/default/js/minicart.js" defer></script>
	
	<link rel="stylesheet" href="<?php echo $url; ?>skin/frontend/rwd/default/css/jquery.sidr.dark.css">
	<script type="text/javascript" src="<?php echo $url; ?>skin/frontend/rwd/default/js/jquery.sidr.min.js" defer></script>
	
	
	
</head>
<body>

	<div class="wrapper">
		<div class="page">
			<?php
				the_block('header');
			?>
			<script>				

				function setCookie(cname, cvalue, exdays) {
					var d = new Date();
					d.setTime(d.getTime() + (exdays*24*60*60*1000));
					var expires = "expires="+ d.toUTCString();
					document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
				}

				function getCookie(cname) {
					var name = cname + "=";
					var decodedCookie = decodeURIComponent(document.cookie);
					var ca = decodedCookie.split(';');
					for(var i = 0; i <ca.length; i++) {
						var c = ca[i];
						while (c.charAt(0) == ' ') {
							c = c.substring(1);
						}
						if (c.indexOf(name) == 0) {
							return c.substring(name.length, c.length);
						}
					}
					return "";					
				}

				jQuery( document ).ready( function() {
					
					jQuery(document).mouseleave(function() {						
						console.log('testing mouse out');
						var leave_cookie = getCookie('popup_leave');

						//if( leave_cookie != 'yes' ){
							jQuery( '#leave-popup' ).addClass('active');
							var set_cookie = setCookie('popup_leave', 'yes', 1);							
						//}
						
					});
					jQuery( '.close-leave-popup span' ).on('click', function(){
						jQuery( '#leave-popup' ).removeClass('active');
					})

					jQuery( "#popup-form-submit" ).unbind( "click" ).bind( "click", function( e ) {
						e.preventDefault();
						var make = ( jQuery( "#make" ).val() != "~" ) ? "category=" + jQuery( "#make" ).val() : "";
						var model = ( jQuery( "#model" ).val() != "~" ) ? "model=" + jQuery( "#model option:selected" ).attr( "alt-value" ) : "";
						var year = ( jQuery( "#year" ).val() != "~" ) ? "year[]=" + jQuery( "#year" ).val() : "";
						var engine_type = ( jQuery( "#engine-type" ).val() != "~" ) ? "engine_type[]=" + jQuery( "#engine-type" ).val() : "";
						if ( make != "Select..." ) {
							__query = [ make, model, year, engine_type ];
							__query = __query.filter( function( n ) { return n.indexOf( "null" ) == -1 } );
							console.log(__query);
							window.location.href = "https://www.solopcms.com/catalogsearch/advanced/result/?" +  __query.join( "&" );
						}
						else {
							alert( "Please choose at least the make of the car." );
						}
					});
					getCategoryAjax( 111, "make" );
				});

				function getCategoryAjax( id, location ) {
					jQuery( "#" + location ).addClass( "ajax" );
					switch ( location ) {
						case "make":
							jQuery.get( "https://www.solopcms.com/ajax-call.php?action=get&category=" + id, function( data ) {
								jQuery( "#" + location ).html( data ).removeClass( "ajax" );
							});
							jQuery( ".l-1" ).html( '<option value="" disabled="disabled" selected="selected">Select...</option>' );
						break;
						case "model":
							jQuery.get( "https://www.solopcms.com/ajax-call.php?action=get&category=" + id, function( data ) {
								jQuery( "#" + location ).html( data ).removeClass( "ajax" );
							});
							jQuery( ".l-2" ).html( '<option value="" disabled="disabled" selected="selected">Select...</option>' );
						break;
						case "year":
							jQuery.get( "https://www.solopcms.com/ajax-call.php?action=get&attribute=" + id + "&model=" + jQuery( "#model" ).val(), function( data ) {
								jQuery( "#" + location ).html( data ).removeClass( "ajax" );
							});
							jQuery( ".l-3" ).html( '<option value="" disabled="disabled" selected="selected">Select...</option>' );
						break;
						case "engine-type":
							jQuery.get( "https://www.solopcms.com/ajax-call.php?action=get&attribute=" + id + "&model=" + jQuery( "#model" ).val() + "&year=" + jQuery( "#year" ).val(), function( data ) {
								jQuery( "#" + location ).html( data ).removeClass( "ajax" );
							});
						break;
					}
				}
			</script>			
			<div class="new-main-container">
				<div class="new-main">