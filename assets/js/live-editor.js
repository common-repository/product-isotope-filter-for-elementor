/*
Template Name: Lovelove
Author: wpoceans
Version: 1.0
*/

(function($) {
    'use strict';

    /*----- ELEMENTOR LOAD FUNTION CALL ---*/

    $(window).on('elementor/frontend/init', function() {

        
        var wco_product_filter_live = function() {

            /*------------------------------------------
                = FUNCTION FORM SORTING GALLERY
            -------------------------------------------*/
            function sortingGallery() {
                if ($(".sortable-gallery .gallery-filters").length) {
                    var $container = $('.gallery-container');
                    $container.isotope({
                        filter: '*',
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false,
                        }
                    });

                    $(".gallery-filters li a").on("click", function() {
                        $('.gallery-filters li .current').removeClass('current');
                        $(this).addClass('current');
                        var selector = $(this).attr('data-filter');
                        $container.isotope({
                            filter: selector,
                            animationOptions: {
                                duration: 750,
                                easing: 'linear',
                                queue: false,
                            }
                        });
                        return false;
                    });
                }
            }

            sortingGallery();


            /*------------------------------------------
                = MASONRY GALLERY SETTING
            -------------------------------------------*/
            function masonryGridSetting() {
                if ($('.masonry-gallery').length) {
                    var $grid = $('.masonry-gallery').masonry({
                        itemSelector: '.grid-item',
                        columnWidth: '.grid-item',
                        percentPosition: true
                    });

                    $grid.imagesLoaded().progress(function() {
                        $grid.masonry('layout');
                    });
                }
            }

        }; // end

       
        //wco_product_filter_live
        elementorFrontend.hooks.addAction('frontend/element_ready/wco_product_script.default', function($scope, $) {
            wco_product_filter_live();
        });
    

    });



    $(window).on("elementor/frontend/init", function() {

  
        elementorFrontend.hooks.addAction("frontend/element_ready/wco_product_filter.default", function(scope, $) {
            /*------------------------------------------
            = FUNCTION FORM SORTING GALLERY
            -------------------------------------------*/
            function sortingGallery() {
                if ($(".sortable-gallery .gallery-filters").length) {
                    var $container = $('.gallery-container');
                    $container.isotope({
                        filter: '*',
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false,
                        }
                    });

                    $(".gallery-filters li a").on("click", function() {
                        $('.gallery-filters li .current').removeClass('current');
                        $(this).addClass('current');
                        var selector = $(this).attr('data-filter');
                        $container.isotope({
                            filter: selector,
                            animationOptions: {
                                duration: 750,
                                easing: 'linear',
                                queue: false,
                            }
                        });
                        return false;
                    });
                }
            }

            sortingGallery();


            /*------------------------------------------
                = MASONRY GALLERY SETTING
            -------------------------------------------*/
            function masonryGridSetting() {
                if ($('.masonry-gallery').length) {
                    var $grid = $('.masonry-gallery').masonry({
                        itemSelector: '.grid-item',
                        columnWidth: '.grid-item',
                        percentPosition: true
                    });

                    $grid.imagesLoaded().progress(function() {
                        $grid.masonry('layout');
                    });
                }
            }
        });




    })

})(jQuery);