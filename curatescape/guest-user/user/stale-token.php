<?php
$head = array('title'=> __('Stale Token'));
echo head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<article class="page show">
<div id='primary'>
<?php echo flash(); ?>
<p><?php echo __("Your temporary access to the site has expired. Please check your email for the link to follow to confirm your registration."); ?></p>

<p><?php echo __("You have been logged out, but can continue browsing the site."); ?></p>
</div>
</article>

<?php echo foot(); ?>