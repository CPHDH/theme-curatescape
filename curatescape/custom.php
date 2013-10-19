<?php
// Build some custom data for Facebook Open Graph, Twitter Cards, general SEO, etc...

// SEO Page description
function mh_seo_pagedesc($item=null,$tour=null){
	if($item){
		$itemdesc=snippet(item('Dublin Core', 'Description'),0,500,"...");
		return strip_tags($itemdesc);
	}elseif($tour){
		$tourdesc=snippet(tour('Description'),0,500,"...");
		return strip_tags($tourdesc);
	}else{
		return mh_seo_sitedesc();
	}
}

// SEO Site description
function mh_seo_sitedesc(){
	return mh_about() ? strip_tags(mh_about()) : strip_tags(settings('description'));
}

// SEO Page Title
function mh_seo_pagetitle($title){
	return $title ? htmlspecialchars($title).' | '.settings('site_title') : settings('site_title');	
}

// SEO Page image
function mh_seo_pageimg($item=null){
	if($item){
		if(item_has_thumbnail()){
			$itemimg=item_square_thumbnail();	
			preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $itemimg, $result);
			$itemimg=array_pop($result);
		}
	}
	return $itemimg ? $itemimg : mh_lg_logo_url();
}


/*
** Modify Omeka's auto_discovery_link_tags
** Changes labels for feed names to be more appropriate
** Introduces item limit to avoid excessive memory use
*/
function mh_auto_discovery_link_tags() {
    $html = '<link rel="alternate" type="application/rss+xml" title="'. __('New '.mh_item_label('plural').': RSS') . '" href="'. html_escape(items_output_uri()) .'&per_page=15" />';
    $html .= '<link rel="alternate" type="application/atom+xml" title="'. __('New '.mh_item_label('plural').': Atom') .'" href="'. html_escape(items_output_uri('atom')) .'&per_page=15" />';
    return $html;
}

function mh_item_label_option($which=null){
	if($which=='singular'){
		return ($singular=get_theme_option('item_label_singular')) ? $singular : __('Story');
		}
	elseif($which=='plural'){
		return ($plural=get_theme_option('item_label_plural')) ? $plural : __('Stories');
		}		
}

function mh_tour_label_option($which=null){
	if($which=='singular'){
		return ($singular=get_theme_option('tour_label_singular')) ? $singular : __('Tour');
		}
	elseif($which=='plural'){
		return ($plural=get_theme_option('tour_label_plural')) ? $plural : __('Tours');
		}		
}

/*
** Item Labels
*/
function mh_item_label($which=null){
	if($which=='plural'){
		return mh_item_label_option('plural');
	}else{
		return mh_item_label_option('singular');
	}
}

/*
** Tour Labels
*/
function mh_tour_label($which=null){
	if($which=='plural'){
		return mh_tour_label_option('plural');
	}else{
		return mh_tour_label_option('singular');
	}
}

/*
** Tour Header on homepage
*/
function mh_tour_header(){
	if($text=get_theme_option('tour_header')){
		return $text;
	}else{
		return __('Take a ').mh_tour_label_option('singular').'';
	}
}
/*
** Global navigation
*/
function mh_global_nav(){
	return public_nav_main(array(
			'Home' => uri('/'),
			mh_item_label('plural') => uri('items/browse'),
			mh_tour_label('plural') => uri('/tour-builder/tours/browse/')));
}

/*
** Get the correct logo for the page
** uses body class to differentiate between home, stealth-home, and other
*/
function mh_the_logo(){
	if ( ($bodyid='home') && ($bodyclass='public') ) {
		return '<img src="'.mh_lg_logo_url().'" class="home" id="logo-img" alt="'.settings('site_title').'"/>';
	}elseif( ($bodyid='home') && ($bodyclass='stealth-mode') ){
		return '<img src="'.mh_stealth_logo_url().'" class="stealth" id="logo-img" alt="'.settings('site_title').'"/>';
	}else{
		return '<img src="'.mh_med_logo_url().'" class="inner" id="logo-img" alt="'.settings('site_title').'"/>';
	}
}

/*
** Link to Random item
*/

function random_item_link($text=null,$class='show'){
	$items = get_items(array('random' => 1), 1);
	$item = $items[0];
	if(!$text){
		$text=__('View a random ').mh_item_label();
	}
	return link_to($item, 'show', $text, array('class'=>'random-story-link '.$class));
}


/*
** Global header
** includes nav, logo, search bar
** site title h1 is visually hidden but included for semantic purposes and screen readers
*/
function mh_global_header(){
	$html ='<h1 id="site-title" class="visuallyhidden">'.link_to_home_page().'</h1>';

	$html .= '<div id="mobile-logo-container" class="clearfix">';

	$html .= '<div id="logo">';
	$html .= link_to_home_page(mh_the_logo());
	$html .= '</div>';

	$html  .= '<div id="mobile-menu-button"><a class="icon-reorder"><span class="visuallyhidden">'.__('Menu').'</span></a></div>';
	$html .= '</div>';


	$html .= '<div id="mobile-menu-cluster" class="active">';

	$html .= '<nav id="primary-nav">';
	$html .= '<ul class="navigation">';
	$html .= mh_global_nav();
	$html .= '</ul>';
	$html .= '</nav>';



	$html .= '<div id="search-wrap">';
	$html .= mh_simple_search($buttonText, $formProperties=array('id'=>'header-search'), $uri);

	$html .= '</div>';

	$html .= random_item_link();

	$html .= '</div>'; // end mobile-menu-cluster

	return $html;

}


/*
** Decide which content to display in hero container in header.php
** Uses $bodyclass variable set in page templates via head() function
** TODO: To speed this up, we should just send a specific variable to the header rather than using regexp
*/
function mh_which_content($maptype){
	
	$loading = '<img id="hero_loading" src="'.img('map_loading.gif').'">';
	
	if ($maptype == 'focusarea') {
		return $loading.mh_display_map('focusarea');
	}
	elseif ($maptype == 'story') {
		return $loading.mh_display_map('story');
	}
	elseif ($maptype == 'queryresults') {
		return $loading.mh_display_map('queryresults');
	}
	elseif ($maptype == 'tour') {
		return $loading.mh_display_map('tour');
	}
	elseif ($maptype == 'none') {
		//return null; 
		return $loading;
		}
	else {
		return null;
	}
}

/*
** Render the map using Google Maps API via jQuery-UI-Map http://code.google.com/p/jquery-ui-map/
** Source feed generated from Mobile JSON plugin
** Location data (LatLon and Zoom) created and stored in Omeka using stock Geolocation plugin
** Per_page limits overridden, set to 9,999
*/
function mh_display_map($type=null){
	$pluginlng=get_option( 'geolocation_default_longitude' );
	$pluginlat=get_option( 'geolocation_default_latitude' );
	$plugincenter = $pluginlat .','. $pluginlng;
	$zoom=(get_option('geolocation_default_zoom_level')) ? get_option('geolocation_default_zoom_level') : 12;

	switch($type){

	case 'focusarea':
		/* all stories, map is centered on focus area (plugin center) */
		$json_source=WEB_ROOT.'/items/browse?output=mobile-json&per_page=9999';
		break;

	case 'global':
		/* all stories, map is bounded according to content */
		$json_source=WEB_ROOT.'/items/browse?output=mobile-json&per_page=9999';
		break;

	case 'story':
		/* single story */
		$json_source='?output=mobile-json';
		break;

	case 'tour':
		/* single tour, map is bounded according to content  */
		$json_source='?output=mobile-json&per_page=9999';
		break;

	case 'queryresults': 
		/* browsing by tags, subjects, search results, etc, map is bounded according to content */
		$json_source=$_SERVER['REQUEST_URI'].'&output=mobile-json&per_page=9999';
		break;

	default:
		$json_source='/items/browse?output=mobile-json&per_page=9999';

	}
	
	if(get_theme_option('custom_marker')){
		$marker='/archive/theme_uploads/'.get_theme_option('custom_marker');
	}else{
		$marker='/themes/curatescape/images/map-icn.png';
	}
	if(get_theme_option('custom_shadow')){
		$shadow='/archive/theme_uploads/'.get_theme_option('custom_shadow');
	}else{
		$shadow='/themes/curatescape/images/map-icn-shadow.png';
	}
?>
		<script type="text/javascript">

		var type =  '<?php echo $type ;?>';
		var mapstyle = '<?php echo 'google.maps.MapTypeId.'.get_theme_option('map_style') ;?>';
		
		var root = '<?php echo WEB_ROOT ;?>';
		var source ='<?php echo $json_source ;?>';
		
		var center ='<?php echo $plugincenter ;?>';
		var zoom = <?php echo $zoom ;?>;
		
		var marker = root+"<?php echo $marker ;?>";
		var shadow = root+"<?php echo $shadow ;?>";	
		
		var fallbacklat='<?php echo $pluginlat ;?>';
		var fallbacklng='<?php echo $pluginlng ;?>';
		var fallbackmarker=null;
		var fallbackshadow=null;
		
		jQuery(document).ready(function() {

		jQuery('#hero_loading').fadeIn('slow');
		
		/* setup the default map */
		jQuery('#map_canvas').gmap({
			'center': center,
			'zoom': zoom,
			'mapTypeId': eval(mapstyle),
			'disableDefaultUI':false,
			'zoomControl': true,
			'zoomControlOptions': {
			  'style': google.maps.ZoomControlStyle.SMALL,
			  'position': google.maps.ControlPosition.TOP_RIGHT
			}
		}).bind('init', function() {

			if(type == 'story'){
			
			
			// The MOBILE-JSON source is formatted differently for stories
			// We also add some custom content to the bubble and set bounds to true
			var makemap=jQuery.getJSON( source, function(data) {
					
					// make sure we have a location; if not, use plugin center and empty marker
					var lat=data.latitude;
					var lng=data.longitude;
					if( (!lat) || (!lng) ){
						lat= fallbacklat;
						lng= fallbacklng;	
						marker= fallbackmarker;	
						shadow= fallbackshadow;	
					};

					jQuery('#map_canvas').gmap('addMarker', {
						'position': new google.maps.LatLng(lat, lng),
						'bounds': true,
						'icon': new google.maps.MarkerImage(marker),
						'shadow': new google.maps.MarkerImage(shadow),
					}).click(function() {
						jQuery('#map_canvas').gmap('openInfoWindow', { 'content': '<div style="margin:.25em;min-width:12.5em;line-height:1.7em; "><i class="icon-map-marker"></i> <a href="https://maps.google.com/maps?saddr=current+location&daddr='+lat+','+lng+'" onclick="return !window.open(this.href);">Get Directions</a><br><small><em>Be sure to read the <a href="#map-faq" class="fancybox">MAP FAQ</a>.</em></small></div>' }, this);
					});
			});
			jQuery.when(makemap).done(function() {
				jQuery('#hero_loading').fadeOut('slow');
				jQuery('#map_canvas').gmap('option', 'zoom', 15);
			});				
			}else{
			// The MOBILE-JSON source format for everything else is compatible w/ the following
			// Set bounds to true unless it's the homepage or a "browse all" page, each of which use the "focusarea" view
			var bounds = (type == 'focusarea') ? false : true;
			var makemap=jQuery.getJSON( source, function(data) {
				jQuery.each( data.items, function(i, item) {
					jQuery('#map_canvas').gmap('addMarker', {
						'position': new google.maps.LatLng(item.latitude, item.longitude),
						'bounds': bounds,
						'icon': new google.maps.MarkerImage(marker),
						'shadow': new google.maps.MarkerImage(shadow),
					}).click(function() {
						jQuery('#map_canvas').gmap('openInfoWindow', { 'content': '<a href="' + root + '/items/show/' + item.id +'">' + item.title + '</a>' }, this);
					});
				});
			});
			jQuery.when(makemap).done(function() {
				jQuery('#hero_loading').fadeOut('slow');
			});				
			}
		})
		});

        </script>
		<div id="hm-map">
			<div id="map_canvas" style="height:20em;">		
			</div>
		</div>
<?php }




/*
** Modified search form
** Adds HTML "placeholder" attribute
** Adds HTML "type" attribute
*/

function mh_simple_search($buttonText = null, $formProperties=array('id'=>'simple-search'), $uri = null)
{
	if (!$buttonText) {
		$buttonText = __('Search');
	}

	// Always post the 'items/browse' page by default (though can be overridden).
	if (!$uri) {
		$uri = apply_filters('simple_search_default_uri', uri('items/browse'));
	}

	$searchQuery = array_key_exists('search', $_GET) ? $_GET['search'] : '';
	$formProperties['action'] = $uri;
	$formProperties['method'] = 'get';
	$html  = '<form ' . _tag_attributes($formProperties) . '>' . "\n";
	$html .= '<fieldset>'. "\n\n";
	$html .= __v()->formText('search', $searchQuery, array('type'=>'search','name'=>'search','class'=>'textinput','placeholder'=>'Search '.mh_item_label('plural').''));
	$html .= __v()->formSubmit('submit_search', $buttonText);
	$html .= '</fieldset>' . "\n\n";

	// add hidden fields for the get parameters passed in uri
	$parsedUri = parse_url($uri);
	if (array_key_exists('query', $parsedUri)) {
		parse_str($parsedUri['query'], $getParams);
		foreach($getParams as $getParamName => $getParamValue) {
			$html .= __v()->formHidden($getParamName, $getParamValue);
		}
	}

	$html .= '</form>';
	return $html;
}


/*
** App Store links on homepage
*/
function mh_appstore_downloads(){
	if (get_theme_option('enable_app_links')){

		echo '<h2>Downloads</h2>';

		$ios_app_id = get_theme_option('ios_app_id');
		echo ($ios_app_id ?
			'<a id="apple" class="app-store" href="https://itunes.apple.com/us/app/'.$ios_app_id.'">'.
			__('iOS App Store').
			'</a> ':'<a id="apple" class="app-store" href="#">'.
			__('Coming Soon').
			'</a> ');

		$android_app_id = get_theme_option('android_app_id');
		echo ($android_app_id ?
			'<a id="android" class="app-store" href="http://play.google.com/store/apps/details?id='.$android_app_id.'">'.
			__('Google Play').
			'</a> ':'<a id="android" class="app-store" href="#">'.
			__('Coming Soon').
			'</a> ');

	}else{
		echo '<a id="coming-soon" class="app-store" href="#">'.__('iOS + Android Apps Coming Soon!').'</a>';
	}
}


/*
** App Store links in footer
*/
function mh_appstore_footer(){
	if (get_theme_option('enable_app_links')){

		$ios_app_id = get_theme_option('ios_app_id');
		$android_app_id = get_theme_option('android_app_id');
		if (($ios_app_id != false) && ($android_app_id == false)) {
			echo __('Get the app for <a id="apple-text-link" class="app-store-footer" href="https://itunes.apple.com/us/app/%s">iPhone</a>',$ios_app_id);
		}
		elseif (($ios_app_id == false) && ($android_app_id != false)) {
			echo __('Get the app for <a id="apple-text-link" class="app-store-footer" href="http://play.google.com/store/apps/details?id=%s">Android</a>',$android_app_id);
		}
		elseif (($ios_app_id != false)&&($android_app_id != false)) {
			echo __('Get the app for <a id="apple-text-link" class="app-store-footer" href="https://itunes.apple.com/us/app/%s$1">iPhone</a> and <a id="android-text-link" class="app-store-footer" href="http://play.google.com/store/apps/details?id=%s$2">Android</a>',$ios_app_id,$android_app_id);
		}
		else{
			echo __('iPhone + Android Apps Coming Soon!');
		}
	}
}


/*
** Map FAQ
** used for item map marker onclick
** may be customized by site owner
*/
function mh_mapfaq(){
	$emailincl=($email=get_theme_option('contact_email')) ? 'at <a href="mailto:'.$email.'">'.$email.'</a> ' : '';
	$html ='';
	$html .='<div style="display: none"><div id="map-faq"><div id="map-faq-inner">';
	$html .='<h2>Frequently Asked Questions <span>about the map</span></h2>';
	if((!get_theme_option('map_faq'))){
		   $html .='<h3><a>Are all the locations on '.settings('site_title').' publicly accessible?</a></h3>';
		   $html .='<p>Not necessarily. It is up to you to determine if any given location is one you can physically visit.</p>';
		   $html .='<h3><a>How do you choose locations for each '.strtolower(mh_item_label()).'?</a> <span>or</span> <a>The location is wrong!</a></h3>';
		   $html .='<p>Placing historical '.strtolower(mh_item_label('plural')).' on a map can be tricky. We choose locations based on what we think makes the most sense. Sometimes we get it wrong (and sometimes there is no "right" answer). Feel free to email us '.$emailincl.'with suggestions for improvement.</p>';
	}else{
	$html .=get_theme_option('map_faq');
	}
	$html.='</div></div></div>';

	return $html;

}


/*
** map figure on items/show.php
** uses Geolocation plugin data to create Google Map
** re-uses data to add custom links to Google Maps, Bing Maps (WindowsPhone-only) and Apple Maps (iOS-only)
** at the moment, these links only open a view of the lat-lon coordinates (no custom pins or query titles, etc)
** TODO: make the links open into a more useful view by incorporating query params
*/
function mh_item_map(){
	if (function_exists('geolocation_get_location_for_item')){
		$location = geolocation_get_location_for_item(get_current_item(), true);
		if ($location) {
			echo geolocation_public_show_item_map('100%', '20em');

			$lng = $location['longitude'];
			$lat = $location['latitude'];
			$zoom = ($location['zoom_level']) ? '&z='.$location['zoom_level'] : '';
			$title = (item('Dublin Core','Title')) ? ''.str_replace("&","and", html_entity_decode(item('Dublin Core','Title'))).'' : '';
			$addr = ($location['address']) ? ' near '.str_replace("&","and", html_entity_decode($location['address'])).'' : '';
			$query = ($title || $addr) ? '&q='.$title.$addr : ''; // this is not quite generalizable so we won't use it
			$coords = $lat.','.$lng;

			// Google Maps for all users
			$link = '<a class="item-map-link" href="http://maps.google.com/maps?q='.$coords.$zoom.'">View in Google Maps</a>';

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS') !== false) {
				// + Apple Maps for iOS users
				$link .= ' <a class="item-map-link" href="http://maps.apple.com/maps?ll='.$coords.$zoom.'">Open in iOS Maps</a>';
			};

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone OS') !== false) {
				// + Bing Maps for Windows Phone users
				$link .= ' <a class="item-map-link" href="http://bing.com/maps/default.aspx?cp='.str_replace(',','~',$coords).str_replace('z','lvl',$zoom).'&v=2">Open in Bing Maps</a>';
			};

			echo $link;

		}
	}
}


/*
** author byline on items/show.php
*/
function mh_the_author(){
	if ((get_theme_option('show_author') == true)){
		$html='<span class="story-meta byline">By: ';

		if(item('Dublin Core', 'Creator')){
			$authors=item('Dublin Core', 'Creator', array('all'=>true));
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
			$html .= "The ".settings('site_title')." team";
		}
		$html .='</span>';

		echo $html;
	}
}

/*
** Return the current page URL
*/
function mh_current_page() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/*
** Finds URLs in a given $string and
** Adds zero-width spaces after special characters (really, just slash and dot)
** This allows the long URLs to wrap more efficiently
** Handy for when URLs are breaking responsive page design
** Indended use: mh_wrappable_link(item_citation())
** ...might need some revision for other uses
*/
function mh_wrappable_link($string){

	/* Find a URL in the $string and build the replacement */
	preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/',$string, $matches);
	$origURL = $matches[0];
	$newURL=$origURL;
	$newURL=preg_replace('/\//','/&#8203;', $newURL); //replace slash with slash + zero-width-space
	$newURL=preg_replace('/\./','.&#8203;', $newURL); //replace dot with dot + zero-width-space

	/* Apply the repalcement URL to the original string */
	$string=preg_replace('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/',$newURL, $string);

	return $string;
}


/*
** Custom item citation
*/
function mh_item_citation(){
	return mh_wrappable_link(item_citation());
}


/*
** Loop through and display image files
*/
function mh_item_images(){
	//===========================// ?>
	<script>
	function hideText(){
		var link = jQuery('a.fancybox-hide-text');
		jQuery(".fancybox-title span").fadeToggle(function(){
            if (jQuery(this).is(":visible")) {
                 link.html('X');                
            } else {
                 link.html('&hellip;');                
            } 			
		});
	}
	// checkWidth.js sets 'big' and 'small' body classes
	// FancyBox is used only when the body class is 'big'
	jQuery(".fancybox").fancybox({
        beforeLoad: function() {
            this.title = jQuery(this.element).attr('data-caption');
        },
        beforeShow: function () {
            if (this.title) {
                // Add caption close button
                this.title += '<a class="fancybox-hide-text" onclick="hideText()">X</a> ';
            }
        },
	    helpers : {
	         title: {
	            type: 'over'
	        },
	         overlay : {
	         	locked : false
	         	}
	    }
	});
	</script>
	<?php //========================//

	while ($file = loop_files_for_item()){
		if ($file->hasThumbnail()) {
			if($index==0) echo '<h3><i class="icon-camera-retro"></i>Photos <span class="toggle instapaper_ignore">Show <i class="icon-chevron-right"></i></span></h3>';			
			$photoDesc = mh_normalize_special_characters(item_file('Dublin Core', 'Description'));
			$photoTitle = mh_normalize_special_characters(item_file('Dublin Core', 'Title'));
			$photoSource = (item_file('Dublin Core', 'Source')) ? '<span class="source"><br><br>'.mh_normalize_special_characters(item_file('Dublin Core', 'Source')).'</span>' : '';
			
			if($photoTitle){
				$photoCaption= $photoTitle.(($photoDesc) ? ': '.$photoDesc : '').' ';
				$photoCaption = '<span class="main">'.strip_tags($photoCaption).'</span>'.$photoSource;
				}else{
					$photoCaption = '<span class="main">Image '.($index+1).'</span>';	
				}

			$html = '<div class="item-file-container">';

			$html .= ''.display_file($file, array('imageSize' => 'fullsize','linkAttributes'=>array('data-caption'=>$photoCaption,'title'=>$photoTitle, 'class'=>'fancybox', 'rel'=>'group'),'imgAttributes'=>array('alt'=>$photoTitle) ) );

			$html .= ($photoTitle) ? '<h4 class="title image-title">'.$photoTitle.'</h4>' : '';
			$html .= ($photoDesc) ? '<p class="description image-description">'.$photoDesc.'</p>' : '';
			$html .= '</div>';

			echo $html;
			$index++;

		}
	}
}


/*
** Loop through and display audio files
** FYI: adding "controls" to html <audio> tag causes a
** display error when used in combination w/ Fancybox
** image viewer
*/
function mh_audio_files(){
	$audioTypes = array('audio/mpeg');
	$myaudio = array();
	while ($file = loop_files_for_item()):
		$audioDesc = item_file('Dublin Core','Description');
	$audioTitle = item_file('Dublin Core','Title');
	$mime = item_file('MIME Type');

	if ( array_search($mime, $audioTypes) !== false ) {

		if ($index==0) echo '<h3><i class="icon-volume-up"></i>Audio <span class="toggle instapaper_ignore">Show <i class="icon-chevron-right"></i></span></h3><script>audiojs.events.ready(function() {var as = audiojs.createAll();});</script>';
		$index++;

		array_push($myaudio, $file);

		$html = '<div class="item-file-container">';
		$html .= '<audio>
			<source src="'.file_download_uri($file).'" type="audio/mpeg" />
			<h5 class="no-audio"><strong>Download Audio:</strong><a href="'.file_download_uri($file).'">MP3</a></h5>
			</audio>';
		$html .= ($audioTitle) ? '<h4 class="title audio-title sib">'.$audioTitle.' <i class="icon-info-sign"></i></h4>' : '';
		$html .= ($audioDesc) ? '<p class="description audio-description sib">'.$audioDesc.'</p>' : '';
		$html .= '</div>';
		echo $html;
	}

	endwhile;
}


/*
** Loop through and display video files
** Please use H.264 video format
** Browsers that do not support H.264 will fallback to Flash
** We accept multiple H.264-related MIME-types because Omeka MIME detection is sometimes spotty
** But in the end, we always tell the browser they're looking at "video/mp4"
** Opera and Firefox are currently the key browsers that need flash here, but that may change
*/
function mh_video_files() {
	$videoIndex = 0;
	$videoTypes = array('video/mp4','video/mpeg','video/quicktime');
	$videoPoster = mh_poster_url();
	$videoJS = js('video-js/video.min');
	$videoSWF= '<script> _V_.options.flash.swf = "'. WEB_ROOT .'/themes/curatescape/javascripts/video-js/video-js.swf"</script>';

	while(loop_files_for_item()):
		$file = get_current_file();
	$videoMime = item_file("MIME Type");
	$videoFile = file_download_uri($file);
	$videoTitle = item_file('Dublin Core', 'Title');
	$videoClass = (($videoIndex==0) ? 'first' : 'not-first');
	$videoDesc = item_file('Dublin Core','Description');
	$videoTitle = item_file('Dublin Core','Title');

	if ( in_array($videoMime,$videoTypes) ){
		$html = (($videoIndex==0) ? $videoJS.$videoSWF.'<h3><i class="icon-film"></i>Video <span class="toggle instapaper_ignore">Show <i class="icon-chevron-right"></i></span></h3>' : '');

		$html .= '<div class="item-file-container">';
		$html .= '<video width="640" height="360" id="video-'.$videoIndex.'" class="'.$videoClass.' video-js vjs-default-skin" controls poster="'.$videoPoster.'"  preload="auto" data-setup="{}">';
		$html .= '<source src="'.$videoFile.'" type="video/mp4">';
		$html .= '</video>';
		$html .= ($videoTitle) ? '<h4 class="title video-title sib">'.$videoTitle.' <i class="icon-info-sign"></i></h4>' : '';
		$html .= ($videoDesc) ? '<p class="description video-description sib">'.$videoDesc.'</p>' : '';
		$html .= '</div>';

		echo $html;

		$videoIndex++;
	}
	echo mh_video_ResponsifyVideoScript($videoIndex);
	endwhile;

}
/*
** Script to resize the video based on desired aspect ratio and browser viewport
** This basically iterates a separate action for each video (see mh_video_files() loop above),
** which is not very efficient, but having more than one video per record is not very common here
*/
function mh_video_ResponsifyVideoScript($videoIndex, $aspectRatio='360/640'){
?>


	<script>
	var vidCount=<?php echo $videoIndex; ?>-1;
	var aspectRatio=<?php echo $aspectRatio; ?>;

	for (var i=0;i<=vidCount;i++){
		var vidID = "#video-"+i+"";

	    _V_(vidID).ready(function(i){
	      var myVid = this;

	      function resizeVideoJS(i){
	        var width = document.getElementById(myVid.id).parentElement.offsetWidth;
	        myVid.width(width).height( width * aspectRatio );
	      }
	      resizeVideoJS(i);

	      var $window = jQuery(window);
	      jQuery($window).resize(resizeVideoJS);

	    });
	}

	</script>

<?php
}

/*
** Display subjects as links
** These links are hard to validate via W3 for some reason
*/
function mh_subjects(){
	$subjects = item('Dublin Core', 'Subject', 'all');
	if (count($subjects) > 0){

		echo '<h3>Subjects</h3>';
		echo '<ul>';
		foreach ($subjects as $subject){
			$link = WEB_ROOT;
			$link .= htmlentities('/items/browse?term=');
			$link .= rawurlencode($subject);
			$link .= htmlentities('&search=&advanced[0][element_id]=49&advanced[0][type]=contains&advanced[0][terms]=');
			$link .= urlencode(str_replace('&amp;','&',$subject));
			$link .= htmlentities('&submit_search=Search');
			echo '<li><a href="'.$link.'">'.$subject.'</a></li>';
		}
		echo '</ul>';

	}
}


/*
Display nav items for Simple Pages sidebar
** (not currently very useful, but we might add some novel content later)
*/
function mh_sidebar_nav(){

	return '<ul>'.mh_global_nav().'</ul>';

}

//Display item relations
function mh_item_relations(){
	if (function_exists('item_relations_display_item_relations')){
		$item=get_current_item();
		$subjectRelations = ItemRelationsPlugin::prepareSubjectRelations($item);
		$objectRelations = ItemRelationsPlugin::prepareObjectRelations($item);
		if ($subjectRelations || $objectRelations){
			echo '<h3>Related '.mh_item_label('plural').'</h3>';
			echo '<ul>';
			foreach ($subjectRelations as $subjectRelation){
				echo '<li><a href="'.uri('items/show/' . $subjectRelation['object_item_id']).'">'.$subjectRelation['object_item_title'].'</a></li>';
			}
			foreach ($objectRelations as $objectRelation){
				echo '<li><a href="'.uri('items/show/' . $objectRelation['subject_item_id']).'">'.$objectRelation['subject_item_title'].'</a></li>';
			}
			echo '</ul>';
		}
	}
}



/*
** Display the item tags
*/
function mh_tags(){
	if (item_has_tags()):

		echo '<h3>Tags</h3>';
	echo item_tags_as_cloud(
		$order = 'alpha',
		$tagsAreLinked = true,
		$item=null,
		$limit=null
	);
	endif;
}

/*
** Display related links
*/
function mh_related_links(){
	$relations = item('Dublin Core', 'Relation', array('all' => true));
	if ($relations){
		echo '<h3>Related Sources</h3><ul>';
		foreach ($relations as $relation) {
			echo "<li>$relation</li>";
		}
		echo '</ul>';
	}
}

/*
** Display the AddThis social sharing widgets
** www.addthis.com
*/
function mh_share_this(){
	$addthis = (get_theme_option('Add This')) ? (get_theme_option('Add This')) : 'ra-4e89c646711b8856';

	$html = '<h3>Share this Page</h3>';
	$html .= '<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_twitter"></a>
<a class="addthis_button_facebook"></a>
<a class="addthis_button_email"></a>
<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid='.$addthis.'"></script>
<!-- AddThis Button END -->';


	return $html;
}

/*
** DISQUS COMMENTS
** disqus.com
*/
function mh_disquss_comments(){
	$shortname=get_theme_option('comments_id');
	if ($shortname){
?>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = '<?php echo $shortname;?>'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
	<?php
	}
}


/*
** Subnavigation for items/browse pages
*/

function mh_item_browse_subnav(){
	if (function_exists('subject_browse_public_navigation_items')){
		echo nav(array(
				'All' => uri('items/browse'),
				'Tags' => uri('items/tags'),
				'Subjects' => uri('items/subject-browse')
			));
	}
	else{
		echo nav(array(
				'All' => uri('items/browse'),
				'Tags' => uri('items/tags')));
	}
}


/*
** See where you're at in a loop and conditionally load content
** This quirky little function is used mainly on items/browse,
** where we need to output all item records (making for one hell of a page load when you have 500+ items)
** NOTE that you can only use this function within loops where $index is defined and incremented
** The +1 allows the index arg to be set at 1, which allows us to use 0 for the second arg
*/
function mh_reducepayload($index,$showThisMany){
	$showThisMany = ($index) ? ($index < ($showThisMany+1)) : true;
	return $showThisMany;
}

/*
** Display the Tours list
*/
function mh_display_random_tours($num = 20){

	// Get the database.
	$db = get_db();

	// Get the Tour table.
	$table = $db->getTable('Tour');

	// Build the select query.
	$select = $table->getSelect();
	$select->from(array(), 'RAND() as rand');
	$select->where('public = 1');

	// Fetch some items with our select.
	$items = $table->fetchObjects($select);
	sort($items);
	$num = (count($items)<$num)? count($items) : $num;

	echo '<h2>'.mh_tour_header().'</h2>';

	for ($i = 0; $i < $num; $i++) {
		echo '<article class="item-result">';
		echo '<h3 class="home-tour-title"><a href="' . WEB_ROOT . '/tour-builder/tours/show/id/'. $items[$i]['id'].'">' . $items[$i]['title'] . '</a></h3>';

		//<div class="item-description">'.snippet($items[$i]['description'],0,250,"...").'</div>';

		echo '</article>';
	}

	echo '<p class="view-more-link"><a href="'.WEB_ROOT.'/tour-builder/tours/browse/">View all <span>'.count($items).' '.mh_tour_label('plural').'</span></a></p>';

	return $items;
}






/*
** Display random featured item
** Used on homepage
*/
function mh_display_random_featured_item($withImage=false)
{
	$featuredItem = random_featured_item($withImage);
	$html = '<h2>Featured '.mh_item_label().'</h2>';
	$html .= '<article class="item-result">';
	if ($featuredItem) {
		$itemTitle = item('Dublin Core', 'Title', array(), $featuredItem);


		if (item_has_thumbnail($featuredItem)) {
			$html .= '<div class="item-thumb">' . link_to_item(item_fullsize(array(), 0, $featuredItem), array(), 'show', $featuredItem) . '</div>';
		}

		$html .= '<h3>' . link_to_item($itemTitle, array(), 'show', $featuredItem) . '</h3>';

		// Grab the 1st Dublin Core description field (first 150 characters)
		if ($itemDescription = item('Dublin Core', 'Description', array('snippet'=>200), $featuredItem)) {
			$html .= '<div class="item-description">' . strip_tags($itemDescription) . '</div>';
		}else{
			$html .= '<div class="item-description empty">Preview text not available.</div>';}

		$html .= '<p class="view-more-link">'. link_to_item('Continue reading <span>'.$itemTitle.'</span>', array(), 'show', $featuredItem) .'</p>';

	}else {
		$html .= '<p>No featured items are available.</p>';
	}
	$html .= '</article>';

	return $html;
}


/*
** Display the most recently added item
** Used on homepage
*/
function mh_display_recent_item($num=1){
	echo ($num <=1) ? '<h2>Newest '.mh_item_label().'</h2>' : '<h2>Newest '.mh_item_label('plural').'</h2>';
	set_items_for_loop(recent_items($num));
	if (has_items_for_loop()){
		while (loop_items()){
			echo '<article class="item-result">';

			echo '<h3>'.link_to_item().'</h3>';

			echo '<div class="item-thumb">'.link_to_item(item_square_thumbnail()).'</div>';


			if($desc = item('Dublin Core', 'Description', array('snippet'=>200))){
				echo '<div class="item-description">'.$desc.'</div>';
			}else{
				echo '<div class="item-description">Text preview unavailable.</div>';
			}

			echo '</article>';

		}
	}
	echo '<p class="view-more-link">'.link_to_browse_items('View all '.mh_item_label('plural').'').'</p>';
}


/*
** Display random items
** Used on homepage
*/

function mh_display_random_item($num=1){
	echo ($num <=1) ? '<h2>Random '.mh_item_label().'</h2>' : '<h2>Random '.mh_item_label('plural').'</h2>';
	$items = get_items(array('random' => 1), $num);
	set_items_for_loop($items);
	if (has_items_for_loop()){
		while (loop_items()){
			echo '<article class="item-result">';

			echo '<h3>'.link_to_item().'</h3>';

			echo '<div class="item-thumb">'.link_to_item(item_square_thumbnail()).'</div>';


			if($desc = item('Dublin Core', 'Description', array('snippet'=>200))){
				echo '<div class="item-description">'.$desc.'</div>';
			}else{
				echo '<div class="item-description">Text preview unavailable.</div>';
			}

			echo '</article>';

		}
	}
	echo '<p class="view-more-link">'.link_to_browse_items('View all '.mh_item_label('plural').'').'</p>';
}

/*
** Display the customizable "About" content on homepage
** also sets content for mobile slideshow, via mh_random_or_recent()
*/
function mh_custom_content($length=500){
	$html ='';
	
	$html .= '<article>';
	
	$html .= '<header>';	
	$html .= '<h2><span class="hidden">About </span>'.settings('site_title').'</h2>';
	$html .= '<span class="find-us">'.mh_home_find_us().'</span>';
	$html .= '</header>';

	$html .= '<div id="inline-logo"><img alt="'.settings('site_title').' logo" src="'.mh_lg_logo_url().'"/></div>';
	$html .= snippet(mh_about(),0,$length,"...");

	$html .= '</article>';

	$html .= '<p class="view-more-link"><a href="'.uri('about').'">Read more <span>About Us</span></a></p>';


	echo $html;

	echo '<div id="rr_home-items" class="hidden">';
	echo mh_random_or_recent();
	echo '</div>';
}

/*
** Build a series of social media link for the homepage
** see: mh_custom_content()
*/
function mh_home_find_us(){
	$service=array();
	($twitter=get_theme_option('twitter_username')) ? array_push($service,'<a href="https://twitter.com/'.$twitter.'">Twitter</a>') : null;
	($facebook=get_theme_option('facebook_link')) ? array_push($service,'<a href="'.$facebook.'">Facebook</a>') : null;
	($youtube=get_theme_option('youtube_username')) ? array_push($service,'<a href="http://www.youtube.com/user/'.$youtube.'">Youtube</a>') : null;
	
	if(count($service)>0){
		$findus='Find us on '.join(' | ',$service);
	}else{
		$findus='A project by '.mh_owner_link();
	}
	return $findus;
}


/*
** Build a link for the footer copyright statement and the fallback credit line on homepage 
** see: mh_home_find_us()
*/
function mh_owner_link(){

	$authname_fallback=(settings('author')) ? settings('author') : settings('site_title');
	
	$authname=(get_theme_option('sponsor_name')) ? get_theme_option('sponsor_name') : $authname_fallback;
	$authlink=(get_theme_option('sponsor_link')) ? '<a href="'.get_theme_option('sponsor_link').'">'.$authname.'</a>' : $authname;
	
	return $authlink;
}

/*
** Get recent/random items for use in mobile slideshow on homepage
*/
function mh_random_or_recent($mode='random',$num=3){
	switch ($mode){
	case 'recent':
		return mh_display_recent_item($num);
		break;
	case 'random':
		return mh_display_random_item($num);
		break;
	}
}

/*
** Csutom CSS
*/
function mh_custom_css(){
	return '<style type="text/css">
	body{
		background:url('.mh_bg_url().') repeat-x fixed right top #CCCCCC;
		}
	.big #hero{
		background:url('.mh_bg_url().') repeat-x fixed center top #CCCCCC;
		box-shadow:none;
	}
	#swipenav #position li.current, .random-story-link.big-button{
		background-color:'.mh_link_color().'}
	a,blockquote{
		color:'.mh_link_color().'
		}
	a:hover,#items #tour-nav-links a{
		color:'.mh_secondary_link_color().'
		}'.get_theme_option('custom_css').
		'@media only screen and (min-width: 60em){
			#featured-story .view-more-link a, footer.main a{
			color:'.mh_secondary_link_color().'
			}
		}
	nav.secondary-nav ul li.current{
		border-bottom-color:'.mh_link_color().'
		}	
		</style>';
}

/*
** Typekit script for header.php
*/
function mh_typekit(){
	if(get_theme_option('typekit')){
		$html ='<script type="text/javascript" src="http://use.typekit.com/'.get_theme_option('typekit').'.js"></script>';
		$html .='<script type="text/javascript">try{Typekit.load();}catch(e){}</script>';
		return $html;
	}
}

/*
** About text
** Used on homepage (stealth and public)
*/
function mh_about($text=null){
	if (!$text) {
		// If the 'About Text' option has a value, use it. Otherwise, use default text
		$text =
			get_theme_option('about') ?
			get_theme_option('about') :
			settings('site_title').' is powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org/">Curatescape</a>, a humanities-centered web and mobile framework available for both Android and iOS devices. ';
	}
	return $text;
}



/*
** Google Analytics
*/
function mh_google_analytics($webPropertyID=null){
	$webPropertyID= get_theme_option('google_analytics');
	if ($webPropertyID!=null){
		echo "<script type=\"text/javascript\">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '".$webPropertyID."']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>";
	}
}

/*
** Edit item link
*/
function link_to_item_edit()
{
	$current = Omeka_Context::getInstance()->getCurrentUser();
	if ($current->role == 'super') {
		echo '<a class="edit" href="'. html_escape(uri('admin/items/edit/')).item('ID').'">Edit this item...</a>';
	}
	elseif($current->role == 'admin'){
		echo '<a class="edit" href="'. html_escape(uri('admin/items/edit/')).item('ID').'">Edit this item...</a>';
	}
}

/*
** <video> placeholder image
*/
function mh_poster_url()
{
	$poster = get_theme_option('poster');

	$posterimg = $poster ? WEB_ROOT.'/archive/theme_uploads/'.$poster : img('poster.png');

	return $posterimg;
}



/*
** Main logo
*/
function mh_lg_logo_url()
{
	$lg_logo = get_theme_option('lg_logo');

	$logo_img = $lg_logo ? WEB_ROOT.'/archive/theme_uploads/'.$lg_logo : img('hm-logo.png');

	return $logo_img;
}




/*
** Icon file for iOS devices
** Used when the user saves a link to the website to their homescreen
** May also be used by other iOS apps, including a few RSS Readers (e.g. Reeder)
*/
function mh_apple_icon_logo_url()
{
	$apple_icon_logo = get_theme_option('apple_icon_144');

	$logo_img = $apple_icon_logo ? WEB_ROOT.'/archive/theme_uploads/'.$apple_icon_logo : img('Icon.png');

	return $logo_img;
}

/*
** Background image (home)
*/
function mh_bg_url()
{
	$bg_image = get_theme_option('bg_img');

	$img_url = $bg_image ? WEB_ROOT.'/archive/theme_uploads/'.$bg_image : img('bg-home.png');

	return $img_url;
}



/*
** Custom link CSS colors
*/
function mh_link_color()
{
	$color = get_theme_option('link_color');

	if ( ($color) && (preg_match('/^#[a-f0-9]{6}$/i', $color)) ){
		return $color;
	}
}

function mh_secondary_link_color()
{
	$color = get_theme_option('secondary_link_color');

	if ( ($color) && (preg_match('/^#[a-f0-9]{6}$/i', $color)) ){
		return $color;
	}
}

/*
** iOS App ID
** see mh_ios_smartbanner()
*/
function mh_app_id()
{
	$appID = (get_theme_option('ios_app_id')) ? get_theme_option('ios_app_id') : false;

	return $appID;
}

/*
** iOS Smart Banner
** Shown not more than once per day
*/
function mh_ios_smart_banner(){
	// show the iOS Smart Banner once per day if the app ID is set
	if (mh_app_id()!=false){
		$AppBanner = 'Curatescape_AppBanner_'.mh_app_id();
		$numericID=str_replace('id', '', mh_app_id());
		if (!isset($_COOKIE[$AppBanner])){
			echo '<meta name="apple-itunes-app" content="app-id='.$numericID.'">';
			setcookie($AppBanner, true,  time()+86400); // 1 day
		}
	}
}



/*
** Character normalization
** Used to strip away unwanted or problematic formatting
*/
function mh_normalize_special_characters( $str )
{
	# Quotes cleanup
	$str = str_replace( chr(ord("`")), "'", $str );        # `
	$str = str_replace( chr(ord("´")), "'", $str );        # ´
	$str = str_replace( chr(ord("?")), ",", $str );        # ?
	$str = str_replace( chr(ord("`")), "'", $str );        # `
	$str = str_replace( chr(ord("´")), "'", $str );        # ´
	$str = str_replace( chr(ord("?")), "\"", $str );        # ?
	$str = str_replace( chr(ord("?")), "\"", $str );        # ?
	$str = str_replace( chr(ord("´")), "'", $str );        # ´

	$unwanted_array = array(    '?'=>'S', '?'=>'s', '?'=>'Z', '?'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
		'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
		'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
		'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y');

	$str = strtr( $str, $unwanted_array );

	#For reasons yet unknown, only some servers may require an additional $unwanted_array item: 'height'=>'h&#101;ight'

	# Bullets, dashes, and trademarks
	$str = str_replace( chr(149), "&#8226;", $str );    # bullet ?
	$str = str_replace( chr(150), "&ndash;", $str );    # en dash
	$str = str_replace( chr(151), "&mdash;", $str );    # em dash
	$str = str_replace( chr(153), "&#8482;", $str );    # trademark
	$str = str_replace( chr(169), "&copy;", $str );    # copyright mark
	$str = str_replace( chr(174), "&reg;", $str );        # registration mark
	$str = str_replace( "&quot;", "\"", $str );        # "
	$str = str_replace( "&apos;", "\'", $str );        # '
	$str = str_replace( "&#039;", "'", $str );        # '

	return $str;
}

function mh_showmap(){
	return '<div id="showmap" class="hidden"><a style="cursor:pointer" ><i class="icon-map-marker"></i><i class="icon-camera-retro hidden"></i></a></div>';
}

?>
