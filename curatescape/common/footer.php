<aside id="action-buttons">
	<?php echo random_item_link("View A Random ".mh_item_label('singular'),'big-button');?>
	<?php mh_appstore_downloads(); ?>
</aside> 

<div class="clearfix"></div>
</div><!--end wrap-->
<footer class="main">
	<nav id="footer-nav">
	    
	    <?php echo mh_global_nav(); ?>      	
	    
    	<div id="search-wrap">
	    	<?php echo mh_simple_search($formProperties=array('id'=>'footer-search')); ?>
	    </div>  
	    	        
    </nav>	
 
	<p class="default">
		<span id="app-store-links"><?php mh_appstore_footer(); ?></span>
		<?php echo mh_footer_find_us();?>
		<span id="copyright"><?php echo mh_license();?></span> 
		<span id="powered-by"><?php echo __('Powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org">Curatescape</a>');?></span>
	</p>
	
	<div class="custom">
		<?php echo get_theme_option('custom_footer_html');?>
	</div>

	<?php 		
		echo mh_footer_scripts_init(); 
	?>
	
	
	<!-- Plugin Stuff -->
	<?php echo fire_plugin_hook('public_footer', array('view'=>$this)); ?>	
		
<div class="clearfix"></div>
</footer>
</body>

</html>