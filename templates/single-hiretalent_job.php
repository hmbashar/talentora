<?php
/**
 * Single Job Template
 *
 * Template for displaying single job posts.
 * Can be overridden by copying to yourtheme/hiretalent/single-hiretalent_job.php
 *
 * @package HireTalent
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()):
    the_post();
    $job_id = get_the_ID();

    // Get job meta
    $location = get_post_meta($job_id, 'hiretalent_location', true);
    $salary_min = get_post_meta($job_id, 'hiretalent_salary_min', true);
    $salary_max = get_post_meta($job_id, 'hiretalent_salary_max', true);
    $deadline = get_post_meta($job_id, 'hiretalent_deadline', true);
    $company_name = get_post_meta($job_id, 'hiretalent_company_name', true);
    $company_website = get_post_meta($job_id, 'hiretalent_company_website', true);
    $company_logo_id = get_post_meta($job_id, 'hiretalent_company_logo_id', true);
    $is_filled = get_post_meta($job_id, 'hiretalent_is_filled', true);

    // Get taxonomies
    $job_categories = get_the_terms($job_id, 'hiretalent_job_category');
    $job_types = get_the_terms($job_id, 'hiretalent_job_type');
    ?>

    <article id="job-<?php echo esc_attr($job_id); ?>" <?php post_class('hiretalent-single-job'); ?>>
        <div class="hiretalent-job-container">

            <!-- Job Header -->
            <header class="job-header">
                <div class="job-header-content">
                    <h1 class="job-title">
                        <?php the_title(); ?>
                    </h1>

                    <?php if ($is_filled): ?>
                        <span class="job-filled-badge">
                            <?php esc_html_e('Position Filled', 'hiretalent'); ?>
                        </span>
                    <?php endif; ?>

                    <div class="job-meta-info">
                        <?php if ($company_name): ?>
                            <div class="meta-item company-info">
                                <?php if ($company_logo_id): ?>
                                    <div class="company-logo">
                                        <?php echo wp_get_attachment_image($company_logo_id, 'thumbnail'); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="company-details">
                                    <strong>
                                        <?php echo esc_html($company_name); ?>
                                    </strong>
                                    <?php if ($company_website): ?>
                                        <br>
                                        <a href="<?php echo esc_url($company_website); ?>" target="_blank"
                                            rel="noopener noreferrer">
                                            <?php esc_html_e('Visit Website', 'hiretalent'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($location): ?>
                            <div class="meta-item">
                                <span class="dashicons dashicons-location"></span>
                                <strong>
                                    <?php esc_html_e('Location:', 'hiretalent'); ?>
                                </strong>
                                <?php echo esc_html($location); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($salary_min || $salary_max): ?>
                            <div class="meta-item">
                                <span class="dashicons dashicons-money-alt"></span>
                                <strong>
                                    <?php esc_html_e('Salary:', 'hiretalent'); ?>
                                </strong>
                                <?php
                                if ($salary_min && $salary_max) {
                                    echo esc_html(number_format($salary_min) . ' - ' . number_format($salary_max));
                                } elseif ($salary_min) {
                                    echo esc_html(number_format($salary_min) . '+');
                                } else {
                                    echo esc_html(number_format($salary_max));
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($deadline): ?>
                            <div class="meta-item">
                                <span class="dashicons dashicons-calendar-alt"></span>
                                <strong>
                                    <?php esc_html_e('Deadline:', 'hiretalent'); ?>
                                </strong>
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($deadline))); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($job_types && !is_wp_error($job_types)): ?>
                            <div class="meta-item">
                                <span class="dashicons dashicons-tag"></span>
                                <strong>
                                    <?php esc_html_e('Job Type:', 'hiretalent'); ?>
                                </strong>
                                <?php
                                $type_names = array();
                                foreach ($job_types as $type) {
                                    $type_names[] = $type->name;
                                }
                                echo esc_html(implode(', ', $type_names));
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($job_categories && !is_wp_error($job_categories)): ?>
                            <div class="meta-item">
                                <span class="dashicons dashicons-category"></span>
                                <strong>
                                    <?php esc_html_e('Category:', 'hiretalent'); ?>
                                </strong>
                                <?php
                                $cat_names = array();
                                foreach ($job_categories as $cat) {
                                    $cat_names[] = $cat->name;
                                }
                                echo esc_html(implode(', ', $cat_names));
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Job Description -->
            <div class="job-description">
                <h2>
                    <?php esc_html_e('Job Description', 'hiretalent'); ?>
                </h2>
                <?php the_content(); ?>
            </div>

            <!-- Apply Section -->
            <?php if (!$is_filled): ?>
                <div class="job-apply-section">
                    <?php echo do_shortcode('[hiretalent_apply_form]'); ?>
                </div>
            <?php else: ?>
                <div class="job-filled-notice">
                    <p>
                        <?php esc_html_e('This position has been filled. Thank you for your interest!', 'hiretalent'); ?>
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </article>

    <?php
endwhile;

get_footer();
