jQuery(document).ready(function($) {
    // Job Filtering
    $('#talentora-job-filter-form').on('change', 'select, input', function(e) {
        e.preventDefault();
        
        var filterData = $(this).closest('form').serialize();
        var $container = $('.talentora-jobs-list');
        
        // Add loading state
        $container.addClass('loading').css('opacity', '0.5');

        $.ajax({
            url: talentora_ajax.ajax_url,
            type: 'POST',
            data: filterData + '&action=talentora_filter_jobs&nonce=' + talentora_ajax.nonce,
            success: function(response) {
                if (response.success) {
                    $container.html(response.data);
                } else {
                    console.log('Error: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error: ' + error);
            },
            complete: function() {
                $container.removeClass('loading').css('opacity', '1');
            }
        });
    });
});
