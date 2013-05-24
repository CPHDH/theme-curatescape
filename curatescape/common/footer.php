<footer class="main">
	<nav id="footer-nav">
    	<ul class="navigation">
	    	<?php echo mh_global_nav(); ?>      	
	    </ul>
	    
    	<div id="search-wrap">
	    	<?php echo mh_simple_search($buttonText, $formProperties=array('id'=>'footer-search'), $uri); ?>
	    </div>  
	    
	    <?php echo random_item_link();?>	
	        
    </nav>	
 
	<p>
		Powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org">Curatescape</a>
		<br>
		<span id="copyright">&copy; <?php echo date('Y').' '.settings('author');?></span> 
		<br>
		<span id="app-store-links"><?php mh_appstore_footer(); ?></span>
	</p>

	<?php echo mh_mapfaq();?>

</footer>

</div><!--end wrap-->

</body>

</html>