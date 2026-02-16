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


}
