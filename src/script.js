( function( $ ) {
	var el = document.querySelector( '.underscore-gallery' );
	if ( el ) {
		var me = '/_gallery/js/script.min.js';
		var css = '/_gallery/css/style.min.css';

		var scripts = document.querySelectorAll( 'script' );
		scripts.forEach( function( item ) {
			var src = item.getAttribute( 'src' );
			if ( src ) {
				if ( 0 < src.indexOf( me ) ) {
					css = src.replace( me, css );
				}
			}
		} );

		var link = document.createElement( 'link' );
		link.setAttribute( 'rel', 'stylesheet' );
		link.setAttribute( 'type', 'text/css' );
		link.setAttribute( 'media', 'all' );
		link.setAttribute( 'href', css );
		document.head.appendChild( link );
	}

	$( 'a' ).each( function() {
		if ( $( this ).attr( 'href' ).match( /\.jpg$|\.jpeg$|\.gif$|\.png$/i ) ) {
			if ( ! $( this ).parents( '.underscore-gallery' ).length ) {
				$( this ).simpleLightbox();
			}
		}
	} );

	var gallery = $( '.underscore-gallery a' ).simpleLightbox();
	gallery.next();
} )( jQuery );
