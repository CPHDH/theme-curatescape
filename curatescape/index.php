<?php 
$stealthMode=(get_theme_option('stealth_mode')==1)&&(is_allowed('Items', 'edit')!==true);
$classname='home'.($stealthMode ? ' stealth' : null);
if ($stealthMode) queue_css_file('stealth');
if (
	plugin_is_active('Curatescape') && 
	option('curatescape_map_mirror_geolocation')
) {
	queue_css_file('geolocation-items-map');
}
echo head(array('maptype'=>'focusarea','bodyid'=>'home','bodyclass'=>$classname)); 
?>
<?php
if ($stealthMode){
	include_once('stealth-index.php');
}
else{ ?>
	<?php if(plugin_is_active('Curatescape')){
		if(option('curatescape_map_mirror_geolocation')){
			echo get_view()->CuratescapeMap()->GeolocationShortcode(null, null, null, "home-items-map");
		}else{
			echo get_view()->CuratescapeMap()->Multi(null, true, "home", null, WEB_ROOT.'/items/browse?output=mobile-json');
		}
	} ?>
	<div id="content" role="main">
		<article id="homepage" class="homepage show">
			<?php echo homepage_widget_sections();?>
			<?php fire_plugin_hook('public_home', array('view' => $this)); ?>
		</article>
	</div> <!-- end content -->
	<?php
	echo foot();
	//end stealth mode
}?>

