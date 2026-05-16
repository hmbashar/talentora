<?php
/**
 * Deactivate.php
 *
 * Handles plugin deactivation tasks.
 *
 * @package Talentora\Inc
 * @since 1.0.0
 */

namespace Talentora;

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
        wp_clear_scheduled_hook('talentora_daily_event');
    }
}
