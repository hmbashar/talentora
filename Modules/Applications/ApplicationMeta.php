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

            <!-- Applicant Information Section -->
            <div class="hiretalent-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-id"></span>
                        <?php esc_html_e('Applicant Information', 'hiretalent'); ?></h3>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-admin-users"></span>
                            <?php esc_html_e('Full Name', 'hiretalent'); ?>
                        </span>
                        <span class="detail-value">
                            <?php echo esc_html($name); ?>
                        </span>
                    </div>

                    <div class="field-group half-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-email"></span>
                            <?php esc_html_e('Email Address', 'hiretalent'); ?>
                        </span>
                        <span class="detail-value">
                            <a href="mailto:<?php echo esc_attr($email); ?>">
                                <?php echo esc_html($email); ?>
                            </a>
                        </span>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-phone"></span>
                            <?php esc_html_e('Phone Number', 'hiretalent'); ?>
                        </span>
                        <span class="detail-value">
                            <?php echo esc_html($phone); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Application Information Section -->
            <div class="hiretalent-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-portfolio"></span>
                        <?php esc_html_e('Application Details', 'hiretalent'); ?></h3>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-awards"></span>
                            <?php esc_html_e('Applied For', 'hiretalent'); ?>
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

                    <div class="field-group half-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Submission Date', 'hiretalent'); ?>
                        </span>
                        <span class="detail-value">
                            <?php echo esc_html(get_the_date('', $post) . ' @ ' . get_the_time('', $post)); ?>
                        </span>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-media-document"></span>
                            <?php esc_html_e('Resume / CV', 'hiretalent'); ?>
                        </span>
                        <span class="detail-value">
                            <?php if ($resume_id): ?>
                                <a href="<?php echo esc_url(add_query_arg(array('action' => 'hiretalent_download_resume', 'id' => $post->ID, 'nonce' => wp_create_nonce('hiretalent_download_resume_' . $post->ID)), admin_url('admin-post.php'))); ?>"
                                    class="button button-primary">
                                    <span class="dashicons dashicons-download"></span>
                                    <?php esc_html_e('Download Resume', 'hiretalent'); ?>
                                </a>
                            <?php else: ?>
                                <span class="description"><?php esc_html_e('No resume uploaded', 'hiretalent'); ?></span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="field-group half-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-flag"></span>
                            <?php esc_html_e('Current Status', 'hiretalent'); ?>
                        </span>
                        <span class="detail-value">
                            <?php wp_nonce_field('hiretalent_save_application_status', 'hiretalent_application_status_nonce'); ?>
                            <select name="hiretalent_application_status" class="hiretalent-select-status">
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo esc_attr($status); ?>" <?php selected($current_status, $status); ?>>
                                        <?php echo esc_html($status); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group full-width">
                        <span class="detail-label">
                            <span class="dashicons dashicons-editor-quote"></span>
                            <?php esc_html_e('Cover Letter', 'hiretalent'); ?>
                        </span>
                        <div class="cover-letter-box">
                            <?php echo esc_html($cover_letter); ?>
                        </div>
                    </div>
                </div>
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
            $bulk_actions['mark_status_' . sanitize_key($status)] = sprintf(
                /* translators: %s: Application status label. */
                esc_html__('Change status to %1$s', 'hiretalent'),
                $status
            );
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
                    sprintf(
                        /* translators: 1: Old application status, 2: New application status. */
                        esc_html__('Status changed from %1$s to %2$s via bulk action', 'hiretalent'),
                        $old_status,
                        $new_status
                    ),
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
                    sprintf(
                        /* translators: 1: Old application status, 2: New application status. */
                        esc_html__('Status changed from %1$s to %2$s', 'hiretalent'),
                        $old_status,
                        $new_status
                    ),
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
            $timestamp = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($log['timestamp']));
            $user = get_userdata($log['user_id']);
            $user_name = $user ? $user->display_name : __('System', 'hiretalent');

            echo '<li>';
            echo '<div class="activity-meta">';
            echo '<span class="activity-time">' . esc_html($timestamp) . '</span>';
            echo '<span class="activity-user">' . esc_html($user_name) . '</span>';
            echo '</div>';
            echo '<div class="activity-message">' . esc_html($log['message']) . '</div>';
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
        if (!isset($_GET['nonce'], $_GET['id'])) {
            wp_die(esc_html__('Invalid request.', 'hiretalent'));
        }

        $post_id = absint(wp_unslash($_GET['id']));

        $nonce = sanitize_text_field(wp_unslash($_GET['nonce']));

        if (!wp_verify_nonce($nonce, 'hiretalent_download_resume_' . $post_id)) {
            wp_die(esc_html__('Security check failed.', 'hiretalent'));
        }

        if (!current_user_can('edit_post', $post_id)) {
            wp_die(esc_html__('You do not have permission to access this file.', 'hiretalent'));
        }

        $resume_id = absint(get_post_meta($post_id, 'hiretalent_resume_id', true));

        if (!$resume_id) {
            wp_die(esc_html__('Resume not found.', 'hiretalent'));
        }

        $file_path = get_attached_file($resume_id);

        if (empty($file_path) || !file_exists($file_path)) {
            wp_die(esc_html__('File not found.', 'hiretalent'));
        }

        // Serve file using WP_Filesystem (avoids direct PHP filesystem calls).
        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        $mime_type = get_post_mime_type($resume_id);
        $filename = basename($file_path);
        $file_content = $wp_filesystem->get_contents($file_path);

        if (false === $file_content) {
            wp_die(esc_html__('Could not read the file.', 'hiretalent'));
        }

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($file_content));
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- binary file content must not be escaped.
        echo $file_content;
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
	public function handle_csv_export() {
		if (
			! isset( $_GET['nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_GET['nonce'] ) ),
				'hiretalent_export_applications'
			)
		) {
			wp_die( esc_html__( 'Security check failed.', 'hiretalent' ) );
		}

		if ( ! current_user_can( 'edit_others_posts' ) ) {
			wp_die( esc_html__( 'You do not have permission to export applications.', 'hiretalent' ) );
		}

		$query = new \WP_Query(
			array(
				'post_type'      => 'hiretalent_app',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		if ( ! $query->have_posts() ) {
			wp_die( esc_html__( 'No applications found.', 'hiretalent' ) );
		}

		$filename = sprintf( 'applications-%s.csv', current_time( 'Y-m-d' ) );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ) );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen -- Writing to php://output is not file system I/O.
		$output = fopen( 'php://output', 'w' );

		// Header row (CSV values are data, so use esc_html__).
		fputcsv(
			$output,
			array(
				esc_html__( 'ID', 'hiretalent' ),
				esc_html__( 'Applicant Name', 'hiretalent' ),
				esc_html__( 'Email', 'hiretalent' ),
				esc_html__( 'Phone', 'hiretalent' ),
				esc_html__( 'Job Title', 'hiretalent' ),
				esc_html__( 'Status', 'hiretalent' ),
				esc_html__( 'Date Submitted', 'hiretalent' ),
			)
		);

		while ( $query->have_posts() ) {
			$query->the_post();

			$post_id = get_the_ID();
			$job_id  = absint( get_post_meta( $post_id, 'hiretalent_job_id', true ) );

			$job_title = $job_id ? get_the_title( $job_id ) : esc_html__( 'N/A', 'hiretalent' );

			$status = get_post_meta( $post_id, 'hiretalent_application_status', true );
			if ( empty( $status ) ) {
				$status = esc_html__( 'Pending', 'hiretalent' );
			}

			// Use WP datetime and WP formatting (site timezone).
			$dt = get_post_datetime( $post_id );
			$submitted = $dt ? wp_date( 'Y-m-d H:i:s', $dt->getTimestamp() ) : '';

			fputcsv(
				$output,
				array(
					$post_id,
					(string) get_post_meta( $post_id, 'hiretalent_applicant_name', true ),
					(string) get_post_meta( $post_id, 'hiretalent_applicant_email', true ),
					(string) get_post_meta( $post_id, 'hiretalent_applicant_phone', true ),
					(string) $job_title,
					(string) $status,
					(string) $submitted,
				)
			);
		}

		wp_reset_postdata();
		exit;
	}
}
