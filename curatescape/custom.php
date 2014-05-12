<?php

/**
 * Add Curatescape translations directory for all requests.
 */
add_translation_source(dirname(__FILE__) . '/languages');

// Build some custom data for Facebook Open Graph, Twitter Cards, general SEO, etc...

// SEO Page description
function mh_seo_pagedesc($item=null,$tour=null,$file=null){
	if($item != null){
		$itemdesc=snippet(metadata('item',array('Dublin Core', 'Description')),0,500,"...");
		return strip_tags($itemdesc);
	}elseif($tour != null){
		$tourdesc=snippet(tour('Description'),0,500,"...");
		return strip_tags($tourdesc);
	}elseif($file != null){
		$filedesc=snippet(metadata('file',array('Dublin Core', 'Description')),0,500,"...");
		return strip_tags($filedesc);		
	}else{
		return mh_seo_sitedesc();
	}
}

// SEO Site description
function mh_seo_sitedesc(){
	return mh_about() ? strip_tags(mh_about()) : strip_tags(option('description'));
}

// SEO Page Title
function mh_seo_pagetitle($title){
	return $title ? htmlspecialchars($title).' | '.option('site_title') : option('site_title');	
}

// SEO Page image
function mh_seo_pageimg($item=null,$file=null){
	if($item){
		if(metadata($item, 'has thumbnail')){
			$itemimg=item_image('square_thumbnail') ;	
			preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $itemimg, $result);
			$itemimg=array_pop($result);
		}
	}elseif($file){
		if($itemimg=file_image('square_thumbnail') ){
			preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $itemimg, $result);
			$itemimg=array_pop($result);
		}
	}	
	return isset($itemimg) ? $itemimg : mh_lg_logo_url();
}


/*
** Modify Omeka's auto_discovery_link_tags
** Changes labels for feed names to be more appropriate
** Introduces item limit to avoid excessive memory use
*/
function mh_auto_discovery_link_tags() {
	// if "Super RSS" output is available use it.
    if(plugin_is_active('SuperRSS')){
    	$html = '<link rel="alternate" type="application/rss+xml" title="'. __('New %s: RSS',mh_item_label('plural')) . '" href="'. html_escape(items_output_url('srss')) .'&per_page=15" />';
    }else{
	    $html = '<link rel="alternate" type="application/rss+xml" title="'. __('New %s: RSS',mh_item_label('plural')) . '" href="'. html_escape(items_output_url('rss2')) .'&per_page=15" />';
	    $html .= '<link rel="alternate" type="application/atom+xml" title="'. __('New %s: Atom',mh_item_label('plural')) .'" href="'. html_escape(items_output_url('atom')) .'&per_page=15" />';
	    return $html;
    }
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
		return __('Take a %s', mh_tour_label_option('singular'));
	}
}
/*
** Global navigation
*/
function mh_global_nav(){
	if(get_theme_option('default_nav')!==0){
		return nav(array(
			array('label'=>__('Home'),'uri' => url('/')),
			array('label'=>mh_item_label('plural'),'uri' => url('items/browse')),
			array('label'=>mh_tour_label('plural'),'uri' => url('tours/browse/')),
			array('label'=>__('About'),'uri' => url('about/'))
			));
	}else{
		return public_nav_main();
	}
}

/*
** Get the correct logo for the page
** uses body class to differentiate between home, stealth-home, and other
*/
function mh_the_logo(){
	if ( ($bodyid='home') && ($bodyclass='public') ) {
		return '<img src="'.mh_lg_logo_url().'" class="home" id="logo-img" alt="'.option('site_title').'"/>';
	}elseif( ($bodyid='home') && ($bodyclass='stealth-mode') ){
		return '<img src="'.mh_stealth_logo_url().'" class="stealth" id="logo-img" alt="'.option('site_title').'"/>';
	}else{
		return '<img src="'.mh_med_logo_url().'" class="inner" id="logo-img" alt="'.option('site_title').'"/>';
	}
}

/*
** Link to Random item
*/

function random_item_link($text=null,$class='show'){
	if(!$text){
		$text= __('View a Random %s', mh_item_label('singular'));
	}

	$link = '';
	$randitems = get_records('Item', array( 'sort_field' => 'random', 'hasImage' => true), 1);

	if( count( $randitems ) > 0 ){
	   $linkclass = 'random-story-link ' . $class;
	   $link = link_to( $randitems[0], 'show', $text,
	   		array( 'class' => $linkclass ) );
	}else{
		$linkclass = 'random-story-link ' . $class;
	   $link = link_to( $record, $action, __('Publish some items to activate this link'),
	   		array( 'class' => $linkclass ) );
	}
   	return $link;

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

	$html  .= '<div id="mobile-menu-button"><a class="icon-reorder"><span class="visuallyhidden"> '.__('Menu').'</span></a></div>';
	$html .= '</div>';


	$html .= '<div id="mobile-menu-cluster" class="active">';

	$html .= '<nav id="primary-nav">';

	$html .= mh_global_nav();

	$html .= '</nav>';



	$html .= '<div id="search-wrap">';
	$html .= mh_simple_search($formProperties=array('id'=>'header-search'));

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
** Per_page limits overridden, set to 999
*/
function mh_display_map($type=null){
	$pluginlng=get_option( 'geolocation_default_longitude' );
	$pluginlat=get_option( 'geolocation_default_latitude' );
	$plugincenter = $pluginlat .','. $pluginlng;
	$zoom=(get_option('geolocation_default_zoom_level')) ? get_option('geolocation_default_zoom_level') : 12;

	switch($type){

	case 'focusarea':
		/* all stories, map is centered on focus area (plugin center) */
		$json_source=WEB_ROOT.'/items/browse?output=mobile-json&per_page=999';
		break;

	case 'global':
		/* all stories, map is bounded according to content */
		$json_source=WEB_ROOT.'/items/browse?output=mobile-json&per_page=999';
		break;

	case 'story':
		/* single story */
		$json_source='?output=mobile-json';
		break;

	case 'tour':
		/* single tour, map is bounded according to content  */
		$json_source='?output=mobile-json';
		break;

	case 'queryresults': 
		/* browsing by tags, subjects, search results, etc, map is bounded according to content */
		$json_source=$_SERVER['REQUEST_URI'].'&output=mobile-json&per_page=999';
		break;

	default:
		$json_source='/items/browse?output=mobile-json&per_page=999';

	}
	
	if(get_theme_option('custom_marker')){
		$marker='/files/theme_uploads/'.get_theme_option('custom_marker');
	}else{
		$marker='/themes/curatescape/images/map-icn.png';
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
		
		var fallbacklat='<?php echo $pluginlat ;?>';
		var fallbacklng='<?php echo $pluginlng ;?>';
		var fallbackmarker=null;
		
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
			},
		    'streetViewControl': true,
		    'streetViewControlOptions': {
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
					};

					jQuery('#map_canvas').gmap('addMarker', {
						'position': new google.maps.LatLng(lat, lng),
						'bounds': true,
						'icon': new google.maps.MarkerImage(marker),
					}).click(function() {
						jQuery('#map_canvas').gmap('openInfoWindow', { 'content': '<div style="margin:.25em;min-width:12.5em;line-height:1.7em; "><i class="icon-map-marker"></i> <a href="https://maps.google.com/maps?saddr=current+location&daddr='+lat+','+lng+'" onclick="return !window.open(this.href);"><?php echo __('Get Directions');?></a><br><small><em><?php echo __('Be sure to read the <a href="#map-faq" class="fancybox">MAP FAQ</a>');?>.</em></small></div>' }, this);
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

function mh_simple_search($formProperties=array(), $uri = null){
    // Always post the 'items/browse' page by default (though can be overridden).
    if (!$uri) {
        $uri = url('items/browse');
    }
    
    $searchQuery = array_key_exists('search', $_GET) ? $_GET['search'] : '';
    $formProperties['action'] = $uri;
    $formProperties['method'] = 'get';
    $html = '<form ' . tag_attributes($formProperties) . '>' . "\n";
    $html .= '<fieldset>' . "\n\n";
    $html .= get_view()->formText('search', $searchQuery, array('name'=>'search','class'=>'textinput','placeholder'=>__('Search %s',mh_item_label('plural'))));
    $html .= get_view()->formSubmit('submit_search', __("Search"));
    $html .= '</fieldset>' . "\n\n";
    
    // add hidden fields for the get parameters passed in uri
    $parsedUri = parse_url($uri);
    if (array_key_exists('query', $parsedUri)) {
        parse_str($parsedUri['query'], $getParams);
        foreach($getParams as $getParamName => $getParamValue) {
            $html .= get_view()->formHidden($getParamName, $getParamValue);
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

		echo '<h2>'.__('Downloads').'</h2>';

		$ios_app_id = get_theme_option('ios_app_id');
		echo ($ios_app_id ?
			'<a id="apple" class="app-store" href="https://itunes.apple.com/us/app/'.$ios_app_id.'">
		'.__('iOS App Store').'
		</a> ':'<a id="apple" class="app-store" href="#">
		'.__('Coming Soon').'
		</a> ');

		$android_app_id = get_theme_option('android_app_id');
		echo ($android_app_id ?
			'<a id="android" class="app-store" href="http://play.google.com/store/apps/details?id='.$android_app_id.'">
		'.__('Google Play').'
		</a> ':'<a id="android" class="app-store" href="#">
		'.__('Coming Soon').'
		</a> ');
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
	$html .='<h2>'.__('Frequently Asked Questions <span>about the map</span><').'/h2>';
	if((!get_theme_option('map_faq'))){
		   $html .=__('<h3><a>Are all the locations on %s publicly accessible?</a></h3>',option('site_title'));
		   $html .=__('<p>Not necessarily. It is up to you to determine if any given location is one you can physically visit.</p>');
		   $html .=__('<h3><a>How do you choose locations for each %s?</a> <span>or</span> <a>The location is wrong!</a></h3>',strtolower(mh_item_label()));
		   $html .=__('<p>Placing historical %1$s on a map can be tricky. We choose locations based on what we think makes the most sense. Sometimes we get it wrong (and sometimes there is no "right" answer). Feel free to email us %2$s with suggestions for improvement.</p>',strtolower(mh_item_label('plural')),$emailincl);
		   
	}else{
	$html .=get_theme_option('map_faq');
	}
	$html.='</div></div></div>';

	return $html;

}

/*
** author byline on items/show.php
*/
function mh_the_author(){
	if ((get_theme_option('show_author') == true)){
		$html='<span class="story-meta byline">'.__('By: ');

		if(metadata('item',array('Dublin Core', 'Creator'))){
			$authors=metadata('item',array('Dublin Core', 'Creator'), array('all'=>true));
			$total=count($authors);
			$index=1;
			$authlink=get_theme_option('link_author');

			foreach ($authors as $author){
				if($authlink==1){
					$href='../browse?search=&advanced[0][element_id]=39&advanced[0][type]=is+exactly&advanced[0][terms]='.$author.'&submit_search=Search';
					$author='<a href="'.$href.'">'.$author.'</a>';
				}
			
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
		$html .='</span>';

		echo $html;
	}
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
	
	$result = '';
	
	/* Find a URL in the $string and build the replacement */
	preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/',$string, $matches);
	if( count( $matches ) > 0 ){
	   $origURL = $matches[0];
	   $newURL=$origURL;
	   $newURL=preg_replace('/\//','/&#8203;', $newURL); //replace slash with slash + zero-width-space
	   $newURL=preg_replace('/\./','.&#8203;', $newURL); //replace dot with dot + zero-width-space
	
		/* Apply the repalcement URL to the original string */
		$result=preg_replace('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/',$newURL, $string);
	}

	/* Apply the repalcement URL to the original string */
	$result=preg_replace('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/',$newURL, $string);

	return $result;
}


/*
** Custom item citation
*/
function mh_item_citation(){
	return mh_wrappable_link(html_entity_decode(metadata('item', 'citation')));
}

/*
** Build caption from description, source, and creator
*/
function mh_file_caption($file,$inlineTitle=true){
   
   $caption=array();

   if( $inlineTitle !== false ){
	   $title = metadata( $file, array( 'Dublin Core', 'Title' ) ) ? metadata( $file, array( 'Dublin Core', 'Title' ) ) : null;
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
   
   if( count($caption) ){
	   return ($inlineTitle ? $title.': ' : null).implode(" | ", $caption);
   }else{
	   return $inlineTitle ? $title : null;
   }	
}

/*
** Loop through and display image files
*/
function mh_item_images($item,$index=0){
	//===========================// ?>
	<script>
	// the fancybox caption minimize/expand button
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

	foreach (loop('files', $item->Files) as $file){
		$img = array('image/jpeg','image/jpg','image/png','image/jpeg','image/gif');
		$mime = metadata($file,'MIME Type');
		
		if(in_array($mime,$img)) {
			if($index==0) echo '<h3><i class="icon-camera-retro"></i>Images <span class="toggle instapaper_ignore">Show <i class="icon-chevron-right"></i></span></h3>';		
			$filelink=link_to($file,'show', '<span class="view-file-link"> ['.__('View Additional File Details').']</span>',array('class'=>'view-file-record','rel'=>'nofollow'));	
			$photoDesc = mh_normalize_special_characters(strip_tags(mh_file_caption($file,false),'<a><strong><em><i><b>'));
			$photoTitle = mh_normalize_special_characters(metadata($file,array('Dublin Core', 'Title')));
			
			if($photoTitle){
				$fancyboxCaption= mh_normalize_special_characters(mh_file_caption($file,true));
				$fancyboxCaption = '<span class="main">'.strip_tags($fancyboxCaption,'<a><strong><em><i><b>').'</span>'.$filelink;
				}else{
					$fancyboxCaption = '<span class="main">Image '.($index+1).'</span>';	
				}

			$html .= '<div class="item-file-container">';

			$html .= file_markup($file, array('imageSize' => 'fullsize','linkAttributes'=>array('data-caption'=>$fancyboxCaption,'title'=>$photoTitle, 'class'=>'fancybox', 'rel'=>'group'),'imgAttributes'=>array('alt'=>$photoTitle) ) );

			$html .= ($photoTitle) ? '<h4 class="title image-title">'.$photoTitle.'</h4>' : '';
			$html .= ($photoDesc) ? '<p class="description image-description">'.$photoDesc.' '.link_to($file,'show', '<span class="view-file-link"> ['.__('View Additional File Details').']</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';
			$html .= '</div>';

		}
	}
	$count = count($myphoto);
	if ($count > 0) {
		echo '<h3><i class="icon-camera-retro"></i>'.(($count > 1) ? __('Photos ') : __('Photo ')).'<span class="toggle instapaper_ignore">'.__('Show ').'<i class="icon-chevron-right"></i></span></h3>';			
		echo $html;
	}
}


/*
 ** Loop through and display audio files
 ** FYI: adding "controls" to html <audio> tag causes a
** display error when used in combination w/ Fancybox
** image viewer
*/
function mh_audio_files($item,$index=0){
    if (!$item){
    $item=set_loop_records('files',$item);
    }
	$audioTypes = array('audio/mpeg');
	foreach (loop('files', $item->Files) as $file):
		$audioDesc = strip_tags(mh_file_caption($file,false));
		$audioTitle = metadata($file,array('Dublin Core','Title'));
		$mime = metadata($file,'MIME Type');

		if ( array_search($mime, $audioTypes) !== false ) {
	
			if ($index==0) echo '<h3><i class="icon-volume-up"></i>Audio <span class="toggle instapaper_ignore">Show <i class="icon-chevron-right"></i></span></h3><script>audiojs.events.ready(function() {var as = audiojs.createAll();});</script>';
			$index++;
		
			$html = '<div class="item-file-container">';
			$html .= '<audio><source src="'.file_display_url($file,'original').'" type="audio/mpeg" /><h5 class="no-audio"><strong>'.__('Download Audio').':</strong><a href="'.file_display_url($file,'original').'">MP3</a></h5></audio>';
			$html .= ($audioTitle) ? '<h4 class="title audio-title sib">'.$audioTitle.' <i class="icon-info-sign"></i></h4>' : '';
			$html .= ($audioDesc) ? '<p class="description audio-description sib">'.$audioDesc.' '.link_to($file,'show', '<span class="view-file-link"> ['.__('View Additional File Details').']</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';
			$html .= '</div>';
			
			echo $html;
		}

	endforeach;
}


/*
 ** Loop through and display video files
** Please use H.264 video format
** Browsers that do not support H.264 will fallback to Flash
** We accept multiple H.264-related MIME-types because Omeka MIME detection is sometimes spotty
** But in the end, we always tell the browser they're looking at "video/mp4"
** Opera and Firefox are currently the key browsers that need flash here, but that may change
*/
function mh_video_files($item,$html=null) {
        if (!$item){
        $item=set_loop_records('files',$item);
        }
        $videoIndex = 0;
        $localVid=0;
        $videoTypes = array('video/mp4','video/mpeg','video/quicktime');
        $videoPoster = mh_poster_url();


        foreach (loop('files', $item->Files) as $file):
        	$videoMime = metadata($file,'MIME Type');
	        if ( in_array($videoMime,$videoTypes) ){
		        
		        $videoFile = file_display_url($file,'original');
		        $videoTitle = metadata($file,array('Dublin Core', 'Title'));
		        $videoClass = (($videoIndex==0) ? 'first' : 'not-first');
		        $videoDesc = mh_file_caption($file,false);
		        $videoTitle = metadata($file,array('Dublin Core','Title'));	        
	        	$embeddable=embeddableVersion($file,$videoTitle,$videoDesc);
	        	if($embeddable){
	        	// If a video has an embeddable streaming version, use it.
		        	$html.= $embeddable;
		        	$videoIndex++;
		        	//break;
	        	}else{
	
	                $html .= '<div class="item-file-container">';
	                $html .= '<video width="640" height="360" id="video-'.$localVid.'" class="'.$videoClass.' video-js vjs-default-skin" controls poster="'.$videoPoster.'" preload="auto" data-setup="{}">';
	                $html .= '<source src="'.$videoFile.'" type="video/mp4">';
	                $html .= '</video>';
	                $html .= ($videoTitle) ? '<h4 class="title video-title sib">'.$videoTitle.' <i class="icon-info-sign"></i></h4>' : '';
	                $html .= ($videoDesc) ? '<p class="description video-description sib">'.$videoDesc.' '.link_to($file,'show', '<span class="view-file-link"> ['.__('View Additional File Details').']</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';
	                $html .= '</div>';
					$localVid++;
	                $videoIndex++;
	             }
	        }
        endforeach;
        if ($videoIndex > 0) {
        		echo '<link href="http://vjs.zencdn.net/4.3/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/4.3/video.js"></script><h3><i class="icon-film"></i>'.(($videoIndex > 1) ? __('Videos ') : __('Video ')).'<span class="toggle instapaper_ignore">'.__('Show ').'<i class="icon-chevron-right"></i></span></h3>';
                echo $html;
        }
}  

/* 
** Checks file metadata record for embeddable version of video file
** Because YouTube and Vimeo have better compression, etc.
** returns string $html | false
*/
function embeddableVersion($file,$title=null,$desc=null,$field=array('Dublin Core','Relation')){
	
	$youtube= (strpos(metadata($file,$field), 'youtube.com')) ? metadata($file,$field) : false;
	$vimeo= (strpos(metadata($file,$field), 'vimeo.com')) ? metadata($file,$field) : false;

	if($youtube) {
	// assumes YouTube links look like https://www.youtube.com/watch?v=NW03FB274jg where the v query contains the video identifier
		$url=parse_url($youtube);
		$id=str_replace('v=','',$url['query']);
		$html= '<div class="item-file-container"><div class="embed-container youtube" id="v-streaming" style="position: relative;padding-bottom: 56.25%;height: 0; overflow: hidden;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="//www.youtube.com/embed/'.$id.'" frameborder="0" width="640" height="360" allowfullscreen></iframe></div>';
	    $html .= ($title) ? '<h4 class="title video-title sib">'.$title.' <i class="icon-info-sign"></i></h4>' : '';
	    $html .= ($desc) ? '<p class="description video-description sib">'.$desc.' '.link_to($file,'show', '<span class="view-file-link"> ['.__('View Additional File Details').']</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';	
	    $html.='</div>';
	    return $html;		
		}
	elseif($vimeo) {
	// assumes the Vimeo links look like http://vimeo.com/78254514 where the path string contains the video identifier
		$url=parse_url($vimeo);
		$id=$url['path'];
		$html= '<div class="item-file-container"><div class="embed-container vimeo" id="v-streaming" style="padding-top:0; height: 0; padding-top: 25px; padding-bottom: 67.5%; margin-bottom: 10px; position: relative; overflow: hidden;"><iframe style=" top: 0; left: 0; width: 100%; height: 100%; position: absolute;" src="//player.vimeo.com/video'.$id.'?color=333" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
	    $html .= ($title) ? '<h4 class="title video-title sib">'.$title.' <i class="icon-info-sign"></i></h4>' : '';
	    $html .= ($desc) ? '<p class="description video-description sib">'.$desc.' '.link_to($file,'show', '<span class="view-file-link"> ['.__('View Additional File Details').']</span>',array('class'=>'view-file-record','rel'=>'nofollow')).'</p>' : '';	
	    $html.='</div>';	
	    return $html;	
		}
	else{
		return false;
	}	
}


/*
** Display subjects as links
** These links are hard to validate via W3 for some reason
*/
function mh_subjects(){
	$subjects = metadata('item',array('Dublin Core', 'Subject'), 'all');
	if (count($subjects) > 0){

		echo '<h3>'.__('Subjects').'</h3>';
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

function mh_subjects_string(){
	$subjects = metadata('item',array('Dublin Core', 'Subject'), 'all');
	if (count($subjects) > 0){
		$html=array();

		foreach ($subjects as $subject){
			$link = WEB_ROOT;
			$link .= htmlentities('/items/browse?term=');
			$link .= rawurlencode($subject);
			$link .= htmlentities('&search=&advanced[0][element_id]=49&advanced[0][type]=contains&advanced[0][terms]=');
			$link .= urlencode(str_replace('&amp;','&',$subject));
			$link .= htmlentities('&submit_search=Search');
			$html[]= '<a href="'.$link.'">'.$subject.'</a>';
		}
		
		echo '<div class="item-subjects"><p><span>'.__('Subjects: ').'</span>'.implode(", ", $html).'</p></div>';
	}
}


/*
Display nav items for Simple Pages sidebar
** (not currently very useful, but we might add some novel content later)
*/
function mh_sidebar_nav(){

	return mh_global_nav();

}


/*
** Display the item tags
*/
function mh_tags(){
	if (metadata('item','has tags')):
		echo '<h3>'.__('Tags').'</h3>';
		echo tag_cloud('item','items/browse');
	endif;
}

/*
** Display related links
*/
function mh_related_links(){
	$relations = metadata('item',array('Dublin Core', 'Relation'), array('all' => true));
	if ($relations){
		echo '<h3>'.__('Related Sources').'</h3><ul>';
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
function mh_share_this($type='Page'){
	$addthis = get_theme_option('Add This') ? '#pubid='.get_theme_option('Add This') : null;
	$tracking= ($addthis && get_theme_option('track_address_bar')) ? '"data_track_addressbar":true' : null;

	$html = '<h3>'.__('Share this %s',$type).'</h3>';
	$html .= '<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_twitter"></a>
<a class="addthis_button_facebook"></a>
<a class="addthis_button_email"></a>
<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript">var addthis_config = {'.$tracking.'};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js'.$addthis.'"></script>
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
    <noscript><?php echo __('Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a>');?></noscript>
    <a href="http://disqus.com" class="dsq-brlink"><?php echo __('comments powered by <span class="logo-disqus">Disqus</span>');?></a>
	<?php
	}
}


/*
** Subnavigation for items/browse pages
*/

function mh_item_browse_subnav(){
		echo nav(array(
				array('label'=>'All' ,'uri'=> url('items/browse')),
				array('label'=>'Tags', 'uri'=> url('items/tags')),
			));
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
	shuffle($items);
	$num = (count($items)<$num)? count($items) : $num;

	echo '<h2>'.mh_tour_header().'</h2>';

	for ($i = 0; $i < $num; $i++) {
		echo '<article class="item-result">';
		echo '<h3 class="home-tour-title"><a href="' . WEB_ROOT . '/tours/show/'. $items[$i]['id'].'">' . $items[$i]['title'] . '</a></h3>';
		echo '</article>';
	}

	echo '<p class="view-more-link"><a href="'.WEB_ROOT.'/tours/browse/">'.__('View all <span>%1$s %2$s</span>', count($items), mh_tour_label('plural')).'</a></p>';

}






/*
** Display random featured item
** Used on homepage
*/
function mh_display_random_featured_item($withImage=false)
{
	$featuredItem = get_random_featured_items(1,$withImage);
	$html = '<h2>'.__('Featured %s', mh_item_label()).'</h2>';
	$html .= '<article class="item-result '.($withImage ? "has-image" : null).'">';
	if ($featuredItem) {
		$item=$featuredItem[0];
		$itemTitle = metadata($item, array('Dublin Core', 'Title'));


		if (metadata($item, 'has thumbnail') ) {
			$html .= '<div class="item-thumb">' . link_to_item(item_image('fullsize',array(),0, $item), array(), 'show', $item) . '</div>';
		}

		$html .= '<h3>' . link_to_item($itemTitle, array(), 'show', $item) . '</h3>';

		// Grab the 1st Dublin Core description field (first 150 characters)
		if ($itemDescription = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>200))) {
			$html .= '<div class="item-description">' . strip_tags($itemDescription) . '</div>';
		}else{
			$html .= '<div class="item-description empty">'.__('Preview text not available.').'</div>';}

		$html .= '<p class="view-more-link">'. link_to_item(__('Continue reading <span>%s</span>', $itemTitle), array(), 'show', $item) .'</p>';
	}else {
		$html .= '<div class="item-thumb clearfix"></div><div class="item-description"><p>'.__('No featured items are available.').'</p></div>';
	}
	$html .= '</article>';

	return $html;
}


/*
** Display the most recently added item
** Used on homepage
*/
function mh_display_recent_item($num=1){
	echo ($num <=1) ? '<h2>'.__('Newest %s',mh_item_label()).'</h2>' : '<h2>'.__('Newest %s',mh_item_label('plural')).'</h2>';
	set_loop_records('Item',get_records('Item',array('recent'=>true,'hasImage'=>true), $num));
	
	if (has_loop_records('items')){
		foreach (loop('items') as $item){

			echo '<article class="item-result has-image">';

			echo '<h3>'.link_to_item(metadata($item,array('Dublin Core','Title'))).'</h3>';
			
	
			$hasImage=metadata($item, 'has thumbnail');
			if ($hasImage){
					preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', item_image('fullsize'), $result);
					$item_image = array_pop($result);				
			}			

			echo isset($item_image) ? link_to_item('<span class="item-image" style="background-image:url('.$item_image.');"></span>') : null;
			
			//echo '<div class="item-thumb">'.link_to_item(item_image('square_thumbnail')).'</div>';


			if($desc = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>200))){
				echo '<div class="item-description">'.$desc.'</div>';
			}else{
				echo '<div class="item-description">'.__('Text preview unavailable.').'</div>';
			}

			echo '</article>';

		}
	}
	echo '<p class="view-more-link">'.link_to_items_browse(__('View all %s',mh_item_label('plural'))).'</p>';
}


/*
** Display random items
** Used on homepage
*/

function mh_display_random_item($num=1){
	echo ($num <=1) ? '<h2>'.__('Random %s', mh_item_label()).'</h2>' : '<h2>'.__('Random %s', mh_item_label('plural')).'</h2>';
	set_loop_records('items',get_random_featured_items($num,true));
	if (has_loop_records('items')){
		foreach (loop('items') as $item){
			echo '<article class="item-result has-image">';

			echo '<h3>'.link_to_item(metadata($item,array('Dublin Core','Title'))).'</h3>';

			$hasImage=metadata($item, 'has thumbnail');
			if ($hasImage){
					preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', item_image('fullsize'), $result);
					$item_image = array_pop($result);				
			}			

			echo isset($item_image) ? link_to_item('<span class="item-image" style="background-image:url('.$item_image.');"></span>') : null;


			if($desc = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>200))){
				echo '<div class="item-description">'.$desc.'</div>';
			}else{
				echo '<div class="item-description">'.__('Text preview unavailable.').'</div>';
			}

			echo '</article>';

		}
	}


	echo '<p class="view-more-link">'.link_to_items_browse(__('View all %s',mh_item_label('plural'))).'</p>';
}

/*
** Display the customizable "About" content on homepage
** also sets content for mobile slideshow, via mh_random_or_recent()
*/
function mh_custom_content($length=650){
	$html ='';
	
	$html .= '<article>';
	
	$html .= '<header>';	
	$html .= '<h2><span class="hidden">'.__('About ').'</span>'.option('site_title').'</h2>';
	$html .= '<span class="find-us">'.mh_home_find_us().'</span>';
	$html .= '</header><div class="about-snippet">';

	$html .= '<div id="inline-logo"><img alt="'.option('site_title').' logo" src="'.mh_apple_icon_logo_url().'"/></div>';
	$html .= substr(mh_about(),0,$length);
	$html .= ($length < strlen(mh_about())) ? '...' : null;

	$html .= '</div></article>';

	$html .= '<p class="view-more-link"><a href="'.url('about').'">'.__('Read more <span>About Us</span>').'</a></p>';


	echo $html;

	echo '<div id="rr_home-items" class="hidden">';

	echo mh_random_or_recent(($mode=get_theme_option('random_or_recent') ? get_theme_option('random_or_recent') : 'random'));
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
		$findus=__('Find us on %s', join(' | ',$service));
	}else{
		$findus=__('A project by %s', mh_owner_link());
	}
	return $findus;
}


/*
** Build a link for the footer copyright statement and the fallback credit line on homepage 
** see: mh_home_find_us()
*/
function mh_owner_link(){

	$authname_fallback=(option('author')) ? option('author') : option('site_title');
	
	$authname=(get_theme_option('sponsor_name')) ? get_theme_option('sponsor_name') : $authname_fallback;
	
	return $authname;
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
	.vjs-default-skin .vjs-play-progress,.vjs-default-skin .vjs-volume-level,
	#swipenav #position li.current, .random-story-link.big-button{
		background-color:'.mh_link_color().' !important;}
	a{
		color:'.mh_link_color().'
		}
	blockquote{border-left-color:'.mh_link_color().'}	
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
function link_to_item_edit($item=null)
{
	if (is_allowed($item, 'edit')) {
		echo '<a class="edit" href="'. html_escape(url('admin/items/edit/')).metadata('item','ID').'">['.__('Edit').']</a>';
	}
}

/*
** File item link
*/
function link_to_file_edit($file=null)
{
	if (is_allowed($file, 'edit')) {
		echo ' <a class="edit" href="'. html_escape(url('admin/files/edit/')).metadata('file','ID').'">['.__('Edit').']</a>';
	}
}


/*
** <video> placeholder image
*/
function mh_poster_url()
{
	$poster = get_theme_option('poster');

	$posterimg = $poster ? WEB_ROOT.'/files/theme_uploads/'.$poster : img('poster.png');

	return $posterimg;
}



/*
** Main logo
*/
function mh_lg_logo_url()
{
	$lg_logo = get_theme_option('lg_logo');

	$logo_img = $lg_logo ? WEB_ROOT.'/files/theme_uploads/'.$lg_logo : img('hm-logo.png');

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

	$logo_img = $apple_icon_logo ? WEB_ROOT.'/files/theme_uploads/'.$apple_icon_logo : img('Icon.png');

	return $logo_img;
}


/*
** Background image (home)
*/
function mh_bg_url()
{
	$bg_image = get_theme_option('bg_img');

	$img_url = $bg_image ? WEB_ROOT.'/files/theme_uploads/'.$bg_image : img('bg-home.png');

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
	$str = str_replace( chr(ord("`")), "'", $str );        # `
	$str = str_replace( chr(ord("´")), "'", $str );        # ´
	$str = str_replace( chr(ord("´")), "'", $str );        # ´
	
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
	$str = str_replace( "£", "&#163;", $str );        # pounds £ '	

	$unwanted_array = array(    '?'=>'S', '?'=>'s', '?'=>'Z', '?'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
		'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
		'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
		'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y');

	$str = strtr( $str, $unwanted_array );

	#For reasons yet unknown, only some servers may require an additional $unwanted_array item: 'height'=>'h&#101;ight'

	return $str;
}

function mh_showmap(){
	return '<div id="showmap" class="hidden"><a style="cursor:pointer" ><i class="icon-map-marker"></i><i class="icon-camera-retro hidden"></i></a></div>';
}

?>
