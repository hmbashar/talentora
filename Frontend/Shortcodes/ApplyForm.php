<?php
/**
 * ApplyForm.php
 *
 * Handles the [hiretalent_apply_form] shortcode.
 *
 * @package HireTalent\Frontend\Shortcodes
 * @since 1.0.0
 */

namespace HireTalent\Frontend\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Apply Form Shortcode class.
 */
class ApplyForm
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_shortcode('hiretalent_apply_form', array($this, 'render_apply_form'));
    }

    /**
     * Render apply form shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     * @since 1.0.0
     */
    public function render_apply_form($atts)
    {
        $atts = shortcode_atts(array(
            'form_shortcode' => '',
        ), $atts);

        $job_id = get_the_ID();

        // Get application type for this job
        $application_type = get_post_meta($job_id, 'hiretalent_application_type', true);

        // Default to third_party if not set
        if (empty($application_type)) {
            $application_type = 'third_party';
        }

        ob_start();

        // If built-in application system is selected
        if ($application_type === 'builtin') {
            ?>
            <div class="hiretalent-apply-form">
                <h3>
                    <?php esc_html_e('Apply for this Position', 'hiretalent'); ?>
                </h3>
                <?php echo do_shortcode('[hiretalent_application_form job_id="' . absint($job_id) . '"]'); ?>
            </div>
            <?php
        } else {
            // Third party form
            // Check for job-specific shortcode first, then attribute, then global option
            $form_shortcode = get_post_meta($job_id, 'hiretalent_third_party_shortcode', true);

            if (empty($form_shortcode)) {
                $form_shortcode = !empty($atts['form_shortcode']) ? $atts['form_shortcode'] : get_option('hiretalent_apply_form_shortcode', '');
            }

            // Apply filter hook
            $form_shortcode = apply_filters('hiretalent_apply_form_shortcode', $form_shortcode, $job_id);

            if (!empty($form_shortcode)) {
                ?>
                <div class="hiretalent-apply-form">
                    <h3>
                        <?php esc_html_e('Apply for this Position', 'hiretalent'); ?>
                    </h3>
                    <?php echo do_shortcode($form_shortcode); ?>
                </div>
                <?php
            } elseif (current_user_can('manage_options')) {
                ?>
                <div class="hiretalent-apply-form-notice">
                    <p>
                        <strong>
                            <?php esc_html_e('Admin Notice:', 'hiretalent'); ?>
                        </strong>
                        <?php
                        printf(
                            /* translators: %s: Settings page URL */
                            esc_html__('No apply form shortcode is set. Please configure it in the %s or switch to the built-in application system.', 'hiretalent'),
                            '<a href="' . esc_url(admin_url('edit.php?post_type=hiretalent_job&page=hiretalent-settings')) . '">' . esc_html__('plugin settings', 'hiretalent') . '</a>'
                        );
                        ?>
                    </p>
                </div>
                <?php
            }
        }

        return ob_get_clean();
    }
}
