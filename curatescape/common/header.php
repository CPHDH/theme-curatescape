<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en"  class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7 ]><html lang="en"  class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8 ]><html lang="en"  class="ie ie8 lte9 lte8 no-js"> <![endif]-->
<!--[if IE 9 ]><html lang="en"  class="ie ie9 lte9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="notie no-js"> <!--<![endif]-->
<head>
<meta charset="UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1">

<link rel="shortcut icon" href="<?php echo ($favicon=get_theme_option('favicon')) ? WEB_ROOT.'/files/theme_uploads/'.$favicon : img('favicon.ico');?>"/>
<?php echo auto_discovery_link_tags(); ?>

<?php
isset($title) ? $title : $title=null;
isset($tour) ? $tour : $tour=null;
isset($item) ? $item : $item=null;
isset($file) ? $file : $file=null;
?>
    
<title><?php echo mh_seo_pagetitle($title,$item); ?></title>
<meta name="description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>" />

<!-- FB Open Graph stuff -->
<meta property="og:title" content="<?php echo mh_seo_pagetitle($title,$item); ?>"/>
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

<!-- Installable //// TODO: update icon sizes="192px x 192px" and "128px x 128px" -->
<!--meta name="mobile-web-app-capable" content="yes"-->
<!--link rel="manifest" href="<?php echo WEB_ROOT; ?>/themes/curatescape/manifest.json.php"-->
<link rel="icon" href="<?php echo mh_apple_icon_logo_url(); ?>"/> 

<!-- Stylesheet -->
<?php echo mh_theme_css();?>	

<!-- Fonts -->
<?php echo mh_web_font_loader();?>

<!-- Custom CSS via theme config -->
<?php 
echo mh_custom_css();
if ($uploaded_stylesheet=get_theme_option('custom stylesheet')){
	echo '<link rel="stylesheet" type="text/css" media="screen" href="'.WEB_ROOT.'/files/theme_uploads/'.$uploaded_stylesheet.'" />';
}
?>

<!-- jQuery -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!-- Leaflet -->
<?php echo js_tag('leaflet','javascripts/leaflet');?>
<link rel="stylesheet" href="<?php echo css_src('leaflet','javascripts/leaflet');?>" />
<?php if(get_theme_option('clustering')):?>
	<!-- Clustering -->
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
<?php endif;
queue_js_file('libraries.min'); // <-- Modernizr, MakiMarker, Swipe.js, iSOnScreen, LoadJS, LoadCSS
queue_js_file('check-width');
echo head_js(false); // <-- No to Omeka default scripts
// Fancybox, VideoJS (CDN) and AudioJS are loaded asyncronously as needed
?>
<!--[if lte IE 9]>
<?php echo js_tag('ie-polyfills.min');?>
<![endif]-->


<!-- Google Analytics -->
<?php echo mh_google_analytics();?>

<!-- Plugin Stuff -->
<?php echo fire_plugin_hook('public_head', array('view'=>$this)); ?>

</head>
<body<?php echo isset($bodyid) ? ' id="'.$bodyid.'"' : ''; ?><?php echo isset($bodyclass) ? ' class="'.$bodyclass.'"' : ''; ?>> 

<div id="no-js-message">
	<span><?php echo __('Please enable JavaScript in your browser settings.');?></span>
</div>


<header class="main active" role="banner">	
	<?php echo mh_global_header();?>
	<script>
	    jQuery(".main .menu").removeClass("active");
	    jQuery("#mobile-menu-button a").click(function () {
	    	jQuery(".main .menu").toggleClass("active");
	    });
	</script>
</header>


<div id="wrap">

	<figure id="hero">
		<?php echo mh_which_content($maptype,$item,$tour); ?>	
	</figure>
