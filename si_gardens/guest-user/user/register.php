<?php
queue_js_file('guest-user-password');
queue_css_file('skeleton');
$css = "form > div { clear: both; padding-top: 10px;} .two.columns {width: 30%;} ";
queue_css_string($css);
$pageTitle = __('Register');
echo head(array('bodyclass' => 'register no-hero', 'title' => $pageTitle));
?>
<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $pageTitle; ?></h2>

	<p><em>Already have an account? <a href="/guest-user/user/login">Login now</a>.</em></p>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
		<div id='capabilities'>
		<p>
		<?php echo get_option('guest_user_capabilities'); ?>
		</p>
		</div>
		<?php echo flash(); ?>
		<?php echo $this->form; ?>
		<p id='confirm'></p>
	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->
<?php echo foot(); ?>
