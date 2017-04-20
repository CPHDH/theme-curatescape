<?php 
if(!$collection){
	// We don't use the Collections on the front-end
	include_once($_SERVER["DOCUMENT_ROOT"] . "/themes/curatescape/error/404.php");
}else{
$title=metadata($collection,array('Dublin Core','Title'));
$bodyclass = 'collections show';
$bodyid='collections';
echo head(array('maptype'=>'none','title' => __('Collection').' | '.$title, 'bodyclass' => $bodyclass, 'bodyid' => $bodyid)); 
?>

<div id="content">
<section class="browse collection">			
<h2><?php echo $title;?></h2>
<span class="collection-meta-browse"><?php echo metadata($collection, 'total_items').' '.mh_item_label('plural');?></span>

	<div id="primary" class="browse">
	
	    <section id="text">
		   <div id="tour-description">
		    <?php echo metadata($collection,array('Dublin Core','Description')); ?>
		   </div>
		</section>
		   
		<section id="tour-items">

		    <?php if (metadata('collection', 'total_items') > 0): ?>

				<p><?php echo link_to('items','browse',__("View all %1s %2s in %3s.",metadata('collection', 'total_items'),mh_item_label('plural'),$title),array('class'=>'collection-items-browse'),array('collection'=>$collection->id) ); ?></p>

		    <?php else: ?>
		    
		        <p><?php echo __("This collection currently has no %s.",mh_item_label('plural')); ?></p>
		        
		    <?php endif; ?>
		</section>
    </div>

	

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this('Collection'); ?>
</div>

<?php echo foot();?>

<?php } ?>