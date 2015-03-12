<?php
if ((get_theme_option('stealth_mode')==1)&&(is_allowed('Items', 'edit')!==true)){
queue_css_file('stealth');
include_once('stealth-index.php');
}
else{
//if not stealth mode, do everything else
?>
<?php echo head(array('maptype'=>'focusarea','bodyid'=>'home','bodyclass'=>'home')); ?>

<?php mh_map_actions();?>
	
<div id="content" role="main">
<article id="homepage">
										
	<section id="about"><?php echo mh_home_about();?></section>

	<?php echo homepage_widget_sections();?>

</article>
</div> <!-- end content -->

<script>
	// add map overlay for click function if map is not already expanded
	jQuery('body:not(.expand-map) #hm-map').append('<div class="home-map-overlay"></div>');
	jQuery('#hm-map .home-map-overlay').click(function(){
		jQuery('#home').addClass('expand-map');
		jQuery('.home-map-overlay').remove();
	});
</script>

<?php echo foot(); ?>

<?php
//end stealth mode else statement
 }?>