<?php echo head(array('maptype'=>'none','title' => html_escape(metadata('exhibit_page', 'title') . ' : '. metadata('exhibit', 'title')), 'bodyclass' => 'exhibit show', 'bodyid' => 'exhibit')); ?>

<div id="content">
<article class="page show">
	
	<h1><?php echo metadata('exhibit', 'title'); ?></h1>
	
	<!-- hide/show nav on mobile -->
	<script type="application/javascript">
		jQuery(document).ready(function() {
			jQuery( ".sidebar.navigation h3" ).click(function(event) {
				event.preventDefault();
				jQuery( ".sidebar.navigation .exhibit-page-nav" ).toggleClass( "show" );
				jQuery( ".sidebar.navigation h3" ).toggleClass( "hide" );
			});
		});	
	</script>
	
	<div id="secondary">
		<aside class="sidebar navigation">
			<!-- add left sidebar content here -->
			<input type="checkbox" value="selected" id="nav-header" class="nav-header-input">
			<label for="nav-header" class="nav-header-label"><?php echo __('Sections'); ?></label>
			<?php echo exhibit_builder_page_nav(); ?>
		</aside>
		
		<?php
		// loop through pages and find one with slug sidebar
		set_exhibit_pages_for_loop_by_exhibit();
		foreach (loop('exhibit_page') as $exhibitPage):
			$slug_name = metadata($exhibitPage, 'slug');
			if ($slug_name == "resources") { ?>
				<aside class="sidebar resources">
				<?php exhibit_builder_render_exhibit_page(); ?>
				</aside>
			<?php }
		endforeach;
		// reset current page
		set_current_record('exhibit_page', $exhibit_page);
		?>
		
		<aside class="sidebar more">
			<a href="<?php echo absolute_url('exhibits'); ?>">More Exhibits &#8594;</a>
		</aside>
		
		<aside class="sidebar" id="share-this">
			<?php echo mh_share_this('Exhibit');?>
		</aside>
		
	</div>

	<div id="primary" class="show" role="main">
		
		<h2><?php echo metadata('exhibit_page', 'title'); ?></h2>

		<?php exhibit_builder_render_exhibit_page(); ?>
		
		<div id="exhibit-page-navigation">
		    <?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
		    <div id="exhibit-nav-prev">
		    <?php echo $prevLink; ?>
		    </div>
		    <?php endif; ?>
		    <?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
		    <div id="exhibit-nav-next">
		    <?php echo $nextLink; ?>
		    </div>
		    <?php endif; ?>
		</div>
		
		
	</div>

</article>
</div> <!-- end content -->


<?php echo foot(); ?>