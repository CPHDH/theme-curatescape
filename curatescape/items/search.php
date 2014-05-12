<?php 
$query = (isset($_GET['query']) ? $_GET['query'] : null);
$searchRecordTypes = get_search_record_types();
$title = __('Search %s', mh_item_label('plural'));
$bodyclass ='browse advanced-search';
$maptype='none';


echo head(array('maptype'=>$maptype,'title'=>$title,'bodyid'=>'search','bodyclass'=>$bodyclass)); 
?>


<div id="content">

<section class="search">	
	<h2><?php echo $title; ?></h2>
		
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
		


		<?php echo $this->partial('items/search-form.php',
		    array('formAttributes' =>
		        array('id'=>'advanced-search-form'))); ?>
        
				
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