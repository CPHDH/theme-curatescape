<?php echo head(array('maptype'=>'focusarea', 'title'=>'Browse by Tag','bodyid'=>'items','bodyclass'=>'browse tags')); ?>


<div id="content">
<section class="browse tags">		
<h2><?php echo __('Tags: %s', total_records('Tags'));?></h2>


	<div id="primary" class="browse">
	<section id="tags">
    
	    
	    <nav class="secondary-nav" id="tag-browse"> 
			<?php mh_item_browse_subnav(); ?>
	    </nav>
	
	    <?php echo tag_cloud($tags,url('items/browse')); ?>

	</section> 
	</div><!-- end primary -->


</section>	
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>
<?php echo foot(); ?>