<?php
$user = current_user();
$pageTitle =  get_option('guest_user_dashboard_label');
echo head(array('title' => $pageTitle));
?>
<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $pageTitle; ?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
		<?php echo flash(); ?>
		<p><?php echo __("Browse and manage your work here."); ?></p>
		
		<?php foreach($widgets as $index=>$widget): ?>
		<div class='guest-user-widget <?php if($index & 1): ?>guest-user-widget-odd <?php else:?>guest-user-widget-even<?php endif;?>'>
		<?php echo guest_user_widget($widget); ?>
		</div>
		<?php endforeach; ?>

	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->
<?php echo foot(); ?>
