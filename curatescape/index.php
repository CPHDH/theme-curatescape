<?php 
$stealthMode=(get_theme_option('stealth_mode')==1)&&(is_allowed('Items', 'edit')!==true);
$classname='home'.($stealthMode ? ' stealth' : null);
if ($stealthMode) queue_css_file('stealth');
echo head(array('maptype'=>'focusarea','bodyid'=>'home','bodyclass'=>$classname)); 
?>
<?php
if ($stealthMode){
	include_once('stealth-index.php');
}
else{
//if not stealth mode, do everything else
?>
	<div id="content" role="main">
		<article id="homepage" class="page show">
			<?php echo homepage_widget_sections();?>
			<?php fire_plugin_hook('public_home', array('view' => $this)); ?>
		</article>
	</div> <!-- end content -->
<?php
//end stealth mode else statement
}?>

<?php echo foot(); ?>