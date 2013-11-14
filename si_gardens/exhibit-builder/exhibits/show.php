<?php head(array('maptype'=>'none','title' => html_escape(exhibit('title') . ' : '. exhibit_page('title')), 'bodyclass' => 'show', 'bodyid' => 'exhibit')); ?>

<div id="content">
<article class="page show">

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
		<h2 class="instapaper_title"><?php echo link_to_exhibit(); ?></h2>
	
	    	<nav class="secondary-nav" id="exhibit-sections"> 
	    	<?php echo exhibit_builder_section_nav();?>
			</nav>
			
			<nav class="tertiary-nav" id="exhibit-pages">
			<?php echo exhibit_builder_page_nav();?>
			</nav>
	
		<h2><?php echo exhibit_page('title'); ?></h2>
		
		<section id="text">
		<?php exhibit_builder_render_exhibit_page(); ?>
		</section>
		
		<div id="exhibit-page-navigation">
		   	<?php echo exhibit_builder_link_to_previous_exhibit_page(); ?>
	    	<?php echo exhibit_builder_link_to_next_exhibit_page(); ?>
		</div>
	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

			<!-- Grab some recent images for the image tile montage -->
			<?php mh_display_recent_item(10);?>
			
		</aside>	
	</div>	

<div id="share-this" class="show">
<?php echo mh_share_this("Share this Exhibit");?>
</div>

</article>
</div> <!-- end content -->


<?php echo foot(); ?>