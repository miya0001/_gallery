( function( $ ) {
	$( 'a' ).each( function() {
		if ( $( this ).attr( 'href' ).match( /\.jpg$|\.jpeg$|\.gif$|\.png$/i ) ) {
			$( this ).attr( 'data-lity', true );
		}
	} );
} )( jQuery );
