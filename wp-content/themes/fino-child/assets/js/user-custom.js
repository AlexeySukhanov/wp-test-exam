/*
 * User Custom JS code
 *
 */

jQuery(document).ready(function($){
    if( $('a').is('.btn.btn-fino') ) {
        $('.btn.btn-fino').on('click', function(e){
            e.preventDefault();
            console.log('Ба шумо клик кардан маъқул аст?');
        });
    }
});

jQuery(document).ready(function($){

    if( $('div').is('.lastViewedList') ) {
        $('.owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            nav:false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:4
                }
            }
        });

    }
});