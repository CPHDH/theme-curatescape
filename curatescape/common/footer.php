	
	</div><!--end wrap-->
	
	<footer class="main <?php if(get_theme_option('bg_img')) echo 'container';?>">
		<nav id="footer-nav">
		    <?php echo mh_global_nav(); ?> 
		    <?php echo mh_simple_search();?>
		    <?php echo random_item_link("<i class='fa fa-random fa-lg' aria-hidden='true'></i> View A Random ".mh_item_label('singular'),'random-button button');?>     	
	    </nav>	
	 
		<div class="default">
			<?php echo mh_footer_find_us();?>
			<div id="copyright"><?php echo mh_license();?></div> 
			<div id="powered-by"><?php echo __('Powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org">Curatescape</a>');?></div>
		</div>
		
		<div class="custom"><?php echo get_theme_option('custom_footer_html');?></div>
	
		<?php echo fire_plugin_hook('public_footer', array('view'=>$this)); ?>	
		<?php echo mh_google_analytics();?>	
		<script src="https://vjs.zencdn.net/5.19.2/video.js"></script>
		<?php echo mh_footer_scripts_init();?>
			
	</footer>
</div> <!-- end page-content -->

<div hidden class="hidden">
	<!-- Mmenu Markup -->
	<nav id="secondary-menu">
		<?php echo mh_global_nav();?>
	</nav>
</div>
	
</body>
</html>