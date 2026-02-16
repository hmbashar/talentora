<?php
/**
 * PostType.php
 *
 * Registers the hiretalent_job custom post type.
 *
 * @package HireTalent\Modules\Jobs
 * @since 1.0.0
 */

namespace HireTalent\Modules\Jobs;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Job Post Type class.
 */
class PostType
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
     * Register the job custom post type.
     *
     * @since 1.0.0
     */
    public function register_post_type()
    {
        $labels = array(
            'name' => _x('Jobs', 'Post type general name', 'hiretalent'),
            'singular_name' => _x('Job', 'Post type singular name', 'hiretalent'),
            'menu_name' => _x('HireTalent', 'Admin Menu text', 'hiretalent'),
            'name_admin_bar' => _x('Job', 'Add New on Toolbar', 'hiretalent'),
            'add_new' => __('Add New', 'hiretalent'),
            'add_new_item' => __('Add New Job', 'hiretalent'),
            'new_item' => __('New Job', 'hiretalent'),
            'edit_item' => __('Edit Job', 'hiretalent'),
            'view_item' => __('View Job', 'hiretalent'),
            'all_items' => __('All Jobs', 'hiretalent'),
            'search_items' => __('Search Jobs', 'hiretalent'),
            'parent_item_colon' => __('Parent Jobs:', 'hiretalent'),
            'not_found' => __('No jobs found.', 'hiretalent'),
            'not_found_in_trash' => __('No jobs found in Trash.', 'hiretalent'),
            'featured_image' => _x('Job Featured Image', 'Overrides the "Featured Image" phrase', 'hiretalent'),
            'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'hiretalent'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'hiretalent'),
            'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'hiretalent'),
            'archives' => _x('Job archives', 'The post type archive label', 'hiretalent'),
            'insert_into_item' => _x('Insert into job', 'Overrides the "Insert into post" phrase', 'hiretalent'),
            'uploaded_to_this_item' => _x('Uploaded to this job', 'Overrides the "Uploaded to this post" phrase', 'hiretalent'),
            'filter_items_list' => _x('Filter jobs list', 'Screen reader text for the filter links', 'hiretalent'),
            'items_list_navigation' => _x('Jobs list navigation', 'Screen reader text for the pagination', 'hiretalent'),
            'items_list' => _x('Jobs list', 'Screen reader text for the items list', 'hiretalent'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'job',
                'with_front' => false,
            ),
            'capability_type' => 'post',
            'has_archive' => 'jobs',
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-businessman',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
        );

        register_post_type('hiretalent_job', $args);
    }
}
