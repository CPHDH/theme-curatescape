<?php
$title = __('Map');
$useCuratescape = plugin_is_active('Curatescape');
if (!$useCuratescape) queue_css_file('geolocation-items-map');
echo head(array('title'=>$title,'bodyid'=>'error','bodyclass'=>'map browse geolocation')); ?>
<div id="content">
	<article class="map page show" role="main">
		<h2 class="page_title"><?php echo $title; ?></h2>
		<?php if (!$useCuratescape): ?>
			<div id="geolocation-browse">
				<?php echo $this->geolocationMapBrowse('map_browse', array('list' => 'map-links', 'params' => $params)); ?>
				<div id="map-links"></div>
			</div>
		<?php else: ?>
			<?php
			if(option('curatescape_map_mirror_geolocation')){
				echo get_view()->CuratescapeMap()->GeolocationShortcode(null, null, null, "home-items-map");
			}else{
				echo get_view()->CuratescapeMap()->Multi(null, true, "home", null, WEB_ROOT.'/items/browse?output=mobile-json');
			}
			?>
		<?php endif;?>
	</article>
</div> <!-- end content -->
<?php echo foot(); ?>