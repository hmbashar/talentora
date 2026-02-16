<?php
/**
 * Deactivate.php
 *
 * Handles plugin deactivation tasks.
 *
 * @package HireTalent\Inc
 * @since 1.0.0
 */

namespace HireTalent;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Deactivation handler class.
 */
class Deactivate
{
    /**
     * Run deactivation tasks.
     *
     * @since 1.0.0
     */
    public static function deactivate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Clear scheduled events
        wp_clear_scheduled_hook('hiretalent_daily_event');
    }
}
