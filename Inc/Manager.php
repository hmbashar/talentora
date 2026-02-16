<?php
/**
 * Manager.php
 *
 * This file contains the Manager class, which is responsible for handling
 * the initialization of the required configurations and functionalities
 * for HireTalent. It ensures the proper setup and coordination of
 * Admin and Frontend managers.
 *
 * @package HireTalent\Inc
 * @since 1.0.0
 */

namespace HireTalent;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use HireTalent\Admin\Inc\AdminManager;
use HireTalent\Frontend\Inc\FrontendManager;
use HireTalent\Modules\Jobs\PostType;
use HireTalent\Modules\Jobs\Taxonomies;
use HireTalent\Modules\Applications\ApplicationPostType;
use HireTalent\Modules\Applications\ApplicationMeta;

/**
 * The manager class for HireTalent.
 *
 * This class handles the initialization of the required configurations and functionalities
 * for the HireTalent plugin. The class is responsible for coordinating Admin, Frontend,
 * and Module managers.
 *
 * @package HireTalent\Inc
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
     * This method initializes the HireTalent Manager by calling the init method.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initiate the HireTalent Manager
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
        add_action('hiretalent_daily_event', array($this, 'daily_maintenance'));
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
        $today = date('Y-m-d');

        $args = array(
            'post_type' => 'hiretalent_job',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'hiretalent_deadline',
                    'value' => $today,
                    'compare' => '<',
                    'type' => 'DATE',
                ),
                array(
                    'key' => 'hiretalent_deadline',
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
                $status = get_post_meta($job_id, 'hiretalent_job_status', true);

                // Only close if not already closed or filled
                if ($status !== 'closed' && $status !== 'filled') {
                    update_post_meta($job_id, 'hiretalent_job_status', 'closed');
                }
            }
            wp_reset_postdata();
        }
    }
}
