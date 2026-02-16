<?php
/**
 * AdminManager.php
 *
 * Coordinates all admin-side functionality.
 *
 * @package HireTalent\Admin\Inc
 * @since 1.0.0
 */

namespace HireTalent\Admin\Inc;

if (!defined('ABSPATH')) {
    exit;
}

use HireTalent\Admin\Metaboxes\JobMetabox;
use HireTalent\Admin\Pages\Settings;
use HireTalent\Admin\Assets\Assets;

/**
 * Admin Manager class.
 */
class AdminManager
{
    protected $job_metabox;
    protected $settings;
    protected $assets;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize admin components.
     *
     * @since 1.0.0
     */
    public function init()
    {
        $this->job_metabox = new JobMetabox();
        $this->settings = new Settings();
        $this->assets = new Assets();

        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    /**
     * Add admin menu items.
     *
     * @since 1.0.0
     */
    public function add_admin_menu()
    {
        // Main menu is already added by the CPT
        // Add Settings submenu
        add_submenu_page(
            'edit.php?post_type=hiretalent_job',
            __('Settings', 'hiretalent'),
            __('Settings', 'hiretalent'),
            'manage_options',
            'hiretalent-settings',
            array($this->settings, 'render_settings_page')
        );
    }
}
