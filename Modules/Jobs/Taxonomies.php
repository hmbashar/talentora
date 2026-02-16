<?php
/**
 * Taxonomies.php
 *
 * Registers job taxonomies (category and type).
 *
 * @package HireTalent\Modules\Jobs
 * @since 1.0.0
 */

namespace HireTalent\Modules\Jobs;

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
            'name' => _x('Job Categories', 'taxonomy general name', 'hiretalent'),
            'singular_name' => _x('Job Category', 'taxonomy singular name', 'hiretalent'),
            'search_items' => __('Search Job Categories', 'hiretalent'),
            'all_items' => __('All Job Categories', 'hiretalent'),
            'parent_item' => __('Parent Job Category', 'hiretalent'),
            'parent_item_colon' => __('Parent Job Category:', 'hiretalent'),
            'edit_item' => __('Edit Job Category', 'hiretalent'),
            'update_item' => __('Update Job Category', 'hiretalent'),
            'add_new_item' => __('Add New Job Category', 'hiretalent'),
            'new_item_name' => __('New Job Category Name', 'hiretalent'),
            'menu_name' => __('Job Categories', 'hiretalent'),
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

        register_taxonomy('hiretalent_job_category', array('hiretalent_job'), $args);
    }

    /**
     * Register job type taxonomy.
     *
     * @since 1.0.0
     */
    private function register_job_type()
    {
        $labels = array(
            'name' => _x('Job Types', 'taxonomy general name', 'hiretalent'),
            'singular_name' => _x('Job Type', 'taxonomy singular name', 'hiretalent'),
            'search_items' => __('Search Job Types', 'hiretalent'),
            'all_items' => __('All Job Types', 'hiretalent'),
            'parent_item' => __('Parent Job Type', 'hiretalent'),
            'parent_item_colon' => __('Parent Job Type:', 'hiretalent'),
            'edit_item' => __('Edit Job Type', 'hiretalent'),
            'update_item' => __('Update Job Type', 'hiretalent'),
            'add_new_item' => __('Add New Job Type', 'hiretalent'),
            'new_item_name' => __('New Job Type Name', 'hiretalent'),
            'menu_name' => __('Job Types', 'hiretalent'),
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

        register_taxonomy('hiretalent_job_type', array('hiretalent_job'), $args);
    }
}
