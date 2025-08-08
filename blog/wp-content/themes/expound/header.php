<!DOCTYPE html>
<html lang="en">
<head>
		<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-T2KBPSD');</script>
	<!-- End Google Tag Manager -->
	<?php
		$disable == true;
		$head = get_block('head', false);
		echo $head->getCssJsHtml(); // All CSS and JS files defined in your Magento head
		echo $head->getChildHtml();
		echo $head->helper('core/js')->getTranslatorScript();
		echo $head->getIncludes();
		$url = "https://" . $_SERVER[ "SERVER_NAME" ] . "/";
	?>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:400,700' rel='stylesheet' type='text/css'>
	
	<?php wp_head(); ?>
	
	<script type="text/javascript" src="<?php echo $url; ?>skin/frontend/rwd/default/js/bjqs.min.js"></script>
	<link rel="stylesheet" href="<?php echo $url; ?>skin/frontend/rwd/default/css/jquery.sidr.dark.css">
	<script type="text/javascript" src="<?php echo $url; ?>skin/frontend/rwd/default/js/jquery.sidr.min.js"></script>


</head>
<body>

	<div class="wrapper">
		<div class="page">
			<?php
				the_block('header');
			?>
			<div class="main-container col1-layout">
				<div class="main">