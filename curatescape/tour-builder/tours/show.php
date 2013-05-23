<?php
$tourTitle = strip_formatting( tour( 'title' ) );
if( $tourTitle != '' && $tourTitle != '[Untitled]' ) {
} else {
   $tourTitle = '';
}

head( array( 'maptype'=>'tour','title' => 'Tour: '.$tourTitle, 'content_class' => 'horizontal-nav', 'bodyid'=>'tours',
   'bodyclass' => 'show tour', 'tour'=>$tour) );
?>

<div id="content">
<article class="tour show" role="main">

	<header id="tour-header">
	<?php echo mh_showmap();?>
	<h2 class="tour-title instapaper_title"><?php echo $tourTitle; ?></h2>
	<?php if(tour( 'Credits' )){
		echo '<span class="tour-meta">By '.tour( 'Credits' ).'</span>';
	}elseif(get_theme_option('show_author') == true){
		echo '<span class="tour-meta">By The '.settings('site_title').' Team</span>';
	}?>
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
			<h3 class="locations">Tour Locations</h3>
	         <?php 
	         $i=1;
	         foreach( $tour->Items as $tourItem ): ?>
		         <article class="item-result">
		         <?php $itemID=$tourItem->id;?>
			         <h3><?php echo $i.'.';?> <a href="<?php echo uri('/') ?>items/show/<?php echo $itemID.'?tour='.tour( 'id' ).'&index='.($i-1).''; ?>">
			         <?php echo $this->itemMetadata( $tourItem, 'Dublin Core', 'Title' ); ?>
			         </a></h3>
					<?php if ( $tourItem->hasThumbnail() ): ?>
						<div class="item-thumb hidden">
		    				<?php echo item_square_thumbnail($props = array(),$index = 0, $item = $tourItem);?>						
		    			</div>
					<?php endif; ?>			         
			         <div class="item-description"><?php echo snippet($this->itemMetadata( $tourItem, 'Dublin Core', 'Description' ),0,250); ?></div>
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

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>
<?php foot(); ?>