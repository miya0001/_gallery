( function( $ ) {
	$( 'a' ).each( function() {
		if ( $( this ).attr( 'href' ).match( /\.jpg$|\.jpeg$|\.gif$|\.png$/i ) ) {
			$( this ).simpleLightbox();
		}
	} );

	var gallery = $( '.underscore-gallery a' ).simpleLightbox();
	gallery.next();
} )( jQuery );