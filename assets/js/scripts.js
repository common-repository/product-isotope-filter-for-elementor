(function($){
'use strict';


/*------------------------------------------
    = FUNCTION FORM SORTING GALLERY
  -------------------------------------------*/
  function sortingGallery() {
      if ($(".sortable-gallery .gallery-filters").length) {
          var $container = $('.gallery-container');
          $container.isotope({
              filter:'*',
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
                  filter:selector,
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


  /*=========================================================================
        WHEN DOCUMENT LOADING
    ==========================================================================*/
    $(window).on('load', function() {
        sortingGallery();
    });
    

   $(window).on("scroll", function() { 
     sortingGallery();
   });

  $(window).on("resize", function() {
     sortingGallery();
   });

})(jQuery);  