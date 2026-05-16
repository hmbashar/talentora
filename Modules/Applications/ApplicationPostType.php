<?php
/**
 * ApplicationPostType.php
 *
 * Registers the talentora_application custom post type.
 *
 * @package Talentora\Modules\Applications
 * @since 1.0.0
 */

namespace Talentora\Modules\Applications;

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
            'name' => _x('Applications', 'Post type general name', 'talentora'),
            'singular_name' => _x('Application', 'Post type singular name', 'talentora'),
            'menu_name' => _x('Applications', 'Admin Menu text', 'talentora'),
            'name_admin_bar' => _x('Application', 'Add New on Toolbar', 'talentora'),
            'add_new' => __('Add New', 'talentora'),
            'add_new_item' => __('Add New Application', 'talentora'),
            'new_item' => __('New Application', 'talentora'),
            'edit_item' => __('View Application', 'talentora'),
            'view_item' => __('View Application', 'talentora'),
            'all_items' => __('Applications', 'talentora'),
            'search_items' => __('Search Applications', 'talentora'),
            'not_found' => __('No applications found.', 'talentora'),
            'not_found_in_trash' => __('No applications found in Trash.', 'talentora'),
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=talentora_job',
            'query_var' => false,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title'),
            'show_in_rest' => false,
        );

        register_post_type('talentora_app', $args);
    }


}
