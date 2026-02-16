<?php
/**
 * Assets.php
 *
 * Handles frontend asset enqueuing.
 *
 * @package HireTalent\Frontend\Assets
 * @since 1.0.0
 */

namespace HireTalent\Frontend\Assets;

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

        if (is_singular('hiretalent_job') || is_post_type_archive('hiretalent_job')) {
            $should_enqueue = true;
        }

        // Check for shortcode in post content
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'hiretalent_jobs')) {
            $should_enqueue = true;
        }

        if ($should_enqueue) {
            wp_enqueue_style('dashicons');

            wp_enqueue_style(
                'hiretalent-frontend',
                HIRETALENT_URL . 'assets/css/frontend.css',
                array(),
                HIRETALENT_VERSION
            );

            wp_enqueue_script(
                'hiretalent-frontend',
                HIRETALENT_URL . 'assets/js/frontend.js',
                array('jquery'),
                HIRETALENT_VERSION,
                true
            );

            wp_localize_script('hiretalent-frontend', 'hiretalent_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('hiretalent_filter_nonce'),
            ));
        }
    }
}
