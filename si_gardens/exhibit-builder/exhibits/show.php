<?php echo head(array('maptype'=>'none','title' => html_escape(metadata('exhibit_page', 'title') . ' : '. metadata('exhibit', 'title')), 'bodyclass' => 'show', 'bodyid' => 'exhibit')); ?>

<div id="content">
<article class="page show">
	
	<h1><?php echo metadata('exhibit', 'title'); ?></h1>
	
	<div id="secondary">
		<aside class="navigation">
		<!-- add left sidebar content here -->
			<h3><?php echo __('Sections'); ?></h3>
			<?php echo exhibit_builder_page_nav(); ?>
		</aside>
		
		<aside>
			<a href="<?php echo absolute_url('exhibits'); ?>">More online exhibits</a>
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

<div id="share-this" class="show">
<?php echo mh_share_this('Exhibit');?>
</div>

</article>
</div> <!-- end content -->


<?php echo foot(); ?>