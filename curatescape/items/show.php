<?php 

// Determine which template to use based on the item type

$type = $item->getItemType();
$type = isset($type['name']) ? $type['name'] : null;
switch($type){

	// case 'Curatescape Story':
	// include('show-template-curatescape.php');
	// break;

	default:
	include('show-template-default.php');
	break;
	
}