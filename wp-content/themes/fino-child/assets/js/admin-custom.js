/*
 * Admin Custom JS code
 *
 */

jQuery(document).ready(function($){

    /*
     * Adds the ability to select a year
     */
    if( $('input').is('[value="year"]') ) { // On year taxonomy term.php && edit-tags.php pages
        $('input#name').yearselect({
            start: 1860,
            order:'desc'
        });
        $('#tag-name').yearselect({
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

