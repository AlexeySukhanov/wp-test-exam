/*
 * Admin Custom JS code
 *
 */

jQuery(document).ready(function($){

    /*
     * Adds the ability to select a year
     */
    if( $('input').is('#tag-name') ) { // On year taxonomy edit-tags.php page
        $('#tag-name').yearselect({
            start: 1860,
            order:'desc'
        });
    } else if( $('input').is('[name=taxonomy][value=year]') ) { // On year taxonomy term.php page
        $('#name').yearselect({
            start: 1860,
            order:'desc'
        });
    }
    if( $('input').is('#newyear') ) { // On releases CPT post-new.php && post.php page
        $('#newyear').yearselect({
            start: 1860,
            order:'desc'
        });
    }

});

