<?php 
$tag = (isset($_GET['tag']) ? $_GET['tag'] : null); // items --> browse
$tags = (isset($_GET['tags']) ? $_GET['tags'] : null); // tags/items --> show
$subj = ( (isset($_GET['advanced'][0]['element_id']) && $_GET['advanced'][0]['element_id'] == 49 )  ? $_GET['advanced'][0]['terms'] : null );
$auth= ( (isset($_GET['advanced'][0]['element_id']) && $_GET['advanced'][0]['element_id'] == 39 )  ? $_GET['advanced'][0]['terms'] : null );
$query = (isset($_GET['search']) ? $_GET['search'] : null);
$bodyclass='browse';
$maptype='focusarea';

if ( ($tag || $tags) && !($query) ) {
	$title = ''.mh_item_label('plural').' tagged "'.($tag ? $tag : $tags).'"';
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}
elseif ( !empty($auth) ) {
	$title = ''.mh_item_label('plural').__(' by author "%s"',$auth);
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}elseif ( !empty($subj) ) {
	$title = __('Results for subject term "%s"',$subj);
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}
elseif ($query) {
	$title =  __('Search Results for "%s"',$query);
	$bodyclass .=' queryresults';
	$maptype='queryresults';
}	
else{
	$title = __('All %s', mh_item_label('plural')).'';
	$bodyclass .=' items stories';
}	
echo head(array('maptype'=>$maptype,'title'=>$title,'bodyid'=>'items','bodyclass'=>$bodyclass)); 
?>


<div id="content">

<section class="browse stories items">	
	<h2><?php 
	$title .= ( $total_results  ? __(': <span class="item-number">%s</span>',$total_results) : '');
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
			<?php echo mh_item_browse_subnav();?>
		</nav>
		
		<div class="pagination top"><?php echo pagination_links(); ?></div>
		
		<?php 
		$index=1; // set index to one so we can use zero as an argument below
		$showImgNum= 3; // show this many images on the browse results page; used for slider on mobile devices
		foreach(loop('Items') as $item): 
			$description = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>250));
			$tags=tag_string(get_current_record('item') , url('items/browse'));
			$thumblink=link_to_item(item_image('square_thumbnail') );
			$titlelink=link_to_item(metadata($item, array('Dublin Core', 'Title')), array('class'=>'permalink'));
			?>
			<article class="item-result" id="item-result-<?php echo $index;?>">
			
				<h3><?php echo $titlelink; ?></h3>
				
				<?php if (metadata($item, 'has thumbnail') && mh_reducepayload($index,$showImgNum)): ?>
					<div class="item-thumb">
	    				<?php echo $thumblink; ?>						
	    			</div>
				<?php endif; ?>

				
				<?php if ($description): ?>
    				<div class="item-description">
    					<?php echo strip_tags($description); ?>
    				</div>
				<?php endif; ?>

				<?php if (metadata($item, 'has tags') ): ?>
    				<div class="item-tags">
    				<p><span><?php echo __('Tags:') ?></span> <?php echo $tags; ?></p>
    				</div>
				<?php endif; ?>
				
				<?php 
				if(get_theme_option('subjects_on_browse')==1){
					mh_subjects_string();
					}
				?>
				
			</article> 
		<?php 
		$index++;
		endforeach; 
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

<?php echo foot(); ?>
