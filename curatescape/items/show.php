<?php 
$maptype='story';
$hero_img = mh_hero_image_url($item);
echo head(array(
	'item'=>$item, 
	'bodyid'=>'items', 
	'bodyclass'=>'show item-story',
	'title' => metadata($item,array('Dublin Core', 'Title')),
	)); ?>
<article class="story item show <?php if (get_option('curatescape_template')) echo 'use-curatescape-template';?>" role="main" id="content">
	<header id="story-header">
		<?php
			echo '<div class="item-hero hero '.($hero_img ? 'has-image' : 'no-image').'" style="background-image: url('.$hero_img.')">';
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
			<?php echo mh_tour(); ?>
			<?php echo mh_subjects(); ?>
		<?php endif;?>
		<?php echo mh_tags();?>
		<?php echo mh_collection(); ?>
		<?php echo mh_item_citation(); ?>
	</section>
	<section class="plugin">
		<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item'=>$item)); ?>
	</section>
	<?php echo mh_display_comments();?>
	<nav class="visuallyhidden">
		<ul class="site-page-pagination">
			<li id="previous-item" class="site-page-pagination-button previous"><?php echo link_to_previous_item_show(); ?></li>
			<li id="next-item" class="site-page-pagination-button next"><?php echo link_to_next_item_show(); ?></li>
		</ul>
	</nav>
</article>
<?php echo foot(); ?>