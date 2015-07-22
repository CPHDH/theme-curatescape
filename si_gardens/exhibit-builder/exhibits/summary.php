<?php echo head(array('maptype'=>'none','title' => metadata('exhibit', 'title'), 'bodyclass'=>'summary show','bodyid' => 'exhibit')); ?>

<div id="content">
<article class="page show">

	<h1><?php echo metadata('exhibit', 'title'); ?></h1>
	
	<div id="secondary">
		<aside class="navigation">
		<!-- add left sidebar content here -->
			<h3><?php echo __('Sections'); ?></h3>
			<ul>
		        <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
		        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
		        <?php echo exhibit_builder_page_summary($exhibitPage); ?>
		        <?php endforeach; ?>
		    </ul>
		</aside>
		<aside>
			<a href="<?php echo absolute_url('exhibits'); ?>">More online exhibits</a>
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
		    <ul>
		        <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
		        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
		        <?php echo exhibit_builder_page_summary($exhibitPage); ?>
		        <?php endforeach; ?>
		    </ul>
		</nav>

	
	</div>	
		
<div id="share-this" class="show">
<?php echo mh_share_this('Exhibit');?>
</div>

</article>
</div> <!-- end content -->



<?php echo foot(); ?>