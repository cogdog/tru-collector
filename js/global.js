jQuery( document ).ready( function( $ ) {

	// Masonry
	$masonryWrapper = $( '.posts' );

	if ( $masonryWrapper.length ) {

		// If the grid sizer doesn't exist, add it
		if ( ! $( '.grid-sizer' ).length ) {
			$( '.posts' ).prepend( '<div class="grid-sizer"></div>' );
		}

		$grid = $masonryWrapper.imagesLoaded( function() {

			$grid = $masonryWrapper.masonry( {
				columnWidth: 		'.grid-sizer',
				itemSelector: 		'.post-container',
				percentPosition: 	true,
				stagger: 			0,
				transitionDuration: 0,
			} );

		} );

		$grid.on( 'layoutComplete', function() {
			$( '.posts' ).css( 'opacity', 1 );
			$("#loading").hide();
		} );

	}


	// Toggle navigation
	$(".nav-toggle").on("click", function(){
		$(this).toggleClass("active");
		$(".mobile-navigation").slideToggle();
	});


	// Hide mobile-menu > 1000
	$(window).resize(function() {
		if ($(window).width() > 1000) {
			$(".nav-toggle").removeClass("active");
			$(".mobile-navigation").hide();
		}
	});


	// Load Flexslider
    $(".flexslider").flexslider({
        animation: "slide",
        controlNav: false,
        smoothHeight: false,
        start: function(){
			    $masonryWrapper.masonry();
		    },
    });


	// resize videos after container
	var vidSelector = ".post iframe, .post object, .post video, .widget-content iframe, .widget-content object, .widget-content iframe";
	var resizeVideo = function(sSel) {
		$( sSel ).each(function() {
			var $video = $(this),
				$container = $video.parent(),
				iTargetWidth = $container.width();

			if ( !$video.attr("data-origwidth") ) {
				$video.attr("data-origwidth", $video.attr("width"));
				$video.attr("data-origheight", $video.attr("height"));
			}

			var ratio = iTargetWidth / $video.attr("data-origwidth");

			$video.css("width", iTargetWidth + "px");
			$video.css("height", ( $video.attr("data-origheight") * ratio ) + "px");
		});
	};

	resizeVideo(vidSelector);

	$(window).resize(function() {
		resizeVideo(vidSelector);
	});


	// When Jetpack Infinite scroll posts have loaded
	$( document.body ).on( 'post-load', function () {

		var $container = $('.posts');
		$container.masonry( 'reloadItems' );

		$masonryWrapper.imagesLoaded(function(){
			$masonryWrapper.masonry({
				itemSelector: '.post-container'
			});

			// Fade blocks in after images are ready (prevents jumping and re-rendering)
			$(".post-container").fadeIn();
		});

		// Rerun video resizing
		resizeVideo(vidSelector);

		$container.masonry( 'reloadItems' );

		// Load Flexslider
	    $(".flexslider").flexslider({
	        animation: "slide",
	        controlNav: false,
	        prevText: "Previous",
	        nextText: "Next",
	        smoothHeight: true,


	    });

		$(document).ready( function() {
			setTimeout( function() {
				$masonryWrapper.masonry();
			}, 500);
		});

	});


});
