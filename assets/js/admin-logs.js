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
});
