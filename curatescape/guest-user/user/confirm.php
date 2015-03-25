<?php
$head = array('title' => __('Confirmation Error'));
echo head($head);
?>
<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $head['title']; ?></h2>



	<div id="primary" class="show" role="main">
		<?php echo flash(); ?>
	</div>



</article>
</div> <!-- end content -->

<?php echo foot(); ?>