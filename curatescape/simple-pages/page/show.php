<?php 
$bodyclass = 'page simple-page show';
echo head(array('maptype'=>'none','title' => html_escape(metadata('simple_pages_page', 'title')), 'bodyclass' => $bodyclass, 'bodyid' => html_escape(metadata('simple_pages_page', 'slug')))); ?>

<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo metadata('simple_pages_page', 'title'); ?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
		<section id="text">

		    <?php
		    $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
		    if (metadata('simple_pages_page', 'use_tiny_mce')) {
		        echo $text;
		    } else {
		        echo eval('?>' . $text);
		    }
		    ?>			    

		</section>    
	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">
			<h3><?php echo __('Pages'); ?></h3>
			<nav>
			<?php echo mh_sidebar_nav();?>
			</nav>
			
			<!-- Grab some recent images for the image tile montage -->
			<?php mh_display_recent_item(10);?>
			
		</aside>	
	</div>	

</article>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>
<?php echo foot(); ?>
