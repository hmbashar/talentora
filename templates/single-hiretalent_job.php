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
    $hiretalent_job_id = get_the_ID();

    // Get job meta
    $hiretalent_location = get_post_meta($hiretalent_job_id, 'hiretalent_location', true);
    $hiretalent_salary_min = get_post_meta($hiretalent_job_id, 'hiretalent_salary_min', true);
    $hiretalent_salary_max = get_post_meta($hiretalent_job_id, 'hiretalent_salary_max', true);
    $hiretalent_deadline = get_post_meta($hiretalent_job_id, 'hiretalent_deadline', true);
    $hiretalent_experience = get_post_meta($hiretalent_job_id, 'hiretalent_experience', true);
    $hiretalent_vacancy = get_post_meta($hiretalent_job_id, 'hiretalent_vacancy', true);
    $hiretalent_working_hours = get_post_meta($hiretalent_job_id, 'hiretalent_working_hours', true);
    $hiretalent_working_days = get_post_meta($hiretalent_job_id, 'hiretalent_working_days', true);
    $hiretalent_joining_date = get_post_meta($hiretalent_job_id, 'hiretalent_joining_date', true);
    $hiretalent_company_name = get_post_meta($hiretalent_job_id, 'hiretalent_company_name', true);
    $hiretalent_company_website = get_post_meta($hiretalent_job_id, 'hiretalent_company_website', true);
    $hiretalent_company_logo_id = get_post_meta($hiretalent_job_id, 'hiretalent_company_logo_id', true);
    $hiretalent_is_filled = get_post_meta($hiretalent_job_id, 'hiretalent_is_filled', true);

    // Get taxonomies
    $hiretalent_job_categories = get_the_terms($hiretalent_job_id, 'hiretalent_job_category');
    $hiretalent_job_types = get_the_terms($hiretalent_job_id, 'hiretalent_job_type');
    ?>

    <article id="job-<?php echo esc_attr($hiretalent_job_id); ?>" <?php post_class('hiretalent-single-job'); ?>>

        <!-- Job Hero Header -->
        <div class="job-hero-header">
            <div class="job-hero-container">
                <div class="job-hero-content">
                    <?php if ($hiretalent_is_filled): ?>
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
                        <?php if ($hiretalent_company_name): ?>
                            <div class="hero-meta-item primary">
                                <span class="dashicons dashicons-building"></span>
                                <span><?php echo esc_html($hiretalent_company_name); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($hiretalent_location): ?>
                            <div class="hero-meta-item">
                                <span class="dashicons dashicons-location"></span>
                                <span><?php echo esc_html($hiretalent_location); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($hiretalent_job_types && !is_wp_error($hiretalent_job_types)): ?>
                            <div class="hero-meta-item">
                                <span class="dashicons dashicons-tag"></span>
                                <span><?php echo esc_html($hiretalent_job_types[0]->name); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="hero-meta-item">
                            <span class="dashicons dashicons-clock"></span>
                            <span>
                                <?php
                                printf(
                                    /* translators: %s: Human-readable time difference (e.g., "5 minutes"). */
                                    esc_html__('Posted %1$hiretalent_s ago', 'hiretalent'),
                                    esc_html(
                                        human_time_diff(
                                            get_the_time('U'),
                                            current_time('timestamp')
                                        )
                                    )
                                );
                                ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($hiretalent_company_website): ?>
                        <a href="<?php echo esc_url($hiretalent_company_website); ?>" target="_blank" rel="noopener noreferrer"
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
                        <?php if ($hiretalent_salary_min || $hiretalent_salary_max): ?>
                            <div class="info-card salary-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-money-alt"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Salary Range', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $hiretalent_currency = get_option('hiretalent_currency_symbol', '$');
                                        if ($hiretalent_salary_min && $hiretalent_salary_max) {
                                            echo esc_html($hiretalent_currency . number_format($hiretalent_salary_min) . ' - ' . $hiretalent_currency . number_format($hiretalent_salary_max));
                                        } elseif ($hiretalent_salary_min) {
                                            echo esc_html($hiretalent_currency . number_format($hiretalent_salary_min) . '+');
                                        } else {
                                            echo esc_html($hiretalent_currency . number_format($hiretalent_salary_max));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($hiretalent_deadline): ?>
                            <div class="info-card deadline-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Application Deadline', 'hiretalent'); ?>
                                    </div>
                                    <div class="info-card-value">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($hiretalent_deadline))); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($hiretalent_job_types && !is_wp_error($hiretalent_job_types)): ?>
                            <div class="info-card type-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-businessman"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Employment Type', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $hiretalent_type_names = array();
                                        foreach ($hiretalent_job_types as $hiretalent_type) {
                                            $hiretalent_type_names[] = $hiretalent_type->name;
                                        }
                                        echo esc_html(implode(', ', $hiretalent_type_names));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>



                        <?php if ($hiretalent_joining_date): ?>
                            <div class="info-card joining-date-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-calendar"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Joining Date', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($hiretalent_joining_date))); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($hiretalent_job_categories && !is_wp_error($hiretalent_job_categories)): ?>
                            <div class="info-card category-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-category"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Category', 'hiretalent'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $hiretalent_cat_names = array();
                                        foreach ($hiretalent_job_categories as $hiretalent_cat) {
                                            $hiretalent_cat_names[] = $hiretalent_cat->name;
                                        }
                                        echo esc_html(implode(', ', $hiretalent_cat_names));
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
                    <?php if (!$hiretalent_is_filled): ?>
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
                    <?php if ($hiretalent_company_logo_id): ?>
                        <div class="sidebar-card company-card">
                            <div class="company-logo-wrapper">
                                <?php echo wp_get_attachment_image($hiretalent_company_logo_id, 'medium', false, array('class' => 'company-logo-large')); ?>
                            </div>
                            <?php if ($hiretalent_company_name): ?>
                                <h3 class="company-name"><?php echo esc_html($hiretalent_company_name); ?></h3>
                            <?php endif; ?>
                            <?php if ($hiretalent_company_website): ?>
                                <a href="<?php echo esc_url($hiretalent_company_website); ?>" target="_blank"
                                    rel="noopener noreferrer" class="company-website-btn">
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
                            <?php if (!$hiretalent_is_filled): ?>
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
                            <?php if ($hiretalent_location): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-location"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Location', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($hiretalent_location); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($hiretalent_salary_min || $hiretalent_salary_max): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-money-alt"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Salary', 'hiretalent'); ?></span>
                                        <span class="summary-value">
                                            <?php
                                            $hiretalent_currency = get_option('hiretalent_currency_symbol', '$');
                                            if ($hiretalent_salary_min && $hiretalent_salary_max) {
                                                echo esc_html($hiretalent_currency . number_format($hiretalent_salary_min) . ' - ' . $hiretalent_currency . number_format($hiretalent_salary_max));
                                            } elseif ($hiretalent_salary_min) {
                                                echo esc_html('From ' . $hiretalent_currency . number_format($hiretalent_salary_min));
                                            } else {
                                                echo esc_html('Up to ' . $hiretalent_currency . number_format($hiretalent_salary_max));
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($hiretalent_job_types && !is_wp_error($hiretalent_job_types)): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-portfolio"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Job Type', 'hiretalent'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html($hiretalent_job_types[0]->name); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($hiretalent_experience): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-welcome-learn-more"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Experience', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($hiretalent_experience); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($hiretalent_working_hours): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-clock"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Working Hours', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($hiretalent_working_hours); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($hiretalent_working_days): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar-alt"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Working Days', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($hiretalent_working_days); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($hiretalent_vacancy): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-groups"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Vacancy', 'hiretalent'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($hiretalent_vacancy); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($hiretalent_deadline): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span
                                            class="summary-label"><?php esc_html_e('Last Date of Submission', 'hiretalent'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html(date_i18n('Y-m-d', strtotime($hiretalent_deadline))); ?></span>
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

                            <?php if ($hiretalent_joining_date): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Joining Date', 'hiretalent'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html(date_i18n('Y-m-d', strtotime($hiretalent_joining_date))); ?></span>
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
