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

<div style="display: none">
    <div id="map-faq">
   	<div id="map-faq-inner"> 	
	   <h2>Frequently Asked Questions <span>about the map</span></h2> 
	   
	   <h3><a>Are all the locations on <?php echo settings('site_title');?> publicly accessible?</a></h3> 
	   <p>Not necessarily. It is up to you to determine if any given location is one you can physically visit.</p>

	   <h3><a>How do you choose locations for each story?</a> <span>or</span> <a>The location is wrong!</a></h3> 
	   <p>Placing historical stories on a map can be tricky. We choose locations based on what we think makes the most sense. Sometimes we get it wrong (and sometimes there is no "right" answer). Feel free to email us <?php  if($email=get_theme_option('contact_email')){ echo 'at <a href="mailto:'.$email.'">'.$email.'</a> '; } ;?>with suggestions for improvement.</p>
 
   	</div>
	    
    </div>
</div>

</footer>

</div><!--end wrap-->

</body>

</html>