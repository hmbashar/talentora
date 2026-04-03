<?php
/**
 * ApplyForm.php
 *
 * Handles the [talentora_apply_form] shortcode.
 *
 * @package Talentora\Frontend\Shortcodes
 * @since 1.0.0
 */

namespace Talentora\Frontend\Shortcodes;

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
        add_shortcode('talentora_apply_form', array($this, 'render_apply_form'));
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

        // Check Job Status
        $status = get_post_meta($job_id, 'talentora_job_status', true);
        $deadline = get_post_meta($job_id, 'talentora_deadline', true);
        $today = current_time('Y-m-d');

        if ($status === 'closed' || $status === 'filled') {
            return '<div class="talentora-alert talentora-alert-warning">' . esc_html__('This position is currently closed and no longer accepting applications.', 'talentora') . '</div>';
        }

        if (!empty($deadline) && $deadline < $today) {
            return '<div class="talentora-alert talentora-alert-warning">' . esc_html__('The application deadline for this position has passed.', 'talentora') . '</div>';
        }

        // Get application type for this job
        $application_type = get_post_meta($job_id, 'talentora_application_type', true);

        // Default to third_party if not set
        if (empty($application_type)) {
            $application_type = 'third_party';
        }

        ob_start();

        // If built-in application system is selected
        if ($application_type === 'builtin') {
            ?>
            <div class="talentora-apply-form">
                <h3>
                    <?php esc_html_e('Apply for this Position', 'talentora'); ?>
                </h3>
                <?php echo do_shortcode('[talentora_application_form job_id="' . absint($job_id) . '"]'); ?>
            </div>
            <?php
        } else {
            // Third party form
            // Check for job-specific shortcode first, then attribute, then global option
            $form_shortcode = get_post_meta($job_id, 'talentora_third_party_shortcode', true);

            if (empty($form_shortcode)) {
                $form_shortcode = !empty($atts['form_shortcode']) ? $atts['form_shortcode'] : get_option('talentora_apply_form_shortcode', '');
            }

            // Apply filter hook
            $form_shortcode = apply_filters('talentora_apply_form_shortcode', $form_shortcode, $job_id);

            if (!empty($form_shortcode)) {
                ?>
                <div class="talentora-apply-form">
                    <h3>
                        <?php esc_html_e('Apply for this Position', 'talentora'); ?>
                    </h3>
                    <?php echo do_shortcode($form_shortcode); ?>
                </div>
                <?php
            } elseif (current_user_can('manage_options')) {
                ?>
                <div class="talentora-apply-form-notice">
                    <p>
                        <strong>
                            <?php esc_html_e('Admin Notice:', 'talentora'); ?>
                        </strong>
                        <?php
                        printf(
                            /* translators: %s: Settings page URL */
                            esc_html__('No apply form shortcode is set. Please configure it in the %s or switch to the built-in application system.', 'talentora'),
                            '<a href="' . esc_url(admin_url('edit.php?post_type=talentora_job&page=talentora-settings')) . '">' . esc_html__('plugin settings', 'talentora') . '</a>'
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
