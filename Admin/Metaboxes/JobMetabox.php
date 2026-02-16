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
        $company_name = get_post_meta($post->ID, 'hiretalent_company_name', true);
        $company_website = get_post_meta($post->ID, 'hiretalent_company_website', true);
        $company_logo_id = get_post_meta($post->ID, 'hiretalent_company_logo_id', true);
        $is_filled = get_post_meta($post->ID, 'hiretalent_is_filled', true);
        $expiry_date = get_post_meta($post->ID, 'hiretalent_expiry_date', true);

        ?>
        <div class="hiretalent-metabox">
            <style>
                .hiretalent-metabox .field-group {
                    margin-bottom: 20px;
                }

                .hiretalent-metabox label {
                    display: block;
                    font-weight: 600;
                    margin-bottom: 5px;
                }

                .hiretalent-metabox input[type="text"],
                .hiretalent-metabox input[type="url"],
                .hiretalent-metabox input[type="number"],
                .hiretalent-metabox input[type="date"] {
                    width: 100%;
                    max-width: 400px;
                }

                .hiretalent-metabox .salary-fields {
                    display: flex;
                    gap: 15px;
                }

                .hiretalent-metabox .salary-fields>div {
                    flex: 1;
                }

                .hiretalent-metabox .company-logo-preview {
                    margin-top: 10px;
                }

                .hiretalent-metabox .company-logo-preview img {
                    max-width: 150px;
                    height: auto;
                    border: 1px solid #ddd;
                    padding: 5px;
                }
            </style>

            <div class="field-group">
                <label for="hiretalent_location">
                    <?php esc_html_e('Location', 'hiretalent'); ?>
                </label>
                <input type="text" id="hiretalent_location" name="hiretalent_location"
                    value="<?php echo esc_attr($location); ?>"
                    placeholder="<?php esc_attr_e('e.g., New York, NY', 'hiretalent'); ?>">
            </div>

            <div class="field-group">
                <label>
                    <?php esc_html_e('Salary Range', 'hiretalent'); ?>
                </label>
                <div class="salary-fields">
                    <div>
                        <label for="hiretalent_salary_min">
                            <?php esc_html_e('Minimum', 'hiretalent'); ?>
                        </label>
                        <input type="number" id="hiretalent_salary_min" name="hiretalent_salary_min"
                            value="<?php echo esc_attr($salary_min); ?>"
                            placeholder="<?php esc_attr_e('e.g., 50000', 'hiretalent'); ?>" min="0" step="1000">
                    </div>
                    <div>
                        <label for="hiretalent_salary_max">
                            <?php esc_html_e('Maximum', 'hiretalent'); ?>
                        </label>
                        <input type="number" id="hiretalent_salary_max" name="hiretalent_salary_max"
                            value="<?php echo esc_attr($salary_max); ?>"
                            placeholder="<?php esc_attr_e('e.g., 80000', 'hiretalent'); ?>" min="0" step="1000">
                    </div>
                </div>
            </div>

            <div class="field-group">
                <label for="hiretalent_deadline">
                    <?php esc_html_e('Application Deadline', 'hiretalent'); ?>
                </label>
                <input type="date" id="hiretalent_deadline" name="hiretalent_deadline"
                    value="<?php echo esc_attr($deadline); ?>">
            </div>

            <div class="field-group">
                <label for="hiretalent_company_name">
                    <?php esc_html_e('Company Name', 'hiretalent'); ?>
                </label>
                <input type="text" id="hiretalent_company_name" name="hiretalent_company_name"
                    value="<?php echo esc_attr($company_name); ?>"
                    placeholder="<?php esc_attr_e('e.g., Acme Corporation', 'hiretalent'); ?>">
            </div>

            <div class="field-group">
                <label for="hiretalent_company_website">
                    <?php esc_html_e('Company Website', 'hiretalent'); ?>
                </label>
                <input type="url" id="hiretalent_company_website" name="hiretalent_company_website"
                    value="<?php echo esc_attr($company_website); ?>"
                    placeholder="<?php esc_attr_e('https://example.com', 'hiretalent'); ?>">
            </div>

            <div class="field-group">
                <label>
                    <?php esc_html_e('Company Logo', 'hiretalent'); ?>
                </label>
                <input type="hidden" id="hiretalent_company_logo_id" name="hiretalent_company_logo_id"
                    value="<?php echo esc_attr($company_logo_id); ?>">
                <button type="button" class="button" id="hiretalent_upload_logo_button">
                    <?php esc_html_e('Upload Logo', 'hiretalent'); ?>
                </button>
                <button type="button" class="button" id="hiretalent_remove_logo_button"
                    style="<?php echo empty($company_logo_id) ? 'display:none;' : ''; ?>">
                    <?php esc_html_e('Remove Logo', 'hiretalent'); ?>
                </button>
                <div class="company-logo-preview" id="hiretalent_logo_preview">
                    <?php if ($company_logo_id): ?>
                        <?php echo wp_get_attachment_image($company_logo_id, 'thumbnail'); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="field-group">
                <label for="hiretalent_expiry_date">
                    <?php esc_html_e('Expiry Date (Optional)', 'hiretalent'); ?>
                </label>
                <input type="date" id="hiretalent_expiry_date" name="hiretalent_expiry_date"
                    value="<?php echo esc_attr($expiry_date); ?>">
                <p class="description">
                    <?php esc_html_e('Job will be automatically hidden after this date.', 'hiretalent'); ?>
                </p>
            </div>

            <!-- Application Settings Section -->
            <hr style="margin: 30px 0; border: none; border-top: 2px solid #ddd;">
            <h3><?php esc_html_e('Application Settings', 'hiretalent'); ?></h3>

            <?php
            $application_type = get_post_meta($post->ID, 'hiretalent_application_type', true);
            if (empty($application_type)) {
                $application_type = 'third_party'; // Default
            }
            $third_party_shortcode = get_post_meta($post->ID, 'hiretalent_third_party_shortcode', true);
            ?>

            <div class="field-group">
                <label for="hiretalent_application_type">
                    <?php esc_html_e('Application Type', 'hiretalent'); ?>
                </label>
                <select id="hiretalent_application_type" name="hiretalent_application_type" style="max-width: 400px;">
                    <option value="third_party" <?php selected($application_type, 'third_party'); ?>>
                        <?php esc_html_e('Third Party Form', 'hiretalent'); ?>
                    </option>
                    <option value="builtin" <?php selected($application_type, 'builtin'); ?>>
                        <?php esc_html_e('Built-in Application System', 'hiretalent'); ?>
                    </option>
                </select>
                <p class="description">
                    <?php esc_html_e('Choose how applicants will apply for this job.', 'hiretalent'); ?>
                </p>
            </div>

            <div class="field-group" id="hiretalent_third_party_field"
                style="<?php echo ($application_type === 'builtin') ? 'display:none;' : ''; ?>">
                <label for="hiretalent_third_party_shortcode">
                    <?php esc_html_e('Third Party Form Shortcode', 'hiretalent'); ?>
                </label>
                <input type="text" id="hiretalent_third_party_shortcode" name="hiretalent_third_party_shortcode"
                    value="<?php echo esc_attr($third_party_shortcode); ?>"
                    placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'hiretalent'); ?>" style="max-width: 400px;">
                <p class="description">
                    <?php esc_html_e('Enter the shortcode for your contact form. Leave empty to use global setting.', 'hiretalent'); ?>
                </p>
            </div>

            <div class="field-group">
                <label>
                    <input type="checkbox" id="hiretalent_is_filled" name="hiretalent_is_filled" value="1" <?php checked($is_filled, '1'); ?>>
                    <?php esc_html_e('Mark this job as filled', 'hiretalent'); ?>
                </label>
            </div>

            <script>
                jQuery(document).ready(function ($) {
                    var mediaUploader;

                    // Media uploader for company logo
                    $('#hiretalent_upload_logo_button').on('click', function (e) {
                        e.preventDefault();

                        if (mediaUploader) {
                            mediaUploader.open();
                            return;
                        }

                        mediaUploader = wp.media({
                            title: '<?php esc_html_e('Choose Company Logo', 'hiretalent'); ?>',
                            button: {
                                text: '<?php esc_html_e('Use this logo', 'hiretalent'); ?>'
                            },
                            multiple: false
                        });

                        mediaUploader.on('select', function () {
                            var attachment = mediaUploader.state().get('selection').first().toJSON();
                            $('#hiretalent_company_logo_id').val(attachment.id);
                            $('#hiretalent_logo_preview').html('<img src="' + attachment.url + '" style="max-width:150px;">');
                            $('#hiretalent_remove_logo_button').show();
                        });

                        mediaUploader.open();
                    });

                    $('#hiretalent_remove_logo_button').on('click', function (e) {
                        e.preventDefault();
                        $('#hiretalent_company_logo_id').val('');
                        $('#hiretalent_logo_preview').html('');
                        $(this).hide();
                    });

                    // Toggle third party shortcode field based on application type
                    $('#hiretalent_application_type').on('change', function () {
                        if ($(this).val() === 'builtin') {
                            $('#hiretalent_third_party_field').hide();
                        } else {
                            $('#hiretalent_third_party_field').show();
                        }
                    });
                });
            </script>
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

        // Save is_filled
        $is_filled = isset($_POST['hiretalent_is_filled']) ? '1' : '0';
        update_post_meta($post_id, 'hiretalent_is_filled', $is_filled);
    }
}
