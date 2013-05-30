<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en"  class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en"  class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en"  class="ie ie8 lte9 lte8 no-js"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en"  class="ie ie9 lte9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="notie no-js"> <!--<![endif]-->
<head>

<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" href="<?php echo img('favicon.ico');?>"/> <!-- ICO for old browsers -->
<?php echo mh_auto_discovery_link_tags(); ?>
    
<title><?php echo settings('site_title'); echo $title ? ' | ' . $title : ''; ?></title>
<meta name="description" content="<?php echo mh_seo_pagedesc($item,$tour); ?>" />
<meta name="keywords" content="<?php echo get_theme_option('meta_key') ;?>" /> 

<!-- FB Open Graph stuff -->
<meta property="og:title" content="<?php echo mh_seo_pagetitle($title); ?>"/>
<meta property="og:image" content="<?php echo mh_seo_pageimg($item);?>"/>
<meta property="og:site_name" content="<?php echo settings('site_title');?>"/>
<meta property="og:description" content="<?php echo mh_seo_pagedesc($item,$tour); ?>"/>

<!-- Twitter Card stuff-->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?php echo mh_seo_pagetitle($title); ?>">
<meta name="twitter:description" content="<?php echo mh_seo_pagedesc($item,$tour); ?>">
<meta name="twitter:image:src" content="<?php echo mh_seo_pageimg($item);?>">
<?php echo ($twitter=get_theme_option('twitter_username')) ?  '<meta name="twitter:site" content="'.$twitter.'"> ' : '';?> 
<?php echo ($ios=get_theme_option('ios_app_id')) ?  '<meta name="twitter:app:id:iphone" content="'.$ios.'"> ' : '';?> 
<?php echo ($play=get_theme_option('android_app_id')) ?  '<meta name="twitter:app:id:googleplay" content="'.$play.'"> ' : '';?> 

<!-- Apple Stuff -->
<link rel="apple-touch-icon-precomposed" href="<?php echo mh_apple_icon_logo_url();?>"/>
<?php echo mh_ios_smart_banner(); ?>

<!-- Windows stuff -->
<meta name="msapplication-TileColor" content="#ffffff"/>
<meta name="msapplication-TileImage" content="<?php echo mh_apple_icon_logo_url();?>"/>


<!-- Stylesheets -->
<?php 
// also returns conditional styles from queue above
queue_css('screen');
display_css();
?>

<!-- Custom CSS via theme config -->
<?php echo mh_custom_css(); ?>

<!-- JavaScripts -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<?php
/* AudioJS has directory-structure dependencies 
** so it's not combined with other libraries 
*/
queue_js('audiojs/audiojs/audio.min'); 	
queue_js('check-width');
queue_js('libraries.min');
display_js();
?>

<!--[if lte IE 9]>
<?php echo js('ie-polyfills.min');?>
<![endif]-->



<!-- TypeKit -->
<?php echo mh_typekit();?>

<!-- Google Analytics -->
<?php echo mh_google_analytics();?>

<!-- Plugin Stuff -->
<?php echo plugin_header(); ?>




</head>
<body<?php echo $bodyid ? ' id="'.$bodyid.'"' : ''; ?><?php echo $bodyclass ? ' class="'.$bodyclass.'"' : ''; ?>> 

<div id="no-js-message">
	<span>Please enable JavaScript in your browser settings.</span>
</div>

<div id="wrap">

	<header class="main">	
		<?php echo mh_global_header();?>
		<script>
		    jQuery("#mobile-menu-cluster").removeClass("active");
		    jQuery("#mobile-menu-button a").click(function () {
		    	jQuery("#mobile-menu-cluster").toggleClass("active");
		    });
		</script>
	</header>

	
	
	<figure id="hero">
		<?php echo mh_which_content($maptype); ?>		
	</figure>