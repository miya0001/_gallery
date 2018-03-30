( function( $ ) {
	var need_css = false;
	$( 'a' ).each( function() {
		if ( $( this ).attr( 'href' ).match( /\.jpg$|\.jpeg$|\.gif$|\.png$/i ) ) {
			if ( ! $( this ).parents( '.underscore-gallery' ).length ) {
				$( this ).simpleLightbox();
				need_css = true;
			}
		}
	} );
	
	var galleries = $( '.underscore-gallery' );
	for ( var i = 0; i < galleries.length; i++ ) {
        var gallery = $( "a", galleries[ i ] ).simpleLightbox();
        gallery.next();
    }

	var load_css = function() {

		var me = '/_gallery/js/script.min.js';
		var css = '/_gallery/css/style.min.css';

        var scripts = document.querySelectorAll( 'script' );
        for ( var i = 0; i < scripts.length; i++ ) {
            var item = scripts[ i ];
            var src = item.getAttribute( 'src' );
            if ( src ) {
                if ( 0 < src.indexOf( me ) ) {
                    css = src.replace( me, css );
                }
            }
        }

		var link = document.createElement( 'link' );
		link.setAttribute( 'rel', 'stylesheet' );
		link.setAttribute( 'type', 'text/css' );
		link.setAttribute( 'media', 'all' );
		link.setAttribute( 'href', css );
		document.head.appendChild( link );
	}

	var el = document.querySelector( '.underscore-gallery' );
	if ( el || need_css ) {
		load_css();
	}
} )( jQuery );
