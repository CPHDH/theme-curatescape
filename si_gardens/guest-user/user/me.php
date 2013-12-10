<?php
$user = current_user();
$pageTitle =  get_option('guest_user_dashboard_label');
echo head(array('title' => $pageTitle, 'bodyclass' => 'contribution'));
?>
<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $pageTitle ? $pageTitle : 'My Account'; ?></h2>


	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
		<?php echo flash(); ?>
		<!--p><?php echo __("Browse and manage your work here."); ?></p-->
		
		<ul>
		<li><a href="/guest-user/user/update-account/">Update account info and password</a></li>
		<li><a href="/contribution/my-contributions/">Manage my story contributions</a></li>
		<li><a href="/contribution/">Share a new story</a></li>
		<li><a href="/admin/users/logout/">Logout</a>
		</ul>

	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->
<?php echo foot(); ?>
