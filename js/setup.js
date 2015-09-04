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
	    /**
		  * Set variables for the positioning functions to use
		  */
		    earliestDaysSince = $( '#timeline-footer' ).data( 'start-days-since' );
			latestDaysSince = $( '#timeline-footer' ).data( 'end-days-since' );
			range = latestDaysSince - earliestDaysSince;
			
			timelineWrapperwidth = $( "#timeline-wrapper" ).width();
			ratio = range / timelineWrapperwidth;
			
			negativeOffset = $( "#timeline-wrapper" ).css( "left" ); 
			negativeOffset = parseInt( negativeOffset );
			
			wholeWidth = $( "#timeline-loop" ).width();
			
			/**
			 * Create a 2 dimensional array used to check what 
			 * pixels an entry is taking up in the timeline.
			 */
			table = create2DArray( wholeWidth + 1 );
			
			tallest = 0; // Which row is the tallest. Used to se the height of the entry lines.
			
			currentItem = 0;
			itemsPerLoad = 1;
			lastItem = itemsPerLoad
			
	    nineline_layout_entries();
	    
	    /**
		 * Position the date label titles
		 */
	    $( '.date-label-title' ).each( function() {
			nineline_horizontally_position_element( $( this ) );		
		});
		
		/**
		 * Position the date label marks
		 */
	    $( '.date-label-mark' ).each( function() {
			nineline_horizontally_position_element( $( this ) );		
		});
    }
    
    /* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		function nineline_layout_entries() {
			$( '.entry' ).slice( currentItem, lastItem ).each( function() {
			    nineline_horizontally_position_element( $( this ) );
			});
			
			console.log( 'currentItem:' + currentItem ); 
			
			currentItem = currentItem + itemsPerLoad;
			lastItem = lastItem + itemsPerLoad;
		}
		
		function nineline_show_entry( element ) {
			$( element ).animate({
				opacity: 1
			}, 100, function() {
				nineline_layout_entries();
			});
		}
		
		/**
		 * Vertically position the timeline entry.
		 *
		 * This function uses a 2 dimensional array that represents 
		 * the whole x,y pixel co-ordinates that an entry takes up 
		 * within the timeline div. 
		 *
		 * The element already has a defined left position and width, 
		 * which indicated the date of the entry. This function finds 
		 * enough room to fit it vertically, without overlapping any 
		 * other entries.
		 *
		 * When an element can fit in a space, every pixel that element 
		 * takes up is the recorded in the 2 dimensional array, so that 
		 * the function can check where entries are.
		 */
		function nineline_vertically_position_element( element, left, width ) {
			
			//var leftColumn = left + (negativeOffset/2);
			/**
			 * The starting column to put the value in. The negativeOffset is 
			 * needed to account for the negative space some entries might 
			 * make on the left of the div.
			 */
			var leftColumn = left + negativeOffset; 			
			var endColumn = leftColumn + width; // The last column to put the value in
			var columnHeight = $( element ).height();
			
			var setRows = true;
			var rowCount = 0; // Keeps a record of how many free rows there are in a row.
			var row = 0; // Current row being checked
			var startRow = 0; // What was the start row of the current streak
			//var initialColumn = leftColumn;
			//var columnCount = initialColumn;
			var startAgain = true;
			
			/**
			 * Find a space big enough to put this entry in without it 
			 * overlapping another entry.
			 */
			while( setRows ) {	
				/**
				 * If we've checked that enough rows are free for us to 
				 * fit in this entry then end the loop
				 */		
				if( rowCount > columnHeight ) {
					setRows = false;
				} else {
					/**
					 * If the last row check ws unsuccessful, or this 
					 * is the first iteration then start the rowCount again.
					 */
					if( startAgain ) {
						startRow = row; 
						startAgain = false;
						rowCount = 0;
					}

					var carryOn = true;
					var startColumn = leftColumn;
					
					/**
					 * Loops through all the columns in the current 
					 * row to check if all the cells are free.
					 */
					while( carryOn ) {
						/**
						 * If the column we are chicking is bigger than the end 
						 * column then we have come to the end of the row 
						 * succesfully, without another entry in the way. So we 
						 * will end this while and move on to check the next row.
						 */
						if( startColumn > endColumn ) {
							var result = true;
							carryOn = false;
						} 
						/**
						 * Otherwise, if there is an entry in this position then 
						 * stop the loop and start again with a rowCount of 0.
						 */
						else if( table[startColumn][row] ) {
							var result = false;
							carryOn = false;
						} 
						/**
						 * Otherwise we have successfully checked that there is 
						 * no entry in this column so we move onto the next 
						 * column.
						 */
						else {
							startColumn++;
						}
					}
					
					/**
					 * If there was no entry in the way then move onto 
					 * the next row. Otherwise start the rowCount again.
					 */
					if( result ) {
						rowCount++;
					} else {
						startAgain = true;
					}
					
					row++; // Move to the next row
				}
				
			}
			
			/**
			 * Indicate where the current element is in the 
			 * table, so that no other elements will get 
			 * placed on top of it.
			 */
			for (var column = leftColumn; column <= endColumn; column++) {
				for (var columnRow = startRow; columnRow <= row; columnRow++) {
					table[column][columnRow] = 'set';
				}
			}
			
			if(tallest < row) {
				tallest = row;
			}
			
			$( element ).css( "top", startRow );
			//$(this).siblings(".timeline-article-marker").css("top", startRow + height );
			//$(this).siblings(".timeline-article-marker-horizontal").css("top", startRow + height - 1);
			
			nineline_show_entry( element );
		}
	
		function nineline_horizontally_position_element( element ) {
			var entryStartDaysSince = $( element ).attr( "data-start-days-since" );
			var entryEndDaysSince = $( element ).attr( "data-end-days-since" );
			
			var middle = ( entryStartDaysSince - earliestDaysSince ) / ratio;
			
			if( $( element ).hasClass( 'timeline-line' ) ) {
				var width = $( element ).css( 'border-width' );
				width = parseInt( width );
			} else {
				var width = $( element ).width();
			}
			/*
			var spanWidth = $(this).find('span').outerWidth(true);
			
			if(spanWidth < width) {
				width = spanWidth;
				$(this).width(width);
			}
			*/
			
			var left = Math.floor( middle - ( width / 2 ) );		
			$( element ).css( "left", left );
			
			//return [ left: left, right: right ];
			
			if( $( element ).hasClass( 'entry' ) ) {
				nineline_vertically_position_element( element, left, width )
			}
		}
		
		function create2DArray( columns ) {
		  	var arr = []; // Define empty array
		
		  	/**
			 * For each number of columns specified, create a 
			 * new array item as a blank array (representing 
			 * rows) 
			 */
		  	for ( var i = 0;i < columns; i++ ) {
		    	 arr[i] = []; // Add blank array into array
			}
		
			return arr; // Return the full 2 dimensional array
		}

}) ( jQuery );