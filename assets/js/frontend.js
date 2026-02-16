jQuery(document).ready(function($) {
    // Job Filtering
    $('#hiretalent-job-filter-form').on('change', 'select, input', function(e) {
        e.preventDefault();
        
        var filterData = $(this).closest('form').serialize();
        var $container = $('.hiretalent-jobs-list');
        
        // Add loading state
        $container.addClass('loading').css('opacity', '0.5');

        $.ajax({
            url: hiretalent_ajax.ajax_url,
            type: 'POST',
            data: filterData + '&action=hiretalent_filter_jobs&nonce=' + hiretalent_ajax.nonce,
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
