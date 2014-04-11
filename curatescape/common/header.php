<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en"  class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en"  class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en"  class="ie ie8 lte9 lte8 no-js"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en"  class="ie ie9 lte9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="notie no-js"> <!--<![endif]-->
<head>
<meta charset="UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1">

<link rel="shortcut icon" href="<?php echo ($favicon=get_theme_option('favicon')) ? WEB_ROOT.'/files/theme_uploads/'.$favicon : img('favicon.ico');?>"/>
<?php echo mh_auto_discovery_link_tags(); ?>

<?php
isset($title) ? $title : $title=null;
isset($tour) ? $tour : $tour=null;
isset($item) ? $item : $item=null;
isset($file) ? $file : $file=null;
?>
    
<title><?php echo ($title) ?  $title.' | '.option('site_title') : option('site_title'); ?></title>
<meta name="description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>" />
<meta name="keywords" content="<?php echo get_theme_option('meta_key') ;?>" /> 

<!-- FB Open Graph stuff -->
<meta property="og:title" content="<?php echo mh_seo_pagetitle($title); ?>"/>
<meta property="og:image" content="<?php echo mh_seo_pageimg($item,$file);?>"/>
<meta property="og:site_name" content="<?php echo option('site_title');?>"/>
<meta property="og:description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>"/>

<!-- Twitter Card stuff-->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?php echo mh_seo_pagetitle($title); ?>">
<meta name="twitter:description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>">
<meta name="twitter:image:src" content="<?php echo mh_seo_pageimg($item,$file);?>">
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
queue_css_file('screen');
echo head_css();
?>

<!-- Custom CSS via theme config -->
<?php 
if ($uploaded_stylesheet=get_theme_option('custom stylesheet')){
	echo '<link rel="stylesheet" type="text/css" media="screen" href="'.WEB_ROOT.'/files/theme_uploads/'.$uploaded_stylesheet.'" />';
	}
echo mh_custom_css(); ?>

<!-- JavaScripts -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<?php
queue_js_file('audiojs/audiojs/audio.min'); 	
queue_js_file('libraries.min'); // <-- combined: Modernizr, jQuery UI Maps, Swipe.js, Fancybox
queue_js_file('check-width');
echo head_js(false); // <-- No to Omeka default scripts
?>
<!--[if lte IE 9]>
<?php echo js_tag('ie-polyfills.min');?>
<![endif]-->

<!-- TypeKit -->
<?php echo mh_typekit();?>

<!-- Google Analytics -->
<?php echo mh_google_analytics();?>

<!-- Plugin Stuff -->
<?php echo fire_plugin_hook('public_header', array('view'=>$this)); ?>

</head>
<body<?php echo isset($bodyid) ? ' id="'.$bodyid.'"' : ''; ?><?php echo isset($bodyclass) ? ' class="'.$bodyclass.'"' : ''; ?>> 

<div id="no-js-message">
	<span><?php echo __('Please enable JavaScript in your browser settings.');?></span>
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
		<?php echo mh_which_content(@$maptype); ?>	
	</figure>