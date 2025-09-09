<?php 
$tag = (isset($_GET['tag']) ? htmlspecialchars($_GET['tag']) : null); // items --> browse
$tags = (isset($_GET['tags']) ? htmlspecialchars($_GET['tags']) : null); // tags/items --> show
$subj = ( (isset($_GET['advanced'][0]['element_id']) && $_GET['advanced'][0]['element_id'] == 49 )  ? htmlspecialchars($_GET['advanced'][0]['terms']) : null );
$auth= ( (isset($_GET['advanced'][0]['element_id']) && $_GET['advanced'][0]['element_id'] == 39 )  ? htmlspecialchars($_GET['advanced'][0]['terms']) : null );
$collection = (isset($_GET['collection']) ? htmlspecialchars($_GET['collection']) : null);
$query = (isset($_GET['search']) ? htmlspecialchars($_GET['search']) : null);
$bodyclass='browse';

if ( ($tag || $tags) && !($query) ) {
	$the_tag=($tag ? $tag : $tags);
	$title = __('%1$s tagged "%2$s"', storyLabelString('plural'), $the_tag);
	$bodyclass .=' queryresults';
}
elseif ( !empty($auth) ) {
	$title = __('%1$s by author "%2$s"', storyLabelString('plural'), $auth);
	$bodyclass .=' queryresults';
}elseif ( !empty($subj) ) {
	$title = __('Results for subject term "%s"', $subj);
	$bodyclass .=' queryresults';
}elseif ( !empty($collection) ) {
	$c=get_record_by_id('collection',$collection);
	$collection_title=metadata($c,array('Dublin Core','Title'));
	$title = __('%1s in "%2s"', storyLabelString('plural'), $collection_title);
	$bodyclass .=' queryresults';
}elseif ( isset($_GET['featured']) && $_GET['featured'] == 1){
	$title = __('Featured %s', storyLabelString('plural'));
	$bodyclass .=' queryresults';
}
elseif ($query) {
	$title = __('Search Results for "%s"', $query);
	$bodyclass .=' queryresults';
}
else{
	$title = __('All %s', storyLabelString('plural'));
	$bodyclass .=' items stories';
}
echo head(array('title'=>$title,'bodyid'=>'items','bodyclass'=>$bodyclass)); 
?>

<div id="content">

<article class="browse stories items">	
	<h2 class="query-header"><?php 
	$title .= ( $total_results  ? ': <span class="item-number">'.$total_results.'</span>' : '');
	echo $title; 
	?></h2>
	<div id="primary" class="browse">
		<section id="results">
			<h2 hidden class="hidden"><?php echo storyLabelString('plural');?></h2>
			<nav class="secondary-nav" id="item-browse"> 
				<?php echo public_nav_items(); ?>
			</nav>

			<div id="sort-links">
				<?php
				$sortLinks[__('Title')] = 'Dublin Core,Title';
				$sortLinks[__('Creator')] = 'Dublin Core,Creator';
				$sortLinks[__('Date Added')] = 'added';
				?>
				<span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
			</div>

			<div class="browse-items flex" role="main">
			<?php 
			foreach(loop('Items') as $item): 
				$item_image=null;
				$tags=tag_string(get_current_record('item') , url('items/browse'));
				$hasImage=metadata($item, 'has thumbnail');
				if ($hasImage){
						preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', item_image('fullsize'), $result);
						$item_image = array_pop($result);
				}else{
					$item_image='';
				}
				?>
				<article class="item-result <?php echo $hasImage ? 'has-image' : 'no-image';?>">
					<?php echo link_to_item('<span class="item-image" style="background-image:url('.$item_image.');" role="img" aria-label="'.metadata($item, array('Dublin Core', 'Title')).'"></span>',array('title'=>metadata($item,array('Dublin Core','Title')))); ?>
					<h3><?php echo mh_the_title_link($item); ?></h3>
					<div class="browse-meta-top"><?php echo mh_the_byline($item,false);?></div>
					<div class="item-description">
						<?php echo metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250));?>
					</div>
					<?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>
				</article> 
			<?php endforeach; ?>
			<?php if($query && !$total_results){?>
			<div id="no-results">
				<p><?php echo ($query) ? '<em>'.__('Your query returned <strong>no results</strong>.').'</em>' : null;?></p>
				<?php echo search_form(array('show_advanced'=>true));?>
			</div>
			<?php }?>
			</div>
			<div class="pagination bottom"><?php echo pagination_links(); ?></div>
		</section>	
	</div><!-- end primary -->

</article>
</div> <!-- end content -->
<?php echo foot(); ?>