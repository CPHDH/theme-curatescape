<?php 
$bodyclass = 'page simple-page show';
echo head(array('maptype'=>'none','title' => html_escape(metadata('simple_pages_page', 'title')), 'bodyclass' => $bodyclass, 'bodyid' => html_escape(metadata('simple_pages_page', 'slug')))); ?>

<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo metadata('simple_pages_page', 'title'); ?></h2>


	<div id="primary" class="show" role="main">
		<section id="text">

		    <?php
		    $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
		    echo $this->shortcodes($text);
		    ?>					    

		</section>    
	</div>


</article>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>
<?php echo foot(); ?>