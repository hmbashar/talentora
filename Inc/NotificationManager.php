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
     * Send admin notification email.
     *
     * @param int $application_id Application ID.
     * @return bool True on success, false on failure.
     * @since 1.0.0
     */
    public function send_admin_notification($application_id)
    {
        $admin_email = get_option('admin_email');
        $subject = get_option('hiretalent_admin_notification_subject', __('[%site_name] New Job Application: {job_title}', 'hiretalent'));
        $message = get_option('hiretalent_admin_notification_message', __("You have received a new job application.\n\nJob: {job_title}\nApplicant: {applicant_name}\n\nView application: {application_url}", 'hiretalent'));

        $subject = $this->replace_placeholders($subject, $application_id);
        $message = $this->replace_placeholders($message, $application_id);

        return wp_mail($admin_email, $subject, $message);
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

        return wp_mail($email, $subject, $message);
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

        return wp_mail($email, $subject, $message);
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
}
