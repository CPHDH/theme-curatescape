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

<?php echo auto_discovery_link_tags(); ?>

<?php
$title = (isset($title)) ? $title : null;
$item = (isset($item)) ? $item : null;
$tour = (isset($tour)) ? $tour : null;
$file = (isset($file)) ? $file : null;
?>
    
<title><?php echo mh_seo_pagetitle($title,$item); ?></title>
<meta name="description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>" />

<!-- FB Open Graph stuff -->
<meta property="og:title" content="<?php echo mh_seo_pagetitle($title,$item); ?>"/>
<meta property="og:image" content="<?php echo mh_seo_pageimg($item,$file);?>"/>
<meta property="og:site_name" content="<?php echo option('site_title');?>"/>
<meta property="og:description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>"/>

<!-- Twitter Card stuff-->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo mh_seo_pagetitle($title,$item); ?>">
<meta name="twitter:description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>">
<meta name="twitter:image" content="<?php echo mh_seo_pageimg($item,$file);?>">
<?php echo ($twitter=get_theme_option('twitter_username')) ?  '<meta name="twitter:site" content="@'.$twitter.'"> ' : '';?> 

<!-- Apple Stuff -->
<link rel="apple-touch-icon-precomposed" href="<?php echo mh_apple_icon_logo_url();?>"/>
<?php echo mh_ios_smart_banner(); ?>

<!-- Windows stuff -->
<meta name="msapplication-TileColor" content="#ffffff"/>
<meta name="msapplication-TileImage" content="<?php echo mh_apple_icon_logo_url();?>"/>

<!-- Icon -->
<link rel="shortcut icon" href="<?php echo ($favicon=get_theme_option('favicon')) ? WEB_ROOT.'/files/theme_uploads/'.$favicon : img('favicon.ico');?>"/>
<link rel="icon" href="<?php echo mh_apple_icon_logo_url(); ?>"/> 

<!-- Fonts -->
<?php echo mh_web_font_loader();?>
<link rel="stylesheet" href="<?php echo css_src('font-awesome.min','fonts/font-awesome/css');?>">

<!-- Stylesheet -->
<link type="text/css" href="<?php echo css_src('jquery.mmenu/jquery.mmenu.all','javascripts');?>" rel="stylesheet" />
<link type="text/css" href="<?php echo css_src('leaflet/leaflet','javascripts');?>" rel="stylesheet" /> 
<link type="text/css" href="<?php echo css_src('photoswipe/dist/photoswipe','javascripts');?>" rel="stylesheet" /> 
<link type="text/css" href="<?php echo css_src('photoswipe/dist/default-skin/default-skin','javascripts');?>" rel="stylesheet" />  
<link href="//vjs.zencdn.net/5.19.2/video-js.css" rel="stylesheet">
<?php echo mh_theme_css();?>	

<!-- Custom CSS via theme config -->
<?php 
echo mh_configured_css();
if ($uploaded_stylesheet=get_theme_option('custom stylesheet')){
	echo '<link rel="stylesheet" type="text/css" media="screen" href="'.WEB_ROOT.'/files/theme_uploads/'.$uploaded_stylesheet.'" />';
}?>

<!-- jQuery -->
<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>

<!-- Leaflet -->
<?php echo js_tag('leaflet','javascripts/leaflet');?>
<link rel="stylesheet" href="<?php echo css_src('leaflet','javascripts/leaflet');?>" />

<?php if(get_theme_option('clustering')):?>
<!-- Clustering -->
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
<?php endif; ?>

<?php 
queue_js_file('jquery.mmenu/jquery.mmenu.all');
queue_js_file('maki.min');
queue_js_file('photoswipe/dist/photoswipe.min');
queue_js_file('photoswipe/dist/photoswipe-ui-default.min');
queue_js_file('actions');
echo head_js(false); // <-- No to Omeka default scripts
// Additional scripts are loaded asyncronously as needed
?>

<!--[if lte IE 9]>
<?php echo js_tag('ie-polyfills.min');?>
<![endif]-->

<!-- Plugin Stuff -->
<?php echo fire_plugin_hook('public_head', array('view'=>$this)); ?>

</head>
<body<?php echo isset($bodyid) ? ' id="'.$bodyid.'"' : ''; ?><?php echo isset($bodyclass) ? ' class="'.$bodyclass.'"' : ''; ?>> 

<noscript>
	<div id="no-js-message">
		<span><?php echo __('This web page requires JavaScript to be enabled in your browser settings.');?> <a target="_blank" href="https://goo.gl/koeeaJ"><?php echo __('Need Help?');?></a></span>
	</div>
</noscript>

<div id="page-content">
	
	<header class="container header-nav">
		<?php echo mh_global_header();?>
	</header>
	
	
	<div id="wrap" class="container">
