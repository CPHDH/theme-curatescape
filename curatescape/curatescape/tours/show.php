<?php
$maptype='tour';	
$tourTitle = strip_formatting( metadata($tour, 'title' ) );
$label = tourLabelString('singular');
$tourItems = $tour->Items;
if( $tourTitle != '' && $tourTitle != '[Untitled]' ) {
} else {
   $tourTitle = '';
}
echo head( array( 'title' => ''.$label.' | '.$tourTitle, 'bodyid'=>'tours',
   'bodyclass' => 'show', 'tour'=>$tour ) );
?>
<div id="content">
	<article class="tour-show">
	<h1 id="tourtitle"><?php echo metadata('tour', 'title'); ?></h1>
	<div id="tour-content-container">
			<div class="tour-description">
				<?php echo normalizeTextBlocks(metadata('tour', 'Description'));?>
			</div>
			<div class="tour-map">
				<?php if(count($tourItems)):?>
					<?php 
					if(option('curatescape_map_mirror_geolocation')){
						echo get_view()->CuratescapeMap()->GeolocationShortcode(null, $tour, null, "tour-items-map");
					}
					else{
						echo get_view()->CuratescapeMap()->Multi(null, false, "multi", $tour->id, WEB_ROOT.'/tours/show/'.$tour->id.'?output=mobile-json');
					}
					?>
				<?php endif;?>
			</div>
			<div class="tour-items">
				<?php if(count($tourItems)):?>
					<?php if($tourItemsDiplay = $tour->tourItemsOutput()):?>
						<h2 class="visuallyhidden"><?php echo storyLabelString(true);?> 
							<span class="tour-item-count" aria-label="<?php echo __('(%s total)', count($tourItems));?>">
								<?php echo count($tourItems);?>
							</span>
						</h2>
						<div class="tour-items-browse">
							<?php echo $tourItemsDiplay;?>
						</div>
					<?php endif;?>
				<?php else:?>
					<p class="tour-no-items">
						<?php echo __('This %1s does not have any %2s.', strtolower(tourLabelString()), strtolower(storyLabelString(true)));?>
					</p>
				<?php endif;?>
			</div>
	
			<?php if($colophon = $tour->tourColophon()):?>
				<div class="tour-colophon" role="doc-colophon" aria-label="<?php echo __('%s Information', tourLabelString());?>">
					<?php echo $colophon;?>
				</div>
			<?php endif;?>
	</div>
	</article>
</div> <!-- end content -->
<?php echo foot(); ?>