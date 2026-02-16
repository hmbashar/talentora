<?php
/**
 * JobsList.php
 *
 * Handles the [hiretalent_jobs] shortcode.
 *
 * @package HireTalent\Frontend\Shortcodes
 * @since 1.0.0
 */

namespace HireTalent\Frontend\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Jobs List Shortcode class.
 */
class JobsList
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_shortcode('hiretalent_jobs', array($this, 'render_jobs_list'));
    }

    /**
     * Render jobs list shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     * @since 1.0.0
     */
    public function render_jobs_list($atts)
    {
        $atts = shortcode_atts(array(
            'posts_per_page' => get_option('hiretalent_jobs_per_page', 10),
        ), $atts);

        ob_start();

        // Get filter parameters
        $keyword = isset($_GET['job_keyword']) ? sanitize_text_field($_GET['job_keyword']) : '';
        $category = isset($_GET['job_category']) ? absint($_GET['job_category']) : 0;
        $type = isset($_GET['job_type']) ? absint($_GET['job_type']) : 0;
        $location = isset($_GET['job_location']) ? sanitize_text_field($_GET['job_location']) : '';
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        // Build query args
        $args = array(
            'post_type' => 'hiretalent_job',
            'posts_per_page' => absint($atts['posts_per_page']),
            'paged' => $paged,
            'post_status' => 'publish',
        );

        // Keyword search
        if (!empty($keyword)) {
            $args['s'] = $keyword;
        }

        // Tax query
        $tax_query = array();
        if ($category) {
            $tax_query[] = array(
                'taxonomy' => 'hiretalent_job_category',
                'field' => 'term_id',
                'terms' => $category,
            );
        }
        if ($type) {
            $tax_query[] = array(
                'taxonomy' => 'hiretalent_job_type',
                'field' => 'term_id',
                'terms' => $type,
            );
        }
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        // Location meta query
        if (!empty($location)) {
            $args['meta_query'] = array(
                array(
                    'key' => 'hiretalent_location',
                    'value' => $location,
                    'compare' => 'LIKE',
                ),
            );
        }

        // Apply filter hook
        $args = apply_filters('hiretalent_jobs_query_args', $args);

        $jobs_query = new \WP_Query($args);

        ?>
        <div class="hiretalent-jobs-wrapper">
            <?php do_action('hiretalent_before_job_list'); ?>

            <!-- Filter Bar -->
            <div class="hiretalent-filter-bar">
                <form method="get" class="hiretalent-filters">
                    <div class="filter-field">
                        <input type="text" name="job_keyword" placeholder="<?php esc_attr_e('Search jobs...', 'hiretalent'); ?>"
                            value="<?php echo esc_attr($keyword); ?>">
                    </div>

                    <div class="filter-field">
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'hiretalent_job_category',
                            'hide_empty' => true,
                        ));
                        if (!empty($categories) && !is_wp_error($categories)):
                            ?>
                            <select name="job_category">
                                <option value="">
                                    <?php esc_html_e('All Categories', 'hiretalent'); ?>
                                </option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected($category, $cat->term_id); ?>>
                                        <?php echo esc_html($cat->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="filter-field">
                        <?php
                        $types = get_terms(array(
                            'taxonomy' => 'hiretalent_job_type',
                            'hide_empty' => true,
                        ));
                        if (!empty($types) && !is_wp_error($types)):
                            ?>
                            <select name="job_type">
                                <option value="">
                                    <?php esc_html_e('All Types', 'hiretalent'); ?>
                                </option>
                                <?php foreach ($types as $job_type): ?>
                                    <option value="<?php echo esc_attr($job_type->term_id); ?>" <?php selected($type, $job_type->term_id); ?>>
                                        <?php echo esc_html($job_type->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="filter-field">
                        <input type="text" name="job_location" placeholder="<?php esc_attr_e('Location', 'hiretalent'); ?>"
                            value="<?php echo esc_attr($location); ?>">
                    </div>

                    <div class="filter-field">
                        <button type="submit" class="hiretalent-btn">
                            <?php esc_html_e('Search', 'hiretalent'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Jobs List -->
            <div class="hiretalent-jobs-list">
                <?php if ($jobs_query->have_posts()): ?>
                    <?php while ($jobs_query->have_posts()):
                        $jobs_query->the_post(); ?>
                        <?php $this->render_job_card(get_the_ID()); ?>
                    <?php endwhile; ?>

                    <!-- Pagination -->
                    <div class="hiretalent-pagination">
                        <?php
                        echo paginate_links(array(
                            'total' => $jobs_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('&laquo; Previous', 'hiretalent'),
                            'next_text' => __('Next &raquo;', 'hiretalent'),
                        ));
                        ?>
                    </div>
                <?php else: ?>
                    <p class="hiretalent-no-jobs">
                        <?php esc_html_e('No jobs found.', 'hiretalent'); ?>
                    </p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>

            <?php do_action('hiretalent_after_job_list'); ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Render individual job card.
     *
     * @param int $job_id Job post ID.
     * @since 1.0.0
     */
    private function render_job_card($job_id)
    {
        $company_name = get_post_meta($job_id, 'hiretalent_company_name', true);
        $location = get_post_meta($job_id, 'hiretalent_location', true);
        $deadline = get_post_meta($job_id, 'hiretalent_deadline', true);
        $is_filled = get_post_meta($job_id, 'hiretalent_is_filled', true);

        $job_types = get_the_terms($job_id, 'hiretalent_job_type');
        $job_type_names = array();
        if ($job_types && !is_wp_error($job_types)) {
            foreach ($job_types as $job_type) {
                $job_type_names[] = $job_type->name;
            }
        }

        ?>
        <div class="hiretalent-job-card <?php echo $is_filled ? 'job-filled' : ''; ?>">
            <div class="job-card-header">
                <h3 class="job-title">
                    <a href="<?php echo esc_url(get_permalink($job_id)); ?>">
                        <?php echo esc_html(get_the_title($job_id)); ?>
                    </a>
                </h3>
                <?php if ($is_filled): ?>
                    <span class="job-filled-badge">
                        <?php esc_html_e('Filled', 'hiretalent'); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="job-card-meta">
                <?php if ($company_name): ?>
                    <span class="job-company">
                        <i class="dashicons dashicons-building"></i>
                        <?php echo esc_html($company_name); ?>
                    </span>
                <?php endif; ?>

                <?php if ($location): ?>
                    <span class="job-location">
                        <i class="dashicons dashicons-location"></i>
                        <?php echo esc_html($location); ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($job_type_names)): ?>
                    <span class="job-type">
                        <i class="dashicons dashicons-tag"></i>
                        <?php echo esc_html(implode(', ', $job_type_names)); ?>
                    </span>
                <?php endif; ?>

                <?php if ($deadline): ?>
                    <span class="job-deadline">
                        <i class="dashicons dashicons-calendar-alt"></i>
                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($deadline))); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="job-card-footer">
                <a href="<?php echo esc_url(get_permalink($job_id)); ?>" class="hiretalent-btn">
                    <?php esc_html_e('View Details', 'hiretalent'); ?>
                </a>
            </div>
        </div>
        <?php
    }
}
