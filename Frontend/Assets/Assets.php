<?php
/**
 * Assets.php
 *
 * Handles frontend asset enqueuing.
 *
 * @package Talentora\Frontend\Assets
 * @since 1.0.0
 */

namespace Talentora\Frontend\Assets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Frontend Assets class.
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
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
    }

    /**
     * Enqueue frontend assets.
     *
     * @since 1.0.0
     */
    public function enqueue_frontend_assets()
    {
        global $post;

        // Check if we're on a job-related page or if shortcode is present
        $should_enqueue = false;

        if (is_singular('talentora_job') || is_post_type_archive('talentora_job')) {
            $should_enqueue = true;
        }

        // Check for shortcode in post content
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'talentora_jobs')) {
            $should_enqueue = true;
        }

        if ($should_enqueue) {
            wp_enqueue_style('dashicons');

            wp_enqueue_style(
                'talentora-frontend',
                TALENTORA_URL . 'assets/css/frontend.css',
                array(),
                TALENTORA_VERSION
            );

            wp_enqueue_script(
                'talentora-frontend',
                TALENTORA_URL . 'assets/js/frontend.js',
                array('jquery'),
                TALENTORA_VERSION,
                true
            );

            wp_localize_script('talentora-frontend', 'talentora_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('talentora_filter_nonce'),
            ));

            // Enqueue SweetAlert2
            wp_enqueue_script(
                'sweetalert2',
                TALENTORA_URL . 'assets/js/sweetalert2.all.min.js',
                array(),
                '11.0.18',
                true
            );

            // Enqueue Application Form Redesign JS
            wp_enqueue_script(
                'talentora-application-form',
                TALENTORA_URL . 'assets/js/application-form.js',
                array('jquery', 'sweetalert2'),
                TALENTORA_VERSION,
                true
            );

            wp_localize_script('talentora-application-form', 'talentora_form_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('talentora_application_nonce'),
            ));
        }
    }
}
