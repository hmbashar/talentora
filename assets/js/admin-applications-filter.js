jQuery(document).ready(function($) {
    $(document).on('change', '#talentora-status-filter, #talentora-job-filter', function(e) {
        e.preventDefault();
        var form = $('#posts-filter');
        var params = new URLSearchParams(form.serialize());
        
        // Ensure post_type is always included so the server knows which list table to render
        if (!params.has('post_type')) {
            var currentParams = new URLSearchParams(window.location.search);
            if (currentParams.has('post_type')) {
                params.set('post_type', currentParams.get('post_type'));
            } else {
                params.set('post_type', 'talentora_app');
            }
        }
        
        var url = window.location.pathname + '?' + params.toString();
        
        var list = $('#the-list');
        list.css('opacity', '0.5');
        
        $.get(url, function(data) {
            var new_list = $(data).find('#the-list').html();
            var new_top_nav = $(data).find('.tablenav.top').html();
            var new_bottom_nav = $(data).find('.tablenav.bottom').html();
            
            if (new_list !== undefined) {
                list.html(new_list);
                $('.tablenav.top').html(new_top_nav);
                $('.tablenav.bottom').html(new_bottom_nav);
            }
            list.css('opacity', '1');
            
            // Update URL silently
            if(window.history && window.history.pushState) {
                window.history.pushState(null, null, url);
            }
        });
    });
});
