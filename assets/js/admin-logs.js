jQuery(document).ready(function ($) {
    // Clear Email Log
    $('#talentora-clear-log').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: talentora_admin.strings.confirm_clear_log,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, clear it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: talentora_admin.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'talentora_clear_email_log',
                        nonce: talentora_admin.nonce
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire(
                                'Cleared!',
                                talentora_admin.strings.log_cleared,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.data || talentora_admin.strings.error,
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            talentora_admin.strings.error,
                            'error'
                        );
                    }
                });
            }
        });
    });
});
