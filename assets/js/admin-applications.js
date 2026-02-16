jQuery(document).ready(function ($) {
    // Bulk Action Confirmation
    $('#doaction, #doaction2').on('click', function (e) {
        var selector = $(this).attr('id') === 'doaction' ? '#bulk-action-selector-top' : '#bulk-action-selector-bottom';
        var action = $(selector).val();

        if (action === '-1') {
            return;
        }

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
                $(this).closest('form').submit();
            }
        });
    });
});
