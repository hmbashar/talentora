<?php
/**
 * Activate.php
 *
 * Handles plugin activation tasks.
 *
 * @package HireTalent\Inc
 * @since 1.0.0
 */

namespace HireTalent;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Activation handler class.
 */
class Activate
{
    /**
     * Run activation tasks.
     *
     * @since 1.0.0
     */
    public static function activate()
    {
        // Set default options
        self::set_default_options();

        // Flush rewrite rules to ensure permalinks work
        flush_rewrite_rules();

        // Schedule daily maintenance event
        if (!wp_next_scheduled('hiretalent_daily_event')) {
            wp_schedule_event(time(), 'daily', 'hiretalent_daily_event');
        }
    }

    /**
     * Set default plugin options.
     *
     * @since 1.0.0
     */
    private static function set_default_options()
    {
        // Set default jobs per page
        if (false === get_option('hiretalent_jobs_per_page')) {
            add_option('hiretalent_jobs_per_page', 10);
        }

        // Set default apply form shortcode (empty by default)
        if (false === get_option('hiretalent_apply_form_shortcode')) {
            add_option('hiretalent_apply_form_shortcode', '');
        }
    }
}
