<?php
/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Contribution
 */

$head = array('title' => 'Contribute',
              'bodyid' => 'contribution',
              'bodyclass' => 'contribution thanks thank-you');
head($head); ?>


<div id="content">
<article class="page show">

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">

    
	<h1><?php echo __('Thank You for Contributing!'); ?></h1>

	<p><?php echo __('Your contribution will show up in the archive once an administrator approves it. Meanwhile, feel free to %1$s or <a href="%2$s">browse the archive</a>.',contribution_link_to_contribute('make another contribution'),uri('items/browse')); ?></p>

	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	



</article>
</div> <!-- end content -->



<?php echo foot(); ?>
