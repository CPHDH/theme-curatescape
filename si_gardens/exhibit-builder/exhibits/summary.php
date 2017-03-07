<?php echo head(array('maptype'=>'none','title' => metadata('exhibit', 'title'), 'bodyclass'=>'summary show exhibit','bodyid' => 'exhibit')); ?>

<div id="content">
<article class="page show">

	<h1><?php echo metadata('exhibit', 'title'); ?></h1>
	
	<div class="secondary" id="secondary">
		<aside class="sidebar navigation">
		<!-- add left sidebar content here -->
			<input type="checkbox" value="selected" id="nav-header" class="nav-header-input">
			<label for="nav-header" class="nav-header-label"><?php echo __('Sections'); ?></label>
			<ul class="nav-set">
		        <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
		        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
		        <?php echo exhibit_builder_page_summary($exhibitPage); ?>
		        <?php endforeach;
				// reset current page
				set_current_record('exhibit_page', $exhibit_page);?>
		    </ul>
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
		
		<aside class="sidebar">
			<a href="<?php echo absolute_url('exhibits'); ?>">MORE EXHIBITS &#8594;</a>
		</aside>
		
		<aside class="sidebar" id="share-this">
			<?php echo mh_share_this('Exhibit');?>
		</aside>
		
		
	</div>

	<div id="primary" class="show" role="main">
			
		<?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
		<div class="exhibit-description">
		    <?php echo $exhibitDescription; ?>
		</div>
		<?php endif; ?>
		
		<?php if (($exhibitCredits = metadata('exhibit', 'credits'))): ?>
		<div class="exhibit-credits">
		    <h3><?php echo __('Author'); ?></h3>
		    <p><?php echo $exhibitCredits; ?></p>
		</div>
		<?php endif; ?>


		<nav id="exhibit-pages">
			<h3><?php echo __('Sections'); ?></h3>
		    <?php echo exhibit_builder_page_nav(); ?>
		</nav>

	
	</div>

</article>
</div> <!-- end content -->



<?php echo foot(); ?>