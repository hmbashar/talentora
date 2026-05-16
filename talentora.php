<?php
/**
 * Plugin Name: Talentora – Simple & Powerful Job Board
 * Plugin URI: https://github.com/hmbashar/talentora
 * Description: A simple yet powerful job board plugin for WordPress. Post jobs, manage applications, and help employers find the right talent.
 * Version: 0.0.1
 * Author: Md Abul Bashar
 * Author URI: https://github.com/hmbashar
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: talentora
 * Domain Path: /languages
 * Namespace: Talentora
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

final class Talentora
{
    // Singleton instance.
    private static $instance = null;

    /**
     * Initializes the Talentora class by defining constants, including necessary files, and initializing hooks.
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
     * @return Talentora The singleton instance of the plugin.
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
        define('TALENTORA_VERSION', '0.0.1');

        // Define Plugin Path.
        define('TALENTORA_PATH', plugin_dir_path(__FILE__));

        // Define Plugin URL.
        define('TALENTORA_URL', plugin_dir_url(__FILE__));

        // Define Plugin Basename.
        define('TALENTORA_BASENAME', plugin_basename(__FILE__));

        // Define Plugin File.
        define('TALENTORA_FILE', __FILE__);

        // Define Plugin Name.
        define('TALENTORA_NAME', 'Talentora');
    }

    /**
     * Includes necessary files.
     */
    private function include_files()
    {
        if (file_exists(TALENTORA_PATH . 'vendor/autoload.php')) {
            require_once TALENTORA_PATH . 'vendor/autoload.php';
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
        if (class_exists('Talentora\\Manager')) {
            new \Talentora\Manager();
        }
    }

    /**
     * Activates the plugin.
     */
    public function activate()
    {
        Talentora\Activate::activate();
    }

    /**
     * Deactivates the plugin.
     */
    public function deactivate()
    {
        Talentora\Deactivate::deactivate();
    }
}

/**
 * Initializes the Talentora plugin.
 */
if (!function_exists('talentora_initialize')) {
    function talentora_initialize()
    {
        return Talentora::get_instance();
    }

    talentora_initialize();
}
