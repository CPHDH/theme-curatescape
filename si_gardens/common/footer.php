<footer class="main">
	<nav id="footer-nav">
    	<ul class="navigation">
	    	<?php echo mh_global_nav(); ?>      	
	    </ul>
	    
    	<div id="search-wrap">
	    	<?php echo mh_simple_search($buttonText, $formProperties=array('id'=>'footer-search'), $uri); ?>
	    </div>  
	    
	    <?php echo random_item_link();?>	
	    
        <div class="social-media">
    		<?php echo social_media(); ?>
    	</div>
    
    	<ul class="navigation navigation-legal">
	    	<?php echo mh_legal_nav(); ?>      	
		</ul>
        
    </nav>
 
	<p class="default">
		Powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org">Curatescape</a>
		<br>
		<span id="copyright">Copyright &copy; <?php echo date('Y').' '.mh_owner_link();?></span> 
		<br>
		<span id="app-store-links"><?php mh_appstore_footer(); ?></span>
	</p>
	
	<div class="custom">
		<?php echo get_theme_option('custom footer html');?>
	</div>


	<?php echo mh_mapfaq();?>
	
	
	<div class="clearfix"></div>

</footer>

</div><!--end wrap-->

</body>

</html>