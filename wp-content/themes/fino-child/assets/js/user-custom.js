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