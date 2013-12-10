<?php
$pageTitle = __('User Activation');
echo head(array('title' => $pageTitle, 'bodyclass' => 'contribution'), $header);
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

		<h2>Hello, <?php echo html_escape($user->name); ?>.<br />
		Your username is: <?php echo html_escape($user->username); ?></h2>
		
		<form method="post">
		    <fieldset>
		    <div class="field">
		    <?php echo $this->formLabel('new_password1', __('Create a Password')); ?>
		        <div class="inputs">
		            <input type="password" name="new_password1" id="new_password1" class="textinput" />
		        </div>
		    </div>
		    <div class="field">
		        <?php echo $this->formLabel('new_password2', __('Re-type the Password')); ?>        
		        <div class="inputs">
		            <input type="password" name="new_password2" id="new_password2" class="textinput" />
		        </div>
		    </div>
		    </fieldset>
		    <div>
		    <input type="submit" class="submit" name="submit" value="<?php echo __('Activate'); ?>"/>
		    </div>
		</form>

	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->

<?php echo foot(array(), $footer); ?>
