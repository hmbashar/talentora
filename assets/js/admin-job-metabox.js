jQuery(document).ready(function ($) {
    var mediaUploader;

    // Media uploader for company logo
    $('#hiretalent_upload_logo_button').on('click', function (e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Choose Company Logo',
            button: {
                text: 'Use this logo'
            },
            multiple: false
        });

        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#hiretalent_company_logo_id').val(attachment.id);
            $('#hiretalent_logo_preview').html('<img src="' + attachment.url + '" style="max-width:150px;">');
            $('#hiretalent_remove_logo_button').show();
        });

        mediaUploader.open();
    });

    $('#hiretalent_remove_logo_button').on('click', function (e) {
        e.preventDefault();
        $('#hiretalent_company_logo_id').val('');
        $('#hiretalent_logo_preview').html('');
        $(this).hide();
    });

    // Toggle third party shortcode field based on application type
    $('#hiretalent_application_type').on('change', function () {
        if ($(this).val() === 'builtin') {
            $('#hiretalent_third_party_field').hide();
        } else {
            $('#hiretalent_third_party_field').show();
        }
    });
});
