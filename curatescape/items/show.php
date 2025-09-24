<?php 
$maptype='story';
if ($hasimg=metadata($item, 'has thumbnail') ) {
	$img_markup=item_image('fullsize',array(),0, $item);
	preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $img_markup, $result);
	$hero_img = array_pop($result);
	$hero_class="has-image";
}else{
	$hero_img='';
	$hero_class="no-image";
}
echo head(array(
	'item'=>$item, 
	'bodyid'=>'items', 
	'bodyclass'=>'show item-story',
	'title' => metadata($item,array('Dublin Core', 'Title')),
	)); ?>
<article class="story item show <?php if (get_option('curatescape_template')) echo 'use-curatescape-template';?>" role="main" id="content">
	<header id="story-header">
		<?php
			echo '<div class="item-hero hero '.$hero_class.'" style="background-image: url('.$hero_img.')">';
			echo '<div class="item-hero-text">';
				echo '<h1>'.html_entity_decode(metadata($item, 'rich_title')).'</h1>';
				if(
					plugin_is_active('Curatescape') && 
					get_option('curatescape_template')
				){
					echo mh_the_byline($item,true);
				}
			echo '</div>';
			echo '</div>';	
			if(
				plugin_is_active('Curatescape') && 
				get_option('curatescape_template') &&
				$lede = itm($item, 'Lede')
			){
				echo mh_the_lede($item,true);
			}
		?>
	</header>
	<section class="text">
		<h2 hidden class="hidden"><?php echo __('Text');?></h2>
		<?php echo all_element_texts('item'); ?>
	</section>
	<?php if($item->Files):?>
	<section class="media">
		<h2 hidden class="hidden"><?php echo __('Media');?></h2>
		<?php echo files_for_item(array('imageSize' => 'fullsize')); ?>
	</section>
	<?php endif;?>

	<section class="metadata">
		<h2 hidden class="hidden"><?php echo __('Additional Metadata');?></h2>
		<?php if(plugin_is_active('Curatescape') && get_option('curatescape_template')):?>
			<?php echo mh_official_website();?>
			<?php echo mh_related_links();?>
			<?php echo mh_subjects(); ?>
		<?php endif;?>
		<?php echo mh_tags();?>
		<?php echo function_exists('tours_for_item') ? tours_for_item($item->id, __('Related %s', mh_tour_label('plural'))) : null?>
		<?php echo mh_item_citation(); ?>
	</section>
	<section class="plugin">
		<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item'=>$item)); ?>
	</section>
	<section class="comments">
		<?php echo mh_display_comments();?>
	</section>
</article>

<?php echo foot(); ?>