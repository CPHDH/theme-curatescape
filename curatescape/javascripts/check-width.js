jQuery(document).ready(function() {
	var $window = jQuery(window);
	// exists? function
	jQuery.fn.exists = function() {
		return this.length > 0;
	};
	
	function buildSwipeJS(){

		var titles = []; // get the titles
		
		jQuery(".item-result.has-image h3").each(function(index) {
			titles[index] = '<span class="title">' + jQuery(this).html() + '</span>';
		});
		var images = []; // get the images
		jQuery(".item-result.has-image .item-image").each(function(index) {
			images[index] = jQuery(this).css('background-image');
		});
		var slideCount = images.length; // use number of images to set number of slides	
		var slideNav = '';
		if (!jQuery('#slider').exists()) { // prevent duplicates during window resize
			var slideDiv;
			slideDiv = '<div id="slider"><ul>';
			for (var i = 0; i < slideCount; i++) {

				slideDiv += '<li style="display:block;background-image:' + images[i].replace(/"/g, "'") + '"><div>' + titles[i] + '</div></li>';
				current = (i == 0) ? 'class="current"' : '';
				slideNav += '<li ' + current + '  onclick="mySwipe.slide('+i+', 300)"><em>' + i + '</em></li>';
			}
			slideDiv += '</ul></div>';
			
			if(!jQuery('#hero #swipenav').exists()){
				slideDiv += '<nav id="swipenav"><ul id="position">' + slideNav + '</ul></nav>';
			}
			
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
			jQuery('#hero #swipenav').show();
		}		
	}


	

	function itemShowHeroImg(hasImage){
		//find the first image for the item and set it as the background to the #hero div on items/show
		if (jQuery("body#items").hasClass('show')) {
			if(hasImage){
				var styles = {'background-image': 'url(' + hasImage + ')'};
				jQuery('#hero').css(styles);
			}

		} //endif  
	}

	function doSwipeJS(){
		// Determine the state of the slider for specific pages/viewports
		var yesSwipe = /* these will use the slider */
		(jQuery("body#home").hasClass('home small')) || 
		(jQuery("body#home").hasClass('home expand-map small')) || 
		(jQuery("body#items").hasClass('browse items stories small')) || 
		(jQuery("body#subject-browse").hasClass('subject-browse browse subjects small')) || 
		(jQuery("body#items").hasClass('browse tags small')) || 
		(jQuery("body#items").hasClass('browse queryresults small')) || 
		(jQuery("body#tours").is('.show, .small'));
		//grabs the "recent stories" content to build the slider and swaps it into the #hero div on homepage
		if (yesSwipe) {
			buildSwipeJS();
		} 
	}
			
	function toggleDescriptions(){
		// toggle media file description visibility (speach audio/video bubbles)
		if (jQuery("body#items").is('.show,.big')) {
			jQuery('h4.sib').each(function() {
				jQuery('h4.sib').toggle(

				function() {
					jQuery(this).siblings('.sib').show('fast', 'linear');
				}, function() {
					jQuery(this).siblings('.sib').hide('fast', 'linear');
				});
			}); //endeach
		} //endif  
	}				


	function undoSwipeJS(){           
			jQuery('#hero #slider').hide();
			jQuery('#hero #swipenav').hide();
	}
	



	// Function to handle changes to style classes based on window width
	// Also swaps in thumbnails for larger views where user can utilize Fancybox image viewer
	// Also swaps #hero images in items/show header

	function checkWidth() {
		
		var hasImage=jQuery("#item-photos .item-file img").attr('src');
		if(!hasImage){
			jQuery('body#items.show').addClass('no-image-for-hero');
		}	
		
		// Beakpoint for assigning small or big class
		var breakpoint = 720;
		
		if ($window.width() < breakpoint) {
			
			/*TOGGLE CLASSES*/
			jQuery('body').removeClass('big').addClass('small');
			jQuery('content .item-file a').removeClass('fancybox');	
			
			/*TOGGLE VISIBILITY*/
			jQuery("#item-photos .description , #item-photos .title").show();
			
			jQuery('#map_canvas').hide(); 

				
			
			/*TOGGLE ITEM-IMAGE SIZES*/
			jQuery("#items .item-file img").attr("src", function() {
				return this.src.replace('square_thumbnails', 'fullsize');
			});
			
			/*FUNCTIONS*/
			itemShowHeroImg(hasImage);
			
			doSwipeJS();
			
			
			
		}
		if ($window.width() >= breakpoint) {
		
			/*TOGGLE CLASSES*/
			jQuery('body').removeClass('small').addClass('big');
			jQuery('#content .item-file a').addClass('fancybox');

			
			/*TOGGLE VISIBILITY*/
			jQuery("#item-photos .description , #item-photos .title").hide();
			jQuery('#map_canvas').show(); 
			undoSwipeJS();
		

			/*TOGGLE ITEM-IMAGE SIZES*/
			jQuery("#items .item-file img").attr("src", function() {
				return this.src.replace('fullsize', 'square_thumbnails');
			});

			/*FUNCTIONS*/			
			toggleDescriptions(); 
				
		}
				
	}
	// Execute on load
	checkWidth();
	
	// Bind event listener
	jQuery($window).resize(checkWidth);

});