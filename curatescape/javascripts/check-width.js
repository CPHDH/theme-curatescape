jQuery(document).ready(function() {
	var $window = jQuery(window);
	// exists? function
	jQuery.fn.exists = function() {
		return this.length > 0;
	};
	// Function to handle changes to style classes based on window width
	// Also swaps in thumbnails for larger views where user can utilize Fancybox image viewer
	// Also swaps #hero images in items/show header

	function checkWidth() {
		// Beakpoint for assigning small or big class
		var breakpoint = 720;
		if ($window.width() < breakpoint) {
			jQuery('body').removeClass('big').addClass('small');
			jQuery('.item-file a').removeClass('fancybox');
			jQuery(".item-file img").attr("src", function() {
				return this.src.replace('square_thumbnails', 'fullsize');
			});
			jQuery("#item-photos .description , #item-photos .title").show();
			jQuery('#map_canvas').hide(); // no maps for phones
			//find the first image for the item and set it as the background to the #hero div on items/show
			if (jQuery("body#items").hasClass('show item-story small')) {
				var imageUrl = jQuery("#item-photos .item-file img").attr('src');
				var styles = {
					'background-image': 'url(' + imageUrl + ')'
				};
				jQuery('#hero').css(styles);
			} //endif   
			// Determine the state of the slider for specific pages/viewports
			var slideStateSmall = /* these will use the slider */
			(jQuery("body#home").hasClass('home small')) || (jQuery("body#items").hasClass('browse items stories small')) || (jQuery("body#subject-browse").hasClass('subject-browse browse subjects small')) || (jQuery("body#items").hasClass('browse tags small')) || (jQuery("body#tours").hasClass('browse small')) || (jQuery("body#items").hasClass('browse queryresults small')) || (jQuery("body#tours").hasClass('show tour small'));
			//grabs the "recent stories" content to build the slider and swaps it into the #hero div on homepage
			if (slideStateSmall) {
				jQuery(".item-result img").attr("src", function() {
					return this.src.replace('square_thumbnails', 'fullsize');
				});
				var titles = []; // get the titles
				jQuery(".item-result h3").each(function(index) {
					titles[index] = '<span class="title">' + jQuery(this).html() + '</span>';
				});
				var images = []; // get the images
				jQuery(".item-result img").each(function(index) {
					images[index] = jQuery(this).attr('src');
				});
				var slideCount = images.length; // use number of images to set number of slides	
				var slideNav = '';
				if (!jQuery('#slider').exists()) { // prevent duplicates during window resize
					var slideDiv;
					slideDiv = '<div id="slider"><ul>';
					for (var i = 0; i < slideCount; i++) {
						slideDiv += '<li style="display:block;background-image:url(' + images[i] + ')"><div>' + titles[i] + '</div></li>';
						current = (i == 0) ? 'class="current"' : '';
						slideNav += '<li ' + current + '  onclick="mySwipe.slide('+i+', 300)"><em>' + i + '</em></li>';
					}
					slideDiv += '</ul></div>' + '<nav id="swipenav"><ul id="position">' + slideNav + '</ul></nav>';
					jQuery('#hero').append(slideDiv);
					window.mySwipe = new Swipe(document.getElementById('slider'), {
						speed: 500,
						auto: 5000,
						callback: function(e, pos) {
							var si = mySwipe.index;
							var i = bullets.length;
							
							while (i--) {
								bullets[i].className = ' ';
							}
							bullets[si].className = 'current';
						}
					});
					bullets = document.getElementById('position').getElementsByTagName('li');
					jQuery(document).keydown(function(e){
					    if (e.keyCode == 37) { //left arrow
					       mySwipe.prev();
					       return false;
					    }
					    if (e.keyCode == 39) { //right arrow 
					       mySwipe.next();
					       return false;
					    }					    
					});					
				} else {
					jQuery('#hero #slider').show();
				}
			} //endif
		}
		if ($window.width() >= breakpoint) {
			jQuery('body').removeClass('small').addClass('big');
			jQuery('.item-file a').addClass('fancybox');
			jQuery(".item-file img").attr("src", function() {
				return this.src.replace('fullsize', 'square_thumbnails');
			});
			jQuery("#item-photos .description , #item-photos .title").hide();
			jQuery('#map_canvas').show('fast', 'linear'); // use the map on larger viewports
			//jQuery("#recent-story").show(); // make sure this is visible since we hide it after moving contents to slideshow
			//swaps image background in the #hero div
			if (jQuery("body#items").hasClass('show item-story big')) {
				var styles = {
					'background-image': 'none'
				};
				jQuery('#hero').css(styles);
				jQuery('h4.sib').each(function() {
					jQuery('h4.sib').toggle(

					function() {
						jQuery(this).siblings('.sib').show('fast', 'linear');
					}, function() {
						jQuery(this).siblings('.sib').hide('fast', 'linear');
					});
				}); //endeach
			} //endif              
			// Determine the state of the slider for specific pages/viewports
			var slideStateBig = /* these will not use the slider, but are used to hide it on window resize */
			(jQuery("body#home").hasClass('home big')) || (jQuery("body#items").hasClass('browse items stories big')) || (jQuery("body#subject-browse").hasClass('subject-browse browse subjects big')) || (jQuery("body#items").hasClass('browse tags big')) || (jQuery("body#tours").hasClass('show tour big')) || (jQuery("body#items").hasClass('browse queryresults big')) || (jQuery("body#tours").hasClass('browse big'));
			//removes the slider from the #hero div
			if (slideStateBig) {
				jQuery('#hero #slider').hide();
			} //endif
		}
	}
	// Execute on load
	checkWidth();
	// Bind event listener
	jQuery($window).resize(checkWidth);

	// ShowMap script for "mobile" views
	jQuery('#showmap a').click(function(){
		jQuery('#map_canvas').slideToggle('fast', 'linear',function(){
            var map = jQuery('#map_canvas').gmap('get', 'map');
            var bounds=map.getBounds();
            var center=bounds.getCenter();
            var znum=map.getZoom();
            if(eval(znum)<10){
	            znum=10;
            }
            google.maps.event.trigger(map, "resize");
                	map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
	                map.fitBounds(bounds);
	                map.setCenter(center);
	                map.setZoom(eval(znum)); 
	                
		});
		jQuery('#slider').slideToggle('fast', 'linear');
		jQuery('#swipenav').slideToggle('fast', 'linear');
	 });	
	
});