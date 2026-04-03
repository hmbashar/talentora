<?php
/**
 * Taxonomies.php
 *
 * Registers job taxonomies (category and type).
 *
 * @package Talentora\Modules\Jobs
 * @since 1.0.0
 */

namespace Talentora\Modules\Jobs;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Job Taxonomies class.
 */
class Taxonomies
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('init', array($this, 'register_taxonomies'));
    }

    /**
     * Register job taxonomies.
     *
     * @since 1.0.0
     */
    public function register_taxonomies()
    {
        $this->register_job_category();
        $this->register_job_type();
    }

    /**
     * Register job category taxonomy.
     *
     * @since 1.0.0
     */
    private function register_job_category()
    {
        $labels = array(
            'name' => _x('Job Categories', 'taxonomy general name', 'talentora'),
            'singular_name' => _x('Job Category', 'taxonomy singular name', 'talentora'),
            'search_items' => __('Search Job Categories', 'talentora'),
            'all_items' => __('All Job Categories', 'talentora'),
            'parent_item' => __('Parent Job Category', 'talentora'),
            'parent_item_colon' => __('Parent Job Category:', 'talentora'),
            'edit_item' => __('Edit Job Category', 'talentora'),
            'update_item' => __('Update Job Category', 'talentora'),
            'add_new_item' => __('Add New Job Category', 'talentora'),
            'new_item_name' => __('New Job Category Name', 'talentora'),
            'menu_name' => __('Job Categories', 'talentora'),
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'job-category'),
            'show_in_rest' => true,
        );

        register_taxonomy('talentora_job_category', array('talentora_job'), $args);
    }

    /**
     * Register job type taxonomy.
     *
     * @since 1.0.0
     */
    private function register_job_type()
    {
        $labels = array(
            'name' => _x('Job Types', 'taxonomy general name', 'talentora'),
            'singular_name' => _x('Job Type', 'taxonomy singular name', 'talentora'),
            'search_items' => __('Search Job Types', 'talentora'),
            'all_items' => __('All Job Types', 'talentora'),
            'parent_item' => __('Parent Job Type', 'talentora'),
            'parent_item_colon' => __('Parent Job Type:', 'talentora'),
            'edit_item' => __('Edit Job Type', 'talentora'),
            'update_item' => __('Update Job Type', 'talentora'),
            'add_new_item' => __('Add New Job Type', 'talentora'),
            'new_item_name' => __('New Job Type Name', 'talentora'),
            'menu_name' => __('Job Types', 'talentora'),
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'job-type'),
            'show_in_rest' => true,
        );

        register_taxonomy('talentora_job_type', array('talentora_job'), $args);
    }
}
