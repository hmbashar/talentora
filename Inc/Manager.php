<?php
/**
 * Manager.php
 *
 * This file contains the Manager class, which is responsible for handling
 * the initialization of the required configurations and functionalities
 * for Talentora. It ensures the proper setup and coordination of
 * Admin and Frontend managers.
 *
 * @package Talentora\Inc
 * @since 1.0.0
 */

namespace Talentora;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Talentora\Admin\Inc\AdminManager;
use Talentora\Frontend\Inc\FrontendManager;
use Talentora\Modules\Jobs\PostType;
use Talentora\Modules\Jobs\Taxonomies;
use Talentora\Modules\Applications\ApplicationPostType;
use Talentora\Modules\Applications\ApplicationMeta;

/**
 * The manager class for Talentora.
 *
 * This class handles the initialization of the required configurations and functionalities
 * for the Talentora plugin. The class is responsible for coordinating Admin, Frontend,
 * and Module managers.
 *
 * @package Talentora\Inc
 * @since 1.0.0
 */
class Manager
{
    protected $admin_manager;
    protected $frontend_manager;
    protected $post_type;
    protected $taxonomies;
    protected $application_post_type;
    protected $application_meta;

    /**
     * Constructor for the Manager class.
     *
     * This method initializes the Talentora Manager by calling the init method.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initiate the Talentora Manager
     *
     * This method initializes all managers and modules.
     *
     * @since 1.0.0
     */
    public function init()
    {
        // Initialize post type and taxonomies first
        $this->post_type = new PostType();
        $this->taxonomies = new Taxonomies();

        // Initialize application modules
        $this->application_post_type = new ApplicationPostType();
        $this->application_meta = new ApplicationMeta();

        // Initialize admin and frontend managers
        $this->admin_manager = new AdminManager();
        $this->frontend_manager = new FrontendManager();

        // Register daily maintenance hook
        add_action('talentora_daily_event', array($this, 'daily_maintenance'));
    }

    /**
     * Perform daily maintenance tasks.
     *
     * @since 1.0.0
     */
    /**
     * Perform daily maintenance tasks.
     *
     * @since 1.0.0
     */
    public function daily_maintenance()
    {
        $notification_manager = new NotificationManager();
        $notification_manager->prune_logs(15);
        $this->auto_close_jobs();
    }

    /**
     * Automatically close jobs that have passed their deadline.
     *
     * @since 1.0.0
     */
    public function auto_close_jobs()
    {
        $today = current_time('Y-m-d');

        $args = array(
            'post_type' => 'talentora_job',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- meta_query is unavoidable: no WP API alternative exists to filter published posts by a date-type meta field (deadline) during a scheduled cron event.
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'talentora_deadline',
                    'value' => $today,
                    'compare' => '<',
                    'type' => 'DATE',
                ),
                array(
                    'key' => 'talentora_deadline',
                    'value' => '',
                    'compare' => '!=',
                ),
            ),
        );

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $job_id = get_the_ID();
                $status = get_post_meta($job_id, 'talentora_job_status', true);

                // Only close if not already closed or filled
                if ($status !== 'closed' && $status !== 'filled') {
                    update_post_meta($job_id, 'talentora_job_status', 'closed');
                }
            }
            wp_reset_postdata();
        }
    }
}
