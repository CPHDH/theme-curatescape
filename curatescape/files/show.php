<?php
    $fileTitle = metadata('file', array('Dublin Core', 'Title')) ? strip_formatting(metadata('file', array('Dublin Core', 'Title'))) : metadata('file', 'original filename');

    if ($fileTitle != '') {
        $fileTitle = ': &quot;' . $fileTitle . '&quot; ';
    } else {
        $fileTitle = '';
    }
    $fileTitle = __('File #%s', metadata('file', 'id')) . $fileTitle;
	echo head(array('file'=>$file, 'maptype'=>'none','bodyid'=>'file','bodyclass'=>'show item-file','title' => $fileTitle )); 
?>
<div id="content">

<article class="file show instapaper_body hentry" role="main">
		
	<header id="story-header">
		<h2 class="item-title"><?php echo $fileTitle; ?></h2>
		<?php 
		$info = array();
		($source=metadata('file', array('Dublin Core','Source'))) ? $info[] = '<span class="file-source">Source: '.$source.'</span>' : null;
		($creator=metadata('file', array('Dublin Core','Creator'))) ? $info[] = '<span class="file-creator">Creator: '.$creator.'</span>' : null;

		echo count($info) ? '<span id="file-header-info" class="story-meta byline">'.implode(" | ", $info).'</span>' : null;
		
		?>
	</header>

	<div id="item-primary" class="show">
		<hr>
		<?php 
		$record=get_record_by_id('Item', $file->item_id);
		$title=metadata($record,array('Dublin Core','Title'));
		echo link_to_item('<i class="icon-chevron-left"></i> '.__('This file appears in: <em><strong>%s</strong></em>', $title), array('class'=>'file-appears-in-item'), 'show', $record);	
		?> 
		<hr>
		
		<?php echo file_markup($file, array('imageSize'=>'fullsize')); ?>
		
		<div id="key-file-metadata">
		<?php  
		echo ($desc=metadata('file', array('Dublin Core','Description'))) ? '<span class="file-desc">'.$desc.'</span>' : null; 
		echo link_to_file_edit($file);
		?>	
		</div>	
		
		<hr>
		<?php echo link_to_item('<i class="icon-chevron-left"></i> '.__('This file appears in: <em><strong>%s</strong></em>', $title), array('class'=>'file-appears-in-item'), 'show', $record);?> 
		<hr>
		
		<!--a class="big-button reveal-button">View Full Metadata Record</a-->
		
		<div id="file-metadata">
			<?php echo all_element_texts('file'); ?> 		
	
		    <div id="format-metadata">
		        <h2><?php echo __('Format Metadata'); ?></h2>
		        <div id="original-filename" class="element">
		            <h3><?php echo __('Original Filename'); ?></h3>
		            <div class="element-text"><?php echo metadata('file', 'Original Filename'); ?></div>
		        </div>
		    
		        <div id="file-size" class="element">
		            <h3><?php echo __('File Size'); ?></h3>
		            <div class="element-text"><?php echo __('%s bytes', metadata('file', 'Size')); ?></div>
		        </div>
		
		    </div><!-- end format-metadata -->
		    
		    <div id="type-metadata" class="section">
		        <h2><?php echo __('Type Metadata'); ?></h2>
		        <div id="mime-type-browser" class="element">
		            <h3><?php echo __('Mime Type'); ?></h3>
		            <div class="element-text"><?php echo metadata('file', 'MIME Type'); ?></div>
		        </div>
		        <div id="file-type-os" class="element">
		            <h3><?php echo __('File Type / OS'); ?></h3>
		            <div class="element-text"><?php echo metadata('file', 'Type OS'); ?></div>
		        </div>
		    </div><!-- end type-metadata -->
		</div><!-- end file-metadata -->
	<hr>	
	<?php echo link_to_item('<i class="icon-chevron-left"></i> '.__('This file appears in: <em><strong>%s</strong></em>', $title), array('class'=>'file-appears-in-item'), 'show', $record);?> 
	<hr>
	
	</div><!-- end primary -->
	
	<div id="page-col-right">
		<aside id="page-sidebar">
			
			<!-- Grab some recent images for the image tile montage -->
			<?php mh_display_recent_item(10);?>
			
		</aside>	
	</div>		
	
		


</article>


<div id="share-this" class="browse">
<?php echo mh_share_this(__('File'));?>
</div>	

</div> <!-- end content -->


<?php echo foot(); ?>