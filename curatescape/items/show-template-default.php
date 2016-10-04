<?php 
$dc = get_theme_option('dropcap')==1 ? 'dropcap' : null;
echo head(array('item'=>$item, 'maptype'=>'story', 'bodyid'=>'items', 'bodyclass'=>'show item-story '.$dc,'title' => metadata($item,array('Dublin Core', 'Title')))); ?>

<?php mh_map_actions($item,null);?>

<div id="content">

<article class="story item show instapaper_body hentry" role="main">
			
	<header id="story-header">
	
	<div class="instapaper_title entry-title">	
	
		<h2 class="item-title"><?php echo metadata($item, array('Dublin Core', 'Title'), array('index'=>0)); ?></h2>
		
		<h3 class="item-subtitle">
			<?php echo mh_the_subtitle($item); ?>
		</h3>
		
	</div>	
	
	<?php echo mh_the_byline($item,true,true); echo item_is_private($item);?>
	
	</header>

		
	<div id="item-primary" class="show">
		
		<?php echo mh_the_lede($item);?>
		
		<section id="text">

			<div class="item-description">
				
				<?php echo mh_the_text($item); ?>
				
			</div>
	
		</section>
	
	</div><!-- end primary -->

		

		<div id="item-media">
			<section class="media">
				
				<?php mh_item_images($item);?>	
				
				<?php mh_audio_files($item);?>		
						
				<?php mh_video_files($item);?>
						
			</section>
		</div>

	
		<div id="item-metadata" class="item instapaper_ignore">
			<section class="meta">
				
				<aside id="factoid">  	
				<?php echo mh_factoid(); ?>
				</aside>	

				<div id="access-info">  	
				<?php echo mh_the_access_information(); ?>
				</div>	

				<div id="street-address">
				<?php echo mh_street_address();?>	
				</div>
				
				<div id="official-website">
				<?php echo mh_official_website();?>	
				</div>

				<div id="cite-this">
				<?php echo mh_item_citation(); ?>
				</div>	
				
				<?php if(function_exists('tours_for_item')){
					 $label=mh_tour_label_option('plural');
					 echo tours_for_item($item->id, __('Related %s', $label)); 
				}?>
					
				<div id="subjects">  	
				<?php mh_subjects(); ?>
				</div>	
				
				<div id="tags">
				<?php mh_tags();?>	
				</div>
				
				<?php echo function_exists('tour_nav') ? tour_nav(null,mh_tour_label()) : null; ?>		

				<div class="item-related-links">
				<?php mh_related_links();?>
				</div>
				
				<div class="date-stamp">
				<?php echo mh_post_date(); ?>				
				</div>
				
				<div class="comments">
				<?php mh_disquss_comments();?>
				</div>
					
									
			</section>	
				
			
		</div>	
		

<div class="clearfix"></div>

<div id="share-this" class="instapaper_ignore">
	<?php echo mh_share_this(mh_item_label());?>
</div>	

</article>

</div> <!-- end content -->

<script>
	
	if(jQuery('.tour-nav').length > 0){
		jQuery(window).scroll(function() {
			if( (jQuery('.meta').isOnScreen() || jQuery('footer.main').isOnScreen()) !== false) {
				jQuery('.tour-nav').addClass('look-at-me');
			}else{
				jQuery('.tour-nav').removeClass('look-at-me');		
			}
		}).scroll();
	}
	
</script>

<?php echo foot(); ?>