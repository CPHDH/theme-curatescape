<!DOCTYPE html>
<html lang="en"> 
<head>
<meta charset="UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5,viewport-fit=cover">

<?php echo auto_discovery_link_tags(); ?>

<?php
$title = (isset($title)) ? html_entity_decode(htmlspecialchars($title)) : null;
$item = (isset($item)) ? $item : null;
$tour = (isset($tour)) ? $tour : null;
$file = (isset($file)) ? $file : null;
?>

<title><?php echo mh_seo_pagetitle($title,$item); ?></title>
<meta name="description" content="<?php echo mh_seo_pagedesc($item,$tour,$file); ?>" />

<?php if(
	is_current_url('/search?') || 
	is_current_url('/items/browse') || 
	is_current_url('/items?') || 
	is_current_url('/tours/browse') || 
	is_current_url('/tours?') || 
	is_current_url('/exhibits/browse') || 
	is_current_url('/exhibits?')
):?> 
<!-- No Index: Generated/Query Content -->
<meta name="robots" content="noindex, follow">
<?php endif;?>

<!-- Apple Stuff -->
<link rel="apple-touch-icon-precomposed" href="<?php echo mh_apple_icon_logo_url();?>"/>

<!-- Icon -->
<link rel="shortcut icon" href="<?php echo ($favicon=get_theme_option('favicon')) ? WEB_ROOT.'/files/theme_uploads/'.$favicon : img('favicon.ico');?>"/>
<link rel="icon" href="<?php echo mh_apple_icon_logo_url(); ?>"/> 
<link rel='mask-icon' href='<?php echo img('favicon.svg')?>' color='#1EAEDB'> <!-- Safari -->

<?php fire_plugin_hook('public_head',array('view'=>$this));?>

<!-- Fonts -->
<?php echo mh_web_font_loader();?>

<!-- Assets -->
<script>
	/*!
	loadJS: load a JS file asynchronously. 
	[c]2014 @scottjehl, Filament Group, Inc. (Based on http://goo.gl/REQGQ by Paul Irish). 
	Licensed MIT 
	*/
	(function(w){var loadJS=function(src,cb,ordered){"use strict";var tmp;var ref=w.document.getElementsByTagName("script")[0];var script=w.document.createElement("script");if(typeof(cb)==='boolean'){tmp=ordered;ordered=cb;cb=tmp;}
	script.src=src;script.async=!ordered;ref.parentNode.insertBefore(script,ref);if(cb&&typeof(cb)==="function"){script.onload=cb;}
	return script;};if(typeof module!=="undefined"){module.exports=loadJS;}
	else{w.loadJS=loadJS;}}(typeof global!=="undefined"?global:this));
	
	/*!
	loadCSS: load a CSS file asynchronously.
	[c]2014 @scottjehl, Filament Group, Inc.
	Licensed MIT
	*/
	function loadCSS(href,before,media){"use strict";var ss=window.document.createElement("link");var ref=before||window.document.getElementsByTagName("script")[0];var sheets=window.document.styleSheets;ss.rel="stylesheet";ss.href=href;ss.media="only x";ref.parentNode.insertBefore(ss,ref);function toggleMedia(){var defined;for(var i=0;i<sheets.length;i++){if(sheets[i].href&&sheets[i].href.indexOf(href)>-1){defined=true;}}
	if(defined){ss.media=media||"all";}
	else{setTimeout(toggleMedia);}}
	toggleMedia();return ss;}
</script>
<?php 
$includejquery = true;
if(plugin_is_active('Curatescape')){
	$includejquery = curatescapejQueryConditional(current_url());
	if(!get_option('curatescape_map_mirror_geolocation')) {
		curatescapeRemoveHeadAssets( $this, array('/plugins/Geolocation') );
	}
}
echo head_css(); 
echo mh_theme_css();
echo head_js($includejquery); 
?>

<script>
	// Async CSS 
	loadJS('<?php echo src('global.js','javascripts');?>');
</script>

<!-- Custom CSS via theme config -->
<?php echo mh_configured_css();?>

<!-- Theme Display Settings -->
<?php
$bodyid = isset($bodyid) ? $bodyid : 'default';
$bodyclass = isset($bodyclass) ? $bodyclass.' curatescape' : 'default curatescape';
?>
</head
>
<body id="<?php echo $bodyid;?>" class="<?php echo $bodyclass;?>">
<div id="overlay"></div>
<?php 
$stealthMode=(get_theme_option('stealth_mode')==1)&&(is_allowed('Items', 'edit')!==true);
if($stealthMode) return;
?>

<nav aria-label="<?php echo __('Skip Navigation');?>"><a id="skip-nav" href="#content"><?php echo __('Skip to main content');?></a></nav>
<noscript>
	<div id="no-js-message">
		<span><?php echo __('For full functionality please enable JavaScript in your browser settings.');?> <a target="_blank" href="https://goo.gl/koeeaJ"><?php echo __('Need Help?');?></a></span>
	</div>
</noscript>

<div id="page-content">
	<?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
	<header class="container header-nav">
		<?php echo mh_global_header();?>
	</header>
	
	
	<div id="wrap" class="container">
		<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
