( function( $ ) {

    $(document).find('.js-ddLastViewedAjax').each(function( ) {

        var selector ="#" + $(this).attr("id");
        var data = {
            'action': 'ajax_load_widget',
            'widgetId': $(this).data("id"),
            'postId': lvData.postID
        };

        jQuery.ajax({
            url: lvData.ajaxurl,
            type: 'POST',
            data: data,
            success: function (response) {
                $( selector ).replaceWith( response );
            }
        });
    });

    if (lvData.postID) {

        var data = {
            'action': 'ajax_set_cookie_by_js',
            'postId': lvData.postID,
            'postType': lvData.postType
        };

        jQuery.ajax({
            url: lvData.ajaxurl,
            type: 'POST',
            data: data,
            success: function (cookiesList) {
                $.each( JSON.parse(cookiesList), function( key, cookie ) {
                    var expires = (new Date(cookie['expire']* 1000)).toUTCString();
                    document.cookie = cookie['name'] + "=" + (cookie['list'])  + "; expires=" + expires + "; path=" +cookie['path'];
                });
            }
        });
    }

} )( jQuery );