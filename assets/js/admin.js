jQuery(document).ready(function ($) {
    // Clear Email Log
    $('#hiretalent-clear-log').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: hiretalent_admin.strings.confirm_clear_log,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, clear it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: hiretalent_admin.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'hiretalent_clear_email_log',
                        nonce: hiretalent_admin.nonce
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire(
                                'Cleared!',
                                hiretalent_admin.strings.log_cleared,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.data || hiretalent_admin.strings.error,
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            hiretalent_admin.strings.error,
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Bulk Action Confirmation
    $('#doaction, #doaction2').on('click', function (e) {
        var selector = $(this).attr('id') === 'doaction' ? '#bulk-action-selector-top' : '#bulk-action-selector-bottom';
        var action = $(selector).val();

        if (action === '-1') {
            return;
        }

        // Only confirm for destructive actions or status changes if desired
        // For now, let's confirm for everything to be safe and consistent with "all alert/message"
        e.preventDefault();

        Swal.fire({
            title: hiretalent_admin.strings.confirm_bulk_action,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, apply'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form programmatically bypassing the jQuery listener
                $(this).closest('form').submit();
            }
        });
    });

    // Intercept Window Confirm (Optional, but covers other plugins/standard WP links)
    // var originalConfirm = window.confirm;
    // window.confirm = function (message) {
    //     // This is tricky because window.confirm is synchronous and Swal is async.
    //     // We can't easily replace it for standard links without preventing default and re-triggering.
    //     // So we'll stick to explicit handlers for now.
    //     return originalConfirm(message);
    // };
});
