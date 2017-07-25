<?php 
$maptype='story';
if ($hasimg=metadata($item, 'has thumbnail') ) {
	$img_markup=item_image('fullsize',array(),0, $item);
	preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $img_markup, $result);
	$hero_img = array_pop($result);
}
	
echo head(array(
	'item'=>$item, 
	'maptype'=>$maptype, 
	'bodyid'=>'items', 
	'bodyclass'=>'show item-story',
	'title' => metadata($item,array('Dublin Core', 'Title')),
	)); ?>

<article class="story item show" role="main">
			
	<header id="story-header">
		<?php if($hasimg){
			echo '<div class="item-hero hero" style="background-image: url('.$hero_img.')">';
			echo '<div class="item-hero-text">'.mh_the_title().mh_the_subtitle().mh_the_byline($item,true).'</div>';
			echo '</div>';	
			echo mh_the_lede();
		}else{
			echo mh_the_title();
			echo mh_the_subtitle();
			echo mh_the_lede();
			echo mh_the_byline($item,true);
		}?>
		<?php //echo item_is_private($item);?>
	</header>
	
	<section class="text">
		<h2 hidden class="hidden">Text</h2>
		<?php echo mh_the_text(); ?>
	</section>
	
	<section class="media">
		<h2 hidden class="hidden">Media</h2>
		<?php mh_video_files($item);?>
		<?php mh_item_images($item);?>	
		<?php mh_audio_files($item);?>		
	</section>

	<section class="map">
		<h2>Map</h2>
		<figure>
			<?php echo mh_map_type($maptype,$item); ?>
		</figure>
		<figcaption><?php echo mh_map_caption();?></figcaption>
	</section>
	
	<aside id="factoid">  
		<h2 hidden class="hidden">Factoids</h2>	
		<?php echo mh_factoid(); ?>
	</aside>	
	
	<section class="metadata">
		<h2 hidden class="hidden">Metadata</h2>
		<?php echo mh_official_website();?>	
		<?php echo mh_item_citation(); ?>
		<?php echo function_exists('tours_for_item') ? tours_for_item($item->id, __('Related %s', mh_tour_label('plural'))) : null?>
		<?php echo mh_subjects(); ?>
		<?php echo mh_tags();?>			
		<?php echo mh_related_links();?>
		<?php echo mh_post_date(); ?>				
		<?php echo mh_display_comments();?>
	</section>	

	<aside id="share-this">
		<?php echo mh_share_this(mh_item_label());?>
	</aside>	
	
	<?php echo function_exists('tour_nav') ? tour_nav(null,mh_tour_label()) : null; ?>

</article>
<?php echo foot(); ?>