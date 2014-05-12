<?php 
$query = (isset($_GET['query']) ? $_GET['query'] : null);
$searchRecordTypes = get_search_record_types();
$title = __('Search Results for "%s"', $query);
$bodyclass ='browse queryresults';
$maptype='queryresults';


echo head(array('maptype'=>$maptype,'title'=>$title,'bodyid'=>'search','bodyclass'=>$bodyclass)); 
?>


<div id="content">

<section class="search">	
	<h2><?php 
	$title .= ( $total_results  ? ': <span class="item-number">'.$total_results.'</span>' : '');
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
		
		<?php echo search_filters(); ?>
		
		<?php if ($total_results): ?>
		<table id="search-results">
		    <thead>
		        <tr>
		            <th><?php echo __('Type');?></th>
		            <th><?php echo __('Title');?></th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php foreach (loop('search_texts') as $searchText): ?>
		        <?php 
			        $type_label=str_replace(__('Item'),mh_item_label('singular'),$searchRecordTypes[$searchText['record_type']]);
			        $type_label=str_replace(__('Simple Page'),__('Page'),$searchRecordTypes[$searchText['record_type']]);
		        ?>
		        <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
		        <tr class="<?php echo strtolower($searchText['record_type']);?>">
		            <td><?php echo $type_label; ?></td>
		            <td><a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a></td>
		        </tr>
		        <?php endforeach; ?>
		    </tbody>
		</table>
		
		<?php else: ?>
		<div id="no-results">
		    <p><?php echo __('Your query returned no results.');?></p>
		</div>
		<?php endif; ?>


		<div class="pagination bottom"><?php echo pagination_links(); ?></div>
				
	</section>	
	</div><!-- end primary -->

	<div id="page-col-right">
		<aside id="page-sidebar">
			
			<!-- Grab some recent images for the image tile montage -->
			<?php mh_display_recent_item(10);?>
			
		</aside>	
	</div>	

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>

<?php echo foot(); ?>