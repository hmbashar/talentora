<?php
/**
 * Assets.php
 *
 * Handles admin asset enqueuing.
 *
 * @package HireTalent\Admin\Assets
 * @since 1.0.0
 */

namespace HireTalent\Admin\Assets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Assets class.
 */
class Assets
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Enqueue admin assets.
     *
     * @param string $hook Current admin page hook.
     * @since 1.0.0
     */
    public function enqueue_admin_assets($hook)
    {
        global $post_type;

        // Enqueue on job post type screens
        if ('hiretalent_job' === $post_type) {
            wp_enqueue_media();

            wp_enqueue_style(
                'hiretalent-admin',
                HIRETALENT_URL . 'assets/css/admin.css',
                array(),
                HIRETALENT_VERSION
            );
        }

        // Enqueue on settings page
        if ('hiretalent_job_page_hiretalent-settings' === $hook) {
            // Enqueue WordPress React (wp-element)
            wp_enqueue_script('wp-element');
            wp_enqueue_script('wp-components');
            wp_enqueue_script('wp-api-fetch');

            wp_enqueue_script(
                'hiretalent-admin-settings',
                HIRETALENT_URL . 'assets/js/admin-settings.js',
                array('wp-element', 'wp-components', 'wp-api-fetch'),
                HIRETALENT_VERSION,
                true
            );

            wp_localize_script('hiretalent-admin-settings', 'hireTalentSettings', array(
                'applyFormShortcode' => get_option('hiretalent_apply_form_shortcode', ''),
                'jobsPerPage' => get_option('hiretalent_jobs_per_page', 10),
                'nonce' => wp_create_nonce('wp_rest'),
                'restUrl' => rest_url(),
            ));

            wp_enqueue_style(
                'hiretalent-admin',
                HIRETALENT_URL . 'assets/css/admin.css',
                array('wp-components'),
                HIRETALENT_VERSION
            );
        }
    }
}
