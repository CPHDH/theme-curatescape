jQuery(document).ready(function() {
	var $window = jQuery(window);
	// exists? function
	jQuery.fn.exists = function() {
		return this.length > 0;
	};
	
	function buildSwipeJS(){
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

	 function buildTileMontage(){
			if (!jQuery('#tile-outer-container').exists()){
			
				jQuery(".item-result img").attr("src", function() {
					return this.src.replace('square_thumbnails', 'fullsize');
				});
				var tileLinks = []; // get the titles
				jQuery(".item-result h3 a").each(function(index) {
					tileLinks[index] = jQuery(this).attr('href');
				});
	
				var tileTitles = []; // get the titles
				jQuery(".item-result h3").each(function(index) {
					tileTitles[index] = jQuery(this).text();
				});
				
				var tileImages = []; // get the images
				jQuery(".item-result img").each(function(index) {
					tileImages[index] = jQuery(this).attr('src');
				});
				
				var tileCount = tileImages.length; 			
				
				var tileDiv;
				tileDiv ='<div id="tile-outer-container" style=""><div class="tile-container" id="tile-container">';
				for (var i = 0; i < tileCount; i++) {
					tileDiv += '<div class="box"><a class="tile-item" href="'+ tileLinks[i] +'"><img src="'+ tileImages[i] +'" title="'+ tileTitles[i] +'"></img></a></div>';
				}	
				
				
				tileDiv +='</div></div>';				
				
				undoSwipeJS();
				jQuery('#hero').append(tileDiv);
				
				/* 
				the ideal "safe" number of images to use here is about 15-20, 
				since we have 5 (CSS) columns, each of which will include 2-3 images, 
				depending on their size...
				if the number of tours is low, we'll repeat the tile html...
				*/
				var safeNum=20;
				if((tileCount<safeNum)&&tileCount>0){
					var n= safeNum/parseInt(tileCount);
					var repeater='';
					for(i=0;i<n;i++){
						repeater += jQuery('#hero .tile-container').html();	
					}
					jQuery('#hero .tile-container').append(repeater);
				}	
				
							
			}else{
				jQuery('#tile-outer-container').show();
			}		 
	 }


	function doTileMontage(){
		var yesTiles = /* these will use the tile montage */
		(jQuery("body#tours").hasClass('browse big')) ||
		(jQuery("body").hasClass('page simple-page show big')) ||
		(jQuery("body").hasClass('page simple-page show small'));					
		if (yesTiles) {
			buildTileMontage();
			undoSwipeJS();	
			jQuery('#tile-outer-container').show();	
			jQuery('#tile-container').show();		
			jQuery(window).load(function(){
				jQuery('.box').fadeIn('slow');
				});
			//we use CSS to create the tile montage thing...
			} 
	}
	

	function itemShowHeroImg(){
		//find the first image for the item and set it as the background to the #hero div on items/show
		if (jQuery("body#items").hasClass('show item-story small')) {
			var imageUrl = jQuery("#item-photos .item-file img").attr('src');
			var styles = {
				'background-image': 'url(' + imageUrl + ')'
			};
			jQuery('#hero').css(styles);
		} //endif  
	}

	function doSwipeJS(){
		// Determine the state of the slider for specific pages/viewports
		var yesSwipe = /* these will use the slider */
		(jQuery("body#home").hasClass('home small')) || 
		(jQuery("body#items").hasClass('browse items stories small')) || 
		(jQuery("body#subject-browse").hasClass('subject-browse browse subjects small')) || 
		(jQuery("body#items").hasClass('browse tags small')) || 
		(jQuery("body#tours").hasClass('browse small')) || 
		(jQuery("body#items").hasClass('browse queryresults small')) || 
		(jQuery("body#tours").hasClass('show tour small'));
		//grabs the "recent stories" content to build the slider and swaps it into the #hero div on homepage
		if (yesSwipe) {
			undoTileMontage();
			buildSwipeJS();
		} 
	}
			
	function toggleDescriptions(){
		// toggle media file description visibility (speach audio/video bubbles)
		if (jQuery("body#items").hasClass('show item-story big')) {
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
	
	function undoTileMontage(){           
			jQuery('#tile-outer-container').hide();	
			jQuery('#tile-container').hide();
	}	

	function itemMapToggle(){
		// ShowMap script for "mobile" views
		
		jQuery('#showmap a').click(function(){
			
			jQuery('#map_canvas').slideToggle('fast', 'linear',function(){
	            var map = jQuery('#map_canvas').gmap('get', 'map');
	            var bounds=map.getBounds();
	            var center=bounds.getCenter();
	            var znum=map.getZoom();
	            if( (jQuery('body#tours')) && (znum<8)){
		            // for whatever reason, we need to manually tweak the zoom level on the tour maps
		            znum=8;
	            }
	            google.maps.event.trigger(map, "resize");
	                	map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
		                map.fitBounds(bounds);
		                map.setZoom(eval(znum));
						});
						
			jQuery('#slider').slideToggle('fast', 'linear');
			jQuery('#swipenav').slideToggle('fast', 'linear');	
			jQuery('#showmap a').toggleClass('mapview');
			jQuery('#showmap a i').toggleClass('hidden');
		 });
	 }itemMapToggle();

	// Function to handle changes to style classes based on window width
	// Also swaps in thumbnails for larger views where user can utilize Fancybox image viewer
	// Also swaps #hero images in items/show header

	function checkWidth() {
	
		// Beakpoint for assigning small or big class
		var breakpoint = 720;
		
		if ($window.width() < breakpoint) {
			
			/*TOGGLE CLASSES*/
			jQuery('body').removeClass('big').addClass('small');
			jQuery('.item-file a').removeClass('fancybox');	
			
			/*TOGGLE VISIBILITY*/
			jQuery("#item-photos .description , #item-photos .title").show();
			jQuery('#map_canvas').hide(); 
				
			
			/*TOGGLE ITEM-IMAGE SIZES*/
			jQuery(".item-file img").attr("src", function() {
				return this.src.replace('square_thumbnails', 'fullsize');
			});
			
			/*FUNCTIONS*/
			itemShowHeroImg();

			doSwipeJS();
			
			doTileMontage();
			
			
		}
		if ($window.width() >= breakpoint) {
		
			/*TOGGLE CLASSES*/
			jQuery('body').removeClass('small').addClass('big');
			jQuery('.item-file a').addClass('fancybox');
			
			/*TOGGLE VISIBILITY*/
			jQuery("#item-photos .description , #item-photos .title").hide();
			jQuery('#map_canvas').show(); 
			undoSwipeJS();
		

			/*TOGGLE ITEM-IMAGE SIZES*/
			jQuery(".item-file img").attr("src", function() {
				return this.src.replace('fullsize', 'square_thumbnails');
			});

			/*FUNCTIONS*/			
			toggleDescriptions(); 
		
			doTileMontage();		
		
		}
	}
	// Execute on load
	checkWidth();
	// Bind event listener
	jQuery($window).resize(checkWidth);
	
});