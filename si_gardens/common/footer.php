<footer class="main">
	<nav id="footer-nav">
	    	<?php echo mh_global_nav(false); ?>
	    
    	<div id="search-wrap">
	    	<?php echo mh_simple_search($formProperties=array('id'=>'footer-search')); ?>
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
		<a href="http://s.si.edu/communityofgardensapp" target="_blank"><img src="<?php echo img('Download_on_the_App_Store_Badge_US-UK_135x40.svg'); ?>" alt="Download on the app store"></a>
	</p>
	
	<div class="custom">
		<?php echo get_theme_option('custom footer html');?>
	</div>


	<?php echo mh_mapfaq();?>
	
	
	<div class="clearfix"></div>

</footer>

</div><!--end wrap-->

<!-- begin webtrends, NOTE: add the actual webtrends code in here and reference webtrends folder on top level!!!! -->
<?php
// Add a single quote: 
$root=$_SERVER['DOCUMENT_ROOT'];
include("$root/webtrends/webtrends.php");
?>
<!-- end webtrends -->
</body>

</html>