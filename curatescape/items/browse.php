<?php 
$tag = ($_GET['tags'] ? $_GET['tags'] : null);
$term = ($_GET['term'] ? $_GET['term'] : null);
$query = ($_GET['search'] ? $_GET['search'] : null);
$advanced = ($_GET['advanced'] ? true : false);
$bodyclass='browse';
$maptype='focusarea';

if ( ($tag) && !($query) ) {
	$title = ''.mh_item_label('plural').' tagged "'.$tag.'"';
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}
elseif ( ($term) && !($query) ) {
	$title = __('Results for subject term "%s"',$term);
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}
elseif ($query) {
	$title = (!($advanced) ? __('Search Results for "%s"',$query) : __('Advanced Search Results'));
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}	
else{
	$title = __('All ').mh_item_label('plural').'';
	$bodyclass .=' items stories';
}	
head(array('maptype'=>$maptype,'title'=>$title,'bodyid'=>'items','bodyclass'=>$bodyclass)); 
?>


<div id="content">

<section class="browse stories items">	
	<h2><?php 
	$title .= ( (total_results()) ? ': <span class="item-number">'.total_results().'</span>' : '');
	echo $title; 
	?></h2>
		
		
	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="browse">
	<section id="results">
			
		<nav class="secondary-nav" id="item-browse"> 
			<ul>
			<?php echo mh_item_browse_subnav();?>
			</ul>
		</nav>
		
		<div class="pagination top"><?php echo pagination_links(); ?></div>
		
		<?php 
		$index=1; // set index to one so we can use zero as an argument below
		$showImgNum= 3; // show this many images on the browse results page; used for slider on mobile devices
		while (loop_items()): 
			$description = item('Dublin Core', 'Description', array('snippet'=>250));
			$tags=tag_string(get_current_item(), uri('items/browse?tags='));
			$thumblink=link_to_item(item_square_thumbnail());
			$titlelink=link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink'));
			?>
			<article class="item-result" id="item-result-<?php echo $index;?>">
			
				<h3><?php echo $titlelink; ?></h3>
				
				<?php if (item_has_thumbnail() && mh_reducepayload($index,$showImgNum)): ?>
					<div class="item-thumb">
	    				<?php echo $thumblink; ?>						
	    			</div>
				<?php endif; ?>

				
				<?php if ($description): ?>
    				<div class="item-description">
    					<?php echo strip_tags($description); ?>
    				</div>
				<?php endif; ?>

				<?php if (item_has_tags()): ?>
    				<div class="item-tags">
    				<p><span><?php echo __('Tags:') ?></span> <?php echo $tags; ?></p>
    				</div>
				<?php endif; ?>
			</article> 
		<?php 
		$index++;
		endwhile; 
		?>
		
		<div class="pagination bottom"><?php echo pagination_links(); ?></div>
				
	</section>	
	</div><!-- end primary -->

	<div id="page-col-right">
	</div>	

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>

<?php foot(); ?>
