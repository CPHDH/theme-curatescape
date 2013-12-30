<?php
$label=mh_tour_label('plural');
echo head( array('maptype'=>'none', 'title' => $label, 'bodyid'=>'tours',
   'bodyclass' => 'browse' ) );
?>
<div id="content">
<section class="browse tour">			
<h2><?php echo __('All %1$s: %2$s', $label, total_tours());?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="browse">
	
	<section id="results">
	<nav class="tours-nav navigation secondary-nav">
	  <?php echo public_nav_tours(); ?>
	</nav>	
	<div class="pagination bottom"><?php echo pagination_links(); ?></div>

    <?php 
    
    if( has_tours() ){
    if( has_tours_for_loop() ){
    	$i=1;
    	$tourimg=0;
		foreach( $tours as $tour ){ 
		set_current_record( 'tour', $tour );
		
			$tourdesc = nls2p( tour( 'description' ) );
		
			echo '<article id="item-result-'.$i.'" class="item-result has-image">';
			echo '<h3>'.link_to_tour().'</h3>';
					
			if($i<=10){
			    echo display_tour_thumb($tour,$i,$userDefined=null);
			    $tourimg++;
			}
			
			echo '<div class="item-description"><p>'.snippet($tourdesc,0,300).'</p></div>'; 
			echo '</article>';
			$i++;
		
			}
			
		}
	}
	?>
	
    
	</section>
    </div>

	<div id="page-col-right">
	<?php 
	if($tourimg<10){
		// if there aren't 10 tour images to fill out the collage, grab some item images to fill it out
		$num=10-$tourimg; 
		mh_display_recent_item($num);
	}?>
	</div>	
	
	<div class="pagination bottom"><?php echo pagination_links(); ?></div>

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this(); ?>
</div>

<?php echo foot();?>
