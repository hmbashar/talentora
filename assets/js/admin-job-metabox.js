jQuery(document).ready(function ($) {
    var mediaUploader;

    // Media uploader for company logo
    $('#talentora_upload_logo_button').on('click', function (e) {
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
            $('#talentora_company_logo_id').val(attachment.id);
            $('#talentora_logo_preview').html('<img src="' + attachment.url + '">');
            $('#talentora_remove_logo_button').show();
        });

        mediaUploader.open();
    });

    $('#talentora_remove_logo_button').on('click', function (e) {
        e.preventDefault();
        $('#talentora_company_logo_id').val('');
        $('#talentora_logo_preview').html('');
        $(this).hide();
    });

    // Toggle third party shortcode field based on application type
    $('#talentora_application_type').on('change', function () {
        if ($(this).val() === 'builtin') {
            $('#talentora_third_party_field').hide();
        } else {
            $('#talentora_third_party_field').show();
        }
    });
});
