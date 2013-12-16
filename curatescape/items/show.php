<?php echo head(array('item'=>$item, 'maptype'=>'story', 'bodyid'=>'items', 'bodyclass'=>'show item-story','title' => metadata($item,array('Dublin Core', 'Title')))); ?>
<div id="content">

<article class="story item show instapaper_body hentry" role="main">

			
	<header id="story-header">
	<hgroup class="instapaper_title entry-title">	
		<h2 class="item-title"><?php echo metadata($item, array('Dublin Core', 'Title'), array('index'=>0)); ?></h2>
		<h3 class="item-subtitle">
		<?php echo ( metadata($item, array('Dublin Core', 'Title'), array('index'=>1))!==('[Untitled]') ) ? metadata($item, array('Dublin Core', 'Title'), array('index'=>1)) : null; ?></h3>
	</hgroup>	
	<?php mh_the_author();?>
	</header>


	
	<div id="item-primary" class="show">
		<section id="text">

			<div class="item-description">
			<h3><?php echo __('Description'); ?></h3>
			<?php echo metadata($item, array('Dublin Core', 'Description'));?>
			</div>
			<?php echo link_to_item_edit();?>
		</section>
	</div><!-- end primary -->

		

		<div id="item-media">
			<section class="media">
				<figure id="item-video">
				<?php mh_video_files($item);?>
				</figure> 		
				
				<figure id="item-audio">
				<?php mh_audio_files($item);?>		
				</figure>	
				
				<figure id="item-photos">
				<?php mh_item_images($item);?>
				</figure>	
			</section>
		</div>

	
		<div id="item-metadata" class="item instapaper_ignore">
			<section class="meta">
				<div id="subjects">  	
				<?php mh_subjects(); ?>
				</div>	
				
				<div id="tags">
				<?php mh_tags();?>	
				</div>

				<div id="cite-this">
				<h3><?php echo __('Cite this Page'); ?></h3>
				<?php echo mh_item_citation(); ?>
				</div>	
					
				<div class="item-related-links">
				<?php mh_related_links();?>
				</div>
				
				<?php echo random_item_link(null,'big-button');?>
				
				<div class="comments">
				<?php mh_disquss_comments();?>
				</div>
				
				<?php echo function_exists('tour_nav') ? tour_nav(null,mh_tour_label()) : null; ?>
				
									
			</section>	
				
			
		</div>	
		

<div class="clearfix"></div>

<div id="share-this" class="instapaper_ignore">
<?php echo mh_share_this(mh_item_label());?>
</div>	
</article>
</div> <!-- end content -->

<script>
	//Toggle the media files and their metadata to reduce scrolling on mobile	
		
	var loopSelector = ['#item-video','#item-audio','#item-photos'];	
	
	jQuery.each(loopSelector,function(index,value){
		jQuery('#item-media '+value+' h3 span.toggle').html('Hide <i class="icon-chevron-down"></i>');
	
		jQuery('#item-media '+value+' h3 span.toggle').toggle( 
		function() {
			jQuery(''+value+' .item-file-container').hide('fast','linear');
			jQuery('#item-media '+value+' h3 span.toggle').html('Show <i class="icon-chevron-right"></i>');
		},
		function() {
			jQuery(''+value+' .item-file-container').show('fast','linear');
			jQuery('#item-media '+value+' h3 span.toggle').html('Hide <i class="icon-chevron-down"></i>');
		})				
	})
	
</script>
<?php echo foot(); ?>