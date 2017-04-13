<?php 
$query = (isset($_GET['query']) ? $_GET['query'] : null);
$title = $query ? __('Search Results for "%s"', $query) : __('Search');
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
			echo '<div id="search-filters" style="color:#777; font-size:.8em;">';
			echo '<form>';
			$filters='<span style="font-variant:small-caps">'.__('Record Types').':</span> ';
			foreach($searchable_types as $record_type=>$record_label){
				$checked = (count($active_types) && in_array($record_type,$active_types)) ? "checked" : count($active_types)<=0 ? "checked" : null;
				$filters.= '&nbsp;<input type="checkbox" '.$checked.' value="'.$record_type.'" name="record_types[]"/>&nbsp;<a class="record_types" href="">'.$record_label.'</a>&nbsp;';
			}
			echo $filters.' <input hidden name="query" value="'.$query.'"><input type="submit" value="Apply">';
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
			echo '<style>.result-type-header{border-bottom:1px solid #ccc;padding-bottom:2px;}.mini-thumb{height:40px; width:40px; background-color:#777; margin-right:2px; display:inline-block;margin-bottom:2px;}.tour-thumbs-container{line-height:15px;display:inline-block;}.tour-result-snippet{display: block;line-height: 1.4em;font-size: .9em;margin-bottom: 1em;}.tour-result-title{margin-bottom:0}.search-tours article{margin-bottom:2em;}</style>';
			echo '<h3 class="result-type-header">'.mh_tour_label('plural').'</h3>';
			echo '<div class="search-tours">';
			foreach($tours as $s){
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				set_current_record( 'tour', $record );
				echo '<article>';
				echo '<h3 class="tour-result-title"><a href="'.record_url($record, 'show').'">'.($s['title'] ? $s['title'] : '[Unknown]').'</a></h3>';
				echo '<span class="tour-meta-browse">';
				if(tour('Credits') ){
					echo __('%1s curated by: %2s', mh_tour_label_option('singular'),tour('Credits') ).' | ';
				}elseif(get_theme_option('show_author') == true){
					echo __('%1s curated by: The %2s Team',mh_tour_label_option('singular'),option('site_title')).' | ';
				}		
				echo count($record->Items).' '.__('Locations').'</span><br>';
				echo ($text=tour('Description')) ? '<span class="tour-result-snippet">'.snippet($text,0,300).'</span>' : null;
				$i=0;
				echo '<span class="tour-thumbs-container">';
				foreach($record->Items as $mini_thumb){
					echo metadata($mini_thumb, 'has thumbnail') ? '<div class="mini-thumb">'.item_image('square_thumbnail',array('height'=>'40','width'=>'40'),null,$mini_thumb).'</div>' : null;
				}
				echo '</span>';
				echo '</article>';
			}	
			echo '</div>';
		}	
		if($stories){
			echo '<style>.result-type-header{border-bottom:1px solid #ccc;padding-bottom:2px;}.search-stories h3 span.byline{font-size:inherit;}.featured-decora-outer{border-bottom: 1em solid #fff;}</style>';
			echo '<h3 class="result-type-header">'.mh_item_label('plural').'</h3>';
			echo '<div class="search-stories">';
			foreach($stories as $s){
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				echo mh_homepage_hero_item($record);
			}	
			echo '</div>';
		}
		if($files){
			echo '<style>.result-type-header{border-bottom:1px solid #ccc;padding-bottom:2px;}.search-files{display:flex;flex-wrap:wrap;justify-content:space-between;flex-direction:row;}.search-files-file{width:23%}.search-files-file h4{line-height:1.4em;}</style>';
			echo '<h3 class="result-type-header">Files</h3>';
			echo '<div class="search-files">';
			foreach($files as $s){
				echo '<div class="search-files-file">';
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				echo '<a href="'.record_url($record, 'show').'">'.record_image($record, 'square_thumbnail',array('style'=>'width:100%;')).'</a>';
				echo '<h4><a href="'.record_url($record, 'show').'">'.($s['title'] ? $s['title'] : '[Unknown]').'</a></h4>';
				echo '</div>';
			}	
			echo '<div class="search-files-file"></div><div class="search-files-file"></div><div class="search-files-file"></div>';
			echo '</div>';
		}
		if($collections){
			echo '<style>.result-type-header{border-bottom:1px solid #ccc;padding-bottom:2px;}</style>';
			echo '<h3 class="result-type-header">Collections</h3>';
			echo '<div class="search-collections">';
			foreach($collections as $s){
				$record=get_record_by_id($s['record_type'], $s['record_id']);
				echo '<h4><a href="'.record_url($record, 'show').'">'.($s['title'] ? $s['title'] : '[Unknown]').'</a></h4>';
			}	
			echo '</div>';
		}
		if($pages){
			echo '<style>.result-type-header{border-bottom:1px solid #ccc;padding-bottom:2px;}</style>';
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