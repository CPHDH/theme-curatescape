<?php 
$bodyclass = 'page simple-page show';
if (simple_pages_is_home_page(get_current_simple_page())) {
    $bodyclass .= ' simple-page-home';
} ?>
<?php head(array('title' => html_escape(simple_page('title')), 'bodyclass' => $bodyclass, 'bodyid' => html_escape(simple_page('slug')))); ?>

<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo html_escape(simple_page('title')); ?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
		<section id="text">
		    <?php echo simple_page('text'); ?>
		</section>    
	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">
			<h3>Pages</h3>
			<nav>
			<?php echo mh_sidebar_nav();?>
			</nav>
			
			<div id="share-this">
			<?php echo mh_share_this();?>
			</div>
		</aside>	
	</div>	

</article>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>
<?php echo foot(); ?>