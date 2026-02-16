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
    }

    /**
     * Register plugin settings.
     *
     * @since 1.0.0
     */
    public function register_settings()
    {
        // Register settings
        register_setting('hiretalent_settings', 'hiretalent_apply_form_shortcode', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ));

        register_setting('hiretalent_settings', 'hiretalent_jobs_per_page', array(
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 10,
        ));

        register_setting('hiretalent_settings', 'hiretalent_application_statuses', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => "Pending, Reviewed, Shortlisted, Rejected, Hired",
        ));

        // Register email template settings
        register_setting('hiretalent_settings', 'hiretalent_admin_notification_subject', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => __('[%site_name] New Job Application: {job_title}', 'hiretalent'),
        ));

        register_setting('hiretalent_settings', 'hiretalent_admin_notification_message', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => __("You have received a new job application.\n\nJob: {job_title}\nApplicant: {applicant_name}\n\nView application: {application_url}", 'hiretalent'),
        ));

        register_setting('hiretalent_settings', 'hiretalent_applicant_confirmation_subject', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => __('Application Received: {job_title}', 'hiretalent'),
        ));

        register_setting('hiretalent_settings', 'hiretalent_applicant_confirmation_message', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => __("Dear {applicant_name},\n\nThank you for applying for the position of {job_title}.\n\nWe have received your application and will review it shortly.\n\nBest regards,\n{site_name}", 'hiretalent'),
        ));

        register_setting('hiretalent_settings', 'hiretalent_status_change_subject', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => __('Application Update: {job_title}', 'hiretalent'),
        ));

        register_setting('hiretalent_settings', 'hiretalent_status_change_message', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => __("Dear {applicant_name},\n\nYour application status for {job_title} has been updated to: {status}.\n\nBest regards,\n{site_name}", 'hiretalent'),
        ));

        // Add settings section
        add_settings_section(
            'hiretalent_general_section',
            __('General Settings', 'hiretalent'),
            array($this, 'general_section_callback'),
            'hiretalent_settings'
        );

        add_settings_section(
            'hiretalent_email_templates_section',
            __('Email Templates', 'hiretalent'),
            array($this, 'email_templates_section_callback'),
            'hiretalent_settings'
        );

        // Add settings fields
        add_settings_field(
            'hiretalent_apply_form_shortcode',
            __('Apply Form Shortcode', 'hiretalent'),
            array($this, 'apply_form_shortcode_callback'),
            'hiretalent_settings',
            'hiretalent_general_section'
        );

        add_settings_field(
            'hiretalent_jobs_per_page',
            __('Jobs Per Page', 'hiretalent'),
            array($this, 'jobs_per_page_callback'),
            'hiretalent_settings',
            'hiretalent_general_section'
        );

        add_settings_field(
            'hiretalent_application_statuses',
            __('Application Statuses', 'hiretalent'),
            array($this, 'application_statuses_callback'),
            'hiretalent_settings',
            'hiretalent_general_section'
        );

        // Email Template Fields
        add_settings_field(
            'hiretalent_admin_notification_subject',
            __('Admin Notification Subject', 'hiretalent'),
            array($this, 'admin_notification_subject_callback'),
            'hiretalent_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_admin_notification_message',
            __('Admin Notification Message', 'hiretalent'),
            array($this, 'admin_notification_message_callback'),
            'hiretalent_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_applicant_confirmation_subject',
            __('Applicant Confirmation Subject', 'hiretalent'),
            array($this, 'applicant_confirmation_subject_callback'),
            'hiretalent_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_applicant_confirmation_message',
            __('Applicant Confirmation Message', 'hiretalent'),
            array($this, 'applicant_confirmation_message_callback'),
            'hiretalent_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_status_change_subject',
            __('Status Change Subject', 'hiretalent'),
            array($this, 'status_change_subject_callback'),
            'hiretalent_settings',
            'hiretalent_email_templates_section'
        );

        add_settings_field(
            'hiretalent_status_change_message',
            __('Status Change Message', 'hiretalent'),
            array($this, 'status_change_message_callback'),
            'hiretalent_settings',
            'hiretalent_email_templates_section'
        );
    }

    /**
     * General section callback.
     *
     * @since 1.0.0
     */
    public function general_section_callback()
    {
        echo '<p>' . esc_html__('Configure general settings for HireTalent.', 'hiretalent') . '</p>';
    }

    /**
     * Apply form shortcode field callback.
     *
     * @since 1.0.0
     */
    public function apply_form_shortcode_callback()
    {
        $value = get_option('hiretalent_apply_form_shortcode', '');
        ?>
        <input type="text" id="hiretalent_apply_form_shortcode" name="hiretalent_apply_form_shortcode"
            value="<?php echo esc_attr($value); ?>" class="regular-text"
            placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'hiretalent'); ?>">
        <p class="description">
            <?php esc_html_e('Enter the shortcode of your contact/application form plugin. This will be displayed on single job pages. Leave empty to hide the apply section.', 'hiretalent'); ?>
        </p>
        <?php
    }

    /**
     * Jobs per page field callback.
     *
     * @since 1.0.0
     */
    public function jobs_per_page_callback()
    {
        $value = get_option('hiretalent_jobs_per_page', 10);
        ?>
        <input type="number" id="hiretalent_jobs_per_page" name="hiretalent_jobs_per_page"
            value="<?php echo esc_attr($value); ?>" min="1" max="100" step="1">
        <p class="description">
            <?php esc_html_e('Number of jobs to display per page in the job list.', 'hiretalent'); ?>
        </p>
        <?php
    }

    /**
     * Application statuses field callback.
     *
     * @since 1.0.0
     */
    public function application_statuses_callback()
    {
        $default_statuses = "Pending, Reviewed, Shortlisted, Rejected, Hired";
        $value = get_option('hiretalent_application_statuses', $default_statuses);

        if (empty(trim($value))) {
            $value = $default_statuses;
        }
        ?>
        <textarea id="hiretalent_application_statuses" name="hiretalent_application_statuses" rows="3" cols="50"
            class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php esc_html_e('Enter statuses separated by commas (e.g., Pending, Reviewed, Shortlisted). These statuses will be available in the application details.', 'hiretalent'); ?>
        </p>
        <?php
    }

    /**
     * Email templates section callback.
     *
     * @since 1.0.0
     */
    public function email_templates_section_callback()
    {
        echo '<p>' . esc_html__('Configure email templates for notifications. Supported placeholders: {applicant_name}, {job_title}, {site_name}, {status}, {application_url}', 'hiretalent') . '</p>';
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
        echo '<textarea name="hiretalent_admin_notification_message" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
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
        echo '<textarea name="hiretalent_applicant_confirmation_message" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
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
        echo '<textarea name="hiretalent_status_change_message" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
    }

    /**
     * Render settings page.
     *
     * @since 1.0.0
     */
    public function render_settings_page()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Add error/update messages
        settings_errors('hiretalent_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <form action="options.php" method="post">
                <?php
                settings_fields('hiretalent_settings');
                do_settings_sections('hiretalent_settings');
                submit_button(__('Save Settings', 'hiretalent'));
                ?>
            </form>
        </div>
        <?php
    }
}
