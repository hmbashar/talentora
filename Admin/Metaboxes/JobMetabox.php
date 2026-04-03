<?php
/**
 * JobMetabox.php
 *
 * Handles job meta fields in the admin.
 *
 * @package Talentora\Admin\Metaboxes
 * @since 1.0.0
 */

namespace Talentora\Admin\Metaboxes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Job Metabox class.
 */
class JobMetabox
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post_talentora_job', array($this, 'save_meta_box'), 10, 2);
    }

    /**
     * Add meta box.
     *
     * @since 1.0.0
     */
    public function add_meta_box()
    {
        add_meta_box(
            'talentora_job_details',
            __('Job Details', 'talentora'),
            array($this, 'render_meta_box'),
            'talentora_job',
            'normal',
            'high'
        );
    }

    /**
     * Render meta box content.
     *
     * @param \WP_Post $post The post object.
     * @since 1.0.0
     */
    public function render_meta_box($post)
    {
        // Add nonce for security
        wp_nonce_field('talentora_save_job_meta', 'talentora_job_meta_nonce');

        // Get current values
        $location = get_post_meta($post->ID, 'talentora_location', true);
        $salary_min = get_post_meta($post->ID, 'talentora_salary_min', true);
        $salary_max = get_post_meta($post->ID, 'talentora_salary_max', true);
        $deadline = get_post_meta($post->ID, 'talentora_deadline', true);
        $experience = get_post_meta($post->ID, 'talentora_experience', true);
        $vacancy = get_post_meta($post->ID, 'talentora_vacancy', true);
        $working_hours = get_post_meta($post->ID, 'talentora_working_hours', true);
        $working_days = get_post_meta($post->ID, 'talentora_working_days', true);
        $joining_date = get_post_meta($post->ID, 'talentora_joining_date', true);
        $company_name = get_post_meta($post->ID, 'talentora_company_name', true);
        $company_website = get_post_meta($post->ID, 'talentora_company_website', true);
        $company_logo_id = get_post_meta($post->ID, 'talentora_company_logo_id', true);
        $is_filled = get_post_meta($post->ID, 'talentora_is_filled', true);
        $expiry_date = get_post_meta($post->ID, 'talentora_expiry_date', true);

        ?>
        <div class="talentora-metabox">

            <!-- Job Information Section -->
            <div class="talentora-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-portfolio"></span>
                        <?php esc_html_e('Job Information', 'talentora'); ?></h3>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="talentora_location">
                            <span class="dashicons dashicons-location"></span>
                            <?php esc_html_e('Location', 'talentora'); ?>
                        </label>
                        <input type="text" id="talentora_location" name="talentora_location"
                            value="<?php echo esc_attr($location); ?>"
                            placeholder="<?php esc_attr_e('e.g., New York, NY', 'talentora'); ?>" class="talentora-input">
                    </div>

                    <div class="field-group half-width">
                        <label>
                            <span class="dashicons dashicons-money-alt"></span>
                            <?php esc_html_e('Salary Range', 'talentora'); ?>
                        </label>
                        <div class="salary-fields">
                            <div class="salary-field">
                                <input type="number" id="talentora_salary_min" name="talentora_salary_min"
                                    value="<?php echo esc_attr($salary_min); ?>"
                                    placeholder="<?php esc_attr_e('Min', 'talentora'); ?>" min="0" step="1000"
                                    class="talentora-input">
                            </div>
                            <span class="salary-separator">—</span>
                            <div class="salary-field">
                                <input type="number" id="talentora_salary_max" name="talentora_salary_max"
                                    value="<?php echo esc_attr($salary_max); ?>"
                                    placeholder="<?php esc_attr_e('Max', 'talentora'); ?>" min="0" step="1000"
                                    class="talentora-input">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="talentora_experience">
                            <span class="dashicons dashicons-welcome-learn-more"></span>
                            <?php esc_html_e('Experience', 'talentora'); ?>
                        </label>
                        <input type="text" id="talentora_experience" name="talentora_experience"
                            value="<?php echo esc_attr($experience); ?>"
                            placeholder="<?php esc_attr_e('e.g., 2 Years+', 'talentora'); ?>" class="talentora-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="talentora_vacancy">
                            <span class="dashicons dashicons-groups"></span>
                            <?php esc_html_e('Vacancy', 'talentora'); ?>
                        </label>
                        <input type="number" id="talentora_vacancy" name="talentora_vacancy"
                            value="<?php echo esc_attr($vacancy); ?>"
                            placeholder="<?php esc_attr_e('e.g., 3', 'talentora'); ?>" min="1" class="talentora-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="talentora_working_hours">
                            <span class="dashicons dashicons-clock"></span>
                            <?php esc_html_e('Working Hours', 'talentora'); ?>
                        </label>
                        <input type="text" id="talentora_working_hours" name="talentora_working_hours"
                            value="<?php echo esc_attr($working_hours); ?>"
                            placeholder="<?php esc_attr_e('e.g., 9:00 AM - 5:00 PM', 'talentora'); ?>"
                            class="talentora-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="talentora_working_days">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Working Days', 'talentora'); ?>
                        </label>
                        <input type="text" id="talentora_working_days" name="talentora_working_days"
                            value="<?php echo esc_attr($working_days); ?>"
                            placeholder="<?php esc_attr_e('e.g., Mon - Fri', 'talentora'); ?>" class="talentora-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="talentora_deadline">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Application Deadline', 'talentora'); ?>
                        </label>
                        <input type="date" id="talentora_deadline" name="talentora_deadline"
                            value="<?php echo esc_attr($deadline); ?>" class="talentora-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="talentora_joining_date">
                            <span class="dashicons dashicons-calendar"></span>
                            <?php esc_html_e('Expected Joining Date', 'talentora'); ?>
                        </label>
                        <input type="date" id="talentora_joining_date" name="talentora_joining_date"
                            value="<?php echo esc_attr($joining_date); ?>" class="talentora-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group full-width">
                        <label for="talentora_expiry_date">
                            <span class="dashicons dashicons-clock"></span>
                            <?php esc_html_e('Expiry Date (Optional)', 'talentora'); ?>
                        </label>
                        <input type="date" id="talentora_expiry_date" name="talentora_expiry_date"
                            value="<?php echo esc_attr($expiry_date); ?>" class="talentora-input">
                        <p class="description">
                            <span class="dashicons dashicons-info"></span>
                            <?php esc_html_e('Job will be automatically hidden after this date.', 'talentora'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Company Information Section -->
            <div class="talentora-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-building"></span>
                        <?php esc_html_e('Company Information', 'talentora'); ?></h3>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="talentora_company_name">
                            <span class="dashicons dashicons-businessperson"></span>
                            <?php esc_html_e('Company Name', 'talentora'); ?>
                        </label>
                        <input type="text" id="talentora_company_name" name="talentora_company_name"
                            value="<?php echo esc_attr($company_name); ?>"
                            placeholder="<?php esc_attr_e('e.g., Acme Corporation', 'talentora'); ?>" class="talentora-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="talentora_company_website">
                            <span class="dashicons dashicons-admin-site"></span>
                            <?php esc_html_e('Company Website', 'talentora'); ?>
                        </label>
                        <input type="url" id="talentora_company_website" name="talentora_company_website"
                            value="<?php echo esc_attr($company_website); ?>"
                            placeholder="<?php esc_attr_e('https://example.com', 'talentora'); ?>" class="talentora-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group full-width">
                        <label>
                            <span class="dashicons dashicons-format-image"></span>
                            <?php esc_html_e('Company Logo', 'talentora'); ?>
                        </label>
                        <div class="logo-upload-wrapper">
                            <input type="hidden" id="talentora_company_logo_id" name="talentora_company_logo_id"
                                value="<?php echo esc_attr($company_logo_id); ?>">
                            <div class="logo-upload-area">
                                <div class="company-logo-preview" id="talentora_logo_preview">
                                    <?php if ($company_logo_id): ?>
                                        <?php echo wp_get_attachment_image($company_logo_id, 'thumbnail'); ?>
                                    <?php else: ?>
                                        <div class="logo-placeholder">
                                            <span class="dashicons dashicons-format-image"></span>
                                            <p><?php esc_html_e('No logo uploaded', 'talentora'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="logo-buttons">
                                    <button type="button" class="button button-primary" id="talentora_upload_logo_button">
                                        <span class="dashicons dashicons-upload"></span>
                                        <?php esc_html_e('Upload Logo', 'talentora'); ?>
                                    </button>
                                    <button type="button" class="button button-secondary" id="talentora_remove_logo_button"
                                        style="<?php echo empty($company_logo_id) ? 'display:none;' : ''; ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                        <?php esc_html_e('Remove', 'talentora'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Settings Section -->
            <div class="talentora-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-admin-settings"></span>
                        <?php esc_html_e('Application Settings', 'talentora'); ?></h3>
                </div>

                <?php
                $application_type = get_post_meta($post->ID, 'talentora_application_type', true);
                if (empty($application_type)) {
                    $application_type = 'third_party'; // Default
                }
                $third_party_shortcode = get_post_meta($post->ID, 'talentora_third_party_shortcode', true);
                $status = get_post_meta($post->ID, 'talentora_job_status', true);
                if (empty($status)) {
                    $status = 'open'; // Default
                }
                ?>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="talentora_job_status">
                            <span class="dashicons dashicons-marker"></span>
                            <?php esc_html_e('Job Status', 'talentora'); ?>
                        </label>
                        <select id="talentora_job_status" name="talentora_job_status" class="talentora-select">
                            <option value="open" <?php selected($status, 'open'); ?>>
                                <?php esc_html_e('✓ Open', 'talentora'); ?>
                            </option>
                            <option value="closed" <?php selected($status, 'closed'); ?>>
                                <?php esc_html_e('✕ Closed', 'talentora'); ?>
                            </option>
                            <option value="filled" <?php selected($status, 'filled'); ?>>
                                <?php esc_html_e('★ Filled', 'talentora'); ?>
                            </option>
                        </select>
                    </div>

                    <div class="field-group half-width">
                        <label for="talentora_application_type">
                            <span class="dashicons dashicons-forms"></span>
                            <?php esc_html_e('Application Type', 'talentora'); ?>
                        </label>
                        <select id="talentora_application_type" name="talentora_application_type" class="talentora-select">
                            <option value="third_party" <?php selected($application_type, 'third_party'); ?>>
                                <?php esc_html_e('Third Party Form', 'talentora'); ?>
                            </option>
                            <option value="builtin" <?php selected($application_type, 'builtin'); ?>>
                                <?php esc_html_e('Built-in Application System', 'talentora'); ?>
                            </option>
                        </select>
                        <p class="description">
                            <span class="dashicons dashicons-info"></span>
                            <?php esc_html_e('Choose how applicants will apply for this job.', 'talentora'); ?>
                        </p>
                    </div>
                </div>

                <div class="field-row" id="talentora_third_party_field"
                    style="<?php echo ($application_type === 'builtin') ? 'display:none;' : ''; ?>">
                    <div class="field-group full-width">
                        <label for="talentora_third_party_shortcode">
                            <span class="dashicons dashicons-shortcode"></span>
                            <?php esc_html_e('Third Party Form Shortcode', 'talentora'); ?>
                        </label>
                        <input type="text" id="talentora_third_party_shortcode" name="talentora_third_party_shortcode"
                            value="<?php echo esc_attr($third_party_shortcode); ?>"
                            placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'talentora'); ?>"
                            class="talentora-input">
                        <p class="description">
                            <span class="dashicons dashicons-info"></span>
                            <?php esc_html_e('Enter the shortcode for your contact form. Leave empty to use global setting.', 'talentora'); ?>
                        </p>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group full-width">
                        <div class="talentora-status-toggle">
                            <span class="status-label"><?php esc_html_e('Job Status:', 'talentora'); ?></span>
                            <div class="toggle-container">
                                <span class="status-badge badge-open"><?php esc_html_e('Open', 'talentora'); ?></span>
                                <label class="switch">
                                    <input type="checkbox" id="talentora_is_filled" name="talentora_is_filled" value="1" <?php checked($is_filled, '1'); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span class="status-badge badge-filled"><?php esc_html_e('Filled', 'talentora'); ?></span>
                            </div>
                            <p class="description">
                                <?php esc_html_e('Enable this to mark the job as filled. Filled jobs are marked as closed on the frontend.', 'talentora'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }
	/**
	 * Save meta box data.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 * @since 1.0.0
	 */
	public function save_meta_box( $post_id, $post ) {
		// Check nonce.
		if ( ! isset( $_POST['talentora_job_meta_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['talentora_job_meta_nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'talentora_save_job_meta' ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save location.
		if ( isset( $_POST['talentora_location'] ) ) {
			update_post_meta(
				$post_id,
				'talentora_location',
				sanitize_text_field( wp_unslash( $_POST['talentora_location'] ) )
			);
		}

		// Save salary min.
		if ( isset( $_POST['talentora_salary_min'] ) ) {
			update_post_meta(
				$post_id,
				'talentora_salary_min',
				absint( wp_unslash( $_POST['talentora_salary_min'] ) )
			);
		}

		// Save salary max.
		if ( isset( $_POST['talentora_salary_max'] ) ) {
			update_post_meta(
				$post_id,
				'talentora_salary_max',
				absint( wp_unslash( $_POST['talentora_salary_max'] ) )
			);
		}

    // Save deadline.
    if ( isset( $_POST['talentora_deadline'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_deadline',
            sanitize_text_field( wp_unslash( $_POST['talentora_deadline'] ) )
        );
    }

    // Save joining date.
    if ( isset( $_POST['talentora_joining_date'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_joining_date',
            sanitize_text_field( wp_unslash( $_POST['talentora_joining_date'] ) )
        );
    }

    // Save experience.
    if ( isset( $_POST['talentora_experience'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_experience',
            sanitize_text_field( wp_unslash( $_POST['talentora_experience'] ) )
        );
    }

    // Save vacancy.
    if ( isset( $_POST['talentora_vacancy'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_vacancy',
            absint( wp_unslash( $_POST['talentora_vacancy'] ) )
        );
    }

    // Save working hours.
    if ( isset( $_POST['talentora_working_hours'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_working_hours',
            sanitize_text_field( wp_unslash( $_POST['talentora_working_hours'] ) )
        );
    }

    // Save working days.
    if ( isset( $_POST['talentora_working_days'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_working_days',
            sanitize_text_field( wp_unslash( $_POST['talentora_working_days'] ) )
        );
    }

    // Save company name.
    if ( isset( $_POST['talentora_company_name'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_company_name',
            sanitize_text_field( wp_unslash( $_POST['talentora_company_name'] ) )
        );
    }

    // Save company website.
    if ( isset( $_POST['talentora_company_website'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_company_website',
            esc_url_raw( wp_unslash( $_POST['talentora_company_website'] ) )
        );
    }

    // Save company logo ID.
    if ( isset( $_POST['talentora_company_logo_id'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_company_logo_id',
            absint( wp_unslash( $_POST['talentora_company_logo_id'] ) )
        );
    }

    // Save expiry date.
    if ( isset( $_POST['talentora_expiry_date'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_expiry_date',
            sanitize_text_field( wp_unslash( $_POST['talentora_expiry_date'] ) )
        );
    }

    // Save application type.
    if ( isset( $_POST['talentora_application_type'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_application_type',
            sanitize_text_field( wp_unslash( $_POST['talentora_application_type'] ) )
        );
    }

    // Save third party shortcode.
    if ( isset( $_POST['talentora_third_party_shortcode'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_third_party_shortcode',
            sanitize_text_field( wp_unslash( $_POST['talentora_third_party_shortcode'] ) )
        );
    }

    // Save job status.
    if ( isset( $_POST['talentora_job_status'] ) ) {
        update_post_meta(
            $post_id,
            'talentora_job_status',
            sanitize_text_field( wp_unslash( $_POST['talentora_job_status'] ) )
        );
    }

    // Save is_filled checkbox.
    $is_filled = isset( $_POST['talentora_is_filled'] ) ? '1' : '0';
    update_post_meta( $post_id, 'talentora_is_filled', $is_filled );
}
}
