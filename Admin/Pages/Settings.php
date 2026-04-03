<?php
/**
 * Settings.php
 *
 * Handles plugin settings page.
 *
 * @package Talentora\Admin\Pages
 * @since 1.0.0
 */

namespace Talentora\Admin\Pages;

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
        add_action('wp_ajax_talentora_clear_email_log', array($this, 'ajax_clear_email_log'));
    }

    /**
     * Register plugin settings.
     *
     * @since 1.0.0
     */
    public function register_settings()
    {
        // General Settings
        register_setting('talentora_general_settings', 'talentora_apply_form_shortcode', 'sanitize_text_field');
        register_setting('talentora_general_settings', 'talentora_jobs_per_page', 'absint');
        register_setting('talentora_general_settings', 'talentora_application_statuses', 'sanitize_textarea_field');
        register_setting('talentora_general_settings', 'talentora_currency_symbol', 'sanitize_text_field');


        add_settings_section(
            'talentora_general_section',
            __('General Configuration', 'talentora'),
            null,
            'talentora_general_settings'
        );

        add_settings_field(
            'talentora_apply_form_shortcode',
            __('Third-Party Form Shortcode', 'talentora'),
            array($this, 'apply_form_shortcode_callback'),
            'talentora_general_settings',
            'talentora_general_section'
        );

        add_settings_field(
            'talentora_jobs_per_page',
            __('Jobs Per Page', 'talentora'),
            array($this, 'jobs_per_page_callback'),
            'talentora_general_settings',
            'talentora_general_section'
        );

        add_settings_field(
            'talentora_application_statuses',
            __('Application Statuses', 'talentora'),
            array($this, 'application_statuses_callback'),
            'talentora_general_settings',
            'talentora_general_section'
        );

        add_settings_field(
            'talentora_currency_symbol',
            __('Currency Symbol', 'talentora'),
            array($this, 'currency_symbol_callback'),
            'talentora_general_settings',
            'talentora_general_section'
        );


        // Email Template Settings
        register_setting('talentora_email_settings', 'talentora_admin_notification_subject', 'sanitize_text_field');
        register_setting('talentora_email_settings', 'talentora_admin_notification_message', 'sanitize_textarea_field');
        register_setting('talentora_email_settings', 'talentora_applicant_confirmation_subject', 'sanitize_text_field');
        register_setting('talentora_email_settings', 'talentora_applicant_confirmation_message', 'sanitize_textarea_field');
        register_setting('talentora_email_settings', 'talentora_status_change_subject', 'sanitize_text_field');
        register_setting('talentora_email_settings', 'talentora_status_change_message', 'sanitize_textarea_field');

        add_settings_section(
            'talentora_email_templates_section',
            __('Email Notification Templates', 'talentora'),
            array($this, 'email_templates_section_callback'),
            'talentora_email_settings'
        );

        add_settings_field(
            'talentora_admin_notification_subject',
            __('Admin Notification Subject', 'talentora'),
            array($this, 'admin_notification_subject_callback'),
            'talentora_email_settings',
            'talentora_email_templates_section'
        );

        add_settings_field(
            'talentora_admin_notification_message',
            __('Admin Notification Message', 'talentora'),
            array($this, 'admin_notification_message_callback'),
            'talentora_email_settings',
            'talentora_email_templates_section'
        );

        add_settings_field(
            'talentora_applicant_confirmation_subject',
            __('Applicant Confirmation Subject', 'talentora'),
            array($this, 'applicant_confirmation_subject_callback'),
            'talentora_email_settings',
            'talentora_email_templates_section'
        );

        add_settings_field(
            'talentora_applicant_confirmation_message',
            __('Applicant Confirmation Message', 'talentora'),
            array($this, 'applicant_confirmation_message_callback'),
            'talentora_email_settings',
            'talentora_email_templates_section'
        );

        add_settings_field(
            'talentora_status_change_subject',
            __('Status Change Subject', 'talentora'),
            array($this, 'status_change_subject_callback'),
            'talentora_email_settings',
            'talentora_email_templates_section'
        );

        add_settings_field(
            'talentora_status_change_message',
            __('Status Change Message', 'talentora'),
            array($this, 'status_change_message_callback'),
            'talentora_email_settings',
            'talentora_email_templates_section'
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
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page tab navigation (read-only). No state change; value is sanitized.
        $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
        ?>
        <div class="wrap talentora-settings-wrapper">
            <!-- Modern Header -->
            <div class="talentora-header">
                <div class="talentora-header-content">
                    <h1 class="talentora-page-title">
                        <span class="dashicons dashicons-admin-settings"></span>
                        <?php echo esc_html(get_admin_page_title()); ?>
                    </h1>
                    <p class="talentora-subtitle">
                        <?php esc_html_e('Manage your recruitment settings and preferences', 'talentora'); ?>
                    </p>
                </div>
            </div>

            <!-- Modern Tab Navigation -->
            <nav class="nav-tab-wrapper talentora-nav-tabs">
                <a href="?post_type=talentora_job&page=talentora-settings&tab=general"
                    class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-admin-generic"></span>
                    <span><?php esc_html_e('General Settings', 'talentora'); ?></span>
                </a>
                <a href="?post_type=talentora_job&page=talentora-settings&tab=email_templates"
                    class="nav-tab <?php echo $active_tab == 'email_templates' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-email"></span>
                    <span><?php esc_html_e('Email Templates', 'talentora'); ?></span>
                </a>
                <a href="?post_type=talentora_job&page=talentora-settings&tab=email_logs"
                    class="nav-tab <?php echo $active_tab == 'email_logs' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-media-text"></span>
                    <span><?php esc_html_e('Email Logs', 'talentora'); ?></span>
                </a>
            </nav>

            <!-- Tab Content -->
            <?php if ($active_tab == 'email_logs'): ?>
                <?php $this->render_email_log_tab(); ?>
            <?php else: ?>
                <form action="options.php" method="post" class="talentora-settings-form">
                    <div class="talentora-card">
                        <div class="talentora-card-body">
                            <?php
                            if ($active_tab == 'general') {
                                settings_fields('talentora_general_settings');
                                do_settings_sections('talentora_general_settings');
                            } else if ($active_tab == 'email_templates') {
                                settings_fields('talentora_email_settings');
                                do_settings_sections('talentora_email_settings');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="talentora-submit-wrapper">
                        <?php submit_button(__('Save Changes', 'talentora'), 'primary large', 'submit', false); ?>
                    </div>
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
        $log_file = wp_upload_dir()['basedir'] . '/talentora-logs/email.log';

        echo '<div class="talentora-card">';
        echo '<div class="talentora-card-header">';
        echo '<h2><span class="dashicons dashicons-media-text"></span>' . esc_html__('Application Emails Log', 'talentora') . '</h2>';
        echo '<p class="description">' . esc_html__('View all email notifications sent by the system', 'talentora') . '</p>';
        echo '</div>';
        echo '<div class="talentora-card-body">';

        if (file_exists($log_file)) {
            $content = file_get_contents($log_file);

            echo '<div class="talentora-log-container">';
            if (empty(trim($content))) {
                echo '<div class="talentora-empty-state">';
                echo '<span class="dashicons dashicons-admin-page"></span>';
                echo '<p>' . esc_html__('No email logs yet. Logs will appear here when emails are sent.', 'talentora') . '</p>';
                echo '</div>';
            } else {
                echo '<div class="talentora-log-viewer">' . esc_html($content) . '</div>';
            }
            echo '</div>';

            echo '<div class="talentora-log-info">';
            echo '<p class="description"><span class="dashicons dashicons-info"></span> ' . esc_html__('Log file location: ', 'talentora') . esc_html($log_file) . '</p>';
            echo '</div>';

            // Clear log button
            echo '<div class="talentora-actions">';
            echo '<button type="button" id="talentora-clear-log" class="button button-secondary">';
            echo '<span class="dashicons dashicons-trash"></span> ';
            echo esc_html__('Clear Log', 'talentora');
            echo '</button>';
            echo '<p class="description warning"><span class="dashicons dashicons-warning"></span> ' . esc_html__('Warning: This action cannot be undone.', 'talentora') . '</p>';
            echo '</div>';
        } else {
            echo '<div class="talentora-empty-state">';
            echo '<span class="dashicons dashicons-info"></span>';
            echo '<p>' . esc_html__('No log file found. Logs will be created automatically when emails are sent.', 'talentora') . '</p>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    }

    /**
     * Apply form shortcode field callback.
     */
    public function apply_form_shortcode_callback()
    {
        $value = get_option('talentora_apply_form_shortcode', '');
        ?>
        <div class="talentora-field-wrapper">
            <input type="text" name="talentora_apply_form_shortcode" value="<?php echo esc_attr($value); ?>"
                class="regular-text talentora-input"
                placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'talentora'); ?>">
            <p class="description">
                <span class="dashicons dashicons-info"></span>
                <?php esc_html_e('Global fallback shortcode for all jobs. Can be overridden per job in the post editor.', 'talentora'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Jobs per page field callback.
     */
    public function jobs_per_page_callback()
    {
        $value = get_option('talentora_jobs_per_page', 10);
        ?>
        <div class="talentora-field-wrapper">
            <input type="number" name="talentora_jobs_per_page" value="<?php echo esc_attr($value); ?>"
                class="small-text talentora-input" min="1" max="100" step="1">
            <p class="description">
                <span class="dashicons dashicons-info"></span>
                <?php esc_html_e('Number of jobs to display per page in the job listings.', 'talentora'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Application statuses field callback.
     */
    public function application_statuses_callback()
    {
        $default_statuses = "Pending, Reviewed, Shortlisted, Rejected, Hired";
        $value = get_option('talentora_application_statuses', $default_statuses);
        if (empty(trim($value)))
            $value = $default_statuses;
        ?>
        <div class="talentora-field-wrapper">
            <textarea name="talentora_application_statuses" rows="3" cols="50"
                class="large-text talentora-textarea"><?php echo esc_textarea($value); ?></textarea>
            <p class="description">
                <span class="dashicons dashicons-info"></span>
                <?php esc_html_e('Comma-separated list of application statuses (e.g., Pending, Reviewed, Shortlisted).', 'talentora'); ?>
            </p>
            <p class="description warning">
                <span class="dashicons dashicons-warning"></span>
                <?php esc_html_e('Changing these may affect existing application filtering.', 'talentora'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Currency symbol field callback.
     */
    public function currency_symbol_callback()
    {
        $value = get_option('talentora_currency_symbol', '$');
        ?>
                <div class="talentora-field-wrapper">
                    <input type="text" name="talentora_currency_symbol" value="<?php echo esc_attr($value); ?>" 
                        class="small-text talentora-input" placeholder="$">
                    <p class="description">
                        <span class="dashicons dashicons-info"></span>
                        <?php esc_html_e('The currency symbol to display before salary values (e.g., $, €, £).', 'talentora'); ?>
                    </p>
                </div>
                <?php
    }


    /**
     * Email templates section callback.
     */
    public function email_templates_section_callback()
    {
        ?>
        <div class="talentora-section-intro">
            <p class="description">
                <span class="dashicons dashicons-email"></span>
                <?php esc_html_e('Customize email templates sent to applicants and administrators.', 'talentora'); ?>
            </p>
            <div class="talentora-info-box">
                <h4><?php esc_html_e('Available Placeholders:', 'talentora'); ?></h4>
                <ul class="talentora-placeholder-list">
                    <li><code>{applicant_name}</code> - <?php esc_html_e('Name of the applicant', 'talentora'); ?></li>
                    <li><code>{job_title}</code> - <?php esc_html_e('Job position title', 'talentora'); ?></li>
                    <li><code>{site_name}</code> - <?php esc_html_e('Your website name', 'talentora'); ?></li>
                    <li><code>{status}</code> - <?php esc_html_e('Application status', 'talentora'); ?></li>
                    <li><code>{application_url}</code> - <?php esc_html_e('Link to view application', 'talentora'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Admin notification subject callback.
     */
    public function admin_notification_subject_callback()
    {
        $value = get_option(
			'talentora_admin_notification_subject',
			/* translators: {site_name}: Site name, {job_title}: Job title. */
			__('[%site_name] New Job Application: {job_title}', 'talentora')
		);
        ?>
        <div class="talentora-field-wrapper">
            <input type="text" name="talentora_admin_notification_subject" value="<?php echo esc_attr($value); ?>"
                class="large-text talentora-input">
        </div>
        <?php
    }

    /**
     * Admin notification message callback.
     */
    public function admin_notification_message_callback()
    {
        $value = get_option('talentora_admin_notification_message', __("You have received a new job application.\n\nJob: {job_title}\nApplicant: {applicant_name}\n\nView application: {application_url}", 'talentora'));
        ?>
        <div class="talentora-field-wrapper">
            <textarea name="talentora_admin_notification_message" rows="10" cols="50"
                class="large-text talentora-textarea code-editor"><?php echo esc_textarea($value); ?></textarea>
        </div>
        <?php
    }

    /**
     * Applicant confirmation subject callback.
     */
    public function applicant_confirmation_subject_callback()
    {
        $value = get_option('talentora_applicant_confirmation_subject', __('Application Received: {job_title}', 'talentora'));
        ?>
        <div class="talentora-field-wrapper">
            <input type="text" name="talentora_applicant_confirmation_subject" value="<?php echo esc_attr($value); ?>"
                class="large-text talentora-input">
        </div>
        <?php
    }

    /**
     * Applicant confirmation message callback.
     */
    public function applicant_confirmation_message_callback()
    {
        $value = get_option('talentora_applicant_confirmation_message', __("Dear {applicant_name},\n\nThank you for applying for the position of {job_title}.\n\nWe have received your application and will review it shortly.\n\nBest regards,\n{site_name}", 'talentora'));
        ?>
        <div class="talentora-field-wrapper">
            <textarea name="talentora_applicant_confirmation_message" rows="10" cols="50"
                class="large-text talentora-textarea code-editor"><?php echo esc_textarea($value); ?></textarea>
        </div>
        <?php
    }

    /**
     * Status change subject callback.
     */
    public function status_change_subject_callback()
    {
        $value = get_option('talentora_status_change_subject', __('Application Update: {job_title}', 'talentora'));
        ?>
        <div class="talentora-field-wrapper">
            <input type="text" name="talentora_status_change_subject" value="<?php echo esc_attr($value); ?>"
                class="large-text talentora-input">
        </div>
        <?php
    }

    /**
     * Status change message callback.
     */
    public function status_change_message_callback()
    {
        $value = get_option('talentora_status_change_message', __("Dear {applicant_name},\n\nYour application status for {job_title} has been updated to: {status}.\n\nBest regards,\n{site_name}", 'talentora'));
        ?>
        <div class="talentora-field-wrapper">
            <textarea name="talentora_status_change_message" rows="10" cols="50"
                class="large-text talentora-textarea code-editor"><?php echo esc_textarea($value); ?></textarea>
        </div>
        <?php
    }

    /**
     * AJAX handler to clear email log.
     *
     * @since 1.0.0
     */
    public function ajax_clear_email_log()
    {
        check_ajax_referer('talentora_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied.', 'talentora'));
        }

        $log_file = wp_upload_dir()['basedir'] . '/talentora-logs/email.log';

        if (file_exists($log_file)) {
            file_put_contents($log_file, '');
            wp_send_json_success();
        } else {
            wp_send_json_error(__('Log file not found.', 'talentora'));
        }
    }
}
