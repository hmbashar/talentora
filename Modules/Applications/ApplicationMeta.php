<?php
/**
 * ApplicationMeta.php
 *
 * Handles application meta fields and admin display.
 *
 * @package HireTalent\Modules\Applications
 * @since 1.0.0
 */

namespace HireTalent\Modules\Applications;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Application Meta class.
 */
class ApplicationMeta
{
    /**
     * Notification Manager instance.
     *
     * @var \HireTalent\NotificationManager
     */
    private $notification_manager;

    /**
     * Activity Logger instance.
     *
     * @var \HireTalent\Inc\ActivityLogger
     */
    private $activity_logger;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->notification_manager = new \HireTalent\NotificationManager();
        $this->activity_logger = new \HireTalent\ActivityLogger();

        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_application_meta'));
        add_filter('manage_hiretalent_app_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_hiretalent_app_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
        add_filter('bulk_actions-edit-hiretalent_app', array($this, 'register_bulk_actions'));
        add_filter('handle_bulk_actions-edit-hiretalent_app', array($this, 'handle_bulk_actions'), 10, 3);
        add_action('admin_post_hiretalent_download_resume', array($this, 'handle_resume_download'));
        add_action('manage_posts_extra_tablenav', array($this, 'render_export_button'));
        add_action('admin_post_hiretalent_export_applications', array($this, 'handle_csv_export'));
    }

    /**
     * Get application statuses.
     *
     * @return array
     * @since 1.0.0
     */
    private function get_statuses()
    {
        $default_statuses = "Pending, Reviewed, Shortlisted, Rejected, Hired";
        $statuses_option = get_option('hiretalent_application_statuses', $default_statuses);

        if (empty(trim($statuses_option))) {
            $statuses_option = $default_statuses;
        }

        $statuses = preg_split('/[\r\n,]+/', $statuses_option);
        $statuses = array_map('trim', $statuses);
        return array_filter($statuses);
    }

    /**
     * Add meta boxes.
     *
     * @since 1.0.0
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            'hiretalent_app_details',
            __('Application Details', 'hiretalent'),
            array($this, 'render_details_metabox'),
            'hiretalent_app',
            'normal',
            'high'
        );

        add_meta_box(
            'hiretalent_activity_log',
            __('Activity Log', 'hiretalent'),
            array($this, 'render_activity_log_metabox'),
            'hiretalent_app',
            'normal',
            'low'
        );
    }

    /**
     * Render application details metabox.
     *
     * @param \WP_Post $post The post object.
     * @since 1.0.0
     */
    public function render_details_metabox($post)
    {
        $job_id = get_post_meta($post->ID, 'hiretalent_job_id', true);
        $name = get_post_meta($post->ID, 'hiretalent_applicant_name', true);
        $email = get_post_meta($post->ID, 'hiretalent_applicant_email', true);
        $phone = get_post_meta($post->ID, 'hiretalent_applicant_phone', true);
        $cover_letter = get_post_meta($post->ID, 'hiretalent_cover_letter', true);
        $resume_id = get_post_meta($post->ID, 'hiretalent_resume_id', true);

        // Get statuses
        $statuses = $this->get_statuses();

        // Get current status
        $current_status = get_post_meta($post->ID, 'hiretalent_application_status', true);
        if (!$current_status) {
            $current_status = 'Pending';
        }

        ?>


        <div class="hiretalent-application-details">
            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Applied For:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php if ($job_id): ?>
                        <a href="<?php echo esc_url(get_edit_post_link($job_id)); ?>">
                            <?php echo esc_html(get_the_title($job_id)); ?>
                        </a>
                    <?php else: ?>
                        <?php esc_html_e('N/A', 'hiretalent'); ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Name:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php echo esc_html($name); ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Email:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <a href="mailto:<?php echo esc_attr($email); ?>">
                        <?php echo esc_html($email); ?>
                    </a>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Phone:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php echo esc_html($phone); ?>
                </span>
            </div>

            <?php if ($resume_id): ?>
                <div class="detail-row">
                    <span class="detail-label">
                        <?php esc_html_e('Resume:', 'hiretalent'); ?>
                    </span>
                    <span class="detail-value">
                        <span class="detail-value">
                            <a href="<?php echo esc_url(add_query_arg(array('action' => 'hiretalent_download_resume', 'id' => $post->ID, 'nonce' => wp_create_nonce('hiretalent_download_resume_' . $post->ID)), admin_url('admin-post.php'))); ?>"
                                class="button">
                                <?php esc_html_e('Download Resume', 'hiretalent'); ?>
                            </a>
                        </span>
                </div>
            <?php endif; ?>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Cover Letter:', 'hiretalent'); ?>
                </span>
                <div class="cover-letter-box">
                    <?php echo esc_html($cover_letter); ?>
                </div>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Submitted:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php echo esc_html(get_the_date('', $post) . ' / ' . get_the_time('', $post)); ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Status:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php wp_nonce_field('hiretalent_save_application_status', 'hiretalent_application_status_nonce'); ?>
                    <select name="hiretalent_application_status">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo esc_attr($status); ?>" <?php selected($current_status, $status); ?>>
                                <?php echo esc_html($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </span>
            </div>
        </div>
        <?php
    }

    /**
     * Set custom columns for applications list.
     *
     * @param array $columns Existing columns.
     * @return array
     * @since 1.0.0
     */
    public function set_custom_columns($columns)
    {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = __('Applicant', 'hiretalent');
        $new_columns['job'] = __('Job', 'hiretalent');
        $new_columns['email'] = __('Email', 'hiretalent');
        $new_columns['phone'] = __('Phone', 'hiretalent');
        $new_columns['status'] = __('Status', 'hiretalent');
        $new_columns['date'] = __('Date', 'hiretalent');

        return $new_columns;
    }

    /**
     * Display custom column content.
     *
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     * @since 1.0.0
     */
    public function custom_column_content($column, $post_id)
    {
        switch ($column) {
            case 'job':
                $job_id = get_post_meta($post_id, 'hiretalent_job_id', true);
                if ($job_id) {
                    echo '<a href="' . esc_url(get_edit_post_link($job_id)) . '">' . esc_html(get_the_title($job_id)) . '</a>';
                } else {
                    esc_html_e('N/A', 'hiretalent');
                }
                break;

            case 'email':
                $email = get_post_meta($post_id, 'hiretalent_applicant_email', true);
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                break;

            case 'phone':
                $phone = get_post_meta($post_id, 'hiretalent_applicant_phone', true);
                echo esc_html($phone);
                break;

            case 'status':
                $status = get_post_meta($post_id, 'hiretalent_application_status', true);
                echo esc_html($status ? $status : __('Pending', 'hiretalent'));
                break;
        }
    }

    /**
     * Register bulk actions.
     *
     * @param array $bulk_actions Existing bulk actions.
     * @return array
     * @since 1.0.0
     */
    public function register_bulk_actions($bulk_actions)
    {
        $statuses = $this->get_statuses();

        foreach ($statuses as $status) {
            $bulk_actions['mark_status_' . sanitize_key($status)] = sprintf(__('Change status to %s', 'hiretalent'), $status);
        }

        return $bulk_actions;
    }

    /**
     * Handle bulk actions.
     *
     * @param string $redirect_to Redirect URL.
     * @param string $doaction    Action name.
     * @param array  $post_ids    Post IDs.
     * @return string
     * @since 1.0.0
     */
    public function handle_bulk_actions($redirect_to, $doaction, $post_ids)
    {
        if (strpos($doaction, 'mark_status_') !== 0) {
            return $redirect_to;
        }

        $new_status_key = substr($doaction, 12); // Remove 'mark_status_'

        // Find the original status name from options to ensure correct casing/spacing if possible, 
        // essentially reverse mapping the sanitized key to the real status.
        // However, since we don't have a map, we might need to rely on the fact that we might not match casing exactly
        // OR we iterate the statuses to find the matching key.

        $statuses = $this->get_statuses();
        $new_status = '';

        foreach ($statuses as $status) {
            if (sanitize_key($status) === $new_status_key) {
                $new_status = $status;
                break;
            }
        }

        if (!$new_status) {
            return $redirect_to;
        }

        foreach ($post_ids as $post_id) {
            $old_status = get_post_meta($post_id, 'hiretalent_application_status', true);
            if ($old_status !== $new_status) {
                update_post_meta($post_id, 'hiretalent_application_status', $new_status);
                $this->notification_manager->send_status_change_notification($post_id, $old_status, $new_status);

                // Log activity
                $this->activity_logger->log(
                    $post_id,
                    sprintf(__('Status changed from %s to %s via bulk action', 'hiretalent'), $old_status, $new_status),
                    'info'
                );
            }
        }

        $redirect_to = add_query_arg('bulk_processed', count($post_ids), $redirect_to);
        return $redirect_to;
    }

    /**
     * Save application meta data.
     *
     * @param int $post_id Post ID.
     * @since 1.0.0
     */
    public function save_application_meta($post_id)
    {
        // Check nonce
        if (!isset($_POST['hiretalent_application_status_nonce']) || !wp_verify_nonce($_POST['hiretalent_application_status_nonce'], 'hiretalent_save_application_status')) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save status
        if (isset($_POST['hiretalent_application_status'])) {
            $new_status = sanitize_text_field($_POST['hiretalent_application_status']);
            $old_status = get_post_meta($post_id, 'hiretalent_application_status', true);

            if ($old_status !== $new_status) {
                $this->notification_manager->send_status_change_notification($post_id, $old_status, $new_status);

                // Log activity
                $this->activity_logger->log(
                    $post_id,
                    sprintf(__('Status changed from %s to %s', 'hiretalent'), $old_status, $new_status),
                    'info'
                );
            }
        }
    }

    /**
     * Render activity log metabox.
     *
     * @param \WP_Post $post The post object.
     * @since 1.0.0
     */
    public function render_activity_log_metabox($post)
    {
        $logs = $this->activity_logger->get_logs($post->ID);

        if (empty($logs)) {
            echo '<p>' . esc_html__('No activity recorded yet.', 'hiretalent') . '</p>';
            return;
        }

        echo '<ul class="hiretalent-activity-log">';
        foreach ($logs as $log) {
            $message = $log['message'];
            $timestamp = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($log['timestamp']));
            $user = get_userdata($log['user_id']);
            $user_name = $user ? $user->display_name : __('System', 'hiretalent');

            echo '<li>';
            echo '<strong>' . esc_html($timestamp) . '</strong> - ';
            echo esc_html($message);
            echo ' <span class="description">(' . esc_html($user_name) . ')</span>';
            echo '</li>';
        }
        echo '</ul>';

    }
    /**
     * Handle secure resume download.
     *
     * @since 1.0.0
     */
    public function handle_resume_download()
    {
        if (!isset($_GET['nonce']) || !isset($_GET['id'])) {
            wp_die(__('Invalid request.', 'hiretalent'));
        }

        $post_id = absint($_GET['id']);

        if (!wp_verify_nonce($_GET['nonce'], 'hiretalent_download_resume_' . $post_id)) {
            wp_die(__('Security check failed.', 'hiretalent'));
        }

        if (!current_user_can('edit_post', $post_id)) {
            wp_die(__('You do not have permission to access this file.', 'hiretalent'));
        }

        $resume_id = get_post_meta($post_id, 'hiretalent_resume_id', true);

        if (!$resume_id) {
            wp_die(__('Resume not found.', 'hiretalent'));
        }

        $file_path = get_attached_file($resume_id);

        if (!file_exists($file_path)) {
            wp_die(__('File not found.', 'hiretalent'));
        }

        // Serve file
        $mime_type = get_post_mime_type($resume_id);
        $filename = basename($file_path);

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }

    /**
     * Render export button.
     *
     * @param string $which Table navigation location (top or bottom).
     * @since 1.0.0
     */
    public function render_export_button($which)
    {
        global $typenow;

        if ('hiretalent_app' !== $typenow || 'top' !== $which) {
            return;
        }

        ?>
        <div class="alignleft actions">
            <a href="<?php echo esc_url(add_query_arg(array('action' => 'hiretalent_export_applications', 'nonce' => wp_create_nonce('hiretalent_export_applications')), admin_url('admin-post.php'))); ?>"
                class="button button-primary">
                <?php esc_html_e('Export CSV', 'hiretalent'); ?>
            </a>
        </div>
        <?php
    }

    /**
     * Handle CSV export.
     *
     * @since 1.0.0
     */
    public function handle_csv_export()
    {
        if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'hiretalent_export_applications')) {
            wp_die(__('Security check failed.', 'hiretalent'));
        }

        if (!current_user_can('edit_others_posts')) {
            wp_die(__('You do not have permission to export applications.', 'hiretalent'));
        }

        $args = array(
            'post_type' => 'hiretalent_app',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            wp_die(__('No applications found.', 'hiretalent'));
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="applications-' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, array(
            __('ID', 'hiretalent'),
            __('Applicant Name', 'hiretalent'),
            __('Email', 'hiretalent'),
            __('Phone', 'hiretalent'),
            __('Job Title', 'hiretalent'),
            __('Status', 'hiretalent'),
            __('Date Submitted', 'hiretalent'),
        ));

        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $job_id = get_post_meta($post_id, 'hiretalent_job_id', true);

            fputcsv($output, array(
                $post_id,
                get_post_meta($post_id, 'hiretalent_applicant_name', true),
                get_post_meta($post_id, 'hiretalent_applicant_email', true),
                get_post_meta($post_id, 'hiretalent_applicant_phone', true),
                $job_id ? get_the_title($job_id) : __('N/A', 'hiretalent'),
                get_post_meta($post_id, 'hiretalent_application_status', true) ?: 'Pending',
                get_the_date('Y-m-d H:i:s'),
            ));
        }

        fclose($output);
        exit;
    }
}
