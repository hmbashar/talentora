<?php
/**
 * ActivityLogger.php
 *
 * Handles activity logging for applications.
 *
 * @package Talentora\Inc
 * @since 1.0.0
 */

namespace Talentora;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Activity Logger class.
 */
class ActivityLogger
{
    /**
     * Log an activity.
     *
     * @param int    $application_id Application ID.
     * @param string $message        Log message.
     * @param string $type           Log type (info, success, error, warning).
     * @return bool True on success, false on failure.
     * @since 1.0.0
     */
    public function log($application_id, $message, $type = 'info')
    {
        if (!$application_id) {
            return false;
        }

        $logs = get_post_meta($application_id, 'talentora_activity_log', true);

        if (!is_array($logs)) {
            $logs = array();
        }

        $log_entry = array(
            'message' => $message,
            'type' => $type,
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
        );

        $logs[] = $log_entry;

        return update_post_meta($application_id, 'talentora_activity_log', $logs);
    }

    /**
     * Get logs for an application.
     *
     * @param int $application_id Application ID.
     * @return array List of logs.
     * @since 1.0.0
     */
    public function get_logs($application_id)
    {
        $logs = get_post_meta($application_id, 'talentora_activity_log', true);

        if (!is_array($logs)) {
            return array();
        }

        // Sort by timestamp descending
        usort($logs, function ($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        return $logs;
    }
}
