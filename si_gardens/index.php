<?php
if ((get_theme_option('stealth_mode')==1)&&(is_allowed('Items', 'edit')!==true)){
include_once('stealth-index.php');
}
else{
//if not stealth mode, do everything else
?>
<?php echo head(array('maptype'=>'focusarea','bodyid'=>'home','bodyclass'=>'home')); ?>

	
<div id="content">
<article id="homepage">

	<div id="desktop-block">
			<section id="custom-block">
				<?php 
				mh_custom_content();
				echo random_item_link("View A Random ".mh_item_label('singular'),'big-button');
				$text='View all <span>'.total_records('Item').' '.mh_item_label('plural').'</span>';
				echo '<p class="view-more-link">'.link_to_items_browse($text).'</p>';		
				?>					
			</section>
				
			<section id="featured-story"> 
				<?php echo mh_display_random_featured_item(true); ?>
			</section>	
					
	</div>	
			<section id="home-tours">
				<?php mh_display_random_tours(11); ?>
			</section>			
	
			<section id="downloads">
				<?php mh_appstore_downloads(); ?>
			</section> 	
	
</article>
</div> <!-- end content -->

<?php echo foot(); ?>

<?php
//end stealth mode else statement
 }?>