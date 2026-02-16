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
        if (in_array($post_type, array('hiretalent_job', 'hiretalent_app'))) {
            wp_enqueue_media();

            wp_enqueue_style(
                'hiretalent-admin',
                HIRETALENT_URL . 'assets/css/admin.css',
                array(),
                HIRETALENT_VERSION
            );
        }

        // Enqueue on settings page
        if (in_array($hook, array('hiretalent_job_page_hiretalent-settings', 'hiretalent_job_page_admin-post'))) {
            wp_enqueue_style(
                'hiretalent-admin',
                HIRETALENT_URL . 'assets/css/admin.css',
                array(),
                HIRETALENT_VERSION
            );

            wp_enqueue_script(
                'sweetalert2',
                HIRETALENT_URL . 'assets/js/sweetalert2.all.min.js',
                array(),
                '11.0.0',
                true
            );

            wp_enqueue_script(
                'hiretalent-admin-js',
                HIRETALENT_URL . 'assets/js/admin.js',
                array('jquery', 'sweetalert2'),
                HIRETALENT_VERSION,
                true
            );

            wp_localize_script('hiretalent-admin-js', 'hiretalent_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('hiretalent_admin_nonce'),
                'strings' => array(
                    'confirm_clear_log' => __('Are you sure you want to clear the email log?', 'hiretalent'),
                    'log_cleared' => __('Log cleared successfully.', 'hiretalent'),
                    'error' => __('Something went wrong.', 'hiretalent'),
                    'confirm_bulk_action' => __('Are you sure you want to perform this action?', 'hiretalent'),
                )
            ));
        }
    }
}
