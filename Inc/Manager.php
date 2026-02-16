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
    public function daily_maintenance()
    {
        $notification_manager = new NotificationManager();
        $notification_manager->prune_logs(15);
    }
}
