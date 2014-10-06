<footer class="main">
	<nav id="footer-nav">
	    	<?php echo mh_global_nav(); ?>
	    
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
	</p>
	
	<div class="custom">
		<?php echo get_theme_option('custom footer html');?>
	</div>


	<?php echo mh_mapfaq();?>
	
	
	<div class="clearfix"></div>

</footer>

</div><!--end wrap-->

<!-- begin webtrends-->
<?php
// Add a single quote: 
$root=$_SERVER['DOCUMENT_ROOT'];
include("$root/webtrends/webtrends.php");
?>
<!-- end webtrends -->

<!--BEGIN QUALTRICS POPUP-->
<script type="text/javascript">
var q_viewrate=100;
if (Math.random() < q_viewrate / 100){var q_popup_f = function(){var q_script = document.createElement("script");var q_popup_g = function(){new QualtricsEmbeddedPopup({
            id: "SV_1ACz0XMA3ddjgaN",
            imagePath: "https://qdistribution.qualtrics.com/WRQualtricsShared/Graphics/",
            surveyBase: "http://si.az1.qualtrics.com/WRQualtricsSurveyEngine/",
            delay:30000,
            preventDisplay:30,
            animate:true,
            width:400,
            height:300,
            surveyPopupWidth:900,
            surveyPopupHeight:600,
            startPos:"ML",
            popupText:"Please take a moment to participate in a survey.",
            linkText:"Click Here"
});};q_script.onreadystatechange= function () {if (this.readyState == "loaded") q_popup_g();};q_script.onload= q_popup_g;q_script.src="https://qdistribution.qualtrics.com/WRQualtricsShared/JavaScript/Distribution/popup.js";document.getElementsByTagName("head")[0].appendChild(q_script);};if (window.addEventListener){window.addEventListener("load",q_popup_f,false);}else if (window.attachEvent){r=window.attachEvent("onload",q_popup_f);}else {};};
</script>
<noscript><a target="_blank" href="http://si.az1.qualtrics.com/SE/?SID=SV_1ACz0XMA3ddjgaN">Click Here</a><br/></noscript>
<!--END QUALTRICS POPUP-->
</body>

</html>