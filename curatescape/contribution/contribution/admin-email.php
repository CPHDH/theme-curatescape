<?php echo __('A new contribution to %s has been made.', get_option('site_title')); ?> 
	
<?php echo __('Contribution URL for review:'); ?>

    <?php
        set_theme_base_uri('admin');
        echo abs_item_uri($item);
        set_theme_base_uri();
    ?>
