<?php head(array('maptype'=>'focusarea', 'title'=>'Browse by Tag','bodyid'=>'items','bodyclass'=>'browse tags')); ?>


<div id="content">
<section class="browse tags">		
<h2>Tags: <?php echo total_tags();?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="browse">
	<section id="tags">
    
	    
	    <nav class="secondary-nav" id="tag-browse"> 
		    <ul>
			<?php mh_item_browse_subnav(); ?>
		    </ul>
	    </nav>
	
	    <?php echo tag_cloud($tags,uri('items/browse')); ?>

	</section> 
	</div><!-- end primary -->

	<div id="page-col-right">
		<aside id="page-sidebar">
			<section id="recent-story" class="hidden">
				<?php mh_display_recent_item(3); /* Used for swipe.js slider script */ ?>
			</section>
		</aside>
	</div>	

</section>	
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>
<?php foot(); ?>