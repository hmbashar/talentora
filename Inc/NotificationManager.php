<?php
/**
 * NotificationManager.php
 *
 * Handles email notifications for the HireTalent plugin.
 *
 * @package HireTalent\Inc
 * @since 1.0.0
 */

namespace HireTalent;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Notification Manager class.
 */
class NotificationManager
{
    /**
     * Activity Logger instance.
     *
     * @var \HireTalent\Inc\ActivityLogger
     */
    private $activity_logger;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->activity_logger = new \HireTalent\ActivityLogger();
    }
    /**
     * Send admin notification email.
     *
     * @param int $application_id Application ID.
     * @return bool True on success, false on failure.
     * @since 1.0.0
     */
    public function send_admin_notification($application_id)
    {
        $admin_email = get_option('admin_email');
		
		$subject = get_option(
			'hiretalent_admin_notification_subject',
			/* translators: {site_name}: Site name, {job_title}: Job title. */
			esc_html__('[%site_name] New Job Application: {job_title}', 'hiretalent')
		);

		/* translators: {job_title}: Job title, {applicant_name}: Applicant full name, {application_url}: URL to the application details page. */
		$message = get_option(			
			'hiretalent_admin_notification_message',
			esc_html__("You have received a new job application.\n\nJob: {job_title}\nApplicant: {applicant_name}\n\nView application: {application_url}", 'hiretalent')
		);

        $subject = $this->replace_placeholders($subject, $application_id);
        $message = $this->replace_placeholders($message, $application_id);

        $result = wp_mail($admin_email, $subject, $message);
        $this->log_email($admin_email, $subject, $message, $result);

        if ($result) {
            $this->activity_logger->log($application_id, __('Admin notification email sent.', 'hiretalent'), 'info');
        } else {
            $this->activity_logger->log($application_id, __('Failed to send admin notification email.', 'hiretalent'), 'error');
        }

        return $result;
    }

    /**
     * Send applicant confirmation email.
     *
     * @param int $application_id Application ID.
     * @return bool True on success, false on failure.
     * @since 1.0.0
     */
    public function send_applicant_confirmation($application_id)
    {
        $email = get_post_meta($application_id, 'hiretalent_applicant_email', true);
        if (!$email) {
            return false;
        }

        $subject = get_option('hiretalent_applicant_confirmation_subject', __('Application Received: {job_title}', 'hiretalent'));
        $message = get_option('hiretalent_applicant_confirmation_message', __("Dear {applicant_name},\n\nThank you for applying for the position of {job_title}.\n\nWe have received your application and will review it shortly.\n\nBest regards,\n{site_name}", 'hiretalent'));

        $subject = $this->replace_placeholders($subject, $application_id);
        $message = $this->replace_placeholders($message, $application_id);

        $result = wp_mail($email, $subject, $message);
        $this->log_email($email, $subject, $message, $result);

        if ($result) {
            $this->activity_logger->log($application_id, __('Application confirmation email sent.', 'hiretalent'), 'info');
        } else {
            $this->activity_logger->log($application_id, __('Failed to send application confirmation email.', 'hiretalent'), 'error');
        }

        return $result;
    }

    /**
     * Send status change notification email.
     *
     * @param int    $application_id Application ID.
     * @param string $old_status     Old status.
     * @param string $new_status     New status.
     * @return bool True on success, false on failure.
     * @since 1.0.0
     */
    public function send_status_change_notification($application_id, $old_status, $new_status)
    {
        $email = get_post_meta($application_id, 'hiretalent_applicant_email', true);
        if (!$email) {
            return false;
        }

        $subject = get_option('hiretalent_status_change_subject', __('Application Update: {job_title}', 'hiretalent'));
        $message = get_option('hiretalent_status_change_message', __("Dear {applicant_name},\n\nYour application status for {job_title} has been updated to: {status}.\n\nBest regards,\n{site_name}", 'hiretalent'));

        $subject = $this->replace_placeholders($subject, $application_id, $new_status);
        $message = $this->replace_placeholders($message, $application_id, $new_status);

        $result = wp_mail($email, $subject, $message);
        $this->log_email($email, $subject, $message, $result);

		if ($result) {
			$this->activity_logger->log(
				$application_id,
				sprintf(
					/* translators: %s: New application status label. */
					__('Status change email sent for status: %s', 'hiretalent'),
					$new_status
				),
				'info'
			);
		} else {
			$this->activity_logger->log(
				$application_id,
				sprintf(
					/* translators: %s: New application status label. */
					__('Failed to send status change email for status: %s', 'hiretalent'),
					$new_status
				),
				'error'
			);
		}

        return $result;
    }

    /**
     * Replace placeholders in text.
     *
     * @param string $text           Text with placeholders.
     * @param int    $application_id Application ID.
     * @param string $status         Optional status.
     * @return string Text with placeholders replaced.
     * @since 1.0.0
     */
    private function replace_placeholders($text, $application_id, $status = '')
    {
        $job_id = get_post_meta($application_id, 'hiretalent_job_id', true);
        $applicant_name = get_post_meta($application_id, 'hiretalent_applicant_name', true);
        $job_title = get_the_title($job_id);
        $site_name = get_bloginfo('name');
        $application_url = admin_url('post.php?post=' . $application_id . '&action=edit');

        $replacements = array(
            '{applicant_name}' => $applicant_name,
            '{job_title}' => $job_title,
            '{site_name}' => $site_name,
            '{status}' => $status,
            '{application_url}' => $application_url,
        );

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    /**
     * Log email activity.
     *
     * @param string $to      Recipient email.
     * @param string $subject Email subject.
     * @param string $message Email message.
     * @param bool   $success Whether the email was sent successfully.
     * @since 1.0.0
     */
    private function log_email($to, $subject, $message, $success)
    {
        $log_dir = wp_upload_dir()['basedir'] . '/hiretalent-logs';
        if (!file_exists($log_dir)) {
            wp_mkdir_p($log_dir);
        }

        $log_file = $log_dir . '/email.log';
        $timestamp = current_time('mysql');
        $status = $success ? 'SUCCESS' : 'FAILED';
        $log_entry = "[{$timestamp}] [{$status}] To: {$to} | Subject: {$subject}" . PHP_EOL;

        error_log($log_entry, 3, $log_file);
    }

    /**
     * Prune email logs older than X days.
     *
     * @param int $days Number of days to keep logs.
     * @return int Number of lines removed.
     * @since 1.0.0
     */
    public function prune_logs($days = 15)
    {
        $log_dir = wp_upload_dir()['basedir'] . '/hiretalent-logs';
        $log_file = $log_dir . '/email.log';

        if (!file_exists($log_file)) {
            return 0;
        }

        $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            return 0;
        }

        $cutoff_date = strtotime("-{$days} days");
        $new_lines = array();
        $removed_count = 0;

        foreach ($lines as $line) {
            // Extract timestamp from [Y-m-d H:i:s]
            if (preg_match('/^\[(.*?)\]/', $line, $matches)) {
                $timestamp = strtotime($matches[1]);
                if ($timestamp >= $cutoff_date) {
                    $new_lines[] = $line;
                } else {
                    $removed_count++;
                }
            } else {
                // If format doesn't match, keep it to be safe
                $new_lines[] = $line;
            }
        }

        if ($removed_count > 0) {
            file_put_contents($log_file, implode(PHP_EOL, $new_lines) . PHP_EOL);
        }

        return $removed_count;
    }
}
