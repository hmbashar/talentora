jQuery(function ($) {
  // AJAX Form Submission
  $('.talentora-form').on('submit', function (e) {
    e.preventDefault();
    const $form = $(this);
    const $submitBtn = $form.find('button[type="submit"]');
    const originalBtnHtml = $submitBtn.html();

    // Show loading state
    $submitBtn.prop('disabled', true).html('<span class="dashicons dashicons-update spin"></span> ' + originalBtnHtml);

    const formData = new FormData(this);
    formData.append('action', 'talentora_submit_application');

    $.ajax({
      url: talentora_form_ajax.ajax_url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: 'Application Submitted!',
            text: response.data.message,
            confirmButtonColor: '#2563eb'
          });
          $form[0].reset();
          $('.file-label-text').text('Choose a file...');
        } else {
          let errorHtml = '<ul style="text-align:left;">';
          (response.data.messages || [response.data.message]).forEach(function (msg) {
            errorHtml += '<li>' + msg + '</li>';
          });
          errorHtml += '</ul>';

          Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            html: errorHtml,
            confirmButtonColor: '#2563eb'
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An unexpected error occurred. Please try again.',
          confirmButtonColor: '#2563eb'
        });
      },
      complete: function () {
        $submitBtn.prop('disabled', false).html(originalBtnHtml);
      }
    });
  });

  // Handle existing state in DOM (for fallback non-AJAX submissions)
  $('.talentora-form-state').each(function () {
    const raw = $(this).attr('data-state');
    if (!raw) return;
    let state = null;
    try {
      state = JSON.parse(raw);
    } catch (e) {
      return;
    }
    if (typeof Swal === 'undefined') return;

    if (state.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Application Submitted!',
        text: state.message,
        confirmButtonColor: '#2563eb'
      });
      const form = $(this).closest('.talentora-application-form').find('form')[0];
      if (form) form.reset();
    } else if (state.status === 'error') {
      let errorHtml = '<ul style="text-align:left;">';
      (state.messages || []).forEach(function (msg) {
        errorHtml += '<li>' + msg + '</li>';
      });
      errorHtml += '</ul>';
      Swal.fire({
        icon: 'error',
        title: 'Submission Failed',
        html: errorHtml,
        confirmButtonColor: '#2563eb'
      });
    }
  });

  $('.inputfile').on('change', function (e) {
    let fileName = '';
    if (this.files && this.files.length > 1) {
      fileName = ((this.getAttribute('data-multiple-caption') || '')).replace('{count}', this.files.length);
    } else {
      fileName = e.target.value.split('\\').pop();
    }
    $(this).next('.file-input-label').find('.file-label-text').text(fileName || 'Choose a file...');
  });
});
