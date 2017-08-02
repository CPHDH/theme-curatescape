<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en"  class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7 ]><html lang="en"  class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8 ]><html lang="en"  class="ie ie8 lte9 lte8 no-js"> <![endif]-->
<!--[if IE 9 ]><html lang="en"  class="ie ie9 lte9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="notie no-js"> <!--<![endif]-->
<head>
<meta charset="UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=5">

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
<link rel='mask-icon' href='<?php echo img('favicon.svg')?>' color='#1EAEDB'> <!-- Safari -->

<!-- Fonts -->
<?php echo mh_web_font_loader();?>

<!-- Assets -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script src="https://ft-polyfill-service.herokuapp.com/v2/polyfill.min.js"></script>
<?php 
queue_css_file('leaflet/leaflet','all', false, 'javascripts');
queue_js_file('leaflet','javascripts/leaflet');	
queue_js_file('maki.min');
if(get_theme_option('clustering')){
	queue_js_file('leaflet.markercluster','javascripts/leaflet.markercluster');
	queue_css_file('leaflet.markercluster.min', 'all', false, 'javascripts/leaflet.markercluster');
}
queue_js_file('async_loaders');
echo head_css(); 
echo head_js(false); 
?>

<script>
	// Async CSS 	
	loadCSS('<?php echo src('font-awesome/css/font-awesome.min.css','fonts');?>'); // font awesome css
	loadCSS('<?php echo src('jquery.mmenu/jquery.mmenu.all.css','javascripts');?>'); // mmenu css
	loadCSS('<?php echo src('photoswipe/dist/photoswipe.all.css','javascripts');?>'); // photoswipe css
	// Async JS 
	loadJS('<?php echo src('global.js','javascripts');?>'); // global.js
	<?php if( 0 === strpos(current_url(), '/items/show') ):?>
		loadJS('<?php echo src('items-show.js','javascripts');?>'); // items-show.js
	<?php endif;?>	
</script>

<!-- Custom CSS via theme config -->
<?php 
echo mh_configured_css();
if ($uploaded_stylesheet=get_theme_option('custom stylesheet')){
	echo '<link rel="stylesheet" type="text/css" media="screen" href="'.WEB_ROOT.'/files/theme_uploads/'.$uploaded_stylesheet.'" />';
}?>
<?php echo mh_theme_css();?>


<!-- Plugin Stuff -->
 <?php fire_plugin_hook('public_head',array('view'=>$this)); ?>

<!-- Theme Display Settings -->
<?php
$bgImg=get_theme_option('bg_img');
$themeClass= ($bgImg) ? ' fancy' : ' minimalist';
$bodyid = isset($bodyid) ? $bodyid : 'default';
$bodyclass = isset($bodyclass) ? $bodyclass.$themeClass : 'default'.$themeClass;
$bodyStyle= ($bgImg) ? 'background-image: url('.mh_bg_url().')' : null;
?>

</head>
<body id="<?php echo $bodyid;?>" class="<?php echo $bodyclass;?>" style="<?php echo $bodyStyle;?>"> 

<noscript>
	<div id="no-js-message">
		<span><?php echo __('For full functionality please enable JavaScript in your browser settings.');?> <a target="_blank" href="https://goo.gl/koeeaJ"><?php echo __('Need Help?');?></a></span>
	</div>
</noscript>

<div id="page-content">
	<?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
	<header class="<?php if($bgImg) echo 'container';?> header-nav">
		<?php echo mh_global_header();?>
	</header>
	
	
	<div id="wrap" class="container">
		<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
