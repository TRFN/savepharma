// -----------------------------------------------------------------------------
// Main Javascript
// -----------------------------------------------------------------------------
(function($) {
    "use strict";

    // Preloader
    // ----------------------------------------
    $(window).load(function(){
        if($('#preloader').length > 0){
            
        }
    }).scroll(function(){
        $('.toggle').removeClass('active');
        $('.sidebar').removeClass('sidebar-active');
    });

    $(document).ready(function($) {

        // Animating the navbar toggle
        // ----------------------------------------
        $('.toggle').on('click', function () {
            $(this).toggleClass('active');
            $('.sidebar').toggleClass('sidebar-active');
        });

        // Bootstrap Dropdown on hover
        // ----------------------------------------
        $('.dropdown').on({
            mouseenter: function (){
                $(this).addClass('open');
            },
            mouseleave: function(){
                $(this).removeClass('open');
            }
        });

        // Sliders
        // ----------------------------------------

        // home splash slider
        if($('.splash-slider').length > 0){
            $('.splash-slider').owlCarousel({
                singleItem: true,
                transitionStyle: 'fade',
                slideSpeed: 1000,
                autoPlay: 5000,
                mouseDrag: false,
                pagination: true,
                navigation: false
            });
        }

        // simple gallery slider
        if($('.gallery-slider').length > 0){
            $('.gallery-slider').owlCarousel({
                singleItem: true,
                slideSpeed: 1000,
                autoPlay: 5000,
                mouseDrag: false,
                pagination: false,
                navigation: true,
                navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"]
            });
        }

        // full width gallery slider
        if($('.full-gallery-slider').length > 0){
            $('.full-gallery-slider').owlCarousel({
                itemsDesktop: [1199,4],
                itemsDesktopSmall: [992,3],
                itemsTablet: [768,3],
                itemsMobile: [479,1],
                slideSpeed: 1000,
                autoPlay: 5000,
                pagination: false,
                navigation: true,
                navigationText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"]
            });
        }

        // testimonials slider
        if($('.testimonials-slider').length > 0){
            $('.testimonials-slider ').owlCarousel({
                singleItem: true,
                transitionStyle: 'fade',
                slideSpeed: 1000,
                autoPlay: 5000,
                mouseDrag: false,
                pagination: true,
                navigation: false
            });
        }

        // Gallery Popup
        // ----------------------------------------

        // image popup
        if($('.popup').length > 0){
            $('.popup').magnificPopup({
              type: 'image'
            });
        }

        // video popup
        if($('.popup-video').length > 0){
            $('.popup-video').magnificPopup({
                type: 'iframe'
            });
        }
    });
})(jQuery);



