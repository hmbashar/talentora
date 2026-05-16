<?php
/**
 * Assets.php
 *
 * Handles admin asset enqueuing.
 *
 * @package Talentora\Admin\Assets
 * @since 1.0.0
 */

namespace Talentora\Admin\Assets;

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
        if (in_array($post_type, array('talentora_job', 'talentora_app')) || in_array($hook, array('talentora_job_page_talentora-settings', 'talentora_job_page_admin-post'))) {
            // We can use a common file or just enqueue specific ones. 
            // For now, let's keep it simple.
        }

        // 1. Job Post Type Screen (Edit Job)
        if ($post_type === 'talentora_job') {
            wp_enqueue_media();

            // Job Metabox CSS
            wp_enqueue_style(
                'talentora-admin-job-metabox',
                TALENTORA_URL . 'assets/css/admin-job-metabox.css',
                array(),
                TALENTORA_VERSION
            );

            // Job Metabox JS
            wp_enqueue_script(
                'talentora-admin-job-metabox',
                TALENTORA_URL . 'assets/js/admin-job-metabox.js',
                array('jquery'),
                TALENTORA_VERSION,
                true
            );
        }

        // 2. Application Post Type Screen (View Application)
        if ($post_type === 'talentora_app') {
            // Application Metabox CSS
            wp_enqueue_style(
                'talentora-admin-application-metabox',
                TALENTORA_URL . 'assets/css/admin-application-metabox.css',
                array(),
                TALENTORA_VERSION
            );

            // Application List (Bulk Actions) JS - Only strictly needed on list table, but fine here
            // actually bulk actions are on edit.php so we need to check screen base
        }

        // 3. Application List Screen (edit.php?post_type=talentora_app)
        $screen = get_current_screen();
        if ($screen && $screen->id === 'edit-talentora_app') {
            wp_enqueue_script(
                'sweetalert2',
                TALENTORA_URL . 'assets/js/sweetalert2.all.min.js',
                array(),
                '11.0.0',
                true
            );

            wp_enqueue_script(
                'talentora-admin-applications',
                TALENTORA_URL . 'assets/js/admin-applications.js',
                array('jquery', 'sweetalert2'),
                TALENTORA_VERSION,
                true
            );

            wp_localize_script('talentora-admin-applications', 'talentora_admin', array(
                'strings' => array(
                    'confirm_bulk_action' => __('Are you sure you want to perform this action?', 'talentora'),
                )
            ));
        }

        // 4. Settings Page
        if ($hook === 'talentora_job_page_talentora-settings') {
            // Settings CSS
            wp_enqueue_style(
                'talentora-admin-settings',
                TALENTORA_URL . 'assets/css/admin-settings.css',
                array(),
                TALENTORA_VERSION
            );

            // SweetAlert2
            wp_enqueue_script(
                'sweetalert2',
                TALENTORA_URL . 'assets/js/sweetalert2.all.min.js',
                array(),
                '11.0.0',
                true
            );

            // Settings JS (Logs)
            wp_enqueue_script(
                'talentora-admin-logs',
                TALENTORA_URL . 'assets/js/admin-logs.js',
                array('jquery', 'sweetalert2'),
                TALENTORA_VERSION,
                true
            );

            wp_localize_script('talentora-admin-logs', 'talentora_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('talentora_admin_nonce'),
                'strings' => array(
                    'confirm_clear_log' => __('Are you sure you want to clear the email log?', 'talentora'),
                    'log_cleared' => __('Log cleared successfully.', 'talentora'),
                    'error' => __('Something went wrong.', 'talentora'),
                )
            ));
        }
    }
}
