<?php
$head = array('title'=> __('Stale Token'));
echo head($head);
?>
<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $head['title']; ?></h2>


	<div id="primary" class="show" role="main">
	<?php echo flash(); ?>
	<p><?php echo __("Your temporary access to the site has expired. Please check your email for the link to follow to confirm your registration."); ?></p>
	
	<p><?php echo __("You have been logged out, but can continue browsing the site."); ?></p>
	</div>


</article>
</div> <!-- end content -->
<?php echo foot(); ?>