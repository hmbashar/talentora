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
        register_setting('hiretalent_general_settings', 'hiretalent_currency_symbol', 'sanitize_text_field');


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

        add_settings_field(
            'hiretalent_currency_symbol',
            __('Currency Symbol', 'hiretalent'),
            array($this, 'currency_symbol_callback'),
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
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page tab navigation (read-only). No state change; value is sanitized.
        $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
        ?>
        <div class="wrap hiretalent-settings-wrapper">
            <!-- Modern Header -->
            <div class="hiretalent-header">
                <div class="hiretalent-header-content">
                    <h1 class="hiretalent-page-title">
                        <span class="dashicons dashicons-admin-settings"></span>
                        <?php echo esc_html(get_admin_page_title()); ?>
                    </h1>
                    <p class="hiretalent-subtitle">
                        <?php esc_html_e('Manage your recruitment settings and preferences', 'hiretalent'); ?>
                    </p>
                </div>
            </div>

            <!-- Modern Tab Navigation -->
            <nav class="nav-tab-wrapper hiretalent-nav-tabs">
                <a href="?post_type=hiretalent_job&page=hiretalent-settings&tab=general"
                    class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-admin-generic"></span>
                    <span><?php esc_html_e('General Settings', 'hiretalent'); ?></span>
                </a>
                <a href="?post_type=hiretalent_job&page=hiretalent-settings&tab=email_templates"
                    class="nav-tab <?php echo $active_tab == 'email_templates' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-email"></span>
                    <span><?php esc_html_e('Email Templates', 'hiretalent'); ?></span>
                </a>
                <a href="?post_type=hiretalent_job&page=hiretalent-settings&tab=email_logs"
                    class="nav-tab <?php echo $active_tab == 'email_logs' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-media-text"></span>
                    <span><?php esc_html_e('Email Logs', 'hiretalent'); ?></span>
                </a>
            </nav>

            <!-- Tab Content -->
            <?php if ($active_tab == 'email_logs'): ?>
                <?php $this->render_email_log_tab(); ?>
            <?php else: ?>
                <form action="options.php" method="post" class="hiretalent-settings-form">
                    <div class="hiretalent-card">
                        <div class="hiretalent-card-body">
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
                    </div>
                    <div class="hiretalent-submit-wrapper">
                        <?php submit_button(__('Save Changes', 'hiretalent'), 'primary large', 'submit', false); ?>
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
        $log_file = wp_upload_dir()['basedir'] . '/hiretalent-logs/email.log';

        echo '<div class="hiretalent-card">';
        echo '<div class="hiretalent-card-header">';
        echo '<h2><span class="dashicons dashicons-media-text"></span>' . esc_html__('Application Emails Log', 'hiretalent') . '</h2>';
        echo '<p class="description">' . esc_html__('View all email notifications sent by the system', 'hiretalent') . '</p>';
        echo '</div>';
        echo '<div class="hiretalent-card-body">';

        if (file_exists($log_file)) {
            $content = file_get_contents($log_file);

            echo '<div class="hiretalent-log-container">';
            if (empty(trim($content))) {
                echo '<div class="hiretalent-empty-state">';
                echo '<span class="dashicons dashicons-admin-page"></span>';
                echo '<p>' . esc_html__('No email logs yet. Logs will appear here when emails are sent.', 'hiretalent') . '</p>';
                echo '</div>';
            } else {
                echo '<div class="hiretalent-log-viewer">' . esc_html($content) . '</div>';
            }
            echo '</div>';

            echo '<div class="hiretalent-log-info">';
            echo '<p class="description"><span class="dashicons dashicons-info"></span> ' . esc_html__('Log file location: ', 'hiretalent') . esc_html($log_file) . '</p>';
            echo '</div>';

            // Clear log button
            echo '<div class="hiretalent-actions">';
            echo '<button type="button" id="hiretalent-clear-log" class="button button-secondary">';
            echo '<span class="dashicons dashicons-trash"></span> ';
            echo esc_html__('Clear Log', 'hiretalent');
            echo '</button>';
            echo '<p class="description warning"><span class="dashicons dashicons-warning"></span> ' . esc_html__('Warning: This action cannot be undone.', 'hiretalent') . '</p>';
            echo '</div>';
        } else {
            echo '<div class="hiretalent-empty-state">';
            echo '<span class="dashicons dashicons-info"></span>';
            echo '<p>' . esc_html__('No log file found. Logs will be created automatically when emails are sent.', 'hiretalent') . '</p>';
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
        $value = get_option('hiretalent_apply_form_shortcode', '');
        ?>
        <div class="hiretalent-field-wrapper">
            <input type="text" name="hiretalent_apply_form_shortcode" value="<?php echo esc_attr($value); ?>"
                class="regular-text hiretalent-input"
                placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'hiretalent'); ?>">
            <p class="description">
                <span class="dashicons dashicons-info"></span>
                <?php esc_html_e('Global fallback shortcode for all jobs. Can be overridden per job in the post editor.', 'hiretalent'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Jobs per page field callback.
     */
    public function jobs_per_page_callback()
    {
        $value = get_option('hiretalent_jobs_per_page', 10);
        ?>
        <div class="hiretalent-field-wrapper">
            <input type="number" name="hiretalent_jobs_per_page" value="<?php echo esc_attr($value); ?>"
                class="small-text hiretalent-input" min="1" max="100" step="1">
            <p class="description">
                <span class="dashicons dashicons-info"></span>
                <?php esc_html_e('Number of jobs to display per page in the job listings.', 'hiretalent'); ?>
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
        $value = get_option('hiretalent_application_statuses', $default_statuses);
        if (empty(trim($value)))
            $value = $default_statuses;
        ?>
        <div class="hiretalent-field-wrapper">
            <textarea name="hiretalent_application_statuses" rows="3" cols="50"
                class="large-text hiretalent-textarea"><?php echo esc_textarea($value); ?></textarea>
            <p class="description">
                <span class="dashicons dashicons-info"></span>
                <?php esc_html_e('Comma-separated list of application statuses (e.g., Pending, Reviewed, Shortlisted).', 'hiretalent'); ?>
            </p>
            <p class="description warning">
                <span class="dashicons dashicons-warning"></span>
                <?php esc_html_e('Changing these may affect existing application filtering.', 'hiretalent'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Currency symbol field callback.
     */
    public function currency_symbol_callback()
    {
        $value = get_option('hiretalent_currency_symbol', '$');
        ?>
                <div class="hiretalent-field-wrapper">
                    <input type="text" name="hiretalent_currency_symbol" value="<?php echo esc_attr($value); ?>" 
                        class="small-text hiretalent-input" placeholder="$">
                    <p class="description">
                        <span class="dashicons dashicons-info"></span>
                        <?php esc_html_e('The currency symbol to display before salary values (e.g., $, €, £).', 'hiretalent'); ?>
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
        <div class="hiretalent-section-intro">
            <p class="description">
                <span class="dashicons dashicons-email"></span>
                <?php esc_html_e('Customize email templates sent to applicants and administrators.', 'hiretalent'); ?>
            </p>
            <div class="hiretalent-info-box">
                <h4><?php esc_html_e('Available Placeholders:', 'hiretalent'); ?></h4>
                <ul class="hiretalent-placeholder-list">
                    <li><code>{applicant_name}</code> - <?php esc_html_e('Name of the applicant', 'hiretalent'); ?></li>
                    <li><code>{job_title}</code> - <?php esc_html_e('Job position title', 'hiretalent'); ?></li>
                    <li><code>{site_name}</code> - <?php esc_html_e('Your website name', 'hiretalent'); ?></li>
                    <li><code>{status}</code> - <?php esc_html_e('Application status', 'hiretalent'); ?></li>
                    <li><code>{application_url}</code> - <?php esc_html_e('Link to view application', 'hiretalent'); ?></li>
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
			'hiretalent_admin_notification_subject',
			/* translators: {site_name}: Site name, {job_title}: Job title. */
			__('[%site_name] New Job Application: {job_title}', 'hiretalent')
		);
        ?>
        <div class="hiretalent-field-wrapper">
            <input type="text" name="hiretalent_admin_notification_subject" value="<?php echo esc_attr($value); ?>"
                class="large-text hiretalent-input">
        </div>
        <?php
    }

    /**
     * Admin notification message callback.
     */
    public function admin_notification_message_callback()
    {
        $value = get_option('hiretalent_admin_notification_message', __("You have received a new job application.\n\nJob: {job_title}\nApplicant: {applicant_name}\n\nView application: {application_url}", 'hiretalent'));
        ?>
        <div class="hiretalent-field-wrapper">
            <textarea name="hiretalent_admin_notification_message" rows="10" cols="50"
                class="large-text hiretalent-textarea code-editor"><?php echo esc_textarea($value); ?></textarea>
        </div>
        <?php
    }

    /**
     * Applicant confirmation subject callback.
     */
    public function applicant_confirmation_subject_callback()
    {
        $value = get_option('hiretalent_applicant_confirmation_subject', __('Application Received: {job_title}', 'hiretalent'));
        ?>
        <div class="hiretalent-field-wrapper">
            <input type="text" name="hiretalent_applicant_confirmation_subject" value="<?php echo esc_attr($value); ?>"
                class="large-text hiretalent-input">
        </div>
        <?php
    }

    /**
     * Applicant confirmation message callback.
     */
    public function applicant_confirmation_message_callback()
    {
        $value = get_option('hiretalent_applicant_confirmation_message', __("Dear {applicant_name},\n\nThank you for applying for the position of {job_title}.\n\nWe have received your application and will review it shortly.\n\nBest regards,\n{site_name}", 'hiretalent'));
        ?>
        <div class="hiretalent-field-wrapper">
            <textarea name="hiretalent_applicant_confirmation_message" rows="10" cols="50"
                class="large-text hiretalent-textarea code-editor"><?php echo esc_textarea($value); ?></textarea>
        </div>
        <?php
    }

    /**
     * Status change subject callback.
     */
    public function status_change_subject_callback()
    {
        $value = get_option('hiretalent_status_change_subject', __('Application Update: {job_title}', 'hiretalent'));
        ?>
        <div class="hiretalent-field-wrapper">
            <input type="text" name="hiretalent_status_change_subject" value="<?php echo esc_attr($value); ?>"
                class="large-text hiretalent-input">
        </div>
        <?php
    }

    /**
     * Status change message callback.
     */
    public function status_change_message_callback()
    {
        $value = get_option('hiretalent_status_change_message', __("Dear {applicant_name},\n\nYour application status for {job_title} has been updated to: {status}.\n\nBest regards,\n{site_name}", 'hiretalent'));
        ?>
        <div class="hiretalent-field-wrapper">
            <textarea name="hiretalent_status_change_message" rows="10" cols="50"
                class="large-text hiretalent-textarea code-editor"><?php echo esc_textarea($value); ?></textarea>
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
