(function($) {


	'use strict';

	// Load Foundation
	$(document).foundation();

    $(window).on('load changed.zf.mediaquery', function(event, newSize, oldSize) {
        
        $( '.nav-primary' ).doubleTapToGo();
        
        if( ! Foundation.MediaQuery.atLeast('xlarge') ) {
          $( '.nav-primary' ).doubleTapToGo( 'destroy' );
        }
        
    });
    
    
    // Basic table sorting
    
    var $table = $("table").stupidtable();
    $table.bind('aftertablesort', function (event, data) {
        // data.column - the index of the column sorted after a click
        // data.direction - the sorting direction (either asc or desc)
        // data.$th - the th element (in jQuery wrapper)
        // $(this) - this table object
        
    
        console.log("The sorting direction: " + data.direction);
        console.log("The column index: " + data.column);
    });
    
    var $th_to_sort = $table.find("thead th").eq(0);    
    // You can also force a direction.
    $th_to_sort.stupidsort('asc');
    
    $('table tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });


    
    /*
	$('.contact-button a').click(function(e){
		e.preventDefault();
		$('#contact').foundation('open');
	});

	$('.promotion-slider').slick({
		slidesToShow: 1,
		dots: true,
		arrows: true,
		fade: true,
		cssEase: 'linear'
	});

    $('.featured-designs-slider').slick({
        slidesToShow: 3,
        arrows: true,
        infinite: false,
        //infinite: true,
        //centerMode: true,
        //variableWidth: true,
        //centerPadding: '0px',
        responsive: [
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        }
     ]
    });
    
    $('.sidebar-slick').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
    });
    */
    
    
    // Single Product
    
    /*
    $('.single-product .images.slider, .single-doolittle_design .images.slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        slidesToShow: 1,
        fade: true,
        customPaging: function (slider, i) {
            var thumbnail = $(slider.$slides[i]).find("[data-thumbnail]").data('thumbnail');
            return '<span style="background-image: url('+thumbnail+');"></span>';
        }
    });
    */
    
    $('.single-product .images.slider, .single-doolittle_design .images.slider').slick({
        dots: false,
        arrows: false,
        infinite: true,
        asNavFor: '.single-product .thumbnails.slider, .single-doolittle_design .thumbnails.slider, .modal-slideshow .photos.slider',
        slidesToShow: 1,
        fade: true,
    });
    
    $('.single-product .thumbnails.slider, .single-doolittle_design .thumbnails.slider').slick({
        dots: false,
        arrows: true,
        prevArrow: '<button class="slick-prev"><svg width="36" height="22"  viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        nextArrow: '<button class="slick-next"><svg width="36" height="22" viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.single-product .images.slider, .single-doolittle_design .images.slider, .modal-slideshow .photos.slider',
        centerMode: false,
        focusOnSelect: true,
        centerPadding: 0
    });
    
    // Set current slide for the modal
    $('.single-product .thumbnails.slider, .single-doolittle_design .thumbnails.slider').on('afterChange', function(event, slick, currentSlide ){
      //console.log('set current slide: ' + currentSlide);  
      //$('.modal-slideshow').attr( 'data-current-slide', currentSlide );
   });
            
    //$('.modal-slideshow .photos.slider.slick-initialized').slick('unslick');
    
    $('.modal-slideshow .photos.slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        slidesToShow: 1,
        focusOnSelect: true,
        asNavFor: '.single-product .images.slider, .single-doolittle_design .images.slider,.single-product .thumbnails.slider, .single-doolittle_design .thumbnails.slider',
        fade: true,
        nextArrow: '<button class="slick-next"><svg width="37" height="61" viewBox="0 0 37 61" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M0 52.936l8.707 7.976L36.61 30.456 8.706 0 0 7.977l20.594 22.48" fill-rule="nonzero" fill="#FFF" fill-opacity=".3"/></svg></button',
    prevArrow: '<button class="slick-prev"><svg width="37" height="61" viewBox="0 0 37 61" xmlns="http://www.w3.org/2000/svg"><title>Path 3 Copy</title><path d="M36.61 7.977L27.9 0 0 30.456l27.902 30.456 8.707-7.976-20.595-22.48" fill-rule="nonzero" fill="#FFF" fill-opacity=".3"/></svg></button',
    });
        
    
    $('.modal-slideshow').on('open.zf.reveal', function() {
        $('.modal-slideshow .photos.slider').slick('resize');
        $('.modal-slideshow .photos.slider').slick('setPosition');
    });

   
    $('.single-product .related-products-slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        vertical: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        verticalSwiping: true,
        adaptiveHeight: true,
        nextArrow: '<button class="slick-prev"><svg width="36" height="22"  viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        prevArrow: '<button class="slick-next"><svg width="36" height="22" viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        responsive: [{
            breakpoint: 979,
            settings: {
              vertical: false,
            }
        }]
    });
    
    
     $('.single-product .related-designs-slider.slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
         prevArrow: '<button class="slick-prev"><svg width="36" height="22"  viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        nextArrow: '<button class="slick-next"><svg width="36" height="22" viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        responsive: [
            {
                breakpoint: 979,
                settings: {
                  slidesToShow: 2,
                }
            },
            {
                breakpoint: 639,
                settings: {
                  slidesToShow: 1,
                }
            }
        ]
    });
    
    
    $('.price-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        nextArrow: '<button class="slick-next"><svg width="37" height="61" viewBox="0 0 37 61" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M0 52.936l8.707 7.976L36.61 30.456 8.706 0 0 7.977l20.594 22.48" fill-rule="nonzero" fill="#1E1E1E"/></svg></button',
        responsive: [

        {
          breakpoint: 639,
          settings: {
             slidesToShow: 2,
             slidesToScroll: 1,
          }
      },
     ]
    });
    
    $(".single-product .accordion").on("down.zf.accordion", function() {
      $('.price-slider').slick('resize');
    });
    
    
    
    // Doolittle Design Single
    
    
    $('.single-doolittle_design .related-designs-slider.slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        vertical: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        verticalSwiping: true,
        nextArrow: '<button class="slick-prev"><svg width="36" height="22"  viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        prevArrow: '<button class="slick-next"><svg width="36" height="22" viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        responsive: [{
            breakpoint: 979,
            settings: {
              vertical: false,
            }
        }]
    });
    
    
    $('.single-doolittle_design .suggested-products-slider.slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
         prevArrow: '<button class="slick-prev"><svg width="36" height="22"  viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        nextArrow: '<button class="slick-next"><svg width="36" height="22" viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M31.286 21.636L36 16.49 18 0 0 16.49l4.714 5.146L18 9.466" fill-rule="nonzero" fill="#1E1E1E"/></svg></button>',
        responsive: [
            {
                breakpoint: 400,
                settings: {
                  slidesToShow: 3,
                }
            }
        ]
    });
    
    
    // Mega Menu
    
    $('.mega-menu li a[data-photo]').on('mouseenter mouseleave', function(e) {
                
        var $parent_item = $(this).parents('.mega-menu-item');
        var current_thumbnail = decodeURI( $(this).data('photo') );
        var $thumbnail_placeholder = $parent_item.find('.mega-menu-thumbnail');
        var default_thumbnail = decodeURI( $thumbnail_placeholder.data('photo') );
        
        
        
        // branch based on the event that occurred here
        if (e.type === "mouseenter") {
            $thumbnail_placeholder.html(current_thumbnail);
        } else {
            //setTimeout(function(){
              $thumbnail_placeholder.html(default_thumbnail);
            //}, 2000);
            
        }
    });
      

})(jQuery);
