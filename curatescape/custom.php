<?php
/*
** Set Fallback Thumbnails
*/
add_file_fallback_image('audio','audio.png');
add_file_fallback_image('video','video.png');

/*
** Icons
*/
function mh_icon($icon){
	return '<svg aria-hidden="true" class="icon-sprite '.$icon.'" viewBox="0 0 512 512"><use xlink:href="'.img('sprites.svg').'#'.$icon.'" /></svg>';
}

/*
** SEO Page Description
*/
function mh_seo_pagedesc($item=null,$tour=null,$file=null){
	if($item != null){
		$itemdesc=snippet(mh_the_text($item),0,500,"...");
		return htmlspecialchars(strip_tags($itemdesc));
	}elseif($tour != null){
		$tourdesc=snippet(metadata($tour,'Description'),0,500,"...");
		return htmlspecialchars(strip_tags($tourdesc));
	}elseif($file != null){
		$filedesc=snippet(metadata('file',array('Dublin Core', 'Description')),0,500,"...");
		return htmlspecialchars(strip_tags($filedesc));
	}else{
		return mh_seo_sitedesc();
	}
}

/* 
** SEO Site Description
*/
function mh_seo_sitedesc(){
	return strip_tags(option('description')) ? strip_tags(option('description')) : (mh_about() ? strip_tags(mh_about()) : null);
}

/* 
** SEO Page Title
*/
function mh_seo_pagetitle($title,$item){
	$subtitle=$item ? (mh_the_subtitle($item) ? ' - '.mh_the_subtitle($item) : null) : null;
	$pt = $title ? $title.$subtitle.' | '.option('site_title') : option('site_title');
	return strip_tags($pt);
}

/*
** SEO Page Image
*/
function mh_seo_pageimg($item=null,$file=null){
	if($item){
		if(metadata($item, 'has thumbnail')){
			$itemimg=item_image('fullsize') ;
			preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $itemimg, $result);
			$itemimg=array_pop($result);
		}
	}elseif($file){
		if($itemimg=file_image('fullsize') ){
			preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $itemimg, $result);
			$itemimg=array_pop($result);
		}
	}
	return isset($itemimg) ? $itemimg : mh_seo_pageimg_custom();
}

/* 
** SEO Site Image
*/
function mh_seo_pageimg_custom(){
	$custom_img = get_theme_option('custom_meta_img');
	$custom_img_url = $custom_img ? WEB_ROOT.'/files/theme_uploads/'.$custom_img : mh_the_logo_url();	
	return $custom_img_url;
}

/*
** Get theme CSS link with version number
*/
function mh_theme_css($media='all'){
	$themeName = Theme::getCurrentThemeName();
	$theme = Theme::getTheme($themeName);
	return '<link href="'.WEB_PUBLIC_THEME.'/'.$themeName.'/css/screen.css?v='.$theme->version.'" media="'.$media.'" rel="stylesheet">';
}

/*
** Global navigation
*/
function mh_global_nav(){
	return '<div class="global-nav"><h4>'.__('Navigation').'</h4>'.public_nav_main()->setMaxDepth(1).'</div>';
}

/*
** Subnavigation for items/browse
*/
function mh_item_browse_subnav(){
	echo nav(array(
			array('label'=>__('All') ,'uri'=> url('items/browse')),
			array('label'=>__('Tags'), 'uri'=> url('items/tags')),
			array('label'=>__('Sitewide Search'), 'uri'=> url('search')),
			array('label'=>__('%s Search', storyLabelString()), 'uri'=> url('items/search')),
		));
}

/*
** Subnavigation for collections/browse
*/
function mh_collection_browse_subnav(){
	echo nav(array(
			array('label'=>__('All') ,'uri'=> url('collections/browse')),
		));
}

function mh_tour_browse_subnav($label,$id){
	echo nav(array(
			array('label'=>__('Locations for %s', $label) ,'uri'=> url('tours/show/'.$id)),
		));	
}

/*
** Logo URL
*/
function mh_the_logo_url()
{
	$logo = get_theme_option('lg_logo');
	$logo_url = $logo ? WEB_ROOT.'/files/theme_uploads/'.$logo : img('hm-logo.png');
	return $logo_url;
}

/*
** Logo IMG Tag
*/
function mh_the_logo(){
	return '<img src="'.mh_the_logo_url().'" alt="'.option('site_title').'"/>';
}

/*
** Link to Random Item
*/
function random_item_link($text=null,$hasImage=true, $html = null){
	if(!$text){
		$label = plugin_is_active('Curatescape') ? storyLabelString() : __('Item');
		$text= mh_icon('shuffle').__('View a Random %s', $label);
	}
	$randitems = get_records('Item', array( 
		'sort_field' => 'random', 'hasImage' => $hasImage), 1);
	if( count( $randitems ) > 0 ){
		return '<h4>'.__('Discover').'</h4>'.link_to( 
			$randitems[0], 
			'show', 
			$text, 
			array( 
				'class' => 'button button-primary icon random-story-link'
			) 
		);
	}
	return $html;
}

// Parse HTML Link
function parseLink($html) {
	$dom = new DOMDocument();
	// Suppress warnings
	libxml_use_internal_errors(true);
	// Load the HTML
	$dom->loadHTML($html);
	// Clear libxml errors
	libxml_clear_errors();
	$links = $dom->getElementsByTagName('a');
	$results = [];
	foreach ($links as $link) {
		return array(
			'href' => $link->getAttribute('href'),
			'target' => $link->getAttribute('target'),
			'text' => trim($link->textContent)
		);
	}
	return null;
}

// Priority Nav Links
function mh_priority_nav_links($links=array()){
	$configs = array(
		get_theme_option('quicklink_1'),
		get_theme_option('quicklink_2'),
		get_theme_option('quicklink_3')
	);
	$items = $collections = $tours = $exhibits = $map = $about = $custom = 0;
	foreach($configs as $config){
		switch ($config) {
			case 'items':
				if(!$items){
					$label = plugin_is_active('Curatescape') ? storyLabelString('plural') : __('Items'); 
					$links[] = '<a href="'.url('/items/browse/').'" class="button button-primary">'.$label.'</a>';
					$items++;
				}
				break;
			case 'collections':
				if(!$collections){
					$label = __('Collections');
					$links[] = '<a href="'.url('/collections/browse/').'" class="button button-primary">'.$label.'</a>';
					$collections++;
				}
				break;
			case 'tours':
				if(!$tours && plugin_is_active('Curatescape')){
					$label = tourLabelString('plural');
					$links[] = '<a href="'.url('/tours/browse/').'" class="button button-primary">'.$label.'</a>';
					$tours++;
				}
				break;
			case 'exhibits':
				if(!$exhibits && plugin_is_active('ExhibitBuilder')){
					$label = __('Exhibits');
					$links[] = '<a href="'.url('/exhibits/browse/').'" class="button button-primary">'.$label.'</a>';
					$exhibits++;
				}
				break;
			case 'map':
				if(!$map && plugin_is_active('Geolocation')){
					$label = __('Map');
					$links[] = '<a href="'.url('/geolocation/map/browse/').'" class="button button-primary">'.$label.'</a>';
					$map++;
				}
				break;
			case 'about':
				if(!$about && plugin_is_active('SimplePages')){
					$label = __('About');
					$links[] = '<a href="'.url('/about/').'" class="button button-primary">'.$label.'</a>';
					$about++;
				}
				break;
			case 'custom':
				if(!$custom){
					if($link = get_theme_option('quicklink_custom')){
						if($details = parseLink($link)){
							if( isset($details['href']) && isset($details['text']) ){
								$target = isset($details['target']) ? 'target="'.$details['target'].'" ' : null;
								$links[] = '<a '.$target.'href="'.$details['href'].'" class="button button-primary">'.$details['text'].'</a>';
								$custom++;
							}
						}
					}
				}
				break;
		}
	}
	return $links;
}
/*
** Global header
*/
function mh_global_header($html=null){ 
	$links = implode('', mh_priority_nav_links());
?>
<div id="navigation">
	<nav class="static" aria-label="<?php echo __('Main Navigation');?>">
		<?php echo link_to_home_page(mh_the_logo(),array('id'=>'home-logo'));?>
		<div class="spacer"></div>
		<div id="header-nav-main" class="flex flex-end flex-nav-container">
			<?php if($links): ?>
				<div class="priority flex">
					<?php echo $links;?>
				</div>
				<div class="vertical-line"></div>
			<?php endif;?>
			<div class="controls">
				<a role="button" aria-expanded="false" aria-controls="header-nav-control" aria-label="Toggle Menu" title="<?php echo __('Menu');?>" id="menu" href="#navigation-target" class="button icon-only"><?php echo mh_icon('search');?> <?php echo mh_icon('menu');?></a>	
			</div>
		</div>
	</nav>
</div>
<?php
}

/*
** Sanitize user-input to prevent bad control character messages
*/	
function mh_json_plaintextify($text=null){
	return $text ? trim(addslashes(preg_replace( "/\r|\n/", " ",strip_tags( $text )))) : null;
}

/*
** Single Tour JSON
*/
function mh_get_tour_json($tour=null){
			
	if($tour){
		$tourItems=array();
		foreach($tour->Items as $item){
			$location = get_db()->getTable( 'Location' )->findLocationByItem( $item, true );
			$address = ( element_exists('Item Type Metadata','Street Address') ) 
				? metadata( $item, array( 'Item Type Metadata','Street Address' ))
				: null;		
			$title=metadata( $item, array( 'Dublin Core', 'Title' ));
			if($location && $item->public){
				$tourItems[] = array(
					'id'		=> $item->id,
					'title'		=> mh_json_plaintextify($title),
					'address'	=> mh_json_plaintextify($address),
					'latitude'	=> $location[ 'latitude' ],
					'longitude'	=> $location[ 'longitude' ],
					);
				}
		}
		$tourMetadata = array(
			'id' => $tour->id,
			'items' => $tourItems,
		);
		return json_encode($tourMetadata);
	}	
}


/*
** Single Item JSON	
*/
function mh_get_item_json($item=null){
			
	if($item){
		$location = get_db()->getTable( 'Location' )->findLocationByItem( $item, true );
		$address = ( element_exists('Item Type Metadata','Street Address') ) 
			? metadata( $item, array( 'Item Type Metadata','Street Address' ))
			: null;		
		$title=metadata( $item, array( 'Dublin Core', 'Title' ));
		$accessinfo= ( element_exists('Item Type Metadata','Access Information') && metadata($item, array('Item Type Metadata','Access Information')) ) ? true : false;
		if(metadata($item, 'has thumbnail')){
			$thumbnail = (preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', item_image('square_thumbnail'), $result)) ? array_pop($result) : null;
		}else{ 
			$thumbnail=''; 
		}
		if($location){
			$itemMetadata = array(
				'id'          => $item->id,
				'featured'    => $item->featured,
				'latitude'    => $location[ 'latitude' ],
				'longitude'   => $location[ 'longitude' ],
				'title'       => mh_json_plaintextify($title),
				'address'	  => mh_json_plaintextify($address),
				'accessinfo'  => $accessinfo,
				'thumbnail'   => $thumbnail,
			);
			return json_encode($itemMetadata);
		}else{
			return false;
		}
	}
}

/*
** Add the map actions toolbar
*/
function mh_map_actions($item=null,$tour=null,$collection=null,$saddr='current',$coords=null){
	
		$street_address=null;

		if($item!==null){

			// get the destination coordinates for the item
			$location = get_db()->getTable('Location')->findLocationByItem($item, true);
			$coords=$location[ 'latitude' ].','.$location[ 'longitude' ];
			$street_address=mh_street_address($item);

			$showlink=true;
		
		}elseif($tour!==null){

			// get the waypoint coordinates for the tour
			$coords = array();
			foreach( $tour->Items as $item ){
				set_current_record( 'item', $item );
				$location = get_db()->getTable('Location')->findLocationByItem($item, true);
				$coords[] = mh_street_address($item) ? urlencode(strip_tags(mh_street_address($item))) : $location['latitude'].','.$location['longitude'];
			}

			$daddr=end($coords);
			reset($coords);
			$waypoints=array_pop($coords);		
			$waypoints=implode('+to:', $coords);
			$coords=$daddr.'+to:'.$waypoints;	

			$showlink=get_theme_option('show_tour_dir');
		}
	
	?>
	
	<div id="map-actions-anchor" class="map-actions flex">

		<!-- Directions link -->
		<?php if( $showlink && $coords && ($item || $tour) ):?>
				<a onclick="jQuery(\'body\').removeClass(\'fullscreen-map\')" class="directions" title="<?php echo __('Get Directions on Google Maps');?>" target="_blank" rel="noopener" href="https://maps.google.com/maps?saddr=<?php echo $saddr;?>+location&daddr=<?php echo $street_address ? urlencode(strip_tags($street_address)) : $coords;?>">
				<i class="fa fa-lg fa-external-link-square" aria-hidden="true"></i> <span class="label"><?php echo __('Get Directions');?></span>
		</a>
		<?php endif;?>

	</div>

	<?php	
}

/*
** Modified search form
** Adds HTML "placeholder" attribute
** Adds HTML "type" attribute
** Includes settings for simple and advanced search via theme options
*/

function mh_simple_search($inputID='search',$formProperties=array(),$ariaLabel="Search"){
	
	$sitewide = (get_theme_option('use_sitewide_search') == 1) ? 1 : 0;	
	$qname = ($sitewide==1) ? 'query' : 'search';
	$searchUri = ($sitewide==1) ? url('search') : url('items/browse?sort_field=relevance');
	$placeholder =  __('Search');	
	$default_record_types = unserialize(get_option('search_record_types'));

	$searchQuery = array_key_exists($qname, $_GET) ? $_GET[$qname] : '';
	$formProperties['action'] = $searchUri;
	$formProperties['method'] = 'get';
	
	$html = '<h4>'.__('Search').'</h4>';
	$html .= '<form ' . tag_attributes($formProperties) . '>' . "\n";
	$html .= '<fieldset>' . "\n\n";
	$html .= get_view()->formText('search', $searchQuery, array('aria-label'=>$ariaLabel,'name'=>$qname,'id'=>$inputID,'class'=>'textinput search','placeholder'=>$placeholder));
	$html .= '</fieldset>' . "\n\n";

	// add hidden fields for the get parameters passed in uri
	$parsedUri = parse_url($searchUri);
	if (array_key_exists('query', $parsedUri)) {
		parse_str($parsedUri['query'], $getParams);
		foreach($getParams as $getParamName => $getParamValue) {
			$html .= get_view()->formHidden($getParamName, $getParamValue,array('id'=>$inputID.'-'.$getParamValue));
		}
	}
	if($sitewide==1 && count($default_record_types)){
		foreach($default_record_types as $drt){
			$html .= get_view()->formHidden('record_types[]', $drt,array('id'=>$inputID.'-'.$drt));
		}
	}
	$html .= '<input type="submit" class="submit" name="submit_'.$inputID.'" id="submit_search_advanced_'.$inputID.'" value="'.__('Submit').'">';
	
	$html .= '</form>';
	return $html;	
	
}


/*
** Get plugin-validated app store URLs 
*/
function getAppStoreUrl($platform){
	if(!plugin_is_active('Curatescape')) return null;
	if(
		$platform == 'ios' && 
		$string = option('curatescape_app_ios')
	) {
		return appStoreValidURL($string);
	}
	if(
		$platform == 'android' && 
		$string = option('curatescape_app_android')
	) {
		return playStoreValidURL($string);
	}
	return null;
}
/*
** App Store button links
*/
function mh_appstore_downloads($apps=array()){
	if(!get_theme_option('enable_app_links')){
		return null;
	}
	if($href=getAppStoreUrl('ios')){
		$apps[]='<a class="button icon appstore ios" href="'.$href.'" target="_blank" rel="noopener">'.mh_icon('appstore').__('App Store').'</a>';
	}
	if($href=getAppStoreUrl('android')){
		$apps[]='<a class="button icon appstore android" href="'.$href.'" target="_blank" rel="noopener">'.mh_icon('googleplay').__('Google Play').'</a>';
	}
	if(count($apps) > 1){
		return '<h4>'.__('Download the App').'</h4><div class="downloads">'.implode(' ', $apps).'</div>';
	}
}


/*
** App Store links in footer
*/
function mh_appstore_footer(){
	if (get_theme_option('enable_app_links')){
		echo '<div id="app-store-links">';

		$ios_app_id = get_theme_option('ios_app_id');
		$android_app_id = get_theme_option('android_app_id');
		if (($ios_app_id != false) && ($android_app_id == false)) {
			echo '<a id="apple-text-link" class="app-store-footer" href="https://itunes.apple.com/us/app/'.$ios_app_id.'">'.__('Get the app for iPhone').'</a>';
		}
		elseif (($ios_app_id == false) && ($android_app_id != false)) {
			echo '<a id="android-text-link" class="app-store-footer" href="http://play.google.com/store/apps/details?id='.$android_app_id.'">'.__('Get the app for Android').'</a>';

		}
		elseif (($ios_app_id != false)&&($android_app_id != false)) {
			$iphone='<a id="apple-text-link" class="app-store-footer" href="https://itunes.apple.com/us/app/'.$ios_app_id.'">'.__('iPhone').'</a>';
			$android='<a id="android-text-link" class="app-store-footer" href="http://play.google.com/store/apps/details?id='.$android_app_id.'">'.__('Android').'</a>';
			echo __('Get the app for %1$s and %2$s', $iphone, $android);
		}
		else{
			echo __('iPhone + Android Apps Coming Soon!');
		}
		echo '</div>';
	}
}


/*
** Replace BR tags, wrapping text in P tags instead
*/
function replace_br($data) {
	$data = preg_replace('#(?:<br\s*/?>\s*?){2,}#', '</p><p>', $data);
	return "<p>$data</p>";
}

/*
** primary item text  
*/

function mh_the_text($item='item',$options=array()){
	
	$dc_desc = metadata($item, array('Dublin Core', 'Description'),$options);
	$primary_text = element_exists('Item Type Metadata','Story') ? metadata($item,array('Item Type Metadata', 'Story'),$options) : null;
	
	return $primary_text ? replace_br($primary_text) : ($dc_desc ? replace_br($dc_desc) : null);
}

/*
** Title
*/
function mh_the_title($item='item'){
	return '<h1 class="title">'.strip_tags(metadata($item, array('Dublin Core', 'Title')), array('index'=>0)).'</h1>';
}


/*
** Subtitle 
*/

function mh_the_subtitle($item='item'){

	$dc_title2 = metadata($item, array('Dublin Core', 'Title'), array('index'=>1));
	$subtitle=element_exists('Item Type Metadata','Subtitle') ? metadata($item,array('Item Type Metadata', 'Subtitle')) : null;
	
	return  $subtitle ? '<h2 class="subtitle">'.$subtitle.'</h2>' : ($dc_title2!=='[Untitled]' ? '<h2 class="subtitle">'.$dc_title2.'</h2>' : null);
}

/*
** Lede  
*/
function mh_the_lede($item='item'){
	if (element_exists('Item Type Metadata','Lede')){
		$lede=metadata($item,array('Item Type Metadata', 'Lede'));
		return  $lede ? '<div class="lede">'.strip_tags($lede,'<a><em><i><u><b><strong><strike>').'</div>' : null;
	}
}

/*
** Title + Subtitle (for search/browse/home)
*/
function mh_the_title_link($item='item'){
	$title=strip_tags(metadata($item, array('Dublin Core', 'Title')));
	return link_to($item,'show',$title, array('class'=>'permalink'));
}

/*
** sponsor for use in item byline 
*/
function mh_the_sponsor($item='item'){

	if (element_exists('Item Type Metadata','Sponsor')){
		$sponsor=metadata($item,array('Item Type Metadata','Sponsor'));
		return $sponsor ? '<span class="sponsor"> '.__('with research support from %s', $sponsor).'</span>' : null;	
	} 
	
}

/*
** Display subjects as tags
*/
function mh_subjects(){
	$subjects = metadata($item,array('Dublin Core', 'Subject'), 'all');
	if (count($subjects) > 0){
		$html = '<div class="subjects">';
			$html.= '<h3>'.__('Subjects').'</h3>';
			$html.= '<ul>';
			foreach ($subjects as $subject){
				$html.= '<li>'.$subject.'</li> ';
			}
			$html.= '</ul>';
		$html .= '</div>';
		return $html;

	}
}
/*
** Display subjects as single line of links
*/
function mh_subjects_string(){
	$subjects = metadata($item,array('Dublin Core', 'Subject'), 'all');
	if (count($subjects) > 0){
		$html=array();

		foreach ($subjects as $subject){
			$link = WEB_ROOT;
			$link .= htmlentities('/items/browse?term=');
			$link .= rawurlencode($subject);
			$link .= htmlentities('&search=&advanced[0][element_id]=49&advanced[0][type]=contains&advanced[0][terms]=');
			$link .= urlencode(str_replace('&amp;','&',$subject));
			$html[]= '<a href="'.$link.'">'.$subject.'</a>';
		}

		return '<div class="subjects"><span>'.__('Subjects: ').'</span>'.implode(", ", $html).'</div>';
	}
}

/*
** Display the item tags
*/
function mh_tags(){
	if (metadata($item,'has tags')){
		$html  = '<div class="tags">';
		$html .= '<h3>'.__('Tags').'</h3>';
		$html .= tag_cloud('item','items/browse');
		$html .= '</div>';
		return $html;
	}
}

/*
** Display the official website
*/
function mh_official_website($item='item'){

	if (element_exists('Item Type Metadata','Official Website')){
		$website=metadata($item,array('Item Type Metadata','Official Website'));
		return $website ? '<h3>'.__('Official Website').'</h3><div>'.$website.'</div>' : null;	
	} 

}

/*
** Display the street address
*/
function mh_street_address($item='item'){

	if (element_exists('Item Type Metadata','Street Address')){
		$address=metadata($item,array('Item Type Metadata','Street Address'));
		$map_link= $address ? '<a target="_blank" rel="noopener" href="https://maps.google.com/maps?saddr=current+location&daddr='.urlencode(strip_tags($address)).'">map</a>' : null;
		return $address ? $address : null;	
	}else{
		return null;
	} 

}

/*
** Display the access info  
*/
function mh_access_information($item='item',$formatted=true){
	if (element_exists('Item Type Metadata','Access Information')){
		$access_info=metadata($item,array('Item Type Metadata', 'Access Information'));
		return  $access_info ? ($formatted ? '<div class="access-information"><h3>'.__('Access Information').'</h3><div>'.$access_info.'</div></div>' : $access_info) : null;
	}else{
		return null;
	}
		
}

/*
** Display the map caption
*/

function mh_map_caption($item='item'){
	$caption=array();
	if($addr=mh_street_address($item)) $caption[]=strip_tags($addr,'<a>');
	if($accs=mh_access_information($item,false)) $caption[]=strip_tags($accs,'<a>');
	return implode( ' ~ ', $caption );
}

/*
** Display the factoid
*/
function mh_factoid($item='item'){

	if (element_exists('Item Type Metadata','Factoid')){
		$factoids=metadata($item,array('Item Type Metadata','Factoid'),array('all'=>true));
		if($factoids){
			$html=null;
			$tw1script=null;
			$tw2script=null;
			$tweetable=get_theme_option('tweetable_factoids');
			if($tweetable){
				$tw1script='<script async defer src="https://platform.twitter.com/widgets.js"></script>';
				$tw2script="<script async defer>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
			}
			$via=get_theme_option('twitter_username') ? 'data-via="'.get_theme_option('twitter_username').'"' : '';
			foreach($factoids as $factoid){
				$html.='<div class="factoid flex"><span>'.$factoid.'</span>'.($tweetable ? '<span><a href="https://twitter.com/share" class="twitter-share-button"{count} data-text="'.strip_tags($factoid).'"'.$via.'">Tweet this factoid</a></span>' : '').'</div>';
			}
			
			if($html){
				return $tw1script.'<aside id="factoid">'.'<h2 hidden class="hidden">Factoids</h2>'.$html.'</aside>'.$tw2script;				
			}
		}
	} 

}

/*
** Display related links
*/
function mh_related_links(){
	$dc_relations_field = metadata($item,array('Dublin Core', 'Relation'), array('all' => true));
	
	$related_resources = element_exists('Item Type Metadata','Related Resources') ? metadata($item,array('Item Type Metadata', 'Related Resources'), array('all' => true)) : null;
	
	$relations = $related_resources ? $related_resources : $dc_relations_field;
	
	if ($relations){
		$html= '<h3>'.__('Related Sources').'</h3><div class="related-resources"><ul>';
		foreach ($relations as $relation) {
			$html.= '<li>'.strip_tags($relation,'<a><i><cite><em><b><strong>').'</li>';
		}
		$html.= '</ul></div>';
		return $html;
	}
}


/*
** Author Byline
*/
function mh_the_byline($itemObj='item',$include_sponsor=false){
	if ((get_theme_option('show_author') == true)){
		$html='<div class="byline">'.__('By').' ';
		if(metadata($itemObj,array('Dublin Core', 'Creator'))){
			$authors=metadata($itemObj,array('Dublin Core', 'Creator'), array('all'=>true));
			$total=count($authors);
			$index=1;
			foreach ($authors as $author){
				switch ($index){
				case ($total):
					$delim ='';
					break;

				case ($total-1):
					$delim =' <span class="amp">&amp;</span> ';
					break;

				default:
					$delim =', ';
					break;
				}
				$html .= $author.$delim;
				$index++;
			}
		}else{
			$html .= __('The %s team', option('site_title'));
		}
		
		$html .= (($include_sponsor) && (mh_the_sponsor($itemObj)!==null ))? ''.mh_the_sponsor($itemObj) : null;
		
		$html .='</div>';
		return $html;
	}
}


/*
** Custom item citation
*/
function mh_item_citation(){
	return '<div class="item-citation"><h3>'.__('Cite this Page').'</h3><div>'.html_entity_decode(metadata($item, 'citation')).'</div></div>';
}

/*
** Post Added/Modified String
*/
function mh_post_date(){

	if(get_theme_option('show_datestamp')==1){
		$a=format_date(metadata($item, 'added'));
		$m=format_date(metadata($item, 'modified'));	
	
		return '<div class="item-post-date"><em>'.__('Published on %s.', $a ).( ($a!==$m) ? ' '.__('Last updated on %s.', $m ) : null ).'</em></div>';	
	}
}

/*
** Build caption from description, source, creator, date
*/
function mh_file_caption($file,$inlineTitle=true){

	$caption=array();

	if( $inlineTitle !== false ){
		$title = metadata( $file, array( 'Dublin Core', 'Title' ) ) ? '<span class="title">'.metadata( $file, array( 'Dublin Core', 'Title' ) ).'</span>' : null;
	}

	$description = metadata( $file, array( 'Dublin Core', 'Description' ) );
	if( $description ) {
		$caption[]= $description;
	}

	$source = metadata( $file, array( 'Dublin Core', 'Source' ) );
	if( $source ) {
		$caption[]= __('Source: %s',$source);
	}


	$creator = metadata( $file, array( 'Dublin Core', 'Creator' ) );
	if( $creator ) {
		$caption[]= __('Creator: %s', $creator);
	}

	$date = metadata( $file, array( 'Dublin Core', 'Date' ) );
	if( $date ) {
		$caption[]= __('Date: %s', $date);
	}

	if( count($caption) ){
		return ($inlineTitle ? $title.': ' : null).implode(" ~ ", $caption);
	}else{
		return $inlineTitle ? $title : null;
	}
}


/*
** Loop through and display image files
*/
function mh_item_images($item,$index=0){
	$html=null;
	$captionID=1;
	foreach (loop('files', $item->Files) as $file){
		$img = array('image/jpeg','image/jpg','image/png','image/jpeg','image/gif');
		$mime = metadata($file,'MIME Type');
		if(in_array($mime,$img)) {
			$title=metadata($file, array('Dublin Core', 'Title')) ? metadata($file, array('Dublin Core', 'Title')) : 'Untitled';
			$title_formatted=link_to($file,'show','<strong>'.$title.'</strong>',array('title'=>'View File Record'));
			$desc=metadata($file, array('Dublin Core', 'Description'));
			$caption=$title_formatted.($desc ? ': ' : ' ~ ').mh_file_caption($file,false);
			$src=WEB_ROOT.'/files/fullsize/'.str_replace( array('.JPG','.jpeg','.JPEG','.png','.PNG','.gif','.GIF'), '.jpg', $file->filename );
			$html.= '<figure class="flex-image" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" aria-label="'.$title.'" aria-describedby="caption'.$captionID.'">';
				$html.= '<a href="'.$src.'" title="'.$title.'" class="file flex" style="background-image: url(\''.$src.'\');" data-size=""></a>';
				$html.= '<figcaption id="caption'.$captionID.'" hidden class="hidden;">'.strip_tags($caption,'<a><u><strong><em><i><cite>').'</figcaption>';
			$html.= '</figure>';
			$captionID++;
		}
	}
	if($html): ?>
		<h3><?php echo __('Images');?></h3>
		<figure id="item-photos" class="flex flex-wrap" itemscope itemtype="http://schema.org/ImageGallery">
			<?php echo $html;?>
		</figure>		
		<!-- PhotoSwipe -->
		<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="pswp__bg"></div>
			<div class="pswp__scroll-wrap">
			<div class="pswp__container">
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
			</div>
			<div class="pswp__ui pswp__ui--hidden">
				<div class="pswp__top-bar">
					<div class="pswp__counter"></div>
					<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
					<button class="pswp__button pswp__button--share" title="Share"></button>
					<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
					<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
					<div class="pswp__preloader">
						<div class="pswp__preloader__icn">
							<div class="pswp__preloader__cut">
								<div class="pswp__preloader__donut"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip"></div> 
				</div>
				<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
				<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
				<div class="pswp__caption">
					<div class="pswp__caption__center"></div>
				</div>
			</div>
		</div>
	</div>
	<?php endif;
}


/*
** Loop through and display audio files
*/
function mh_audio_files($item,$index=0){
	if (!$item){
		$item=set_loop_records('files',$item);
	}
	$html=null;
	$audioTypes = array('audio/mpeg');
	foreach (loop('files', $item->Files) as $file){
		$mime = metadata($file,'MIME Type');
		if ( array_search($mime, $audioTypes) !== false ){
			$audioTitle = metadata($file,array('Dublin Core','Title')) ? metadata($file,array('Dublin Core','Title')) : 'Audio File '.($index+1);
			$audioDesc = strip_tags(mh_file_caption($file,false));
			$html.='<div class="flex media-select" data-source="'.WEB_ROOT.'/files/original/'.$file->filename.'" role="button" aria-label="click to play '.$audioTitle.'" tabindex="0">';
				$html.='<div class="media-thumb"><i class="fa fa-lg fa-microphone media-icon" aria-hidden="true"></i></div>';
				$html.='<div class="media-caption">';
					$html.='<div class="media-title">'.$audioTitle.'</div>';
					//$html.='<strong>Duration</strong>: <span class="duration">00:00:00</span><br>';
					$html.=snippet($audioDesc,0,250,"...");
				$html.='</div>';
			$html.='</div>';
			$html.=link_to($file,'show',__('View File Record'));
		}
	};
	if($html): ?>
		<h3><?php echo __('Audio');?></h3>
		<figure id="item-audio">	
			<div class="media-container audio">
				<audio id="curatescape-player-audio" class="video-js" controls preload="auto">
					<p class="media-no-js">To listen to this audio please enable JavaScript, and consider upgrading to a web browser that supports HTML5 audio</p>
				</audio>
				<div class="flex media-list audio">
					<?php echo $html;?>		
				</div>
			</div>
		</figure>	
		<script>
		jQuery(document).ready(function($) {
			var audioplayer = $('#curatescape-player-audio');
			var src=$('.media-list.audio .media-select:first-child').attr('data-source');
			audioplayer.html(
				'<source src="'+src+'" type="audio/mp3"></source>'
			)
			if(typeof audioplayer == 'object'){
				$('.media-list.audio .media-select:first-child').addClass('now-playing');	
				
				$('.media-list.audio .media-select').on('click keydown',function(e){	
					if(( e.type == 'click' ) || ( e.type == 'keydown' && e.which  == 13 )){
						var newsrc=$(this).attr('data-source');
						$('.media-list.audio .now-playing').removeClass('now-playing');
						$(this).addClass('now-playing');
						audioplayer.html(
							'<source src="'+newsrc+'" type="audio/mp3"></source>'
						);
						audioplayer.get(0).load();
						audioplayer.get(0).play();
					}
				});
				
			}
		});		
		</script>
	<?php endif;
}


/*
** Loop through and display video files
** Please use H.264 video format
** We accept multiple H.264-related MIME-types because Omeka MIME detection is sometimes spotty
** But in the end, we always tell the browser they're looking at "video/mp4"
*/
function mh_video_files($item='item',$html=null) {

	$videoTypes = array('video/mp4','video/mpeg','video/quicktime');
	foreach (loop('files', $item->Files) as $file){
		$videoMime = metadata($file,'MIME Type');
		if ( in_array($videoMime,$videoTypes) ){
			$videoTitle = metadata($file,array('Dublin Core','Title')) ? metadata($file,array('Dublin Core','Title')) : 'Video File '.($videoIndex+1);
			$videoDesc = strip_tags(mh_file_caption($file,false));
			$html.='<div class="flex media-select" data-source="'.WEB_ROOT.'/files/original/'.$file->filename.'" role="button" aria-label="click to play '.$videoTitle.'" tabindex="0">';
				$html.='<div class="media-thumb"><i class="fa fa-lg fa-film media-icon" aria-hidden="true"></i></div>';
				$html.='<div class="media-caption">';
					$html.='<div class="media-title">'.$videoTitle.'</div>';
					//$html.='<strong>Duration</strong>: <span class="duration">00:00:00</span><br>';
					$html.=snippet($videoDesc,0,250,"...");
				$html.='</div>';
			$html.='</div>';
			$html.=link_to($file,'show',__('View File Record'));

		}
	}
	if($html): ?>
		<h3><?php echo __('Video');?></h3>
		<figure id="item-video">
			<div class="media-container video">		
				<video id="curatescape-player" playsinline controls preload="auto">
					<p class="media-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video</p>
				</video>
				<div class="flex media-list video">
					<?php echo $html;?>
				</div>
			</div>
		</figure>
		<script>
		jQuery(document).ready(function($) {
			var videoplayer = $('#curatescape-player');
			var src=$('.media-list.video .media-select:first-child').attr('data-source');
			videoplayer.html(
				'<source src="'+src+'" type="video/mp4"></source>'
			)
			if(typeof videoplayer == 'object'){
				$('.media-list.video .media-select:first-child').addClass('now-playing');
				
				$('.media-list.video .media-select').on('click keydown',function(e){
					if(( e.type == 'click' ) || ( e.type == 'keydown' && e.which  == 13 )){
						var newsrc=$(this).attr('data-source');
						$('.media-list.video .now-playing').removeClass('now-playing');
						$(this).addClass('now-playing');
						videoplayer.html(
							'<source src="'+newsrc+'" type="video/mp4"></source>'
						);
						videoplayer.get(0).load();
						videoplayer.get(0).play();
					}
				});
			}
		});
		</script>
	<?php endif;
}

/*
** loop through and display DOCUMENT files other than the supported audio, video, and image types
*/
function mh_document_files($item='item',$html=null){
	
	$blacklist=array('image/jpeg','image/jpg','image/png','image/jpeg','image/gif','video/mp4','video/mpeg','video/quicktime','audio/mpeg');
	foreach (loop('files', $item->Files) as $file){
		$documentMime = metadata($file,'MIME Type');
		if ( !in_array($documentMime,$blacklist) ){	
			
			$title = metadata($file,array('Dublin Core','Title')) ? metadata($file,array('Dublin Core','Title')) : $file->original_filename;
			$extension=pathinfo($file->getWebPath('original'), PATHINFO_EXTENSION);
			$size=formatSizeUnits($file->size);
			$download=$file->getWebPath('original');
			
			$html .= '<tr>';
			$html .= '<td class="title"><a href="/files/show/'.$file->id.'">'.$title.'</a></td>';
			$html .= '<td class="info"><span>'.$extension.'</span> / '.$size.'</td>';
			$html .= '<td class="download"><a class="button" target="_blank" title="Download" href="'.$download.'"><i class="fa fa-download" aria-hidden="true"></i> <span>Download</span></a></td>';
			$html .= '</tr>';
		}

	}	
	if($html){
		echo '<h3>'.__('Documents').'</h3>';
		echo '<figure id="item-documents">';
		echo '<table><tbody><tr><th>Name</th><th>Info</th><th>Actions</th></tr>'.$html.'</tbody></table>';
		echo '</figure>';
	}
	
}
/*
** display single file in FILE TEMPLATE
*/

function mh_single_file_show($file=null){
		$html=null;
		$mime = metadata($file,'MIME Type');
		$img = array('image/jpeg','image/jpg','image/png','image/jpeg','image/gif');
		$audioTypes = array('audio/mpeg');
		$videoTypes = array('video/mp4','video/mpeg','video/quicktime');

		// SINGLE AUDIO FILE
		if ( array_search($mime, $audioTypes) !== false ){

			?>
			<figure id="item-audio">	
				<div class="media-container audio">
					<audio src="<?php echo file_display_url($file,'original');?>" id="curatescape-player-audio" class="video-js" controls preload="auto">
						<p class="media-no-js">To listen to this audio please consider upgrading to a web browser that supports HTML5 audio</p>
					</audio>
				</div>
			</figure>
			<?php

		// SINGLE VIDEO FILE	
		}elseif(array_search($mime, $videoTypes) !== false){
			$videoTypes = array('video/mp4','video/mpeg','video/quicktime');
			$videoFile = file_display_url($file,'original');
			$videoTitle = metadata($file,array('Dublin Core', 'Title'));
			$videoDesc = mh_file_caption($file,false);
			$videoTitle = metadata($file,array('Dublin Core','Title'));
			$embeddable=embeddableVersion($file,$videoTitle,$videoDesc,array('Dublin Core','Relation'),false);
			if($embeddable){
				// If a video has an embeddable streaming version, use it.
				$html.= $embeddable;
			}else{

				$html .= '<div class="item-file-container">';
				$html .= '<video width="725" height="410" controls preload="auto" data-setup="{}">';
				$html .= '<source src="'.$videoFile.'" type="video/mp4">';
				$html .= '<p class="media-no-js">To listen to this audio please consider upgrading to a web browser that supports HTML5 video</p>';
				$html .= '</video>';

			}	

			return $html;

		// SINGLE IMAGE OR OTHER FILE	
		}else{
			return file_markup($file, array('imageSize'=>'fullsize'));
		}
}
/*
** display additional (non-core) file metadata in FILE TEMPLATE
*/
function mh_file_metadata_additional($file='file',$html=null){
	$fields = all_element_texts($file,array('return_type'=>'array','show_element_sets'=>'Dublin Core')); 

	if($fields['Dublin Core']){

		// Omit Primary DC Fields
		$dc = array_filter($fields['Dublin Core'],function($key){
			$omit=array('Description','Title','Creator','Date','Rights','Source');
			return !(in_array($key, $omit));
		},ARRAY_FILTER_USE_KEY); 

		// Output
		foreach($dc as $dcname=>$values){
			$html.='<div class="additional-element">';
			$html.='<h4 class="additional-element-name">'.$dcname.'</h4>';
			$html.='<div class="additional-element-value-container">';
			foreach($values as $value){
				$html.='<div class="additional-element-value">'.$value.'</div>';
			}
			$html.='</div>';
			$html.='</div>';
		}
	}

	if($html){
		echo '<h3>'.__('Additional Information').'</h3>';
		echo '<div class="additional-elements">'.$html.'</div>';
	}
} 

/*
** Checks file metadata record for embeddable version of video file
** Because YouTube and Vimeo have better compression, etc.
** returns string $html | false
*/
function embeddableVersion($file,$title=null,$desc=null,$field=array('Dublin Core','Relation'),$caption=true){

	$youtube= (strpos(metadata($file,$field), 'youtube.com')) ? metadata($file,$field) : false;
	$youtube_shortlink= (strpos(metadata($file,$field), 'youtu.be')) ? metadata($file,$field) : false;
	$vimeo= (strpos(metadata($file,$field), 'vimeo.com')) ? metadata($file,$field) : false;

	if($youtube) {
		// assumes YouTube links look like https://www.youtube.com/watch?v=NW03FB274jg where the v query contains the video identifier
		$url=parse_url($youtube);
		$id=str_replace('v=','',$url['query']);
		$html= '<div class="embed-container youtube" id="v-streaming" style="position: relative;padding-bottom: 56.25%;height: 0; overflow: hidden;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="//www.youtube.com/embed/'.$id.'" frameborder="0" width="725" height="410" allowfullscreen></iframe></div>';
		if($caption==true){
			$html .= ($title) ? '<h4 class="title video-title sib">'.$title.' <span class="icon-info-sign" aria-hidden="true"></span></h4>' : '';
			$html .= ($desc) ? '<p class="description video-description sib">'.$desc.link_to($file,'show', '<span class="view-file-link"><span class="icon-file" aria-hidden="true"></span> '.__('View File Details Page').'</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';
		}
		return '<div class="item-file-container">'.$html.'</div>';
	}
	elseif($youtube_shortlink) {
		// assumes YouTube links look like https://www.youtu.be/NW03FB274jg where the path string contains the video identifier
		$url=parse_url($youtube_shortlink);
		$id=$url['path'];
		$html= '<div class="embed-container youtube" id="v-streaming" style="position: relative;padding-bottom: 56.25%;height: 0; overflow: hidden;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="//www.youtube.com/embed/'.$id.'" frameborder="0" width="725" height="410" allowfullscreen></iframe></div>';
		if($caption==true){
			$html .= ($title) ? '<h4 class="title video-title sib">'.$title.' <span class="icon-info-sign" aria-hidden="true"></span></h4>' : '';
			$html .= ($desc) ? '<p class="description video-description sib">'.$desc.link_to($file,'show', '<span class="view-file-link"><span class="icon-file" aria-hidden="true"></span> '.__('View File Details Page').'</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';
		}
		return '<div class="item-file-container">'.$html.'</div>';
	}
	elseif($vimeo) {
		// assumes the Vimeo links look like http://vimeo.com/78254514 where the path string contains the video identifier
		$url=parse_url($vimeo);
		$id=$url['path'];
		$html= '<div class="embed-container vimeo" id="v-streaming" style="padding-top:0; height: 0; padding-top: 25px; padding-bottom: 67.5%; margin-bottom: 10px; position: relative; overflow: hidden;"><iframe style=" top: 0; left: 0; width: 100%; height: 100%; position: absolute;" src="//player.vimeo.com/video'.$id.'?color=222" width="725" height="410" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
		if($caption==true){
			$html .= ($title) ? '<h4 class="title video-title sib">'.$title.' <span class="icon-info-sign" aria-hidden="true"></span></h4>' : '';
			$html .= ($desc) ? '<p class="description video-description sib">'.$desc.link_to($file,'show', '<span class="view-file-link"><span class="icon-file" aria-hidden="true"></span> '.__('View File Details Page').'</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';
		}
		return '<div class="item-file-container">'.$html.'</div>';
	}
	else{
		return false;
	}
}


/*
** Display the social sharing widgets
** @TODO
*/
function mh_share_this($type='Page'){
	if(get_theme_option('add_this_buttons')==1){
		$addThisAnalytics = get_theme_option('add_this_analytics');
		$html = '<aside id="share-this"><h3>'.__('Share this %s',$type).'</h3>';
		$html .= '<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
				<a class="addthis_button_twitter"></a>
				<a class="addthis_button_facebook"></a>
				<a class="addthis_button_pinterest_share"></a>
				<a class="addthis_button_email"></a>
				<a class="addthis_button_compact"></a>
				</div></aside>
				
				<script>
				jQuery(document).ready(function(){
					loadJS("//s7.addthis.com/js/300/addthis_widget.js#async=1",function(){
						console.log("Add This ready...");
						var addthis_config = addthis_config||{};
						addthis_config.pubid = "'.get_theme_option('add_this_analytics').'";
						addthis.init();	
					});
				});
				</script>	
				<!-- AddThis Button END -->';
		return $html;
	}
}

/*
** DISQUS COMMENTS
** disqus.com
*/
function mh_disquss_comments($shortname){
	if ($shortname){
	?>

	<div id="disqus_thread">
	  <a class="load-comments" title="Click to load the comments section" href="#" onclick="disqus();return false;">Show Comments</a> 
	</div>

	<script async defer>
		var disqus_shortname = "<?php echo $shortname;?>";
		var disqus_loaded = false;

		// This is the function that will load Disqus comments on demand
		function disqus() {

			if (!disqus_loaded)  {
				disqus_loaded = true;
				console.log("Disqus ready...");

				var e = document.createElement("script");
				e.type = "text/javascript";
				e.async = true;
				e.src = "//" + disqus_shortname + ".disqus.com/embed.js";
				(document.getElementsByTagName("head")[0] ||
				document.getElementsByTagName("body")[0])
				.appendChild(e);
		  }
		} 	
	</script>

	<?php
	}
}

/*
** INTENSE DEBATE COMMENTS
** intensedebate.com
*/	
function mh_intensedebate_comments($intensedebate_id){
	if ($intensedebate_id){ ?>
	    <div id="disqus_thread"></div>
	
		<script>
		var idcomments_acct = '<?php echo $intensedebate_id;?>';
		var idcomments_post_id;
		var idcomments_post_url;
		</script>
		<span id="IDCommentsPostTitle" style="display:none"></span>
		<script async defer src="https://www.intensedebate.com/js/genericCommentWrapperV2.js"></script>
		<?php
	}
}

/*
** DISPLAY COMMENTS
*/	
function mh_display_comments(){
	if(get_theme_option('comments_id')){
		return mh_disquss_comments(get_theme_option('comments_id'));
	}else if(get_theme_option('intensedebate_site_account')){
		return mh_intensedebate_comments(get_theme_option('intensedebate_site_account'));
	}else{
		return null;
	}
}

/*
** Get total tour items, omitting unpublished items unless logged in
*/
function mh_tour_total_items($tour){
	$i=0;
	foreach($tour->Items as $ti){
		if($ti->public || current_user()){
			$i++;
		}
	}
	return $i;
}

/*
** Display the Tours search results
*/
function mh_tour_preview($s){
	$html=null;
	$record=get_record_by_id($s['record_type'], $s['record_id']);
	set_current_record( 'tour', $record );
	$html.=  '<article>';
	$html.=  '<h3 class="tour-result-title"><a href="'.record_url($record, 'show').'">'.($s['title'] ? $s['title'] : '[Unknown]').'</a></h3>';
	$html.=  '<div class="tour-meta-browse browse-meta-top byline">';
	$html.= '<span class="total">'.mh_tour_total_items($record).' '.__('Locations').'</span> ~ ';
	if(metadata($tour,'Credits') ){
		$html.=  __('%1s curated by %2s', tourLabelString(),metadata($tour,'Credits') );
	}elseif(get_theme_option('show_author') == true){
		$html.=  __('%1s curated by The %2s Team',tourLabelString(),option('site_title'));
	}		
	$html.=  '</div>';
	$html.=  ($text=strip_tags(html_entity_decode(metadata($tour,'Description')))) ? '<span class="tour-result-snippet">'.snippet($text,0,300).'</span>' : null;
	if(get_theme_option('show_tour_item_thumbs') == true){
		$html.=  '<span class="tour-thumbs-container">';
		foreach($record->Items as $mini_thumb){
			$html.=  metadata($mini_thumb, 'has thumbnail') ? 
			'<div class="mini-thumb">'.item_image('square_thumbnail',array('height'=>'40','width'=>'40'),null,$mini_thumb).'</div>' : 
			null;
		}
		$html.=  '</span>';
	}
	$html.= '</article>';	
	return $html;
}	


/*
** Display the Tours list
*/
function mh_display_homepage_tours($scope='random', $num=5){
	if(!plugin_is_active('Curatescape')) return;
	// Get the database.
	$db = get_db();
	// Get the Tour table.
	$table = $db->getTable('CuratescapeTour');
	// Build the select query.
	$select = $table->getSelect();
	$select->where('public = 1');
	// Get total count
	$public = $table->fetchObjects($select);
	// Continue, get scope
	switch($scope){
		case 'random':
			$select->from(array(), 'RAND() as rand');
			break;
		case 'featured':
			$select->where('featured = 1');
			break;
	}
	// Fetch some items with our select.
	$items = $table->fetchObjects($select);
	$customheader=get_theme_option('tour_header');
	if($scope=='random'){
		shuffle($items);
		$heading = $customheader ? $customheader : __('Take a Tour');
	}else{
		$heading = $customheader ? $customheader : ucfirst($scope).' '.tourLabelString('plural');
	}
	$num = (count($items)<$num)? count($items) : $num;
	$html=null;
	$html .= '<h2 class="result-type-header">'.$heading.'</h2>';
	if($items){
		for ($i = 0; $i < $num; $i++) {
			set_current_record( 'tour', $items[$i] );
			$tour=getCurrentTour();		
			$html .= '<article class="item-result tour">';
			$html .= '<div class="tour-flex-container">';
				if ($tourImage = $tour->getFileCustom()){
					$html .= linkToTour($tour, 'show', $tourImage, array('class'=>'tour-image'));
				}
				$html .= '<div class="details">';
					$html .= '<h3>'.linkToTour($tour).'</h3>';
					if(metadata($tour, 'credits')){
						$byline= __('Curated by %s',metadata($tour, 'credits'));
					}else{
						$byline= __('Curated by The %s Team',option('site_title'));
					}
					$html .=  '<span class="total">'.__('%s Locations',mh_tour_total_items($tour)).'</span> ~ <span>'.$byline.'</span>';
					
					if ($tourDescription = metadata($tour, 'description', array('no_escape'=>true))){
						$html .= '<div class="description">'.snippet($tourDescription, 0, 500).'</div>';
					}
				$html .= '</div>';
			$html .= '</div>';
			$html .= '</article>';
		}
		if(count($public)>=1){
			$html .= '<a class="button button-primary view-more-link" href="'.WEB_ROOT.'/tours/browse/">'.__('Browse all <span>%s</span>', tourLabelString('plural')).'</a>';
		}	
	}else{
		$html .= '<p>'.__('No tours are available. Publish some now.').'</p>';
	}
	return $html;
}

function mh_hero_item($item){
	$itemTitle = mh_the_title_link($item);
	$itemDescription = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>300));
	$html=null;
	if (metadata($item, 'has thumbnail') ) {
		$img_markup=item_image('fullsize',array(),0, $item);
		preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $img_markup, $result);
		$img_url = array_pop($result);
		$html .= '<article class="featured-story-result">';
			$html .= '<div class="featured-decora-outer">' ;
				$html .= '<div class="featured-decora-bg" style="background-image:url('.$img_url.')">' ;
				$html .= '<div class="featured-decora-text"><div class="featured-decora-text-inner">';
					$html .= '<header><h3>' .$itemTitle. '</h3><span class="featured-item-author">'.mh_the_byline($item,false).'</span></header>';
					$html .= '<div class="item-description">' . $itemDescription ? $itemDescription : __('Preview text not available.') . '</div>';
				$html .= '</div></div>' ;
			$html .= '</div></div>' ;
		$html .= '</article>';
	}
	return $html;
}

/*
** Display random featured item(s)
*/
function mh_display_random_featured_item($withImage=false,$num=1)
{
	$featuredItems = get_random_featured_items($num,$withImage);
	$label = plugin_is_active('Curatescape') ? storyLabelString('plural') : __('Items');
	$html = '<h2 class="result-type-header">'.__('Featured %s',$label).'</h2>';
	if ($featuredItems) {
		foreach($featuredItems as $item):
			$html .=mh_hero_item($item);
		endforeach;	
		$html.='<a class="button button-primary view-more-link" href="/items/browse?featured=1">'.__('Browse Featured %s',$label).'</a>';
	}else {
		$html .= '<article class="featured-story-result none">';
		$html .= '<p>'.__('No featured items are available. Publish some now.').'</p>';
		$html .= '</article>';
	}
	return $html;
}

/*
** Display the customizable "About" content on homepage
*/
function mh_home_about($length=800,$html=null){
	$html .= '<h2 class="result-type-header">'.__('About').'</h2>';
	$html .= '<div class="about-text">';
		$html .= '<article>';
			
			$html .= '<header>';
				$html .= '<h3>'.option('site_title').'</h3>';
				$html .= '<span class="sponsor">'.__('A project by').' <span class="sponsor-name">'.mh_owner_link().'</span></span>';
			$html .= '</header>';
		
			$html .= '<div class="about-main"><p>';
				$html .= substr(mh_about(),0,$length);
				$html .= ($length < strlen(mh_about())) ? '... ' : null;
			$html .= '</p><a class="button button-primary view-more-link" href="'.url('about').'">'.__('Read more About Us').'</a></div>';
	
		$html .= '</article>';
	$html .= '</div>';

	return $html;
}

/*
** Display the customizable "Call to Action" content on homepage
*/
function mh_home_cta($html=null){
	
	$cta_title=get_theme_option('cta_title');
	$cta_text=get_theme_option('cta_text');
	$cta_img_src=get_theme_option('cta_img_src');
	$cta_button_label=get_theme_option('cta_button_label');
	$cta_button_url=get_theme_option('cta_button_url');
	$cta_button_url_target=get_theme_option('cta_button_url_target') ? ' target="_blank" rel="noreferrer noopener"' : null;
	
	if($cta_title && $cta_button_label && $cta_button_url){	
		$html .='<h2 class="result-type-header">'.$cta_title.'</h2>';
	
		$html .= '<div class="cta-inner">';
			$html .= '<article style="background-image:url(/files/theme_uploads/'.$cta_img_src.');">';
				if($cta_img_src){
					$html .= '<div class="cta-hero"></div>';
				}
				if($cta_text){
					$html .= '<div class="cta-description">';
					$html .= '<p>';
						$html .= $cta_text;
					$html .= '</p>';
					$html .= '<a class="button" href="'.$cta_button_url.'" '.$cta_button_url_target.'>'.$cta_button_label.'</a>';
					$html .= '</div>';
				}
			$html .= '</article>';
		$html .= '</div>';
	
		return $html;
	}
}

function mh_footer_cta($html=null){
	$footer_cta_button_label=get_theme_option('footer_cta_button_label');
	$footer_cta_button_url=get_theme_option('footer_cta_button_url');
	$footer_cta_button_target=get_theme_option('footer_cta_button_target') ? 'target="_blank" rel="noreferrer noopener"' : null;
	if($footer_cta_button_label && $footer_cta_button_url){
		$html.= '<div class="footer_cta"><a class="button" href="'.$footer_cta_button_url.'" '.$footer_cta_button_target.'>'.$footer_cta_button_label.'</a></div>';
	}
	return $html;
}

/*
** Tag cloud for homepage
*/
function mh_home_popular_tags($num=40){
	
	$tags=get_records('Tag',array('sort_field' => 'count', 'sort_dir' => 'd'),$num);
	$html = '<h2 class="result-type-header">'.__('Popular Tags').'</h2>';
	$html.=tag_cloud($tags,url('items/browse'));
	$html.='<a class="button button-primary view-more-link" href="/items/tags/">'.__('Browse %s tags',total_records('Tags')).'</a>';
	return $html;
	
}

/*
** Build an array of social media links (including icons) from theme settings
*/
function mh_social_array(){
	$services=array();
	($email=get_theme_option('contact_email') ? get_theme_option('contact_email') : get_option('administrator_email')) ? array_push($services,'<a target="_blank" rel="noopener" title="Email" href="mailto:'.$email.'" class="button icon-only email">'.mh_icon('mail').'</a>') : null;
	($instagram=get_theme_option('instagram_username')) ? array_push($services,'<a target="_blank" rel="noopener" title="Instagram" href="https://www.instagram.com/'.$instagram.'" class="button icon-only instagram">'.mh_icon('instagram').'</a>') : null;
	($facebook=get_theme_option('facebook_link')) ? array_push($services,'<a target="_blank" rel="noopener" title="Facebook" href="'.$facebook.'" class="button icon-only facebook">'.mh_icon('facebook').'</a>') : null;	
	($threads=get_theme_option('threads_link')) ? array_push($services,'<a target="_blank" rel="noopener" title="Threads" href="'.$threads.'" class="button icon-only threads">'.mh_icon('threads').'</a>') : null;
	($youtube=get_theme_option('youtube_username')) ? array_push($services,'<a target="_blank" rel="noopener" title="Youtube" href="'.$youtube.'" class="button icon-only youtube">'.mh_icon('youtube').'</a>') : null;
	($mastodon=get_theme_option('mastodon_link')) ? array_push($services,'<a target="_blank" rel="noopener" title="Mastodon" href="'.$mastodon.'" class="button icon-only mastodon">'.mh_icon('mastodon').'</a>') : null;
	($bluesky=get_theme_option('bluesky_link')) ? array_push($services,'<a target="_blank" rel="noopener" title="Bluesky" href="'.$bluesky.'" class="button icon-only bluesky">'.mh_icon('bluesky').'</a>') : null;	
	($twitter=get_theme_option('twitter_username')) ? array_push($services,'<a target="_blank" rel="noopener" title="X" href="https://x.com/'.$twitter.'" class="button icon-only twitter">'.mh_icon('x').'</a>') : null;	
	($pinterest=get_theme_option('pinterest_username')) ? array_push($services,'<a target="_blank" rel="noopener" title="Pinterest" href="https://www.pinterest.com/'.$pinterest.'" class="button icon-only pinterest">'.mh_icon('pinterest').'</a>') : null;
	($tumblr=get_theme_option('tumblr_link')) ? array_push($services,'<a target="_blank" rel="noopener" title="Tumblr" href="'.$tumblr.'" class="button icon-only tumblr">'.mh_icon('tumblr').'</a>') : null;
	($reddit=get_theme_option('reddit_link')) ? array_push($services,'<a target="_blank" rel="noopener" title="Reddit" href="'.$reddit.'" class="button icon-only reddit">'.mh_icon('reddit').'</a>') : null;

	

	if( ($total=count($services)) > 0 ){
		return $services;
	}else{
		return false;
	}
}

/*
** Build a series of social media link for the footer
*/
function mh_footer_find_us($class=null){
	if( $services=mh_social_array() ){
		return '<h4>'.__('Get in Touch').'</h4><div class="link-icons colored">'.implode(' ',$services).'</div>';
	}
}


/*
** Build a link for the footer copyright statement and credit line on homepage
*/
function mh_owner_link(){

	$fallback=(option('author')) ? option('author') : option('site_title');

	$authname=(get_theme_option('sponsor_name')) ? get_theme_option('sponsor_name') : $fallback;

	return $authname;
}


/*
** Build HTML content for homepage widget sections
** Each widget can be used ONLY ONCE
*/

function homepage_widget_1($content='items_featured'){
	$content = get_theme_option('widget_section_1') ? get_theme_option('widget_section_1') : null;
	return $content;
}

function homepage_widget_2($content='tours_random'){
	$content = get_theme_option('widget_section_2') ? get_theme_option('widget_section_2') : null;
	return $content;
}

function homepage_widget_3($content='popular_tags'){
	$content = get_theme_option('widget_section_3') ? get_theme_option('widget_section_3') : null;
	return $content;
}
function homepage_widget_4($content='about'){
	$content = get_theme_option('widget_section_4') ? get_theme_option('widget_section_4') : null;
	return $content;
}

function homepage_widget_sections($html = null){
		$recent_or_random=0; 
		$tours=0;
		$featured=0;
		$popular_tags=0;
		$about=0;
		$meta=0;
		$cta=0;
		foreach(array_unique(array(
			homepage_widget_1(),
			homepage_widget_2(),
			homepage_widget_3(),
			homepage_widget_4()
		)) as $setting ){
			
			switch ($setting) {
				case 'items_featured':
					$html.= ($featured==0) ? '<section id="featured-stories">'.mh_display_random_featured_item(true,3).'</section>' : null;
					$featured++;
					break;
				case 'tours_random':
					$html.= ($tours==0) ? '<section id="home-tours">'.mh_display_homepage_tours('random').'</section>' : null;
					$tours++;
					break;
				case 'tours_featured':
					$html.= ($tours==0) ? '<section id="home-tours">'.mh_display_homepage_tours('featured').'</section>' : null;
					$tours++;
					break;
				case 'items_recent':
					$html.= ($recent_or_random==0) ? '<section id="home-item-list">'.mh_random_or_recent('recent').'</section>' : null;
					$recent_or_random++;
					break;
				case 'items_random':
					$html.= ($recent_or_random==0) ? '<section id="home-item-list">'.mh_random_or_recent('random').'</section>' : null;
					$recent_or_random++;
				break;
				case 'popular_tags':
					$html.= ($popular_tags==0) ? '<section id="home-popular-tags">'.mh_home_popular_tags().'</section>' : null;
					$popular_tags++;
					break;
				case 'about':
					$html.= ($about==0) ? '<section id="about">'.mh_home_about().'</section>	' : null;
					$about++;
					break;
				case 'cta':
					$html.= ($cta==0) ? '<section id="cta">'.mh_home_cta().'</section>	' : null;
					$cta++;
					break;
				default:
					$html.=null;
			}

		}

		return $html;
}


/*
** Get recent/random items for use in mobile slideshow on homepage
*/
function mh_random_or_recent($mode='recent',$num=6,$html=null){
	$label = plugin_is_active('Curatescape') ? storyLabelString('plural') : __('Items');
	switch ($mode){
	
	case 'random':
		$items=get_records('Item', array('hasImage'=>true,'sort_field' => 'random', 'sort_dir' => 'd','public'=>true), $num);;
		$heading=__("Random %s",$label);
		break;
	case 'recent':
		$items=get_records('Item', array('hasImage'=>true,'sort_field' => 'added', 'sort_dir' => 'd','public'=>true), $num);
		$heading=__("Recent %s",$label);
		break;
	}
	set_loop_records('items',$items);
	$labelcount='<span>'.total_records('Item').' '.$label.'</span>';
	$html.='<h2 class="result-type-header">'.$heading.'</h2>';

	if (has_loop_records('items')){
		$html.='<div class="browse-items flex">';
		foreach(loop('Items') as $item){
			$item_image=null;
			$description = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>300));
			$tags=tag_string(get_current_record('item') , url('items/browse'));
			$titlelink=mh_the_title_link($item);
			$hasImage=metadata($item, 'has thumbnail');
			if ($hasImage){
					preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', item_image('fullsize'), $result);
					$item_image = array_pop($result);				
			}

			$html.='<article class="item-result'.( $hasImage ? ' has-image' : null ).'">';
				$html.=( isset($item_image) ? link_to_item('<span class="item-image" style="background-image:url('.$item_image.');" role="img" aria-label="'.metadata($item, array('Dublin Core', 'Title')).'"></span>',array('title'=>metadata($item,array('Dublin Core','Title')))) : null );
				$html.='<h3>'.$titlelink.'</h3>';
				$html.='<div class="browse-meta-top">'.mh_the_byline($item,false).'</div>';
				
				
				if ($description){
					$html.='<div class="item-description">';
					$html.=$description;
					$html.='</div>';
				}
				
			$html.='</article> ';
		}
		
		$html.='</div>';
		$html.='<a class="button button-primary view-more-link" href="/items/browse/">'.__('Browse all %s',$labelcount).'</a>';
		
		
	}else{
		$html.='<p>'.__('No items are available. Publish some now.').'</p>';
	}
	return $html;
}
/*
** Icon file for mobile devices
*/
function mh_apple_icon_logo_url()
{
	$apple_icon_logo = get_theme_option('apple_icon_144');

	$logo_img = $apple_icon_logo ? WEB_ROOT.'/files/theme_uploads/'.$apple_icon_logo : img('Icon.png');

	return $logo_img;
}


/*
** Background image
*/
function mh_bg_url()
{
	$bg_image = get_theme_option('bg_img');

	$img_url = $bg_image ? WEB_ROOT.'/files/theme_uploads/'.$bg_image : null;

	return $img_url;
}

/*
** Custom color validator
*/
function mh_color($color) {
	$color = strtolower(trim($color));	
	// HEX
	if (preg_match('/^#[a-f0-9]{3}$|^#[a-f0-9]{6}$|^#[a-f0-9]{4}$|^#[a-f0-9]{8}$/i', $color)) {
		return $color;
	}
	// RGB/RGBA
	if (preg_match('/^rgba?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\s*(?:,\s*(\d+(?:\.\d+)?))?\s*\)$/', $color, $matches)) {
		$r = floatval($matches[1]);
		$g = floatval($matches[2]);
		$b = floatval($matches[3]);
		$a = isset($matches[4]) ? floatval($matches[4]) : 1;
		if ($r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255 && $a >= 0 && $a <= 1) {
			return $color;
		}
	}
	// HSL/HSLA
	if (preg_match('/^hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)%\s*,\s*(\d+(?:\.\d+)?)%\s*(?:,\s*(\d+(?:\.\d+)?))?\s*\)$/', $color, $matches)) {
		$h = floatval($matches[1]);
		$s = floatval($matches[2]);
		$l = floatval($matches[3]);
		$a = isset($matches[4]) ? floatval($matches[4]) : 1;
		if ($h >= 0 && $h <= 360 && $s >= 0 && $s <= 100 && $l >= 0 && $l <= 100 && $a >= 0 && $a <= 1) {
			return $color;
		}
	}
	// LAB 
	if (preg_match('/^lab\(\s*(\d+(?:\.\d+)?)%\s+(-?\d+(?:\.\d+)?)\s+(-?\d+(?:\.\d+)?)\s*\)$/', $color, $matches)) {
		$l = floatval($matches[1]);
		$a = floatval($matches[2]);
		$b = floatval($matches[3]);
		if ($l >= 0 && $l <= 100 && $a >= -125 && $a <= 125 && $b >= -125 && $b <= 125) {
			return $color;
		}
	}
	return null;
}

/*
** Custom CSS
*/
function mh_configured_css($configured_css=null, $user_css=null){
	$color_primary=mh_color(get_theme_option('link_color'));
	$color_secondary=mh_color(get_theme_option('secondary_link_color'));
	$color_tertiary=mh_color(get_theme_option('tertiary_link_color'));
	$color_block=mh_color(get_theme_option('block_color'));

	$configured_css .= ':root{';
	$configured_css .=	$color_primary ? '--color-primary:'.$color_primary.';' : '';
	$configured_css .=	$color_secondary ? '--color-secondary:'.$color_secondary.';' : '';
	$configured_css .=	$color_tertiary ? '--color-tertiary:'.$color_tertiary.';' : '';
	$configured_css .=	$color_block ? '--color-large-block:'.$color_block.';' : '';
	$configured_css .= '}';

	if(get_theme_option('bg_img') && mh_bg_url()){
		$configured_css .='body{
			background-image: url('.mh_bg_url().');
		}';
	}
	$user_css = get_theme_option('custom_css') ? '/* Theme Option: User CSS */ '.get_theme_option('custom_css') : $user_css;
	return '<style>'.$configured_css.$user_css.'</style>';
}


/*
** Which fonts/service to use?
** Typekit, FontDeck, Monotype or fallback to defaults using Google Fonts
*/
function mh_font_config(){
	if($tk=get_theme_option('typekit')){
		$config="typekit: { id: '".$tk."' }";
	}elseif($fd=get_theme_option('fontdeck')){
		$config="fontdeck: { id: '".$fd."' }";
	}elseif($fdc=get_theme_option('fonts_dot_com')){
		$config="monotype: { projectId: '".$fdc."' }";
	}elseif($gf=get_theme_option('google_fonts')){
		$config="google: { families: [".$gf."] }";		
	}else{
		$config="google: { families: [ 'Raleway:latin', 'Playfair+Display:latin' ] }";
	}
	return $config;
}


/*
** Web Font Loader async script
** https://developers.google.com/fonts/docs/webfont_loader
** see also screen.css
*/
function mh_web_font_loader(){ ?>
<script>
	WebFontConfig = {
		<?php echo mh_font_config(); ?>
	};
   (function(d) {
      var wf = d.createElement('script'), s = d.scripts[0];
      wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
      wf.async = true;
      s.parentNode.insertBefore(wf, s);
   })(document);
</script>	
<?php }

/*
** Google Analytics
** Theme option: google_analytics
** Accepts G- and UA- measurement IDs
*/
function mh_google_analytics()
{
   $id=get_theme_option('google_analytics');
   if ($id):
	  if (substr($id, 0, 2) == 'G-'): ?>
		 <!-- GA -->
		 <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $id; ?>"></script>
		 <script>
		 window.dataLayer = window.dataLayer || [];
		 
		 function gtag() {
			dataLayer.push(arguments);
		 }
		 gtag('js', new Date());
		 gtag('config', '<?php echo $id; ?>', {
			cookie_flags: 'SameSite=None;Secure'
		 });
		 </script>
	  
	  <?php elseif (substr($id, 0, 3) == 'UA-'): ?>
		 <!-- GA (Legacy) -->
		 <script>
		 var _gaq = _gaq || [];
		 _gaq.push(['_setAccount', '<?php echo $id; ?>']);
		 _gaq.push(['_trackPageview']);
		 (function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		 })();
		 </script>
	  <?php endif;
   endif;
}

/*
** About text
*/
function mh_about($text=null){
	if (!$text) {
		// If the 'About Text' option has a value, use it. Otherwise, use default text
		$text = get_theme_option('about') ? 
			strip_tags(get_theme_option('about'),'<a><em><i><cite><strong><b><u><br><img><video><iframe>') : 
			__('%s is powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org/">Curatescape</a>, a humanities-centered web and mobile framework available for both Android and iOS devices.',option('site_title'));
	}
	return $text;
}

/*
**
*/
function mh_license(){
	$cc_license=get_theme_option('cc_license');
	$cc_version=get_theme_option('cc_version');
	$cc_jurisdiction=get_theme_option('cc_jurisdiction');
	$cc_readable=array(
		'1'=>'1.0',
		'2'=>'2.0',
		'2-5'=>'2.5',
		'3'=>'3.0',
		'4'=>'4.0',
		'by'=>'Attribution',
		'by-sa'=>'Attribution-ShareAlike',
		'by-nd'=>'Attribution-NoDerivs',
		'by-nc'=>'Attribution-NonCommercial',
		'by-nc-sa'=>'Attribution-NonCommercial-ShareAlike',
		'by-nc-nd'=>'Attribution-NonCommercial-NoDerivs'
	);
	$cc_jurisdiction_readable=array(
		'intl'=>'International',
		'ca'=>'Canada',
		'au'=>'Australia',
		'uk'=>'United Kingdom (England and Whales)',
		'us'=>'United States'
	);
	if($cc_license != 'none'){
		return __('This work is licensed by '.mh_owner_link().' under a <a rel="license" href="http://creativecommons.org/licenses/'.$cc_license.'/'.$cc_readable[$cc_version].'/'.($cc_jurisdiction !== 'intl' ? $cc_jurisdiction : null).'">Creative Commons '.$cc_readable[$cc_license].' '.$cc_readable[$cc_version].' '.$cc_jurisdiction_readable[$cc_jurisdiction].' License</a>.');
	}else{
		return __('&copy; %1$s %2$s', date('Y'), mh_owner_link() );
	}
}


/*
** Edit item link
*/
function link_to_item_edit($item=null,$pre=null,$post=null)
{
	if (is_allowed($item, 'edit')) {
		return $pre.'<a class="edit" href="'. html_escape(url('admin/items/edit/')).metadata($item,'ID').'">'.__('Edit Item').'</a>'.$post;
	}
}

/*
** File item link
*/
function link_to_file_edit($file=null,$pre=null,$post=null)
{
	if (is_allowed($file, 'edit')) {
		return $pre.'<a class="edit" href="'. html_escape(url('admin/files/edit/')).metadata('file','ID').'">'.__('Edit File Details').'</a>'.$post;
	}
}

/*
** Display notice to admins if item is private
*/
function item_is_private($item=null){
	if(is_allowed($item, 'edit') && ($item->public)==0){
		return '<div class="item-is-private">This item is private.</div>';
	}else{
		return null;
	}
}


/*
** iOS Smart Banner
** Shown not more than once per day
*/
function mh_ios_smart_banner(){
	// show the iOS Smart Banner once per day if the app ID is set
	$appID = (get_theme_option('ios_app_id')) ? get_theme_option('ios_app_id') : false;
	if ($appID != false){
		$AppBanner = 'Curatescape_AppBanner_'.$appID;
		$numericID=str_replace('id', '', $appID);
		if (!isset($_COOKIE[$AppBanner])){
			echo '<meta name="apple-itunes-app" content="app-id='.$numericID.'">';
			setcookie($AppBanner, true,  time()+86400); // 1 day
		}
	}
}

/*
** Adjust color brightness
** via: https://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php#11951022
*/
function adjustBrightness($hex, $steps) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter
	$steps = max(-255, min(255, $steps));
	
	// Normalize into a six character long hex string
	$hex = str_replace('#', '', $hex);
	if (strlen($hex) == 3) {
		$hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
	}
	
	// Split into three parts: R, G and B
	$color_parts = str_split($hex, 2);
	$return = '#';
	
	foreach ($color_parts as $color) {
		$color   = hexdec($color); // Convert to decimal
		$color   = max(0,min(255,$color + $steps)); // Adjust color
		$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
	}
	
	return $return;
}

/* 
** https://stackoverflow.com/questions/5501427/php-filesize-mb-kb-conversion
*/
function formatSizeUnits($bytes){
	if ($bytes >= 1073741824)
	{
	$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	}
	elseif ($bytes >= 1048576)
	{
	$bytes = number_format($bytes / 1048576, 2) . ' MB';
	}
	elseif ($bytes >= 1024)
	{
	$bytes = number_format($bytes / 1024, 2) . ' kB';
	}
	elseif ($bytes > 1)
	{
	$bytes = $bytes . ' bytes';
	}
	elseif ($bytes == 1)
	{
	$bytes = $bytes . ' byte';
	}
	else
	{
	$bytes = '0 bytes';
	}
	return $bytes;
}

?>