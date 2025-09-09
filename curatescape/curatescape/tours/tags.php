<?php 
$pageTitle = toursBrowsePageTitle('0', true);
echo head(array(
	'maptype'=>'none', 
	'title'=>$pageTitle,
	'bodyid'=>'items',
	'bodyclass'=>'browse tags'
)); ?>
<div id="content">
<article class="browse tags">
	<h2 class="query-header"><?php echo $pageTitle;?></h2>
	<div id="primary" class="browse">
	<section id="tags">
		<h2 hidden class="hidden"><?php echo __('Tags');?></h2>
		<nav class="secondary-nav" id="tag-browse"> 
			<?php echo publicNavTours(); ?>
		</nav>
		
		<?php echo tag_cloud($tags,url('tours/browse')); ?>
	</section> 
	</div><!-- end primary -->
</article>	
</div> <!-- end content -->
<?php echo foot(); ?>