<?php
$pageTitle = __('Forgot Password');
echo head(array('title' => $pageTitle, 'bodyclass' => 'login'), $header);
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
		
		<p><?php echo __('Enter your email address to retrieve your password.'); ?></p>
		
		<div class="eight columns alpha offset-by-one">
		<form method="post" accept-charset="utf-8">
		    <div class="field">    
		        <div class="inputs six columns offset-by-one omega">
		            <?php echo $this->formText('email', @$_POST['email']); ?>
		        </div>
		    </div>
		
		    <input type="submit" class="submit" value="<?php echo __('Submit'); ?>" />
		</form>
		
		<p id="login-links">
		<span id="backtologin"><?php echo link_to('users', 'login', __('Back to Log In')); ?></span>
		</p>
	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->
<?php echo foot(array(), $footer); ?>
