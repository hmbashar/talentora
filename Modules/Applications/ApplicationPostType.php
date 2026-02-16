<?php
/**
 * ApplicationPostType.php
 *
 * Registers the hiretalent_application custom post type.
 *
 * @package HireTalent\Modules\Applications
 * @since 1.0.0
 */

namespace HireTalent\Modules\Applications;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Application Post Type class.
 */
class ApplicationPostType
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_status_taxonomy'));
    }

    /**
     * Register the application custom post type.
     *
     * @since 1.0.0
     */
    public function register_post_type()
    {
        $labels = array(
            'name' => _x('Applications', 'Post type general name', 'hiretalent'),
            'singular_name' => _x('Application', 'Post type singular name', 'hiretalent'),
            'menu_name' => _x('Applications', 'Admin Menu text', 'hiretalent'),
            'name_admin_bar' => _x('Application', 'Add New on Toolbar', 'hiretalent'),
            'add_new' => __('Add New', 'hiretalent'),
            'add_new_item' => __('Add New Application', 'hiretalent'),
            'new_item' => __('New Application', 'hiretalent'),
            'edit_item' => __('View Application', 'hiretalent'),
            'view_item' => __('View Application', 'hiretalent'),
            'all_items' => __('Applications', 'hiretalent'),
            'search_items' => __('Search Applications', 'hiretalent'),
            'not_found' => __('No applications found.', 'hiretalent'),
            'not_found_in_trash' => __('No applications found in Trash.', 'hiretalent'),
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=hiretalent_job',
            'query_var' => false,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title'),
            'show_in_rest' => false,
        );

        register_post_type('hiretalent_app', $args);
    }

    /**
     * Register application status taxonomy.
     *
     * @since 1.0.0
     */
    public function register_status_taxonomy()
    {
        $labels = array(
            'name' => _x('Application Status', 'taxonomy general name', 'hiretalent'),
            'singular_name' => _x('Status', 'taxonomy singular name', 'hiretalent'),
            'search_items' => __('Search Statuses', 'hiretalent'),
            'all_items' => __('All Statuses', 'hiretalent'),
            'edit_item' => __('Edit Status', 'hiretalent'),
            'update_item' => __('Update Status', 'hiretalent'),
            'add_new_item' => __('Add New Status', 'hiretalent'),
            'new_item_name' => __('New Status Name', 'hiretalent'),
            'menu_name' => __('Status', 'hiretalent'),
        );

        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => false,
            'rewrite' => false,
            'show_in_rest' => false,
        );

        register_taxonomy('hiretalent_app_status', array('hiretalent_app'), $args);

        // Add default statuses
        $this->create_default_statuses();
    }

    /**
     * Create default application statuses.
     *
     * @since 1.0.0
     */
    private function create_default_statuses()
    {
        $statuses = array(
            'pending' => __('Pending', 'hiretalent'),
            'reviewed' => __('Reviewed', 'hiretalent'),
            'shortlist' => __('Shortlisted', 'hiretalent'),
            'rejected' => __('Rejected', 'hiretalent'),
        );

        foreach ($statuses as $slug => $name) {
            if (!term_exists($slug, 'hiretalent_app_status')) {
                wp_insert_term($name, 'hiretalent_app_status', array('slug' => $slug));
            }
        }
    }
}
