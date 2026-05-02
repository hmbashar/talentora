<?php
/**
 * ApplicationHandler.php
 *
 * Handles frontend application form submission.
 *
 * @package Talentora\Frontend\Applications
 * @since 1.0.0
 */

namespace Talentora\Frontend\Applications;

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
    /**
     * Notification Manager instance.
     *
     * @var \Talentora\NotificationManager
     */
    private $notification_manager;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->notification_manager = new \Talentora\NotificationManager();

        add_action('admin_post_talentora_submit_application', array($this, 'handle_application_submission'));
        add_action('admin_post_nopriv_talentora_submit_application', array($this, 'handle_application_submission'));

        // AJAX handlers
        add_action('wp_ajax_talentora_submit_application', array($this, 'handle_ajax_application_submission'));
        add_action('wp_ajax_nopriv_talentora_submit_application', array($this, 'handle_ajax_application_submission'));

        add_shortcode('talentora_application_form', array($this, 'render_application_form'));
    }

    /**
     * Handle application form submission.
     *
     * @since 1.0.0
     */
    /**
     * Handle application form submission via standard POST.
     *
     * @since 1.0.0
     */
    public function handle_application_submission()
    {
        if (!isset($_POST['talentora_submit_application']) || !isset($_POST['talentora_application_nonce'])) {
            return;
        }

        // Unslash + sanitize nonce first.
        $nonce = sanitize_text_field(wp_unslash($_POST['talentora_application_nonce']));

        // Verify nonce.
        if (!wp_verify_nonce($nonce, 'talentora_submit_application')) {
            wp_die(esc_html__('Security check failed.', 'talentora'));
        }

        // Extract and sanitize only the specific fields this plugin needs.
        // Never pass the entire $_POST stack to avoid processing unnecessary data.
        $job_id = isset($_POST['job_id']) ? absint($_POST['job_id']) : 0;

        $input = array(
            'job_id' => $job_id,
            'talentora_website' => isset($_POST['talentora_website'])
                ? sanitize_text_field(wp_unslash($_POST['talentora_website']))
                : '',
            'applicant_name' => isset($_POST['applicant_name'])
                ? sanitize_text_field(wp_unslash($_POST['applicant_name']))
                : '',
            'applicant_email' => isset($_POST['applicant_email'])
                ? sanitize_email(wp_unslash($_POST['applicant_email']))
                : '',
            'applicant_phone' => isset($_POST['applicant_phone'])
                ? sanitize_text_field(wp_unslash($_POST['applicant_phone']))
                : '',
            'cover_letter' => isset($_POST['cover_letter'])
                ? sanitize_textarea_field(wp_unslash($_POST['cover_letter']))
                : '',
        );

        // Extract only the resume file entry from $_FILES.
        $resume_file = array();

        if (isset($_FILES['resume']) && is_array($_FILES['resume'])) {
            $resume_file = array(
                'name' => isset($_FILES['resume']['name']) ? sanitize_file_name(wp_unslash($_FILES['resume']['name'])) : '',
                'type' => isset($_FILES['resume']['type']) ? sanitize_mime_type(wp_unslash($_FILES['resume']['type'])) : '',
                'tmp_name' => isset($_FILES['resume']['tmp_name']) ? sanitize_text_field(wp_unslash($_FILES['resume']['tmp_name'])) : '',
                'error' => isset($_FILES['resume']['error']) ? absint($_FILES['resume']['error']) : UPLOAD_ERR_NO_FILE,
                'size' => isset($_FILES['resume']['size']) ? absint($_FILES['resume']['size']) : 0,
            );
        }

        $result = $this->process_application_submission($input, $resume_file);

        if ($result['success']) {
            set_transient('talentora_application_success_' . $job_id, true, 60);
            wp_safe_redirect(get_permalink($job_id) . '#application-success');
            exit;
        } else {
            if (!empty($result['errors'])) {
                set_transient('talentora_application_errors_' . $job_id, $result['errors'], 60);
                // Build a sanitized data array — never persist raw $_POST.
                // Only store the specific fields needed to repopulate the form,
                // each sanitized with the most appropriate function for its type.
                $sanitized_data = array(
                    'applicant_name' => isset($_POST['applicant_name'])
                        ? sanitize_text_field(wp_unslash($_POST['applicant_name']))
                        : '',
                    'applicant_email' => isset($_POST['applicant_email'])
                        ? sanitize_email(wp_unslash($_POST['applicant_email']))
                        : '',
                    'applicant_phone' => isset($_POST['applicant_phone'])
                        ? sanitize_text_field(wp_unslash($_POST['applicant_phone']))
                        : '',
                    'cover_letter' => isset($_POST['cover_letter'])
                        ? sanitize_textarea_field(wp_unslash($_POST['cover_letter']))
                        : '',
                );
                set_transient('talentora_application_data_' . $job_id, $sanitized_data, 60);
            }
            wp_safe_redirect(get_permalink($job_id) . '#application-form');
            exit;
        }
    }

    /**
     * Handle AJAX application form submission.
     *
     * @since 1.1.0
     */
    public function handle_ajax_application_submission()
    {
        check_ajax_referer('talentora_submit_application', 'talentora_application_nonce');

        // Extract and sanitize only the specific fields this plugin needs.
        // Never pass the entire $_POST stack to avoid processing unnecessary data.
        $input = array(
            'job_id' => isset($_POST['job_id']) ? absint($_POST['job_id']) : 0,
            'talentora_website' => isset($_POST['talentora_website'])
                ? sanitize_text_field(wp_unslash($_POST['talentora_website']))
                : '',
            'applicant_name' => isset($_POST['applicant_name'])
                ? sanitize_text_field(wp_unslash($_POST['applicant_name']))
                : '',
            'applicant_email' => isset($_POST['applicant_email'])
                ? sanitize_email(wp_unslash($_POST['applicant_email']))
                : '',
            'applicant_phone' => isset($_POST['applicant_phone'])
                ? sanitize_text_field(wp_unslash($_POST['applicant_phone']))
                : '',
            'cover_letter' => isset($_POST['cover_letter'])
                ? sanitize_textarea_field(wp_unslash($_POST['cover_letter']))
                : '',
        );

        // Extract only the resume file entry from $_FILES.
        $resume_file = array();

        if (isset($_FILES['resume']) && is_array($_FILES['resume'])) {
            $resume_file = array(
                'name' => isset($_FILES['resume']['name']) ? sanitize_file_name(wp_unslash($_FILES['resume']['name'])) : '',
                'type' => isset($_FILES['resume']['type']) ? sanitize_mime_type(wp_unslash($_FILES['resume']['type'])) : '',
                'tmp_name' => isset($_FILES['resume']['tmp_name']) ? sanitize_text_field(wp_unslash($_FILES['resume']['tmp_name'])) : '',
                'error' => isset($_FILES['resume']['error']) ? absint($_FILES['resume']['error']) : UPLOAD_ERR_NO_FILE,
                'size' => isset($_FILES['resume']['size']) ? absint($_FILES['resume']['size']) : 0,
            );
        }

        $result = $this->process_application_submission($input, $resume_file);

        if ($result['success']) {
            wp_send_json_success(array('message' => $result['message']));
        } else {
            wp_send_json_error(array(
                'message' => $result['message'],
                'messages' => $result['errors']
            ));
        }
    }

    /**
     * Process application submission logic.
     *
     * @param array $post_data Submitted POST data.
     * @param array $files     Submitted FILES data.
     * @return array Results array with success and errors.
     * @since 1.1.0
     */
    private function process_application_submission($post_data, $files)
    {
        // Get and validate job ID
        $job_id = isset($post_data['job_id']) ? absint($post_data['job_id']) : 0;
        if (!$job_id || get_post_type($job_id) !== 'talentora_job') {
            return array(
                'success' => false,
                'message' => esc_html__('Invalid job ID.', 'talentora'),
                'errors' => array(esc_html__('Invalid job ID.', 'talentora'))
            );
        }

        // Honeypot check
        if (!empty($post_data['talentora_website'])) {
            return array(
                'success' => true, // Silently succeed for bots
                'message' => esc_html__('Application submitted successfully.', 'talentora')
            );
        }

        // Sanitize inputs
        $name = isset($post_data['applicant_name']) ? sanitize_text_field($post_data['applicant_name']) : '';
        $email = isset($post_data['applicant_email']) ? sanitize_email($post_data['applicant_email']) : '';
        $phone = isset($post_data['applicant_phone']) ? sanitize_text_field($post_data['applicant_phone']) : '';
        $cover_letter = isset($post_data['cover_letter']) ? sanitize_textarea_field($post_data['cover_letter']) : '';

        // Validate required fields
        $errors = array();

        if (empty($name)) {
            $errors[] = esc_html__('Name is required.', 'talentora');
        }

        if (empty($email) || !is_email($email)) {
            $errors[] = esc_html__('Valid email is required.', 'talentora');
        }

        if (empty($phone)) {
            $errors[] = esc_html__('Phone is required.', 'talentora');
        }

        if (empty($cover_letter)) {
            $errors[] = esc_html__('Cover letter is required.', 'talentora');
        }

        // Handle resume upload
        $resume_id = 0;
        if (!empty($files) && isset($files['error']) && UPLOAD_ERR_OK === $files['error']) {
            $resume_id = $this->handle_resume_upload($files, $errors);
        } else {
            $errors[] = esc_html__('Resume is required.', 'talentora');
        }

        // Return error if any
        if (!empty($errors)) {
            return array(
                'success' => false,
                'message' => esc_html__('Please fix the errors below.', 'talentora'),
                'errors' => $errors
            );
        }

        // Create application post
        $application_id = $this->create_application($job_id, $name, $email, $phone, $cover_letter, $resume_id);

        if ($application_id) {
            // Send notifications
            if (isset($this->notification_manager)) {
                $this->notification_manager->send_admin_notification($application_id);
                $this->notification_manager->send_applicant_confirmation($application_id);
            }

            return array(
                'success' => true,
                'message' => esc_html__('Thank you! Your application has been submitted successfully.', 'talentora')
            );
        }

        return array(
            'success' => false,
            'message' => esc_html__('Failed to submit application. Please try again.', 'talentora'),
            'errors' => array(esc_html__('Failed to submit application. Please try again.', 'talentora'))
        );
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


        if (!in_array($file['type'], $allowed_types)) {
            $errors[] = esc_html__('Resume must be a PDF or DOC file.', 'talentora');
            return 0;
        }

        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = esc_html__('Resume file size must not exceed 5MB.', 'talentora');
            return 0;
        }

        // Load wp-admin file helpers only when needed for upload processing.
        // These are core WordPress admin includes required for wp_handle_upload()
        // and wp_generate_attachment_metadata(). Guarded by function_exists()
        // to avoid redundant loading.
        if (!function_exists('wp_handle_upload')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }

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
            'post_title' => sprintf(
                /* translators: 1: Applicant name, 2: Job title. */
                __('Application from %1$s for %2$s', 'talentora'),
                $name,
                get_the_title($job_id)
            ),
            'post_type' => 'talentora_app',
            'post_status' => 'publish',
        );

        $application_id = wp_insert_post($application_data);

        if ($application_id) {
            // Save meta data
            update_post_meta($application_id, 'talentora_job_id', $job_id);
            update_post_meta($application_id, 'talentora_applicant_name', $name);
            update_post_meta($application_id, 'talentora_applicant_email', $email);
            update_post_meta($application_id, 'talentora_applicant_phone', $phone);
            update_post_meta($application_id, 'talentora_cover_letter', $cover_letter);
            update_post_meta($application_id, 'talentora_resume_id', $resume_id);

            // Set default status to pending
            update_post_meta($application_id, 'talentora_application_status', 'Pending');

            return $application_id;
        }

        return 0;
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

        if (!$job_id || get_post_type($job_id) !== 'talentora_job') {
            return '<p>' . esc_html__('Invalid job ID.', 'talentora') . '</p>';
        }

        // Check for success message
        $success = get_transient('talentora_application_success_' . $job_id);
        if ($success) {
            delete_transient('talentora_application_success_' . $job_id);
            return '<div id="application-success" class="talentora-message talentora-success"><p>' . esc_html__('Thank you! Your application has been submitted successfully. We will contact you soon.', 'talentora') . '</p></div>';
        }

        // Get errors and previous data
        $errors = get_transient('talentora_application_errors_' . $job_id);
        $data = get_transient('talentora_application_data_' . $job_id);

        if ($errors) {
            delete_transient('talentora_application_errors_' . $job_id);
        }
        if ($data) {
            delete_transient('talentora_application_data_' . $job_id);
        }

        ob_start();
        ?>
        <div id="application-form" class="talentora-application-form">
            <div id="talentora-form-state" class="talentora-form-state" data-state="<?php
            if ($success) {
                echo esc_attr(json_encode(array('status' => 'success', 'message' => __('Thank you! Your application has been submitted successfully.', 'talentora'))));
            } elseif ($errors) {
                echo esc_attr(json_encode(array('status' => 'error', 'messages' => $errors)));
            }
            ?>"></div>

            <form method="post" enctype="multipart/form-data" class="talentora-form">
                <?php wp_nonce_field('talentora_submit_application', 'talentora_application_nonce'); ?>
                <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">

                <!-- Honeypot Field -->
                <div style="display:none;">
                    <label for="talentora_website"><?php esc_html_e('Website', 'talentora'); ?></label>
                    <input type="text" id="talentora_website" name="talentora_website" value="">
                </div>

                <div class="form-grid-row">
                    <div class="talentora-form-field">
                        <label for="applicant_name">
                            <?php esc_html_e('Full Name', 'talentora'); ?> <span class="required">*</span>
                        </label>
                        <div class="input-with-icon">
                            <span class="dashicons dashicons-admin-users"></span>
                            <input type="text" id="applicant_name" name="applicant_name"
                                value="<?php echo isset($data['applicant_name']) ? esc_attr($data['applicant_name']) : ''; ?>"
                                placeholder="<?php esc_attr_e('John Doe', 'talentora'); ?>" required>
                        </div>
                    </div>

                    <div class="talentora-form-field">
                        <label for="applicant_email">
                            <?php esc_html_e('Email Address', 'talentora'); ?> <span class="required">*</span>
                        </label>
                        <div class="input-with-icon">
                            <span class="dashicons dashicons-email"></span>
                            <input type="email" id="applicant_email" name="applicant_email"
                                value="<?php echo isset($data['applicant_email']) ? esc_attr($data['applicant_email']) : ''; ?>"
                                placeholder="<?php esc_attr_e('john@example.com', 'talentora'); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-grid-row">
                    <div class="talentora-form-field">
                        <label for="applicant_phone">
                            <?php esc_html_e('Phone Number', 'talentora'); ?> <span class="required">*</span>
                        </label>
                        <div class="input-with-icon">
                            <span class="dashicons dashicons-smartphone"></span>
                            <input type="tel" id="applicant_phone" name="applicant_phone"
                                value="<?php echo isset($data['applicant_phone']) ? esc_attr($data['applicant_phone']) : ''; ?>"
                                placeholder="<?php esc_attr_e('+1 234 567 8900', 'talentora'); ?>" required>
                        </div>
                    </div>

                    <div class="talentora-form-field">
                        <label for="resume">
                            <?php esc_html_e('Resume / CV', 'talentora'); ?> <span class="required">*</span>
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" class="inputfile" required>
                            <label for="resume" class="file-input-label">
                                <span class="dashicons dashicons-upload"></span>
                                <span class="file-label-text"><?php esc_html_e('Choose a file...', 'talentora'); ?></span>
                            </label>
                            <span class="file-help-text"><?php esc_html_e('PDF or DOC, max 5MB', 'talentora'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="talentora-form-field full-width">
                    <label for="cover_letter">
                        <?php esc_html_e('Cover Letter', 'talentora'); ?> <span class="required">*</span>
                    </label>
                    <textarea id="cover_letter" name="cover_letter" rows="6"
                        placeholder="<?php esc_attr_e('Tell us why you are a great fit for this role...', 'talentora'); ?>"
                        required><?php echo isset($data['cover_letter']) ? esc_textarea($data['cover_letter']) : ''; ?></textarea>
                </div>

                <div class="talentora-form-submit">
                    <button type="submit" name="talentora_submit_application" class="talentora-button primary large">
                        <span class="dashicons dashicons-paperplane"></span>
                        <?php esc_html_e('Submit Application', 'talentora'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}
