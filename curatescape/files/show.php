<?php
    $fileTitle = metadata('file', array('Dublin Core', 'Title')) ? strip_formatting(metadata('file', array('Dublin Core', 'Title'))) : metadata('file', 'original filename');

	echo head(array('file'=>$file, 'maptype'=>'none','bodyid'=>'file','bodyclass'=>'show item-file','title' => $fileTitle )); 
?>
<div id="content">

<article class="file show instapaper_body hentry" role="main">
		
	<header id="story-header">
		<h2 class="item-title"><?php echo $fileTitle; ?></h2>
		<?php 
		$info = array();
		
		($fileid=metadata('file', 'id')) ? $info[]='<span class="file-id">ID: '.$fileid.'</span>' : null;
		
		($source=metadata('file', array('Dublin Core','Source'))) ? $info[] = '<span class="file-source">Source: '.$source.'</span>' : null;
		($creators=metadata('file', array('Dublin Core','Creator'),true)) ? $info[] = '<span class="file-creator">Creator: '.mh_format_creators_string($creators).'</span>' : null;

		echo count($info) ? '<span id="file-header-info" class="story-meta byline">'.implode(" | ", $info).link_to_file_edit($file,' ').'</span>' : null;
				
		?>
	</header>

	<div id="item-primary" class="show">
		<hr>
		<?php 
		$record=get_record_by_id('Item', $file->item_id);
		$title=metadata($record,array('Dublin Core','Title'));
		echo link_to_item(__('This file appears in: <em><strong>%s</strong></em>', $title), array('class'=>'file-appears-in-item'), 'show', $record);	
		?> 
		<hr>
		

		<?php echo mh_single_file_show($file); ?>

		<?php if( $rights = metadata('file', array('Dublin Core','Rights')) ) echo '<div class="rights-caption">'.$rights.'</div>';?>
		
		<div id="key-file-metadata">
		<?php  
		echo ($desc=metadata('file', array('Dublin Core','Description'))) ? '<p class="file-desc">'.$desc.'</p>' : null; 
		//echo link_to_file_edit($file);
		?>	
		</div>	

	<hr>	
	<?php echo link_to_item(__('This file appears in: <em><strong>%s</strong></em>', $title), array('class'=>'file-appears-in-item'), 'show', $record);?> 
	<hr>
	<?php echo mh_homepage_hero_item($record);?>
	
	</div><!-- end primary -->
	

</article>


<div id="share-this" class="browse">
<?php echo mh_share_this(__('File'));?>
</div>	

</div> <!-- end content -->


<?php echo foot(); ?>