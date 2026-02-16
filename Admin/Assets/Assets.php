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
            wp_enqueue_style(
                'hiretalent-admin',
                HIRETALENT_URL . 'assets/css/admin.css',
                array(),
                HIRETALENT_VERSION
            );
        }
    }
}
