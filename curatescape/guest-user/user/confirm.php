<?php
$head = array('title' => __('Confirmation Error'));
echo head($head);
?>
<h1><?php echo $head['title']?></h1>
<article class="page show">
<div id='primary'>
<?php echo flash(); ?>
</div>
</article>
<?php echo foot(); ?>