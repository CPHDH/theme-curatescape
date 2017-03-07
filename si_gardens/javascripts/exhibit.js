jQuery(document).ready(function() {
	
	jQuery( "#exhibit #secondary .sidebar.navigation h3" ).click(function(event) {
		event.preventDefault();
		jQuery( "#exhibit #secondary .sidebar.navigation .exhibit-page-nav" ).toggleClass( "show" );
		jQuery( "#exhibit #secondary .sidebar.navigation h3" ).toggleClass( "hide" );
	});
	
});