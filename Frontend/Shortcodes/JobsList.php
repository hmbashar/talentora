<?php
/**
 * JobsList.php
 *
 * Handles the [talentora_jobs] shortcode.
 *
 * @package Talentora\Frontend\Shortcodes
 * @since 1.0.0
 */

namespace Talentora\Frontend\Shortcodes;

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
        add_shortcode('talentora_jobs', array($this, 'render_jobs_list'));
        add_action('wp_ajax_talentora_filter_jobs', array($this, 'handle_ajax_filter'));
        add_action('wp_ajax_nopriv_talentora_filter_jobs', array($this, 'handle_ajax_filter'));
    }

    /**
     * Handle AJAX job filtering.
     *
     * @since 1.0.0
     */
    public function handle_ajax_filter()
    {
        // Verify nonce
        if (!isset($_POST['nonce'])) {
            wp_send_json_error(esc_html__('Invalid nonce', 'talentora'));
        }

        $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));

        if (!wp_verify_nonce($nonce, 'talentora_filter_nonce')) {
            wp_send_json_error(esc_html__('Invalid nonce', 'talentora'));
        }

        $params = $_POST;
        $query = $this->get_jobs_query($params);

        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_job_card(get_the_ID());
            }

            // Pagination
            $paged = isset($params['paged']) ? absint($params['paged']) : 1;
            echo '<div class="talentora-pagination">';
            echo wp_kses_post(
                paginate_links(
                    array(
                        'total' => $query->max_num_pages,
                        'current' => $paged,
                        /* translators: Pagination previous text. */
                        'prev_text' => esc_html__('&laquo; Previous', 'talentora'),
                        /* translators: Pagination next text. */
                        'next_text' => esc_html__('Next &raquo;', 'talentora'),
                    )
                ) ?? ''
            );
            echo '</div>';
        } else {
            echo '<p class="talentora-no-jobs">' . esc_html__('No jobs found.', 'talentora') . '</p>';
        }
        $content = ob_get_clean();
        wp_reset_postdata();

        wp_send_json_success($content);
    }

    /**
     * Get jobs query based on parameters.
     * 
     * @param array $params Query parameters.
     * @return \WP_Query
     */
    private function get_jobs_query($params)
    {
        $posts_per_page = isset($params['posts_per_page']) ? absint($params['posts_per_page']) : get_option('talentora_jobs_per_page', 10);
        $paged = isset($params['paged']) ? absint($params['paged']) : 1;
        $keyword = isset($params['job_keyword']) ? sanitize_text_field($params['job_keyword']) : '';
        $category = isset($params['job_category']) ? absint($params['job_category']) : 0;
        $type = isset($params['job_type']) ? absint($params['job_type']) : 0;
        $location = isset($params['job_location']) ? sanitize_text_field($params['job_location']) : '';

        // Build query args
        $args = array(
            'post_type' => 'talentora_job',
            'posts_per_page' => $posts_per_page,
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
                'taxonomy' => 'talentora_job_category',
                'field' => 'term_id',
                'terms' => $category,
            );
        }
        if ($type) {
            $tax_query[] = array(
                'taxonomy' => 'talentora_job_type',
                'field' => 'term_id',
                'terms' => $type,
            );
        }
        
        if (!empty($tax_query)) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- tax_query is unavoidable: it is the only WP API to filter posts by taxonomy terms (category/type).
            $args['tax_query'] = $tax_query;
        }

        // Location meta query
        if (!empty($location)) {
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- meta_query is unavoidable: no WP API alternative exists to filter posts by a meta field (location) with a LIKE comparison.
            $args['meta_query'] = array(
                array(
                    'key' => 'talentora_location',
                    'value' => $location,
                    'compare' => 'LIKE',
                ),
            );
        }

        // Apply filter hook
        $args = apply_filters('talentora_jobs_query_args', $args);

        return new \WP_Query($args);
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
            'posts_per_page' => get_option('talentora_jobs_per_page', 10),
        ), $atts);
        // Build params from a whitelist of GET keys (read-only filtering).
        $params = $atts;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filtering (no state change). Inputs are whitelisted and sanitized.
		$keyword = isset( $_GET['job_keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['job_keyword'] ) ) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filtering (no state change). Inputs are whitelisted and sanitized.
		$category = isset( $_GET['job_category'] ) ? absint( wp_unslash( $_GET['job_category'] ) ) : 0;
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filtering (no state change). Inputs are whitelisted and sanitized.
		$type = isset( $_GET['job_type'] ) ? absint( wp_unslash( $_GET['job_type'] ) ) : 0;
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filtering (no state change). Inputs are whitelisted and sanitized.
		$location = isset( $_GET['job_location'] ) ? sanitize_text_field( wp_unslash( $_GET['job_location'] ) ) : '';

		if ( '' !== $keyword ) {
            $params['job_keyword'] = $keyword;
        }
		if ( $category > 0 ) {
            $params['job_category'] = $category;
        }
		if ( $type > 0 ) {
            $params['job_type'] = $type;
        }
		if ( '' !== $location ) {
            $params['job_location'] = $location;
        }

		$params['paged'] = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

		$jobs_query = $this->get_jobs_query( $params );

        ob_start();
        ?>
        <div class="talentora-jobs-wrapper">
            <?php do_action('talentora_before_job_list'); ?>

            <!-- Filter Bar -->
            <div class="talentora-filter-bar">
                <form id="talentora-job-filter-form" method="get" class="talentora-filters">
                    <div class="filter-field">
                        <input type="text" name="job_keyword" placeholder="<?php esc_attr_e('Search jobs...', 'talentora'); ?>"
                            value="<?php echo esc_attr($keyword); ?>">
                    </div>

                    <div class="filter-field">
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'talentora_job_category',
                            'hide_empty' => true,
                        ));
                        if (!empty($categories) && !is_wp_error($categories)):
                            ?>
                            <select name="job_category">
                                <option value="">
                                    <?php esc_html_e('All Categories', 'talentora'); ?>
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
                            'taxonomy' => 'talentora_job_type',
                            'hide_empty' => true,
                        ));
                        if (!empty($types) && !is_wp_error($types)):
                            ?>
                            <select name="job_type">
                                <option value="">
                                    <?php esc_html_e('All Types', 'talentora'); ?>
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
                        <input type="text" name="job_location" placeholder="<?php esc_attr_e('Location', 'talentora'); ?>"
                            value="<?php echo esc_attr($location); ?>">
                    </div>

                    <div class="filter-field">
                        <button type="submit" class="talentora-btn">
                            <?php esc_html_e('Search', 'talentora'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Jobs List -->
            <div class="talentora-jobs-list">
                <?php if ($jobs_query->have_posts()): ?>
                    <?php while ($jobs_query->have_posts()):
                        $jobs_query->the_post(); ?>
                        <?php $this->render_job_card(get_the_ID()); ?>
                    <?php endwhile; ?>

                    <!-- Pagination -->
                    <div class="talentora-pagination">
                        <?php
                        echo wp_kses_post(
                            paginate_links(
                                array(
                                    'total' => $jobs_query->max_num_pages,
                                    'current' => $params['paged'],
                                    /* translators: Pagination previous text. */
                                    'prev_text' => esc_html__('&laquo; Previous', 'talentora'),
                                    /* translators: Pagination next text. */
                                    'next_text' => esc_html__('Next &raquo;', 'talentora'),
                                )
                            ) ?? ''
                        );
                        ?>
                    </div>
                <?php else: ?>
                    <p class="talentora-no-jobs">
                        <?php esc_html_e('No jobs found.', 'talentora'); ?>
                    </p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>

            <?php do_action('talentora_after_job_list'); ?>
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
        $company_name = get_post_meta($job_id, 'talentora_company_name', true);
        $location = get_post_meta($job_id, 'talentora_location', true);
        $deadline = get_post_meta($job_id, 'talentora_deadline', true);
        $is_filled = get_post_meta($job_id, 'talentora_is_filled', true);
        $salary_min = get_post_meta($job_id, 'talentora_salary_min', true);
        $salary_max = get_post_meta($job_id, 'talentora_salary_max', true);
        $company_logo_id = get_post_meta($job_id, 'talentora_company_logo_id', true);

        $job_types = get_the_terms($job_id, 'talentora_job_type');
        $job_type_names = array();
        if ($job_types && !is_wp_error($job_types)) {
            foreach ($job_types as $job_type) {
                $job_type_names[] = $job_type->name;
            }
        }

        ?>
        <div class="talentora-job-card <?php echo $is_filled ? 'job-filled' : ''; ?>">

            <div class="job-card-header">
                <?php if ($company_logo_id): ?>
                    <div class="company-logo-mini">
                        <?php echo wp_get_attachment_image($company_logo_id, 'thumbnail'); ?>
                    </div>
                <?php endif; ?>

                <div class="job-card-title-area">
                    <h3 class="job-title">
                        <a href="<?php echo esc_url(get_permalink($job_id)); ?>">
                            <?php echo esc_html(get_the_title($job_id)); ?>
                        </a>
                    </h3>
                    <?php if ($company_name): ?>
                        <p class="job-company-name">
                            <span class="dashicons dashicons-building"></span>
                            <?php echo esc_html($company_name); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ($is_filled): ?>
                    <span class="job-filled-badge">
                        <span class="dashicons dashicons-yes"></span>
                        <?php esc_html_e('Filled', 'talentora'); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="job-card-body">
                <div class="job-card-meta">
                    <?php if ($location): ?>
                        <span class="job-meta-item">
                            <span class="dashicons dashicons-location"></span>
                            <?php echo esc_html($location); ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($job_type_names)): ?>
                        <span class="job-meta-item">
                            <span class="dashicons dashicons-tag"></span>
                            <?php echo esc_html(implode(', ', $job_type_names)); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($salary_min || $salary_max): ?>
                        <span class="job-meta-item">
                            <span class="dashicons dashicons-money-alt"></span>
                            <?php
                            $currency = get_option('talentora_currency_symbol', '$');
                            if ($salary_min && $salary_max) {
                                echo esc_html($currency . number_format($salary_min) . ' - ' . $currency . number_format($salary_max));
                            } elseif ($salary_min) {
                                echo esc_html($currency . number_format($salary_min) . '+');
                            } else {
                                echo esc_html($currency . number_format($salary_max));
                            }
                            ?>
                        </span>
                    <?php endif; ?>


                    <?php if ($deadline): ?>
                        <span class="job-meta-item">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Deadline:', 'talentora'); ?>
                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($deadline))); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <?php
                $excerpt = get_the_excerpt($job_id);
                if ($excerpt):
                    ?>
                    <p class="job-excerpt"><?php echo esc_html(wp_trim_words($excerpt, 20)); ?></p>
                <?php endif; ?>
            </div>

            <div class="job-card-footer">
                <a href="<?php echo esc_url(get_permalink($job_id)); ?>" class="talentora-btn talentora-btn-primary">
                    <?php esc_html_e('View Details', 'talentora'); ?>
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </a>
            </div>
        </div>
        <?php
    }
}
