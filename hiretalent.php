<?php
/**
 * Plugin Name: HireTalent – Simple & Powerful Job Board Plugin
 * Plugin URI: https://github.com/hmbashar/hiretalent
 * Description: A simple yet powerful job board plugin for WordPress. Post jobs, manage applications, and help employers find the right talent.
 * Version: 1.0.0
 * Author: Md Abul Bashar
 * Author URI: https://github.com/hmbashar
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hiretalent
 * Domain Path: /languages
 * Namespace: HireTalent
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

final class HireTalent
{
    // Singleton instance.
    private static $instance = null;

    /**
     * Initializes the HireTalent class by defining constants, including necessary files, and initializing hooks.
     */
    private function __construct()
    {
        $this->define_constants();
        $this->include_files();
        $this->init_hooks();
    }

    /**
     * Retrieves the singleton instance of the plugin.
     *
     * @return HireTalent The singleton instance of the plugin.
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Defines plugin constants.
     */
    private function define_constants()
    {
        // Define Plugin Version.
        define('HIRETALENT_VERSION', '1.0.0');

        // Define Plugin Path.
        define('HIRETALENT_PATH', plugin_dir_path(__FILE__));

        // Define Plugin URL.
        define('HIRETALENT_URL', plugin_dir_url(__FILE__));

        // Define Plugin Basename.
        define('HIRETALENT_BASENAME', plugin_basename(__FILE__));

        // Define Plugin File.
        define('HIRETALENT_FILE', __FILE__);

        // Define Plugin Name.
        define('HIRETALENT_NAME', 'HireTalent');
    }

    /**
     * Includes necessary files.
     */
    private function include_files()
    {
        if (file_exists(HIRETALENT_PATH . 'vendor/autoload.php')) {
            require_once HIRETALENT_PATH . 'vendor/autoload.php';
        }
    }

    /**
     * Initializes hooks.
     */
    private function init_hooks()
    {
        add_action('plugins_loaded', array($this, 'plugin_loaded'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Called when the plugin is loaded.
     */
    public function plugin_loaded()
    {
        if (class_exists('HireTalent\\Manager')) {
            new \HireTalent\Manager();
        }
    }

    /**
     * Activates the plugin.
     */
    public function activate()
    {
        HireTalent\Activate::activate();
    }

    /**
     * Deactivates the plugin.
     */
    public function deactivate()
    {
        HireTalent\Deactivate::deactivate();
    }
}

/**
 * Initializes the HireTalent plugin.
 */
if (!function_exists('hiretalent_initialize')) {
    function hiretalent_initialize()
    {
        return HireTalent::get_instance();
    }

    hiretalent_initialize();
}
