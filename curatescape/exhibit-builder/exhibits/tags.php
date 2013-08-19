<?php 
$title = __('Browse Exhibits by Tag');
head(array('maptype'=>'none','title' => $title, 'bodyid' => 'exhibit', 'bodyclass' => 'tags browse'));
?>


<div id="content">

<section class="browse tags">	
	<h2><?php 
	$title .= ( ($total_records) ? ': <span class="item-number">'.$total_records.'</span>' : '');
	echo $title; 
	?></h2>
		
		
	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="browse">
		<nav class="secondary-nav" id="item-browse"><ul>
		    <?php echo nav(array(
		        __('Browse All') => uri('exhibits/browse'),
		        __('Browse by Tag') => uri('exhibits/tags')
		    )); ?>
		</ul></nav>
		
		<?php echo tag_cloud($tags,uri('exhibits/browse')); ?>
	</div><!-- end primary -->

	<div id="page-col-right">
	</div>	

</section>
</div> <!-- end content -->

<div id="share-this" class="browse">
<?php echo mh_share_this();?>
</div>

<?php foot(); ?>