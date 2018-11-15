(function($) {

	'use strict';
    
    // Promotions
    $('.home .promotion-slider').slick({
        dots: false,
        autoplay:true,
        arrows: true,
        infinite: true,
        slidesToShow: 1,
        fade: true,
        nextArrow: '<button class="slick-next"><svg width="37" height="61" viewBox="0 0 37 61" xmlns="http://www.w3.org/2000/svg"><title>Path 3</title><path d="M0 52.936l8.707 7.976L36.61 30.456 8.706 0 0 7.977l20.594 22.48" fill-rule="nonzero" fill="#FFF" fill-opacity=".3"/></svg></button',
        prevArrow: '<button class="slick-prev"><svg width="37" height="61" viewBox="0 0 37 61" xmlns="http://www.w3.org/2000/svg"><title>Path 3 Copy</title><path d="M36.61 7.977L27.9 0 0 30.456l27.902 30.456 8.707-7.976-20.595-22.48" fill-rule="nonzero" fill="#FFF" fill-opacity=".3"/></svg></button',
    });
    
    
    // Featured Designs
    $('.home .featured-designs-slider.slider').slick({
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

})(jQuery);
