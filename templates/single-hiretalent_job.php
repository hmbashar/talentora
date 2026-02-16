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
    $experience = get_post_meta($job_id, 'hiretalent_experience', true);
    $vacancy = get_post_meta($job_id, 'hiretalent_vacancy', true);
    $working_hours = get_post_meta($job_id, 'hiretalent_working_hours', true);
    $working_days = get_post_meta($job_id, 'hiretalent_working_days', true);
    $joining_date = get_post_meta($job_id, 'hiretalent_joining_date', true);
    $company_name = get_post_meta($job_id, 'hiretalent_company_name', true);
    $company_website = get_post_meta($job_id, 'hiretalent_company_website', true);
    $company_logo_id = get_post_meta($job_id, 'hiretalent_company_logo_id', true);
    $is_filled = get_post_meta($job_id, 'hiretalent_is_filled', true);

    // Get taxonomies
    $job_categories = get_the_terms($job_id, 'hiretalent_job_category');
    $job_types = get_the_terms($job_id, 'hiretalent_job_type');
    ?>

    <article id="job-<?php echo esc_attr($job_id); ?>" <?php post_class('hiretalent-single-job'); ?>>

        <!-- Job Hero Header -->
        <div class="job-hero-header">
            <div class="job-hero-container">
                <div class="job-hero-content">
                    <?php if ($is_filled): ?>
                        <span class="job-status-badge filled">
                            <span class="dashicons dashicons-saved"></span>
                            <?php esc_html_e('Position Filled', 'hiretalent'); ?>
                        </span>
                    <?php else: ?>
                        <span class="job-status-badge active">
                            <span class="dashicons dashicons-star-filled"></span>
                            <?php esc_html_e('Now Hiring', 'hiretalent'); ?>
                        </span>
                    <?php endif; ?>

                    <h1 class="job-hero-title"><?php the_title(); ?></h1>

                    <div class="job-hero-meta">
                        <?php if ($company_name): ?>
                            <div class="hero-meta-item primary">
                                <span class="dashicons dashicons-building"></span>
                                <span><?php echo esc_html($company_name); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($location): ?>
                            <div class="hero-meta-item">
                                <span class="dashicons dashicons-location"></span>
                                <span><?php echo esc_html($location); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($job_types && !is_wp_error($job_types)): ?>
                            <div class="hero-meta-item">
                                <span class="dashicons dashicons-tag"></span>
                                <span><?php echo esc_html($job_types[0]->name); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="hero-meta-item">
                            <span class="dashicons dashicons-clock"></span>
                            <span>
                                <?php
                                printf(
                                    esc_html__('Posted %s ago', 'hiretalent'),
                                    human_time_diff(get_the_time('U'), current_time('timestamp'))
                                );
                                ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($company_website): ?>
                        <a href="<?php echo esc_url($company_website); ?>" target="_blank" rel="noopener noreferrer"
                            class="company-website-link">
                            <span class="dashicons dashicons-admin-links"></span>
                            <?php esc_html_e('Visit Company Website', 'hiretalent'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="hiretalent-job-container">
            <div class="job-content-grid">

                <!-- Left Column: Main Content -->
                <div class="job-main-content">

                    <!-- Key Information Cards -->
                    <div class="job-info-cards">
                        <?php if ($salary_min || $salary_max): ?>
                            <div class="info-card salary-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-money-alt"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Salary Range', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $currency = get_option('hiretalent_currency_symbol', '$');
                                        if ($salary_min && $salary_max) {
                                            echo esc_html($currency . number_format($salary_min) . ' - ' . $currency . number_format($salary_max));
                                        } elseif ($salary_min) {
                                            echo esc_html($currency . number_format($salary_min) . '+');
                                        } else {
                                            echo esc_html($currency . number_format($salary_max));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($deadline): ?>
                            <div class="info-card deadline-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Application Deadline', 'hiretalent'); ?>
                                    </div>
                                    <div class="info-card-value">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($deadline))); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($job_types && !is_wp_error($job_types)): ?>
                            <div class="info-card type-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-businessman"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Employment Type', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $type_names = array();
                                        foreach ($job_types as $type) {
                                            $type_names[] = $type->name;
                                        }
                                        echo esc_html(implode(', ', $type_names));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>



                        <?php if ($joining_date): ?>
                            <div class="info-card joining-date-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-calendar"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Joining Date', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($joining_date))); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($job_categories && !is_wp_error($job_categories)): ?>
                            <div class="info-card category-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-category"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Category', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $cat_names = array();
                                        foreach ($job_categories as $cat) {
                                            $cat_names[] = $cat->name;
                                        }
                                        echo esc_html(implode(', ', $cat_names));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Job Description Section -->
                    <div class="job-description-section">
                        <h2 class="content-section-title">
                            <span class="dashicons dashicons-text-page"></span>
                            <?php esc_html_e('Job Description', 'hiretalent'); ?>
                        </h2>
                        <div class="description-content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Application Section -->
                    <?php if (!$is_filled): ?>
                        <div class="job-apply-section" id="apply-section">
                            <h2 class="content-section-title">
                                <span class="dashicons dashicons-admin-users"></span>
                                <?php esc_html_e('Apply for this Position', 'hiretalent'); ?>
                            </h2>
                            <p class="apply-subtitle">
                                <?php esc_html_e('Ready to take the next step? Submit your application below.', 'hiretalent'); ?>
                            </p>
                            <div class="apply-form-wrapper">
                                <?php echo do_shortcode('[hiretalent_apply_form]'); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="job-filled-notice">
                            <div class="filled-notice-icon">
                                <span class="dashicons dashicons-saved"></span>
                            </div>
                            <div class="filled-notice-content">
                                <h3><?php esc_html_e('Position Filled', 'hiretalent'); ?></h3>
                                <p><?php esc_html_e('This position has been filled. Thank you for your interest! Check out our other openings.', 'hiretalent'); ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column: Sidebar -->
                <aside class="job-sidebar">

                    <!-- Company Logo/QR Code Card -->
                    <?php if ($company_logo_id): ?>
                        <div class="sidebar-card company-card">
                            <div class="company-logo-wrapper">
                                <?php echo wp_get_attachment_image($company_logo_id, 'medium', false, array('class' => 'company-logo-large')); ?>
                            </div>
                            <?php if ($company_name): ?>
                                <h3 class="company-name"><?php echo esc_html($company_name); ?></h3>
                            <?php endif; ?>
                            <?php if ($company_website): ?>
                                <a href="<?php echo esc_url($company_website); ?>" target="_blank" rel="noopener noreferrer"
                                    class="company-website-btn">
                                    <span class="dashicons dashicons-external"></span>
                                    <?php esc_html_e('Company Website', 'hiretalent'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Quick Actions Card -->
                    <div class="sidebar-card actions-card">
                        <h3 class="sidebar-card-title">
                            <span class="dashicons dashicons-admin-tools"></span>
                            <?php esc_html_e('Quick Actions', 'hiretalent'); ?>
                        </h3>
                        <div class="quick-actions">
                            <?php if (!$is_filled): ?>
                                <a href="#apply-section" class="action-btn primary">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <?php esc_html_e('Apply Now', 'hiretalent'); ?>
                                </a>
                            <?php endif; ?>
                            <button class="action-btn secondary" onclick="window.print()">
                                <span class="dashicons dashicons-printer"></span>
                                <?php esc_html_e('Print Job', 'hiretalent'); ?>
                            </button>
                            <button class="action-btn secondary"
                                onclick="navigator.share ? navigator.share({title: '<?php echo esc_js(get_the_title()); ?>', url: window.location.href}) : alert('<?php esc_html_e('Copy this URL to share', 'hiretalent'); ?>')">
                                <span class="dashicons dashicons-share"></span>
                                <?php esc_html_e('Share Job', 'hiretalent'); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="sidebar-card summary-card">
                        <h3 class="sidebar-card-title">
                            <?php esc_html_e('Job Summary:', 'hiretalent'); ?>
                        </h3>
                        <div class="summary-items">
                            <?php if ($location): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-location"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Location', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($location); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($salary_min || $salary_max): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-money-alt"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Salary', 'hiretalent'); ?></span>
                                        <span class="summary-value">
                                            <?php
                                            $currency = get_option('hiretalent_currency_symbol', '$');
                                            if ($salary_min && $salary_max) {
                                                echo esc_html($currency . number_format($salary_min) . ' - ' . $currency . number_format($salary_max));
                                            } elseif ($salary_min) {
                                                echo esc_html('From ' . $currency . number_format($salary_min));
                                            } else {
                                                echo esc_html('Up to ' . $currency . number_format($salary_max));
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($job_types && !is_wp_error($job_types)): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-portfolio"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Job Type', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($job_types[0]->name); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($experience): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-welcome-learn-more"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Experience', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($experience); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($working_hours): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-clock"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Working Hours', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($working_hours); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($working_days): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar-alt"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Working Days', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($working_days); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($vacancy): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-groups"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Vacancy', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($vacancy); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($deadline): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span
                                            class="summary-label"><?php esc_html_e('Last Date of Submission', 'hiretalent'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html(date_i18n('Y-m-d', strtotime($deadline))); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="summary-item">
                                <div class="summary-icon">
                                    <span class="dashicons dashicons-clock"></span>
                                </div>
                                <div class="summary-content">
                                    <span class="summary-label"><?php esc_html_e('Published', 'hiretalent'); ?></span>
                                    <span class="summary-value">
                                        <?php echo esc_html(get_the_date() . ' ' . get_the_time()); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($joining_date): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Joining Date', 'hiretalent'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html(date_i18n('Y-m-d', strtotime($joining_date))); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </aside>
            </div>

        </div>
    </article>

    <?php
endwhile;

get_footer();
