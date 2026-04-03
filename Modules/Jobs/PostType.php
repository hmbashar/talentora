<?php
/**
 * PostType.php
 *
 * Registers the talentora_job custom post type.
 *
 * @package Talentora\Modules\Jobs
 * @since 1.0.0
 */

namespace Talentora\Modules\Jobs;

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
            'name' => _x('Jobs', 'Post type general name', 'talentora'),
            'singular_name' => _x('Job', 'Post type singular name', 'talentora'),
            'menu_name' => _x('Talentora', 'Admin Menu text', 'talentora'),
            'name_admin_bar' => _x('Job', 'Add New on Toolbar', 'talentora'),
            'add_new' => __('Add New', 'talentora'),
            'add_new_item' => __('Add New Job', 'talentora'),
            'new_item' => __('New Job', 'talentora'),
            'edit_item' => __('Edit Job', 'talentora'),
            'view_item' => __('View Job', 'talentora'),
            'all_items' => __('All Jobs', 'talentora'),
            'search_items' => __('Search Jobs', 'talentora'),
            'parent_item_colon' => __('Parent Jobs:', 'talentora'),
            'not_found' => __('No jobs found.', 'talentora'),
            'not_found_in_trash' => __('No jobs found in Trash.', 'talentora'),
            'featured_image' => _x('Job Featured Image', 'Overrides the "Featured Image" phrase', 'talentora'),
            'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'talentora'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'talentora'),
            'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'talentora'),
            'archives' => _x('Job archives', 'The post type archive label', 'talentora'),
            'insert_into_item' => _x('Insert into job', 'Overrides the "Insert into post" phrase', 'talentora'),
            'uploaded_to_this_item' => _x('Uploaded to this job', 'Overrides the "Uploaded to this post" phrase', 'talentora'),
            'filter_items_list' => _x('Filter jobs list', 'Screen reader text for the filter links', 'talentora'),
            'items_list_navigation' => _x('Jobs list navigation', 'Screen reader text for the pagination', 'talentora'),
            'items_list' => _x('Jobs list', 'Screen reader text for the items list', 'talentora'),
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

        register_post_type('talentora_job', $args);
    }
}
