<?php
head( array( 'title' => 'Tours', 'bodyid'=>'tours',
   'bodyclass' => 'browse' ) );
?>
<div id="content">
<section class="browse tour">			
<h2>All Tours: <?php echo $total_records; ?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="browse">
	<section id="results">
	
	

    <?php 
    if( has_tours() ){
    if( has_tours_for_loop() ){
    	$i=1;
		while( loop_tours() ){ 
		
			$tourdesc = nls2p( tour( 'Description' ) );
			
			echo '<article id="item-result-'.$i.'" class="item-result">';
			echo '<h3><a href="'.$this->url( array('action' => 'show', 'id' => tour( 'id' ) ) ).'">'.tour( 'title' ).'</a></h3>';
						
			echo display_tour_thumb($this->tour,0,$userDefined);
			
			echo '<div class="item-description"><p>'.snippet($tourdesc,0,300).'</p></div>'; 
			echo '</article>';
			$i++;
			
			}
		}
	}?>
	
    
	</section>
    </div>

	<div id="page-col-right">
	</div>	

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>
<?php foot();?>