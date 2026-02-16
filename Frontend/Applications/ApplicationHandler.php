<?php
/**
 * ApplicationHandler.php
 *
 * Handles frontend application form submission.
 *
 * @package HireTalent\Frontend\Applications
 * @since 1.0.0
 */

namespace HireTalent\Frontend\Applications;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Application Handler class.
 */
class ApplicationHandler
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('init', array($this, 'handle_application_submission'));
        add_shortcode('hiretalent_application_form', array($this, 'render_application_form'));
    }

    /**
     * Handle application form submission.
     *
     * @since 1.0.0
     */
    public function handle_application_submission()
    {
        if (!isset($_POST['hiretalent_submit_application']) || !isset($_POST['hiretalent_application_nonce'])) {
            return;
        }

        // Verify nonce
        if (!wp_verify_nonce($_POST['hiretalent_application_nonce'], 'hiretalent_submit_application')) {
            wp_die(__('Security check failed.', 'hiretalent'));
        }

        // Get and validate job ID
        $job_id = isset($_POST['job_id']) ? absint($_POST['job_id']) : 0;
        if (!$job_id || get_post_type($job_id) !== 'hiretalent_job') {
            wp_die(__('Invalid job ID.', 'hiretalent'));
        }

        // Sanitize inputs
        $name = isset($_POST['applicant_name']) ? sanitize_text_field($_POST['applicant_name']) : '';
        $email = isset($_POST['applicant_email']) ? sanitize_email($_POST['applicant_email']) : '';
        $phone = isset($_POST['applicant_phone']) ? sanitize_text_field($_POST['applicant_phone']) : '';
        $cover_letter = isset($_POST['cover_letter']) ? sanitize_textarea_field($_POST['cover_letter']) : '';

        // Validate required fields
        $errors = array();

        if (empty($name)) {
            $errors[] = __('Name is required.', 'hiretalent');
        }

        if (empty($email) || !is_email($email)) {
            $errors[] = __('Valid email is required.', 'hiretalent');
        }

        if (empty($phone)) {
            $errors[] = __('Phone is required.', 'hiretalent');
        }

        if (empty($cover_letter)) {
            $errors[] = __('Cover letter is required.', 'hiretalent');
        }

        // Handle resume upload
        $resume_id = 0;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $resume_id = $this->handle_resume_upload($_FILES['resume'], $errors);
        } else {
            $errors[] = __('Resume is required.', 'hiretalent');
        }

        // If there are errors, store them in session and redirect back
        if (!empty($errors)) {
            set_transient('hiretalent_application_errors_' . $job_id, $errors, 60);
            set_transient('hiretalent_application_data_' . $job_id, $_POST, 60);
            wp_safe_redirect(get_permalink($job_id) . '#application-form');
            exit;
        }

        // Create application post
        $application_id = $this->create_application($job_id, $name, $email, $phone, $cover_letter, $resume_id);

        if ($application_id) {
            // Send notifications
            $this->send_admin_notification($application_id, $job_id, $name, $email);
            $this->send_applicant_confirmation($email, $name, $job_id);

            // Set success message
            set_transient('hiretalent_application_success_' . $job_id, true, 60);
            wp_safe_redirect(get_permalink($job_id) . '#application-success');
            exit;
        } else {
            set_transient('hiretalent_application_errors_' . $job_id, array(__('Failed to submit application. Please try again.', 'hiretalent')), 60);
            wp_safe_redirect(get_permalink($job_id) . '#application-form');
            exit;
        }
    }

    /**
     * Handle resume file upload.
     *
     * @param array $file    File data from $_FILES.
     * @param array &$errors Error array reference.
     * @return int Attachment ID or 0 on failure.
     * @since 1.0.0
     */
    private function handle_resume_upload($file, &$errors)
    {
        // Validate file type
        $allowed_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $file_type = wp_check_filetype($file['name']);

        if (!in_array($file['type'], $allowed_types)) {
            $errors[] = __('Resume must be a PDF or DOC file.', 'hiretalent');
            return 0;
        }

        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = __('Resume file size must not exceed 5MB.', 'hiretalent');
            return 0;
        }

        // Upload file
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $upload = wp_handle_upload($file, array('test_form' => false));

        if (isset($upload['error'])) {
            $errors[] = $upload['error'];
            return 0;
        }

        // Create attachment
        $attachment = array(
            'post_mime_type' => $upload['type'],
            'post_title' => sanitize_file_name($file['name']),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $upload['file']);

        if (!is_wp_error($attach_id)) {
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);
            return $attach_id;
        }

        return 0;
    }

    /**
     * Create application post.
     *
     * @param int    $job_id       Job ID.
     * @param string $name         Applicant name.
     * @param string $email        Applicant email.
     * @param string $phone        Applicant phone.
     * @param string $cover_letter Cover letter.
     * @param int    $resume_id    Resume attachment ID.
     * @return int Application post ID or 0 on failure.
     * @since 1.0.0
     */
    private function create_application($job_id, $name, $email, $phone, $cover_letter, $resume_id)
    {
        $application_data = array(
            'post_title' => sprintf(__('Application from %s for %s', 'hiretalent'), $name, get_the_title($job_id)),
            'post_type' => 'hiretalent_app',
            'post_status' => 'publish',
        );

        $application_id = wp_insert_post($application_data);

        if ($application_id) {
            // Save meta data
            update_post_meta($application_id, 'hiretalent_job_id', $job_id);
            update_post_meta($application_id, 'hiretalent_applicant_name', $name);
            update_post_meta($application_id, 'hiretalent_applicant_email', $email);
            update_post_meta($application_id, 'hiretalent_applicant_phone', $phone);
            update_post_meta($application_id, 'hiretalent_cover_letter', $cover_letter);
            update_post_meta($application_id, 'hiretalent_resume_id', $resume_id);

            // Set default status to pending
            update_post_meta($application_id, 'hiretalent_application_status', 'Pending');

            return $application_id;
        }

        return 0;
    }

    /**
     * Send admin notification email.
     *
     * @param int    $application_id Application ID.
     * @param int    $job_id         Job ID.
     * @param string $name           Applicant name.
     * @param string $email          Applicant email.
     * @since 1.0.0
     */
    private function send_admin_notification($application_id, $job_id, $name, $email)
    {
        $admin_email = get_option('admin_email');
        $job_title = get_the_title($job_id);
        $application_url = admin_url('post.php?post=' . $application_id . '&action=edit');

        $subject = sprintf(__('[%s] New Job Application: %s', 'hiretalent'), get_bloginfo('name'), $job_title);

        $message = sprintf(
            __("You have received a new job application.\n\nJob: %s\nApplicant: %s\nEmail: %s\n\nView application: %s", 'hiretalent'),
            $job_title,
            $name,
            $email,
            $application_url
        );

        wp_mail($admin_email, $subject, $message);
    }

    /**
     * Send applicant confirmation email.
     *
     * @param string $email  Applicant email.
     * @param string $name   Applicant name.
     * @param int    $job_id Job ID.
     * @since 1.0.0
     */
    private function send_applicant_confirmation($email, $name, $job_id)
    {
        $job_title = get_the_title($job_id);
        $subject = sprintf(__('Application Received: %s', 'hiretalent'), $job_title);

        $message = sprintf(
            __("Dear %s,\n\nThank you for applying for the position of %s.\n\nWe have received your application and will review it shortly. If your qualifications match our requirements, we will contact you for the next steps.\n\nBest regards,\n%s", 'hiretalent'),
            $name,
            $job_title,
            get_bloginfo('name')
        );

        wp_mail($email, $subject, $message);
    }

    /**
     * Render application form.
     *
     * @param array $atts Shortcode attributes.
     * @return string Form HTML.
     * @since 1.0.0
     */
    public function render_application_form($atts)
    {
        $atts = shortcode_atts(array(
            'job_id' => get_the_ID(),
        ), $atts);

        $job_id = absint($atts['job_id']);

        if (!$job_id || get_post_type($job_id) !== 'hiretalent_job') {
            return '<p>' . esc_html__('Invalid job ID.', 'hiretalent') . '</p>';
        }

        // Check for success message
        $success = get_transient('hiretalent_application_success_' . $job_id);
        if ($success) {
            delete_transient('hiretalent_application_success_' . $job_id);
            return '<div id="application-success" class="hiretalent-message hiretalent-success"><p>' . esc_html__('Thank you! Your application has been submitted successfully. We will contact you soon.', 'hiretalent') . '</p></div>';
        }

        // Get errors and previous data
        $errors = get_transient('hiretalent_application_errors_' . $job_id);
        $data = get_transient('hiretalent_application_data_' . $job_id);

        if ($errors) {
            delete_transient('hiretalent_application_errors_' . $job_id);
        }
        if ($data) {
            delete_transient('hiretalent_application_data_' . $job_id);
        }

        ob_start();
        ?>
        <div id="application-form" class="hiretalent-application-form">
            <?php if ($errors): ?>
                <div class="hiretalent-message hiretalent-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li>
                                <?php echo esc_html($error); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="hiretalent-form">
                <?php wp_nonce_field('hiretalent_submit_application', 'hiretalent_application_nonce'); ?>
                <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">

                <div class="hiretalent-form-field">
                    <label for="applicant_name">
                        <?php esc_html_e('Full Name', 'hiretalent'); ?> <span class="required">*</span>
                    </label>
                    <input type="text" id="applicant_name" name="applicant_name"
                        value="<?php echo isset($data['applicant_name']) ? esc_attr($data['applicant_name']) : ''; ?>" required>
                </div>

                <div class="hiretalent-form-field">
                    <label for="applicant_email">
                        <?php esc_html_e('Email Address', 'hiretalent'); ?> <span class="required">*</span>
                    </label>
                    <input type="email" id="applicant_email" name="applicant_email"
                        value="<?php echo isset($data['applicant_email']) ? esc_attr($data['applicant_email']) : ''; ?>"
                        required>
                </div>

                <div class="hiretalent-form-field">
                    <label for="applicant_phone">
                        <?php esc_html_e('Phone Number', 'hiretalent'); ?> <span class="required">*</span>
                    </label>
                    <input type="tel" id="applicant_phone" name="applicant_phone"
                        value="<?php echo isset($data['applicant_phone']) ? esc_attr($data['applicant_phone']) : ''; ?>"
                        required>
                </div>

                <div class="hiretalent-form-field">
                    <label for="resume">
                        <?php esc_html_e('Resume (PDF or DOC, max 5MB)', 'hiretalent'); ?> <span class="required">*</span>
                    </label>
                    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                </div>

                <div class="hiretalent-form-field">
                    <label for="cover_letter">
                        <?php esc_html_e('Cover Letter', 'hiretalent'); ?> <span class="required">*</span>
                    </label>
                    <textarea id="cover_letter" name="cover_letter" rows="8"
                        required><?php echo isset($data['cover_letter']) ? esc_textarea($data['cover_letter']) : ''; ?></textarea>
                </div>

                <div class="hiretalent-form-submit">
                    <button type="submit" name="hiretalent_submit_application" class="hiretalent-button">
                        <?php esc_html_e('Submit Application', 'hiretalent'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}
