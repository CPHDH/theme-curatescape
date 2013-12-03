<footer class="main">
	<nav id="footer-nav">
    	<ul class="navigation">
	    	<?php echo mh_global_nav(); ?>      	
	    </ul>
	    
    	<div id="search-wrap">
	    	<?php echo mh_simple_search($formProperties=array('id'=>'footer-search')); ?>
	    </div>  
	    
	    <?php echo random_item_link();?>	
	        
    </nav>	
 
	<p class="default">
		<?php echo __('Powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org">Curatescape</a>');?> 
		<br>
		<span id="copyright"><?php echo __('&copy; %1$s %2$s', date('Y'), mh_owner_link() );?></span> 
		<br>
		<span id="app-store-links"><?php mh_appstore_footer(); ?></span>
	</p>
	
	<div class="custom">
		<?php echo get_theme_option('custom_footer_html');?>
	</div>


	<?php echo mh_mapfaq();?>
	
	
	<div class="clearfix"></div>

</footer>

</div><!--end wrap-->

</body>

</html>
