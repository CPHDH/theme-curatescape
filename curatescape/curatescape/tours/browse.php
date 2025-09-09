<?php
$title=tourLabelString('plural');
if(isset($_GET['tags'])){
	$queryheader = __('%1$s tagged "%2$s": %3$s', tourLabelString('plural'), htmlspecialchars($_GET['tags']), count($tours)); 
	$title = __('%1$s tagged "%2$s"', tourLabelString('plural'), htmlspecialchars($_GET['tags']));
}elseif(isset($_GET['featured'])){
	$queryheader = __('Featured %1$s: %2$s', tourLabelString('plural'), count($tours));
	$title = __('Featured').' '.$title;
}else{
	$queryheader = __('All %1$s: %2$s', tourLabelString('plural'), count($tours));
}
echo head( array(
	'maptype'=>'none', 
	'title' => $title, 
	'bodyid'=>'tours',
	'bodyclass' => 'browse' )
	);
?>
<div id="content">
	<article class="browse tour">
	<h2 class="query-header"><?php echo $queryheader;?></h2>
		<div id="primary" class="browse">
			<section id="results">
			<h2 hidden class="hidden"><?php echo tourLabelString('plural');?></h2>
			<nav class="tours-nav navigation secondary-nav">
				<?php echo publicNavTours(); ?>
			</nav>
			<div class="pagination top">
				<?php echo pagination_links(); ?>
			</div>
			<div id="browse-tours-container">
			<?php 
			$html = null;
			if( count($tours) ){
				$i=1;
				$tourimg=0;
				foreach( $tours as $tour ):?>
					<article class="tour item-result fetch-tour-image">
						<div class="tour-flex-container">
							<?php if ($tourImage = $tour->getFileCustom()): ?>
								<?php echo linkToTour($tour, 'show', $tourImage, array('class'=>'tour-image')); ?>
							<?php endif; ?>
							<div class="details">
								<h3>
									<?php echo linkToTour($tour); ?>
								</h3>
								<?php
								if(metadata($tour, 'credits')){
									$byline= __('Curated by %s',metadata($tour, 'credits'));
								}else{
									$byline= __('Curated by The %s Team',option('site_title'));
								}
								echo '<span class="total">'.__('%s Locations',mh_tour_total_items($tour)).'</span> ~ <span>'.$byline.'</span>';
								?>
								<?php if ($tourDescription = metadata($tour, 'description', array('no_escape'=>true))): ?>
									<div class="description">
										<?php echo snippet($tourDescription, 0, 500); ?>
									</div>
								<?php endif; ?>
								<?php if ($tourTags = tag_string($tour, 'tours')): ?>
									<div class="tags">
										<?php echo '<strong>'.__('Tags').': </strong>'.$tourTags; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</article>
				<?php endforeach;
			}else{
				$html .= '<p>'.__('No tours are available. Publish some now.').'</p>';
			}
			echo $html;	
			?>
			</div>
			</section>
		</div>
		<div class="pagination bottom">
			<?php echo pagination_links(); ?>
		</div>
	</article>
</div> <!-- end content -->
<?php echo foot();?>