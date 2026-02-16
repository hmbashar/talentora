<?php
/**
 * JobMetabox.php
 *
 * Handles job meta fields in the admin.
 *
 * @package HireTalent\Admin\Metaboxes
 * @since 1.0.0
 */

namespace HireTalent\Admin\Metaboxes;

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
        add_action('save_post_hiretalent_job', array($this, 'save_meta_box'), 10, 2);
    }

    /**
     * Add meta box.
     *
     * @since 1.0.0
     */
    public function add_meta_box()
    {
        add_meta_box(
            'hiretalent_job_details',
            __('Job Details', 'hiretalent'),
            array($this, 'render_meta_box'),
            'hiretalent_job',
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
        wp_nonce_field('hiretalent_save_job_meta', 'hiretalent_job_meta_nonce');

        // Get current values
        $location = get_post_meta($post->ID, 'hiretalent_location', true);
        $salary_min = get_post_meta($post->ID, 'hiretalent_salary_min', true);
        $salary_max = get_post_meta($post->ID, 'hiretalent_salary_max', true);
        $deadline = get_post_meta($post->ID, 'hiretalent_deadline', true);
        $experience = get_post_meta($post->ID, 'hiretalent_experience', true);
        $vacancy = get_post_meta($post->ID, 'hiretalent_vacancy', true);
        $working_hours = get_post_meta($post->ID, 'hiretalent_working_hours', true);
        $working_days = get_post_meta($post->ID, 'hiretalent_working_days', true);
        $joining_date = get_post_meta($post->ID, 'hiretalent_joining_date', true);
        $company_name = get_post_meta($post->ID, 'hiretalent_company_name', true);
        $company_website = get_post_meta($post->ID, 'hiretalent_company_website', true);
        $company_logo_id = get_post_meta($post->ID, 'hiretalent_company_logo_id', true);
        $is_filled = get_post_meta($post->ID, 'hiretalent_is_filled', true);
        $expiry_date = get_post_meta($post->ID, 'hiretalent_expiry_date', true);

        ?>
        <div class="hiretalent-metabox">

            <!-- Job Information Section -->
            <div class="hiretalent-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-portfolio"></span>
                        <?php esc_html_e('Job Information', 'hiretalent'); ?></h3>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="hiretalent_location">
                            <span class="dashicons dashicons-location"></span>
                            <?php esc_html_e('Location', 'hiretalent'); ?>
                        </label>
                        <input type="text" id="hiretalent_location" name="hiretalent_location"
                            value="<?php echo esc_attr($location); ?>"
                            placeholder="<?php esc_attr_e('e.g., New York, NY', 'hiretalent'); ?>" class="hiretalent-input">
                    </div>

                    <div class="field-group half-width">
                        <label>
                            <span class="dashicons dashicons-money-alt"></span>
                            <?php esc_html_e('Salary Range', 'hiretalent'); ?>
                        </label>
                        <div class="salary-fields">
                            <div class="salary-field">
                                <input type="number" id="hiretalent_salary_min" name="hiretalent_salary_min"
                                    value="<?php echo esc_attr($salary_min); ?>"
                                    placeholder="<?php esc_attr_e('Min', 'hiretalent'); ?>" min="0" step="1000"
                                    class="hiretalent-input">
                            </div>
                            <span class="salary-separator">—</span>
                            <div class="salary-field">
                                <input type="number" id="hiretalent_salary_max" name="hiretalent_salary_max"
                                    value="<?php echo esc_attr($salary_max); ?>"
                                    placeholder="<?php esc_attr_e('Max', 'hiretalent'); ?>" min="0" step="1000"
                                    class="hiretalent-input">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="hiretalent_experience">
                            <span class="dashicons dashicons-welcome-learn-more"></span>
                            <?php esc_html_e('Experience', 'hiretalent'); ?>
                        </label>
                        <input type="text" id="hiretalent_experience" name="hiretalent_experience"
                            value="<?php echo esc_attr($experience); ?>"
                            placeholder="<?php esc_attr_e('e.g., 2 Years+', 'hiretalent'); ?>" class="hiretalent-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="hiretalent_vacancy">
                            <span class="dashicons dashicons-groups"></span>
                            <?php esc_html_e('Vacancy', 'hiretalent'); ?>
                        </label>
                        <input type="number" id="hiretalent_vacancy" name="hiretalent_vacancy"
                            value="<?php echo esc_attr($vacancy); ?>"
                            placeholder="<?php esc_attr_e('e.g., 3', 'hiretalent'); ?>" min="1" class="hiretalent-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="hiretalent_working_hours">
                            <span class="dashicons dashicons-clock"></span>
                            <?php esc_html_e('Working Hours', 'hiretalent'); ?>
                        </label>
                        <input type="text" id="hiretalent_working_hours" name="hiretalent_working_hours"
                            value="<?php echo esc_attr($working_hours); ?>"
                            placeholder="<?php esc_attr_e('e.g., 9:00 AM - 5:00 PM', 'hiretalent'); ?>"
                            class="hiretalent-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="hiretalent_working_days">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Working Days', 'hiretalent'); ?>
                        </label>
                        <input type="text" id="hiretalent_working_days" name="hiretalent_working_days"
                            value="<?php echo esc_attr($working_days); ?>"
                            placeholder="<?php esc_attr_e('e.g., Mon - Fri', 'hiretalent'); ?>" class="hiretalent-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="hiretalent_deadline">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Application Deadline', 'hiretalent'); ?>
                        </label>
                        <input type="date" id="hiretalent_deadline" name="hiretalent_deadline"
                            value="<?php echo esc_attr($deadline); ?>" class="hiretalent-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="hiretalent_joining_date">
                            <span class="dashicons dashicons-calendar"></span>
                            <?php esc_html_e('Expected Joining Date', 'hiretalent'); ?>
                        </label>
                        <input type="date" id="hiretalent_joining_date" name="hiretalent_joining_date"
                            value="<?php echo esc_attr($joining_date); ?>" class="hiretalent-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group full-width">
                        <label for="hiretalent_expiry_date">
                            <span class="dashicons dashicons-clock"></span>
                            <?php esc_html_e('Expiry Date (Optional)', 'hiretalent'); ?>
                        </label>
                        <input type="date" id="hiretalent_expiry_date" name="hiretalent_expiry_date"
                            value="<?php echo esc_attr($expiry_date); ?>" class="hiretalent-input">
                        <p class="description">
                            <span class="dashicons dashicons-info"></span>
                            <?php esc_html_e('Job will be automatically hidden after this date.', 'hiretalent'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Company Information Section -->
            <div class="hiretalent-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-building"></span>
                        <?php esc_html_e('Company Information', 'hiretalent'); ?></h3>
                </div>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="hiretalent_company_name">
                            <span class="dashicons dashicons-businessperson"></span>
                            <?php esc_html_e('Company Name', 'hiretalent'); ?>
                        </label>
                        <input type="text" id="hiretalent_company_name" name="hiretalent_company_name"
                            value="<?php echo esc_attr($company_name); ?>"
                            placeholder="<?php esc_attr_e('e.g., Acme Corporation', 'hiretalent'); ?>" class="hiretalent-input">
                    </div>

                    <div class="field-group half-width">
                        <label for="hiretalent_company_website">
                            <span class="dashicons dashicons-admin-site"></span>
                            <?php esc_html_e('Company Website', 'hiretalent'); ?>
                        </label>
                        <input type="url" id="hiretalent_company_website" name="hiretalent_company_website"
                            value="<?php echo esc_attr($company_website); ?>"
                            placeholder="<?php esc_attr_e('https://example.com', 'hiretalent'); ?>" class="hiretalent-input">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group full-width">
                        <label>
                            <span class="dashicons dashicons-format-image"></span>
                            <?php esc_html_e('Company Logo', 'hiretalent'); ?>
                        </label>
                        <div class="logo-upload-wrapper">
                            <input type="hidden" id="hiretalent_company_logo_id" name="hiretalent_company_logo_id"
                                value="<?php echo esc_attr($company_logo_id); ?>">
                            <div class="logo-upload-area">
                                <div class="company-logo-preview" id="hiretalent_logo_preview">
                                    <?php if ($company_logo_id): ?>
                                        <?php echo wp_get_attachment_image($company_logo_id, 'thumbnail'); ?>
                                    <?php else: ?>
                                        <div class="logo-placeholder">
                                            <span class="dashicons dashicons-format-image"></span>
                                            <p><?php esc_html_e('No logo uploaded', 'hiretalent'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="logo-buttons">
                                    <button type="button" class="button button-primary" id="hiretalent_upload_logo_button">
                                        <span class="dashicons dashicons-upload"></span>
                                        <?php esc_html_e('Upload Logo', 'hiretalent'); ?>
                                    </button>
                                    <button type="button" class="button button-secondary" id="hiretalent_remove_logo_button"
                                        style="<?php echo empty($company_logo_id) ? 'display:none;' : ''; ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                        <?php esc_html_e('Remove', 'hiretalent'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Settings Section -->
            <div class="hiretalent-section">
                <div class="section-header">
                    <h3><span class="dashicons dashicons-admin-settings"></span>
                        <?php esc_html_e('Application Settings', 'hiretalent'); ?></h3>
                </div>

                <?php
                $application_type = get_post_meta($post->ID, 'hiretalent_application_type', true);
                if (empty($application_type)) {
                    $application_type = 'third_party'; // Default
                }
                $third_party_shortcode = get_post_meta($post->ID, 'hiretalent_third_party_shortcode', true);
                $status = get_post_meta($post->ID, 'hiretalent_job_status', true);
                if (empty($status)) {
                    $status = 'open'; // Default
                }
                ?>

                <div class="field-row">
                    <div class="field-group half-width">
                        <label for="hiretalent_job_status">
                            <span class="dashicons dashicons-marker"></span>
                            <?php esc_html_e('Job Status', 'hiretalent'); ?>
                        </label>
                        <select id="hiretalent_job_status" name="hiretalent_job_status" class="hiretalent-select">
                            <option value="open" <?php selected($status, 'open'); ?>>
                                <?php esc_html_e('✓ Open', 'hiretalent'); ?>
                            </option>
                            <option value="closed" <?php selected($status, 'closed'); ?>>
                                <?php esc_html_e('✕ Closed', 'hiretalent'); ?>
                            </option>
                            <option value="filled" <?php selected($status, 'filled'); ?>>
                                <?php esc_html_e('★ Filled', 'hiretalent'); ?>
                            </option>
                        </select>
                    </div>

                    <div class="field-group half-width">
                        <label for="hiretalent_application_type">
                            <span class="dashicons dashicons-forms"></span>
                            <?php esc_html_e('Application Type', 'hiretalent'); ?>
                        </label>
                        <select id="hiretalent_application_type" name="hiretalent_application_type" class="hiretalent-select">
                            <option value="third_party" <?php selected($application_type, 'third_party'); ?>>
                                <?php esc_html_e('Third Party Form', 'hiretalent'); ?>
                            </option>
                            <option value="builtin" <?php selected($application_type, 'builtin'); ?>>
                                <?php esc_html_e('Built-in Application System', 'hiretalent'); ?>
                            </option>
                        </select>
                        <p class="description">
                            <span class="dashicons dashicons-info"></span>
                            <?php esc_html_e('Choose how applicants will apply for this job.', 'hiretalent'); ?>
                        </p>
                    </div>
                </div>

                <div class="field-row" id="hiretalent_third_party_field"
                    style="<?php echo ($application_type === 'builtin') ? 'display:none;' : ''; ?>">
                    <div class="field-group full-width">
                        <label for="hiretalent_third_party_shortcode">
                            <span class="dashicons dashicons-shortcode"></span>
                            <?php esc_html_e('Third Party Form Shortcode', 'hiretalent'); ?>
                        </label>
                        <input type="text" id="hiretalent_third_party_shortcode" name="hiretalent_third_party_shortcode"
                            value="<?php echo esc_attr($third_party_shortcode); ?>"
                            placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'hiretalent'); ?>"
                            class="hiretalent-input">
                        <p class="description">
                            <span class="dashicons dashicons-info"></span>
                            <?php esc_html_e('Enter the shortcode for your contact form. Leave empty to use global setting.', 'hiretalent'); ?>
                        </p>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group full-width">
                        <div class="hiretalent-status-toggle">
                            <span class="status-label"><?php esc_html_e('Job Status:', 'hiretalent'); ?></span>
                            <div class="toggle-container">
                                <span class="status-badge badge-open"><?php esc_html_e('Open', 'hiretalent'); ?></span>
                                <label class="switch">
                                    <input type="checkbox" id="hiretalent_is_filled" name="hiretalent_is_filled" value="1" <?php checked($is_filled, '1'); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span class="status-badge badge-filled"><?php esc_html_e('Filled', 'hiretalent'); ?></span>
                            </div>
                            <p class="description">
                                <?php esc_html_e('Enable this to mark the job as filled. Filled jobs are marked as closed on the frontend.', 'hiretalent'); ?>
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
    public function save_meta_box($post_id, $post)
    {
        // Check nonce
        if (!isset($_POST['hiretalent_job_meta_nonce']) || !wp_verify_nonce($_POST['hiretalent_job_meta_nonce'], 'hiretalent_save_job_meta')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save location
        if (isset($_POST['hiretalent_location'])) {
            update_post_meta($post_id, 'hiretalent_location', sanitize_text_field($_POST['hiretalent_location']));
        }

        // Save salary min
        if (isset($_POST['hiretalent_salary_min'])) {
            update_post_meta($post_id, 'hiretalent_salary_min', absint($_POST['hiretalent_salary_min']));
        }

        // Save salary max
        if (isset($_POST['hiretalent_salary_max'])) {
            update_post_meta($post_id, 'hiretalent_salary_max', absint($_POST['hiretalent_salary_max']));
        }

        // Save deadline
        if (isset($_POST['hiretalent_deadline'])) {
            update_post_meta($post_id, 'hiretalent_deadline', sanitize_text_field($_POST['hiretalent_deadline']));
        }

        // Save joining date
        if (isset($_POST['hiretalent_joining_date'])) {
            update_post_meta($post_id, 'hiretalent_joining_date', sanitize_text_field($_POST['hiretalent_joining_date']));
        }

        // Save experience
        if (isset($_POST['hiretalent_experience'])) {
            update_post_meta($post_id, 'hiretalent_experience', sanitize_text_field($_POST['hiretalent_experience']));
        }

        // Save vacancy
        if (isset($_POST['hiretalent_vacancy'])) {
            update_post_meta($post_id, 'hiretalent_vacancy', absint($_POST['hiretalent_vacancy']));
        }

        // Save working hours
        if (isset($_POST['hiretalent_working_hours'])) {
            update_post_meta($post_id, 'hiretalent_working_hours', sanitize_text_field($_POST['hiretalent_working_hours']));
        }

        // Save working days
        if (isset($_POST['hiretalent_working_days'])) {
            update_post_meta($post_id, 'hiretalent_working_days', sanitize_text_field($_POST['hiretalent_working_days']));
        }

        // Save company name
        if (isset($_POST['hiretalent_company_name'])) {
            update_post_meta($post_id, 'hiretalent_company_name', sanitize_text_field($_POST['hiretalent_company_name']));
        }

        // Save company website
        if (isset($_POST['hiretalent_company_website'])) {
            update_post_meta($post_id, 'hiretalent_company_website', esc_url_raw($_POST['hiretalent_company_website']));
        }

        // Save company logo ID
        if (isset($_POST['hiretalent_company_logo_id'])) {
            update_post_meta($post_id, 'hiretalent_company_logo_id', absint($_POST['hiretalent_company_logo_id']));
        }

        // Save expiry date
        if (isset($_POST['hiretalent_expiry_date'])) {
            update_post_meta($post_id, 'hiretalent_expiry_date', sanitize_text_field($_POST['hiretalent_expiry_date']));
        }

        // Save application type
        if (isset($_POST['hiretalent_application_type'])) {
            update_post_meta($post_id, 'hiretalent_application_type', sanitize_text_field($_POST['hiretalent_application_type']));
        }

        // Save third party shortcode
        if (isset($_POST['hiretalent_third_party_shortcode'])) {
            update_post_meta($post_id, 'hiretalent_third_party_shortcode', sanitize_text_field($_POST['hiretalent_third_party_shortcode']));
        }

        // Save job status
        if (isset($_POST['hiretalent_job_status'])) {
            update_post_meta($post_id, 'hiretalent_job_status', sanitize_text_field($_POST['hiretalent_job_status']));
        }

        // Save is_filled
        $is_filled = isset($_POST['hiretalent_is_filled']) ? '1' : '0';
        update_post_meta($post_id, 'hiretalent_is_filled', $is_filled);
    }
}
