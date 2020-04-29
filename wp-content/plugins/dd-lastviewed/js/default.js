( function( $ ) {

    $( document ).ready( function() {
        bindSelect ();

        $(document).on('keypress', '.exclude_ids .select2-search__field', function () {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if (((event.which < 48 || event.which > 57) && event.which !== 44) || (!$(this).val() && event.which === 44)) {
                event.preventDefault();
            }
        });

    } );

    $(document).on('widget-updated widget-added', function(){
        bindSelect();
    });
    $(document).on('click','.dd-switch', function(){
        $(this).toggleClass('on');
        $(this).next('input').trigger('click');

        if($(this).next('[id*="lastViewed_showExcerpt"]')) {
            $(this).parent().parent().next('.contentSettings').toggleClass('hidden');
        }
    });

    $(document).on('click','.lv_link', function(){
        $(this).toggleClass('button-primary');
        $(this).next('input').trigger("click");
    });

    $(document).on('click','.js-collapse', function(e){
        e.preventDefault();
        $(this).next().toggleClass('visible');
    });

    function bindSelect () {

        var widgetSelector = ".widget[id*=\'dd_last_viewed-\']";

        $(widgetSelector).each(function() {
            var selector = $(this).find('.js-types-and-terms'),
                id = $(this).attr('id').split('-'),
                selector_excl_ids = $(this).find('.js-exclude-ids');

            id = id[2];
            if(!selector.data('select2') && id !== '__i__') {
                selector.select2({
                    width: '100%'
                });
                selector_excl_ids.select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    width: '100%',
                    maximumSelectionLength: 0,
                    dropdownCss: { 'display': 'none' },
                    createTag: function (params) {
                        // Don't offset to create a tag if there is no @ symbol
                        if (isNaN(params.term)) {
                            // Return null to disable tag creation
                            return null;
                        }

                        return {
                            id: params.term,
                            text: params.term
                        }
                    }
                });
            }
        });
    }
    
} )( jQuery );