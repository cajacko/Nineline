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
	    
	    nineline_load_more_entries();
	    
	    /**
		  * Set variables for the positioning functions to use
		  */
	    earliestDaysSince = $( '#timeline-footer' ).data( 'start-days-since' ); // Get the earliest entry
		latestDaysSince = $( '#timeline-footer' ).data( 'end-days-since' ); // Get the latest entry
		range = latestDaysSince - earliestDaysSince; // Get the range of the min and max entry in days
		
		timelineWrapperwidth = $( "#timeline-wrapper" ).width();
		ratio = range / timelineWrapperwidth; // How many days does each pixel represent
		
		/**
		 * Get the negative offset. Used because each entry is placed 
		 * horizontally with it's middle representing the date. If the 
		 * entry is right at the beggining then the width of it would 
		 * cause it to be cropped by the edge of the timeline container. 
		 * This offset takes into account the maximum width of an entry 
		 * and allows enough padding on either side so that these entries 
		 * would still be visible.
		 */
		negativeOffset = $( "#timeline-wrapper" ).css( "left" );
		negativeOffset = parseInt( negativeOffset );
		
		wholeWidth = $( "#timeline-loop" ).width();
		timelineHeight = $( "#timeline-loop" ).height();
		
		delay = 0; // Set the initial delay until the first entry is loaded.
		timeBetweenLayouts = 25; // How long to wait until the next post is displayed
		
		/**
		 * Create a 2 dimensional array used to check what 
		 * pixels an entry is taking up in the timeline.
		 */
		table = create2DArray( wholeWidth + 1 );
		
		tallest = 0; // Which row is the tallest. Used to se the height of the entry lines.
		
		currentItem = 0; // The current entry that is being positioned
		itemsPerLoad = 1;
		lastItem = itemsPerLoad;
		
		page = 1;
		continueLayout = true; // Used to stop laying out post, e.g. when resizing the window.
			
	    nineline_layout_all_timeline_elements();
    }
	
    function onPageLoadOrResize () {
	     
    }
    
    /* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		/**
		 * Registers the click events that show more or previous posts
		 */
		function nineline_load_more_entries() {
			/**
			 * Load more posts on click
			 */
			$( '#load-more' ).click( function() {
				event.preventDefault();
				
				$( '.processed' ).hide(); // Hide all the currently showed posts
				page++; // Indicate that a seperate set of posts are being loaded
				
				table = create2DArray( wholeWidth + 1 ); // Reset the table so can layout posts again
				
				nineline_layout_entries();
			});	
			
			/**
			 * Load the last page of posts
			 */
			$( '#prev' ).click( function() {
				event.preventDefault();
				
				page--;
				
				/**
				 * Find all the entries and hide/show the relevant ones
				 */
				$( '.entry' ).each( function() {
					if( $( this ).attr( 'data-page' ) == ( page ) ) {
						$( this ).show( 'slow' );
					} else {
						$( this ).hide( 'slow' );
					}	
				});
			});
		}
		
		function nineline_layout_all_timeline_elements() {
			if( continueLayout ) {
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
		}
	
		function nineline_layout_entries() {
			if( continueLayout ) {
				var entries = $( '.entry.not-processed' ).slice( currentItem, lastItem ); // Get the entires not yet processed

				if( entries.length ) {
					$( entries ).each( function() {
					    nineline_horizontally_position_element( $( this ) );
					});
				} else {
					$( '.not-shown' ).addClass( 'not-processed' );
				}
			}
		}
		
		function nineline_show_entry( element ) {
			if( continueLayout ) {
				$( element ).attr( 'data-page', page ).removeClass( 'not-shown' ).removeClass( 'not-processed' ).addClass( 'processed' ).animate({
					opacity: 1
				}, 1000 );
				
				setTimeout( function() { 
					nineline_layout_entries(); 
				}, delay );
				
				delay + timeBetweenLayouts;
			}
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
			if( continueLayout ) {
				/**
				 * The starting column to put the value in. The negativeOffset is 
				 * needed to account for the negative space some entries might 
				 * make on the left of the div.
				 */
				var leftColumn = left + negativeOffset; 			
				var endColumn = leftColumn + width; // The last column to put the value in
				var columnHeight = $( element ).height();
				var maxRow = timelineHeight - columnHeight;
				
				var setRows = true;
				var rowCount = 0; // Keeps a record of how many free rows there are in a row.
				var row = 0; // Current row being checked
				var startRow = 0; // What was the start row of the current streak
				//var initialColumn = leftColumn;
				//var columnCount = initialColumn;
				var startAgain = true;
				var showEntry = true;
				
				/**
				 * Find a space big enough to put this entry in without it 
				 * overlapping another entry.
				 */
				while( setRows ) {	
					/**
					 * If we've checked that enough rows are free for us to 
					 * fit in this entry then end the loop
					 */	
					if( row >= maxRow ) {
						setRows = false;
						showEntry = false;
					} else if( rowCount > columnHeight ) {
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
				
				if( showEntry ) {	
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
				} else {
					$( element ).removeClass( 'not-processed' );
					nineline_layout_entries();
				}
			}
		}
	
		function nineline_horizontally_position_element( element ) {
			if( continueLayout ) {
				var entryStartDaysSince = $( element ).attr( "data-start-days-since" );
				var entryEndDaysSince = $( element ).attr( "data-end-days-since" );
				
				var middle = ( entryStartDaysSince - earliestDaysSince ) / ratio;
				
				if( $( element ).hasClass( 'timeline-line' ) ) {
					var width = $( element ).css( 'border-width' );
					width = parseInt( width );
				} else {
					var width = $( element ).width();
					
					var entryWrapWidth = $( element ).find( '.entry-wrap' ).width();
					var lineLeft = entryWrapWidth / 2;
					
					$( element ).find( '.entry-line' ).css( 'left', lineLeft );
				}

				/**
				 * Horizontally position the entry
				 */
				var left = Math.floor( middle - ( width / 2 ) );		
				$( element ).css( "left", left ).width( width );

				if( $( element ).hasClass( 'entry' ) ) {
					nineline_vertically_position_element( element, left, width )
				}
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