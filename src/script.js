( function( $ ) {
	$( 'a' ).each( function() {
		if ( $( this ).attr( 'href' ).match( /\.jpg$|\.jpeg$|\.gif$|\.png$/i ) ) {
			var gallery = $( this ).simpleLightbox();
			gallery.next();
		}
	} );
} )( jQuery );
