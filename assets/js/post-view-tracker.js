/**
 * Post View Tracker
 * Registreert post views per sessie via AJAX
 */

(function($) {
    'use strict';
    
    // Wacht tot DOM klaar is
    $(document).ready(function() {
        // Check of we postViewData hebben (alleen op single posts)
        if (typeof postViewData === 'undefined' || !postViewData.postId) {
            return;
        }
        
        var postId = postViewData.postId;
        var ajaxUrl = postViewData.ajaxUrl;
        
        // Registreer de view via AJAX
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'register_post_view',
                post_id: postId
            },
            success: function(response) {
                if (response.success) {
                    // View is geregistreerd
                    console.log('Post view geregistreerd:', response.data.view_count);
                }
            },
            error: function(xhr, status, error) {
                console.error('Fout bij registreren post view:', error);
            }
        });
    });
    
})(jQuery);

