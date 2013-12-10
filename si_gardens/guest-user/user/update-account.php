<?php

$js = "
var guestUserPasswordAgainText = '" . __('Password again for match') . "'; 
var guestUserPasswordsMatchText = '" . __('Passwords match!') . "'; 
var guestUserPasswordsNoMatchText = '" . __("Passwords do not match!") . "'; ";

queue_js_string($js);
queue_js_file('guest-user-password');
queue_css_file('skeleton');
$css = "form > div { clear: both; padding-top: 10px;} .two.columns {width: 30%;}";
queue_css_string($css);
$pageTitle = __('Update Account');
echo head(array('bodyclass' => 'update-account no-hero', 'title' => $pageTitle));
?>
<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $head['title']; ?></h2>

	<?php echo mh_contribution_user_nav();?>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
		<?php echo flash(); ?>
		<?php echo $this->form; ?>
	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->
<?php echo foot(); ?>