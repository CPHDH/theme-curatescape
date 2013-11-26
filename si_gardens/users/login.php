<?php
queue_js_file('login');
$pageTitle = __('Log In');
echo head(array('bodyclass' => 'login', 'title' => $pageTitle), $header);
?>
<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $head['title']; ?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">

		<h2><?php echo link_to_admin_home_page(); ?></h2>

		<?php echo flash(); ?>

		<div class="eight columns alpha offset-by-one">
		<?php echo $this->form->setAction($this->url('users/login')); ?>
		</div>    

		<p id="forgotpassword">
		<?php echo link_to('users', 'forgot-password', __('(Lost your password?)')); ?>
		</p>

	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->

<?php echo foot(array(), $footer); ?>