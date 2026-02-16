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

        // Shared Admin CSS (Variables & Common Styles) - Enqueue on all plugin pages
        if (in_array($post_type, array('hiretalent_job', 'hiretalent_app')) || in_array($hook, array('hiretalent_job_page_hiretalent-settings', 'hiretalent_job_page_admin-post'))) {
            // We can use a common file or just enqueue specific ones. 
            // For now, let's keep it simple.
        }

        // 1. Job Post Type Screen (Edit Job)
        if ($post_type === 'hiretalent_job') {
            wp_enqueue_media();

            // Job Metabox CSS
            wp_enqueue_style(
                'hiretalent-admin-job-metabox',
                HIRETALENT_URL . 'assets/css/admin-job-metabox.css',
                array(),
                HIRETALENT_VERSION
            );

            // Job Metabox JS
            wp_enqueue_script(
                'hiretalent-admin-job-metabox',
                HIRETALENT_URL . 'assets/js/admin-job-metabox.js',
                array('jquery'),
                HIRETALENT_VERSION,
                true
            );
        }

        // 2. Application Post Type Screen (View Application)
        if ($post_type === 'hiretalent_app') {
            // Application Metabox CSS
            wp_enqueue_style(
                'hiretalent-admin-application-metabox',
                HIRETALENT_URL . 'assets/css/admin-application-metabox.css',
                array(),
                HIRETALENT_VERSION
            );

            // Application List (Bulk Actions) JS - Only strictly needed on list table, but fine here
            // actually bulk actions are on edit.php so we need to check screen base
        }

        // 3. Application List Screen (edit.php?post_type=hiretalent_app)
        $screen = get_current_screen();
        if ($screen && $screen->id === 'edit-hiretalent_app') {
            wp_enqueue_script(
                'sweetalert2',
                HIRETALENT_URL . 'assets/js/sweetalert2.all.min.js',
                array(),
                '11.0.0',
                true
            );

            wp_enqueue_script(
                'hiretalent-admin-applications',
                HIRETALENT_URL . 'assets/js/admin-applications.js',
                array('jquery', 'sweetalert2'),
                HIRETALENT_VERSION,
                true
            );

            wp_localize_script('hiretalent-admin-applications', 'hiretalent_admin', array(
                'strings' => array(
                    'confirm_bulk_action' => __('Are you sure you want to perform this action?', 'hiretalent'),
                )
            ));
        }

        // 4. Settings Page
        if ($hook === 'hiretalent_job_page_hiretalent-settings') {
            // Settings CSS
            wp_enqueue_style(
                'hiretalent-admin-settings',
                HIRETALENT_URL . 'assets/css/admin-settings.css',
                array(),
                HIRETALENT_VERSION
            );

            // SweetAlert2
            wp_enqueue_script(
                'sweetalert2',
                HIRETALENT_URL . 'assets/js/sweetalert2.all.min.js',
                array(),
                '11.0.0',
                true
            );

            // Settings JS (Logs)
            wp_enqueue_script(
                'hiretalent-admin-logs',
                HIRETALENT_URL . 'assets/js/admin-logs.js',
                array('jquery', 'sweetalert2'),
                HIRETALENT_VERSION,
                true
            );

            wp_localize_script('hiretalent-admin-logs', 'hiretalent_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('hiretalent_admin_nonce'),
                'strings' => array(
                    'confirm_clear_log' => __('Are you sure you want to clear the email log?', 'hiretalent'),
                    'log_cleared' => __('Log cleared successfully.', 'hiretalent'),
                    'error' => __('Something went wrong.', 'hiretalent'),
                )
            ));
        }
    }
}
