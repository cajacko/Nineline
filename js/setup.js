(function($) {

    $(document).ready(documentReadyFunction);
    $(window).resize(windowResizeFunction);

    function documentReadyFunction() {
        // functions for document ready
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        // functions for window resize
        onPageLoadOrResize();
    }

    function onPageLoad() {
    
    	if($('main').hasClass('first-visit')) {
    		$('#info-modal').modal('show');
    	}
    	
    	$('#close-key').click(function() {
    		$('#key').hide();
    		
    		$.ajax({
			  url: "http://nineline.io/data/?data-type=close-key",
			});
    	});
    	
    	$(function () {
			$('[data-toggle="popover"]').popover()
		});
		
		$('.timeline-content span a').hover(function() {
			$(this).popover('show');
		});
		
		$("#timeline-loop").scroll(function () {
			
			var start = $("#timeline-loop").scrollTop();
			var end = $("#timeline-loop").height() + start;
			start = start - 100;
			
			$(".timeline-content").each(function() {
				var top = $(this).css('top');
				top = parseInt(top);
				
				if(top > start && top < end) {
					$(this).siblings(".timeline-article-marker").show();
				} else {
					$(this).siblings(".timeline-article-marker").hide();
				}
			});
		});
    			
    }

	
    function onPageLoadOrResize () {
    	$("#loading").show();
		
		arrangeArticles();
	  	  	
	  	$("#loading").hide();
    }
    
    /* -----------------------------
	SUPPORTING FUNCTIONS
	----------------------------- */
	
	function arrangeArticles() {
		//Get min and max dates
    	var earliestDaysSince = "";
    	var latestDaysSince = "";
		var count = 0;
		var earliestArticle = "";
		var latestArticle = "";		
		
		$("#timeline .timeline-content").each(function(){
			var articleStartDaysSince = $(this).attr("data-start-days-since");
			var articleEndDaysSince = $(this).attr("data-end-days-since");
			
			if(count == 0) {
				earliestDaysSince = articleStartDaysSince;
				latestDaysSince = articleEndDaysSince;
				
				earliestArticle = $(this);
				latestArticle = $(this);
			} else {
			
				if(earliestDaysSince > articleStartDaysSince) {
					earliestDaysSince = articleStartDaysSince;
					earliestArticle = $(this);
				}
				
				if(latestDaysSince < articleEndDaysSince) {
					latestDaysSince = articleEndDaysSince;
					latestArticle = $(this);
				}
			}

			count++;
		});
		
		var range = latestDaysSince - earliestDaysSince;
		var timelineContainerWidth = $("#timeline-container").width();
		var ratio = range / timelineContainerWidth;
		
	  //Position articles
	  	var maxWidth = $(".timeline-content").css("max-width");
	  	maxWidth = parseInt(maxWidth);
	  	var wholeWidth = $("#timeline-loop").outerWidth();
		var table = create2DArray(wholeWidth + 1);
		var tallest = 0;

		$("#timeline .timeline-content").each(function(){
			$(this).show().css('position', 'absolute');
			
		  //Define Left Position
			var articleStartDaysSince = $(this).attr("data-start-days-since");
			var articleEndDaysSince = $(this).attr("data-end-days-since");
			var height = $(this).height();
			
			if(articleStartDaysSince == articleEndDaysSince) {
				var middle = (articleStartDaysSince - earliestDaysSince) / ratio;
				var width = $(this).width();
				var spanWidth = $(this).find('span').outerWidth(true);
				
				if(spanWidth < width) {
					width = spanWidth;
					$(this).width(width);
				}
				
				var left = Math.floor(middle - (width/2));
				
				$(this).css("left", left);
				$(this).siblings(".timeline-article-marker").css("left", middle);
			} else {
				var left = Math.floor((articleStartDaysSince - earliestDaysSince) / ratio);
				var right = Math.floor((articleEndDaysSince - earliestDaysSince) / ratio);
				var width = right - left;
				
				$(this).siblings(".timeline-article-marker-start").css("left", left);
				$(this).siblings(".timeline-article-marker-end").css("left", right);
				
				if(width < maxWidth) {
					width = maxWidth;
					left = Math.floor((left + right - width)/2);
				} 
				
				$(this).css("left", left);

				$(this).width(width);
				$(this).siblings(".timeline-article-marker-horizontal").css("left", left).width(width);
			}
			
			
			var leftColumn = left + (maxWidth/2);
			var rightColumn = leftColumn + width;
			var columnHeight = height;
			
			var setRows = true;
			var rowCount = 0;
			var row = 0;
			var startRow = 0;
			var initialColumn = leftColumn;
			var columnCount = initialColumn;
			var startAgain = true;
			
			while(setRows) {				
				if(rowCount > columnHeight) {
					setRows = false;
				} else {
					if(startAgain) {
						startRow = row;
						startAgain = false;
						rowCount = 0;
					}
					
					
					var carryOn = true;
					var startColumn = leftColumn;
					var endColumn = rightColumn;
		
					while(carryOn) {
						if(startColumn > endColumn) {
							var result = true;
							carryOn = false;
						} else if(table[startColumn][row]) {
							var result = false;
							carryOn = false;
						} else {
							startColumn++;
						}
					}

					if(result) {
						rowCount++;
					} else {
						startAgain = true;
					}
					row++;
				}
			}
			
			for (var column = leftColumn; column <= rightColumn; column++) {
				for (var columnRow = startRow; columnRow <= row; columnRow++) {
					table[column][columnRow] = 'set';
				}
			}
			
			if(tallest < row) {
				tallest = row;
			}
			
			$(this).css("top", startRow);
			$(this).siblings(".timeline-article-marker").css("top", startRow + height );
			$(this).siblings(".timeline-article-marker-horizontal").css("top", startRow + height - 1);

		});
		
		var containerHeight = $("#timeline-container").height();
		
		if(containerHeight > tallest) {
			tallest = containerHeight;
		}
		
		$(".timeline-article-marker").each(function() {
			$(this).show();
			
			var top = $(this).css('top');
			top = parseInt(top);
			var height = tallest - top + 100;
			$(this).height(height);
		});
		
	  //Position scale
	  	var earliestYear = $(earliestArticle).attr("data-start-year");
	  	var latestYear = $(latestArticle).attr("data-end-year");
	  	var currentYear = earliestYear;
	  	
	  	var earliestMonth = $(earliestArticle).attr("data-start-month");
	  	var latestMonth = $(latestArticle).attr("data-end-month");
	  	var currentMonth = earliestMonth;
	  	
	  	var earliestDay = $(earliestArticle).attr("data-start-day");
	  	var latestDay = $(latestArticle).attr("data-end-day");
	  	var currentDay = earliestDay;
	  
	  	if (range < 12500) {

		  	while(latestYear >= currentYear) {
		  		
		  		var daysSince = 1 + (31 * (1 + (12 * currentYear)));
		  		
		  		if((daysSince >= earliestDaysSince && daysSince <= latestDaysSince) && (currentYear % 5) == 0)  {
				
			  		var html = "<div class='timeline-scale-label' data-days-since='" + daysSince + "'><h2>" + currentYear + "</h2><div class='timeline-marker'></div>";
			  		$("#timeline-footer-container").append(html);		  		
		  		}
		  		currentYear++;
		  	}

	  	} else {
	  
	  		var earliestYear = $(earliestArticle).attr("data-start-year");
		  	var latestYear = $(latestArticle).attr("data-end-year");
		  	
		  	var currentYear = earliestYear;
		  	
		  	while(latestYear >= currentYear) {
		  		
		  		var daysSince = 1 + (31 * (1 + (12 * currentYear)));
		  		
		  		if((daysSince >= earliestDaysSince && daysSince <= latestDaysSince) && (currentYear % 20) == 0)  {
				
			  		var html = "<div class='timeline-scale-label' data-days-since='" + daysSince + "'><h2>" + currentYear + "</h2><div class='timeline-marker'></div>";
			  		$("#timeline-footer-container").append(html);
		  		
		  		}
	
		  		currentYear++;
		  	}
		 }
	  	
	  	$("#timeline-footer .timeline-scale-label").each(function(){
	  		
	  		var daysSince = $(this).attr("data-days-since");
	  		var middle = (daysSince - earliestDaysSince) / ratio;
			var width = $(this).width();
			var left = middle - (width/2);
			
			$(this).css("left", left);			
	  	});

	}
    
    function create2DArray(columns) {
	  	var arr = []; /* Define empty array */
	
	  	/* For each number of columns specified, create a new array item as a blank array (representing rows) */
	  	for (var i = 0;i < columns; i++) {
	    	 arr[i] = []; /* Add blank array into array */
		}
	
		return arr; /* Return the full 2 dimensional array */
	}

})(jQuery);