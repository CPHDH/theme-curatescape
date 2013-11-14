<?php 
$tag = ($_GET['tags'] ? $_GET['tags'] : null);
$term = ($_GET['term'] ? $_GET['term'] : null);
$query = ($_GET['search'] ? $_GET['search'] : null);
$advanced = ($_GET['advanced'] ? true : false);
$bodyclass='browse';
$maptype='none';

if ( ($tag) && !($query) ) {
	$title = 'Exhibits tagged "'.$tag.'"';
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}
elseif ( ($term) && !($query) ) {
	$title = 'Results for subject term "'.$term.'"';
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}
elseif ($query) {
	$title = (!($advanced) ? 'Search Results for "'.$query.'"':'Advanced Search Results');
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}	
else{
	$title = 'All Exhibits';
	$bodyclass .=' exhibits';
}	
head(array('maptype'=>'none','title'=>$title,'bodyid'=>'exhibit','bodyclass'=>$bodyclass)); 
?>


<div id="content">

<section class="browse stories items">	
	<h2><?php 
	$title .= ( ($total_records) ? ': <span class="item-number">'.$total_records.'</span>' : '');
	echo $title; 
	?></h2>
		
		
	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="browse">
	<section id="results">
			

	<?php if (count($exhibits) > 0): ?>
	
		<nav class="secondary-nav" id="item-browse"> 
			<ul>
			<?php echo nav(array(__('Browse All') => uri('exhibits'), __('Browse by Tag') => uri('exhibits/tags'))); ?>
			</ul>
		</nav>    
    
	
    <div class="pagination"><?php echo pagination_links(); ?></div>
	
    <div id="exhibits">	
    <?php $exhibitCount = 0; ?>
    <?php while(loop_exhibits()): ?>
    	<?php $exhibitCount++; ?>
    	<div class="exhibit <?php if ($exhibitCount%2==1) echo ' even'; else echo ' odd'; ?>">
    		<h2><?php echo link_to_exhibit(); ?></h2>
    		<div class="description"><?php echo exhibit('description'); ?></div>
    		<p class="tags"><?php echo tag_string(get_current_exhibit(), uri('exhibits/browse/tag/')); ?></p>
    	</div>
    <?php endwhile; ?>
    </div>
    
    <div class="pagination"><?php echo pagination_links(); ?></div>

    <?php else: ?>
	<p><?php echo __('There are no exhibits available yet.'); ?></p>
	<?php endif; ?>




				
	</section>	
	</div><!-- end primary -->

	<div id="page-col-right">
	
		<!-- Grab some recent images for the image tile montage -->
		<?php mh_display_recent_item(10);?>
	
	</div>	

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>

<?php foot(); ?>