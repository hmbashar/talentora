<?php
/**
 * Settings.php
 *
 * Handles plugin settings page.
 *
 * @package HireTalent\Admin\Pages
 * @since 1.0.0
 */

namespace HireTalent\Admin\Pages;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings page class.
 */
class Settings
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_hiretalent_clear_email_log', array($this, 'ajax_clear_email_log'));
    }

    /**
     * Register plugin settings.
     *
     * @since 1.0.0
     */
    public function register_settings()
    {
        // General Settings
        register_setting('hiretalent_general_settings', 'hiretalent_apply_form_shortcode', 'sanitize_text_field');
        register_setting('hiretalent_general_settings', 'hiretalent_jobs_per_page', 'absint');
        register_setting('hiretalent_general_settings', 'hiretalent_application_statuses', 'sanitize_textarea_field');

        add_settings_section(
            'hiretalent_general_section',
            __('General Configuration', 'hiretalent'),
            null,
            'hiretalent_general_settings'
        );

        add_settings_field(
            'hiretalent_apply_form_shortcode',
            __('Third-Party Form Shortcode', 'hiretalent'),
            array($this, 'apply_form_shortcode_callback'),
            'hiretalent_general_settings',
            'hiretalent_general_section'
        );

        add_settings_field(
            'hiretalent_jobs_per_page',
            __('Jobs Per Page', 'hiretalent'),
            array($this, 'jobs_per_page_callback'),
            'hiretalent_general_settings',
            'hiretalent_general_section'
        );

        add_settings_field(
            'hiretalent_application_statuses',
            __('Application Statuses', 'hiretalent'),
            array($this, 'application_statuses_callback'),
            'hiretalent_general_settings',
            'hiretalent_general_section'
        );

        // Email Template Settings
        register_setting('hiretalent_email_settings', 'hiretalent_admin_notification_subject', 'sanitize_text_field');
        register_setting('hiretalent_email_settings', 'hiretalent_admin_notification_message', 'sanitize_textarea_field');
        register_setting('hiretalent_email_settings', 'hiretalent_applicant_confirmation_subject', 'sanitize_text_field');
        register_setting('hiretalent_email_settings', 'hiretalent_applicant_confirmation_message', 'sanitize_textarea_field');
        register_setting('hiretalent_email_settings', 'hiretalent_status_change_subject', 'sanitize_text_field');
        register_setting('hiretalent_email_settings', 'hiretalent_status_change_message', 'sanitize_textarea_field');

        add_settings_section(
            'hiretalent_email_templates_section',
            __('Email Notification Templates', 'hiretalent'),
            array($this, 'email_templates_section_callback'),
            'hiretalent_email_settings'
        );

        add_settings_field(
            'hiretalent_admin_notification_subject',
            __('Admin Notification Subject', 'hiretalent'),
            array($this, 'admin_notification_subject_callback'),
            'hiretalent_email_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_admin_notification_message',
            __('Admin Notification Message', 'hiretalent'),
            array($this, 'admin_notification_message_callback'),
            'hiretalent_email_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_applicant_confirmation_subject',
            __('Applicant Confirmation Subject', 'hiretalent'),
            array($this, 'applicant_confirmation_subject_callback'),
            'hiretalent_email_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_applicant_confirmation_message',
            __('Applicant Confirmation Message', 'hiretalent'),
            array($this, 'applicant_confirmation_message_callback'),
            'hiretalent_email_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_status_change_subject',
            __('Status Change Subject', 'hiretalent'),
            array($this, 'status_change_subject_callback'),
            'hiretalent_email_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_status_change_message',
            __('Status Change Message', 'hiretalent'),
            array($this, 'status_change_message_callback'),
            'hiretalent_email_settings',
            'hiretalent_email_templates_section'
        );
    }

    /**
     * Render settings page.
     *
     * @since 1.0.0
     */
    public function render_settings_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
        ?>
        <div class="wrap hiretalent-settings-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <h2 class="nav-tab-wrapper">
                <a href="?post_type=hiretalent_job&page=hiretalent-settings&tab=general"
                    class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('General Settings', 'hiretalent'); ?>
                </a>
                <a href="?post_type=hiretalent_job&page=hiretalent-settings&tab=email_templates"
                    class="nav-tab <?php echo $active_tab == 'email_templates' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Email Templates', 'hiretalent'); ?>
                </a>
                <a href="?post_type=hiretalent_job&page=hiretalent-settings&tab=email_logs"
                    class="nav-tab <?php echo $active_tab == 'email_logs' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Email Logs', 'hiretalent'); ?>
                </a>
            </h2>

            <?php if ($active_tab == 'email_logs'): ?>
                <?php $this->render_email_log_tab(); ?>
            <?php else: ?>
                <form action="options.php" method="post">
                    <div class="hiretalent-card">
                        <?php
                        if ($active_tab == 'general') {
                            settings_fields('hiretalent_general_settings');
                            do_settings_sections('hiretalent_general_settings');
                        } else if ($active_tab == 'email_templates') {
                            settings_fields('hiretalent_email_settings');
                            do_settings_sections('hiretalent_email_settings');
                        }
                        ?>
                    </div>
                    <?php submit_button(); ?>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Email Logs tab.
     */
    private function render_email_log_tab()
    {
        $log_file = wp_upload_dir()['basedir'] . '/hiretalent-logs/email.log';

        echo '<div class="hiretalent-card">';
        echo '<h3>' . esc_html__('Application Emails Log', 'hiretalent') . '</h3>';

        if (file_exists($log_file)) {
            $content = file_get_contents($log_file);
            echo '<div class="hiretalent-log-viewer">' . esc_html($content) . '</div>';
            echo '<p class="description details">' . esc_html__('This log file is located at: ', 'hiretalent') . esc_html($log_file) . '</p>';

            // Clear log button
            echo '<div class="hiretalent-actions">';
            echo '<button type="button" id="hiretalent-clear-log" class="button button-danger">' . esc_html__('Clear Log', 'hiretalent') . '</button>';
            echo '</div>';
            echo '<p class="description warning" style="margin-top: 10px;">' . esc_html__('Note: Clearing the log is permanent.', 'hiretalent') . '</p>';
        } else {
            echo '<p>' . esc_html__('No log file found.', 'hiretalent') . '</p>';
        }
        echo '</div>';
    }

    /**
     * Apply form shortcode field callback.
     */
    public function apply_form_shortcode_callback()
    {
        $value = get_option('hiretalent_apply_form_shortcode', '');
        ?>
        <input type="text" name="hiretalent_apply_form_shortcode" value="<?php echo esc_attr($value); ?>" class="regular-text"
            placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'hiretalent'); ?>">
        <p class="description">
            <?php esc_html_e('Enter the shortcode of your contact/application form plugin. Leave empty to use the built-in form.', 'hiretalent'); ?>
        </p>
        <?php
    }

    /**
     * Jobs per page field callback.
     */
    public function jobs_per_page_callback()
    {
        $value = get_option('hiretalent_jobs_per_page', 10);
        ?>
        <input type="number" name="hiretalent_jobs_per_page" value="<?php echo esc_attr($value); ?>" min="1" max="100" step="1">
        <p class="description"><?php esc_html_e('Number of jobs to display per page in the job list.', 'hiretalent'); ?></p>
        <?php
    }

    /**
     * Application statuses field callback.
     */
    public function application_statuses_callback()
    {
        $default_statuses = "Pending, Reviewed, Shortlisted, Rejected, Hired";
        $value = get_option('hiretalent_application_statuses', $default_statuses);
        if (empty(trim($value)))
            $value = $default_statuses;
        ?>
        <textarea name="hiretalent_application_statuses" rows="3" cols="50"
            class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php esc_html_e('Enter statuses separated by commas or newlines (e.g., Pending, Reviewed, Shortlisted). Warning: Changing these may affect existing application filtering.', 'hiretalent'); ?>
        </p>
        <?php
    }

    /**
     * Email templates section callback.
     */
    public function email_templates_section_callback()
    {
        echo '<p>' . esc_html__('Configure email templates. Supported placeholders: {applicant_name}, {job_title}, {site_name}, {status}, {application_url}', 'hiretalent') . '</p>';
    }

    /**
     * Admin notification subject callback.
     */
    public function admin_notification_subject_callback()
    {
        $value = get_option('hiretalent_admin_notification_subject', __('[%site_name] New Job Application: {job_title}', 'hiretalent'));
        echo '<input type="text" name="hiretalent_admin_notification_subject" value="' . esc_attr($value) . '" class="large-text">';
    }

    /**
     * Admin notification message callback.
     */
    public function admin_notification_message_callback()
    {
        $value = get_option('hiretalent_admin_notification_message', __("You have received a new job application.\n\nJob: {job_title}\nApplicant: {applicant_name}\n\nView application: {application_url}", 'hiretalent'));
        echo '<textarea name="hiretalent_admin_notification_message" rows="10" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
    }

    /**
     * Applicant confirmation subject callback.
     */
    public function applicant_confirmation_subject_callback()
    {
        $value = get_option('hiretalent_applicant_confirmation_subject', __('Application Received: {job_title}', 'hiretalent'));
        echo '<input type="text" name="hiretalent_applicant_confirmation_subject" value="' . esc_attr($value) . '" class="large-text">';
    }

    /**
     * Applicant confirmation message callback.
     */
    public function applicant_confirmation_message_callback()
    {
        $value = get_option('hiretalent_applicant_confirmation_message', __("Dear {applicant_name},\n\nThank you for applying for the position of {job_title}.\n\nWe have received your application and will review it shortly.\n\nBest regards,\n{site_name}", 'hiretalent'));
        echo '<textarea name="hiretalent_applicant_confirmation_message" rows="10" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
    }

    /**
     * Status change subject callback.
     */
    public function status_change_subject_callback()
    {
        $value = get_option('hiretalent_status_change_subject', __('Application Update: {job_title}', 'hiretalent'));
        echo '<input type="text" name="hiretalent_status_change_subject" value="' . esc_attr($value) . '" class="large-text">';
    }

    /**
     * Status change message callback.
     */
    public function status_change_message_callback()
    {
        $value = get_option('hiretalent_status_change_message', __("Dear {applicant_name},\n\nYour application status for {job_title} has been updated to: {status}.\n\nBest regards,\n{site_name}", 'hiretalent'));
        echo '<textarea name="hiretalent_status_change_message" rows="10" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
    }

    /**
     * AJAX handler to clear email log.
     *
     * @since 1.0.0
     */
    public function ajax_clear_email_log()
    {
        check_ajax_referer('hiretalent_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied.', 'hiretalent'));
        }

        $log_file = wp_upload_dir()['basedir'] . '/hiretalent-logs/email.log';

        if (file_exists($log_file)) {
            file_put_contents($log_file, '');
            wp_send_json_success();
        } else {
            wp_send_json_error(__('Log file not found.', 'hiretalent'));
        }
    }
}
