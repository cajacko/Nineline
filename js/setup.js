( function( $ ) {

    $( document ).ready( documentReadyFunction );
    $( window ).resize( windowResizeFunction );

    function documentReadyFunction() {
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        onPageLoadOrResize();
    }

    function onPageLoad() {
    }
	
    function onPageLoadOrResize () {
	    var elements = $( '.entry' ).slice( 0, 10 );
	    
	    nineline_horizontally_position_elements( elements );
    }
    
    /* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		function nineline_horizontally_position_elements( elements ) {
			$( elements ).each( function() {
				console.log( this );
			});
		}

}) ( jQuery );