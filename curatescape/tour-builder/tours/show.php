<?php
$tourTitle = strip_formatting( tour( 'title' ) );
$label = (function_exists('mh_tour_label')) ? mh_tour_label() : __('Tour');
if( $tourTitle != '' && $tourTitle != '[Untitled]' ) {
} else {
   $tourTitle = '';
}

echo head( array( 'maptype'=>'tour','title' => ''.$label.' | '.$tourTitle, 'content_class' => 'horizontal-nav', 'bodyid'=>'tours',
   'bodyclass' => 'show tour', 'tour'=>$tour) );
?>

<div id="content">
<article class="tour show" role="main">

	<header id="tour-header">
	<h2 class="tour-title instapaper_title"><?php echo $tourTitle; ?></h2>
	<?php if(tour( 'Credits' )){
		echo '<span class="tour-meta">'.__('By %s',tour( 'Credits' )).'</span>';
	}elseif(get_theme_option('show_author') == true){
		echo '<span class="tour-meta">'.__('By The %s Team',option('site_title')).'</span>';
	}else{}?>
	</header>
			
	<div id="page-col-left">
	</div>


	<div id="primary" class="show">
	    <section id="text">
		   <div id="tour-description">
		    <?php echo nls2p( tour( 'Description' ) ); ?>
		   </div>
		</section>
		   
		<section id="tour-items">
			<h3 class="locations"><?php echo __('Locations for %s', $label);?></h3>
	         <?php 
	         $i=1;
	         foreach( $tour->getItems() as $tourItem ): 
	        	 set_current_record( 'item', $tourItem );
	         	$itemID=$tourItem->id
	         ?>
		         <article class="item-result">
			         <h3><?php echo $i.'.';?> <a href="<?php echo url('/') ?>items/show/<?php echo $itemID.'?tour='.tour( 'id' ).'&index='.($i-1).''; ?>">
			         <?php echo metadata( $tourItem, array('Dublin Core', 'Title') ); ?>
			         </a></h3>
					<?php if ( metadata($tourItem,'has thumbnail')): ?>
						<div class="item-thumb hidden">
		    				<?php echo item_image('square_thumbnail') ;?>						
		    			</div>
					<?php endif; ?>			         
			         <div class="item-description"><?php echo snippet(metadata( $tourItem, array('Dublin Core', 'Description') ),0,250); ?></div>
		         </article>
	         <?php 
	         $i++;
	         endforeach; ?>
		</section>
			   
	</div>


	<div id="page-col-right">	
	</div>	
	
	
</article>
</div> <!-- end content -->

<?php echo function_exists('mh_share_this') ? '<div id="share-this" class="browse">'.mh_share_this(mh_tour_label()).'</div>' : null;?>
<?php echo foot(); ?>
