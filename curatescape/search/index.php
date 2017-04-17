<?php 
$query = (isset($_GET['query']) ? $_GET['query'] : null);
$title = $query ? __('Search Results for "%s"', $query) : __('Sitewide Search');
$bodyclass ='browse queryresults';
$maptype='none';


echo head(array('maptype'=>$maptype,'title'=>$title,'bodyid'=>'search','bodyclass'=>$bodyclass)); 
?>


<div id="content">

<section class="search">	
	<h2><?php 
	$title .= ( $total_results  ? ': <span class="item-number">'.$total_results.'</span>' : '');
	echo $title; 
	?></h2>

	<div id="primary" class="browse">
	<section id="results">
			
		<nav class="secondary-nav" id="item-browse"> 
			<?php echo mh_item_browse_subnav();?>
		</nav>
	
		<div class="pagination top"><?php echo pagination_links(); ?></div>
		
		<?php if ($total_results): ?>
		<?php
		$searchable_types=get_custom_search_record_types();
		$active_types=isset($_GET['record_types']) ? $_GET['record_types'] : null;	
		?>
		<?php if($searchable_types):?> 
			<?php
			
			echo '<div id="search-filters">';
			echo '<a href="'.url('search').'" class="sitewide-search-legend sitewide-search-edit"><span class="icon-pencil" aria-hidden="true"></span> '.__('Searching %1s of %2s Record Types',(count($active_types) ? count($active_types) : count($searchable_types)),count($searchable_types) ).'</a> ';
			echo '<form id="sitewide-search-filters" class="hidden">';
			$filters='<div class="fieldset">';
			foreach($searchable_types as $record_type=>$record_label){
				$checked = (count($active_types) && in_array($record_type,$active_types)) ? "checked" : count($active_types)<=0 ? "checked" : null;
				$filters.= '<div><input type="checkbox" '.$checked.' id="'.$record_type.'" value="'.$record_type.'" name="record_types[]"/><label for="'.$record_type.'" class="record_types">'.$record_label.'</label></div>';
			}
			echo $filters.'</div>'; 

			echo '<input hidden name="query" value="'.$query.'"><input type="submit" value="Resubmit" id="submit_search">';
			echo '</form>';
			echo '</div>';
			?>
				
		<?php endif;?>			
		<?php $tours=$stories=$files=$pages=$collections=array();?>
		<?php foreach (loop('search_texts') as $st){
			switch($st['record_type']){
				case 'Tour':
					$tours[]=$st;
					break;
				case 'Item':
					$stories[]=$st;
					break;
				case 'File':
					$files[]=$st;
					break;
				case 'SimplePagesPage':
					$pages[]=$st;
					break;
				case 'Collection':
					$collections[]=$st;
					break;																				
			}
		}
		if($tours){
			
			echo '<h3 class="result-type-header">'.mh_tour_label('plural').'</h3>';
			echo '<div class="search-tours">';
			foreach($tours as $s){
				echo mh_tour_preview($s);
			}	
			echo '</div>';
		}	
		if($stories){
			echo '<h3 class="result-type-header">'.mh_item_label('plural').'</h3>';
			echo '<div class="search-stories">';
			foreach($stories as $s){
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				echo mh_homepage_hero_item($record);
			}	
			echo '</div>';
		}
		if($files){
			echo '<h3 class="result-type-header">Files</h3>';
			echo '<div class="search-files">';
			foreach($files as $s){
				echo '<div class="search-files-file">';
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				echo '<a href="'.record_url($record, 'show').'">'.record_image($record, 'square_thumbnail').'</a>';
				echo '<h4><a href="'.record_url($record, 'show').'">'.($s['title'] ? $s['title'] : '[Unknown]').'</a></h4>';
				echo '</div>';
			}	
			echo '<div class="search-files-file"></div><div class="search-files-file"></div><div class="search-files-file"></div>';
			echo '</div>';
		}
		if($collections){
			echo '<h3 class="result-type-header">Collections</h3>';
			echo '<div class="search-collections">';
			foreach($collections as $s){
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				echo '<h4><a href="'.record_url($record, 'show').'">'.($s['title'] ? $s['title'] : '[Unknown]').'</a></h4>';
			}	
			echo '</div>';
		}
		if($pages){
			echo '<h3 class="result-type-header">Pages</h3>';
			echo '<div class="search-pages">';
			foreach($pages as $s){
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				echo '<h4><a href="'.record_url($record, 'show').'">'.($s['title'] ? $s['title'] : '[Unknown]').'</a></h4>';
			}	
			echo '</div>';
		}
		?>
		
		<?php else: ?>
		<div id="no-results">
		    <p><?php echo ($query) ? '<em>'.__('Your query returned <strong>no results</strong>.').'</em>' : null;?></p>
		    <?php echo search_form(array('show_advanced'=>true));?>
		</div>
		<?php endif; ?>


		<div class="pagination bottom"><?php echo pagination_links(); ?></div>
				
	</section>	
	</div><!-- end primary -->

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>

<?php echo foot(); ?>