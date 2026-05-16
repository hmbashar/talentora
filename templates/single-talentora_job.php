<?php
/**
 * Single Job Template
 *
 * Template for displaying single job posts.
 * Can be overridden by copying to yourtheme/talentora/single-talentora_job.php
 *
 * @package Talentora
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()):
    the_post();
    $talentora_job_id = get_the_ID();

    // Get job meta
    $talentora_location = get_post_meta($talentora_job_id, 'talentora_location', true);
    $talentora_salary_min = get_post_meta($talentora_job_id, 'talentora_salary_min', true);
    $talentora_salary_max = get_post_meta($talentora_job_id, 'talentora_salary_max', true);
    $talentora_deadline = get_post_meta($talentora_job_id, 'talentora_deadline', true);
    $talentora_experience = get_post_meta($talentora_job_id, 'talentora_experience', true);
    $talentora_vacancy = get_post_meta($talentora_job_id, 'talentora_vacancy', true);
    $talentora_working_hours = get_post_meta($talentora_job_id, 'talentora_working_hours', true);
    $talentora_working_days = get_post_meta($talentora_job_id, 'talentora_working_days', true);
    $talentora_joining_date = get_post_meta($talentora_job_id, 'talentora_joining_date', true);
    $talentora_company_name = get_post_meta($talentora_job_id, 'talentora_company_name', true);
    $talentora_company_website = get_post_meta($talentora_job_id, 'talentora_company_website', true);
    $talentora_company_logo_id = get_post_meta($talentora_job_id, 'talentora_company_logo_id', true);
    $talentora_is_filled = get_post_meta($talentora_job_id, 'talentora_is_filled', true);

    // Get taxonomies
    $talentora_job_categories = get_the_terms($talentora_job_id, 'talentora_job_category');
    $talentora_job_types = get_the_terms($talentora_job_id, 'talentora_job_type');
    ?>

    <article id="job-<?php echo esc_attr($talentora_job_id); ?>" <?php post_class('talentora-single-job'); ?>>

        <!-- Job Hero Header -->
        <div class="job-hero-header">
            <div class="job-hero-container">
                <div class="job-hero-content">
                    <?php if ($talentora_is_filled): ?>
                        <span class="job-status-badge filled">
                            <span class="dashicons dashicons-saved"></span>
                            <?php esc_html_e('Position Filled', 'talentora'); ?>
                        </span>
                    <?php else: ?>
                        <span class="job-status-badge active">
                            <span class="dashicons dashicons-star-filled"></span>
                            <?php esc_html_e('Now Hiring', 'talentora'); ?>
                        </span>
                    <?php endif; ?>

                    <h1 class="job-hero-title"><?php the_title(); ?></h1>

                    <div class="job-hero-meta">
                        <?php if ($talentora_company_name): ?>
                            <div class="hero-meta-item primary">
                                <span class="dashicons dashicons-building"></span>
                                <span><?php echo esc_html($talentora_company_name); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($talentora_location): ?>
                            <div class="hero-meta-item">
                                <span class="dashicons dashicons-location"></span>
                                <span><?php echo esc_html($talentora_location); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($talentora_job_types && !is_wp_error($talentora_job_types)): ?>
                            <div class="hero-meta-item">
                                <span class="dashicons dashicons-tag"></span>
                                <span><?php echo esc_html($talentora_job_types[0]->name); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="hero-meta-item">
                            <span class="dashicons dashicons-clock"></span>
                            <span>
                                <?php
                                printf(
                                    /* translators: %s: Human-readable time difference (e.g., "5 minutes"). */
                                    esc_html__('Posted %1$s ago', 'talentora'),
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

                    <?php if ($talentora_company_website): ?>
                        <a href="<?php echo esc_url($talentora_company_website); ?>" target="_blank" rel="noopener noreferrer"
                            class="company-website-link">
                            <span class="dashicons dashicons-admin-links"></span>
                            <?php esc_html_e('Visit Company Website', 'talentora'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="talentora-job-container">
            <div class="job-content-grid">

                <!-- Left Column: Main Content -->
                <div class="job-main-content">

                    <!-- Key Information Cards -->
                    <div class="job-info-cards">
                        <?php if ($talentora_salary_min || $talentora_salary_max): ?>
                            <div class="info-card salary-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-money-alt"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Salary Range', 'talentora'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $talentora_currency = get_option('talentora_currency_symbol', '$');
                                        if ($talentora_salary_min && $talentora_salary_max) {
                                            echo esc_html($talentora_currency . number_format($talentora_salary_min) . ' - ' . $talentora_currency . number_format($talentora_salary_max));
                                        } elseif ($talentora_salary_min) {
                                            echo esc_html($talentora_currency . number_format($talentora_salary_min) . '+');
                                        } else {
                                            echo esc_html($talentora_currency . number_format($talentora_salary_max));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($talentora_deadline): ?>
                            <div class="info-card deadline-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Application Deadline', 'talentora'); ?>
                                    </div>
                                    <div class="info-card-value">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($talentora_deadline))); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($talentora_job_types && !is_wp_error($talentora_job_types)): ?>
                            <div class="info-card type-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-businessman"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Employment Type', 'talentora'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $talentora_type_names = array();
                                        foreach ($talentora_job_types as $talentora_type) {
                                            $talentora_type_names[] = $talentora_type->name;
                                        }
                                        echo esc_html(implode(', ', $talentora_type_names));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>



                        <?php if ($talentora_joining_date): ?>
                            <div class="info-card joining-date-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-calendar"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Joining Date', 'talentora'); ?></div>
                                    <div class="info-card-value">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($talentora_joining_date))); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($talentora_job_categories && !is_wp_error($talentora_job_categories)): ?>
                            <div class="info-card category-card">
                                <div class="info-card-icon">
                                    <span class="dashicons dashicons-category"></span>
                                </div>
                                <div class="info-card-content">
                                    <div class="info-card-label"><?php esc_html_e('Category', 'talentora'); ?></div>
                                    <div class="info-card-value">
                                        <?php
                                        $talentora_cat_names = array();
                                        foreach ($talentora_job_categories as $talentora_cat) {
                                            $talentora_cat_names[] = $talentora_cat->name;
                                        }
                                        echo esc_html(implode(', ', $talentora_cat_names));
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
                            <?php esc_html_e('Job Description', 'talentora'); ?>
                        </h2>
                        <div class="description-content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Application Section -->
                    <?php if (!$talentora_is_filled): ?>
                        <div class="job-apply-section" id="apply-section">
                            <h2 class="content-section-title">
                                <span class="dashicons dashicons-admin-users"></span>
                                <?php esc_html_e('Apply for this Position', 'talentora'); ?>
                            </h2>
                            <p class="apply-subtitle">
                                <?php esc_html_e('Ready to take the next step? Submit your application below.', 'talentora'); ?>
                            </p>
                            <div class="apply-form-wrapper">
                                <?php echo do_shortcode('[talentora_apply_form]'); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="job-filled-notice">
                            <div class="filled-notice-icon">
                                <span class="dashicons dashicons-saved"></span>
                            </div>
                            <div class="filled-notice-content">
                                <h3><?php esc_html_e('Position Filled', 'talentora'); ?></h3>
                                <p><?php esc_html_e('This position has been filled. Thank you for your interest! Check out our other openings.', 'talentora'); ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column: Sidebar -->
                <aside class="job-sidebar">

                    <!-- Company Logo/QR Code Card -->
                    <?php if ($talentora_company_logo_id): ?>
                        <div class="sidebar-card company-card">
                            <div class="company-logo-wrapper">
                                <?php echo wp_get_attachment_image($talentora_company_logo_id, 'medium', false, array('class' => 'company-logo-large')); ?>
                            </div>
                            <?php if ($talentora_company_name): ?>
                                <h3 class="company-name"><?php echo esc_html($talentora_company_name); ?></h3>
                            <?php endif; ?>
                            <?php if ($talentora_company_website): ?>
                                <a href="<?php echo esc_url($talentora_company_website); ?>" target="_blank"
                                    rel="noopener noreferrer" class="company-website-btn">
                                    <span class="dashicons dashicons-external"></span>
                                    <?php esc_html_e('Company Website', 'talentora'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Quick Actions Card -->
                    <div class="sidebar-card actions-card">
                        <h3 class="sidebar-card-title">
                            <span class="dashicons dashicons-admin-tools"></span>
                            <?php esc_html_e('Quick Actions', 'talentora'); ?>
                        </h3>
                        <div class="quick-actions">
                            <?php if (!$talentora_is_filled): ?>
                                <a href="#apply-section" class="action-btn primary">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <?php esc_html_e('Apply Now', 'talentora'); ?>
                                </a>
                            <?php endif; ?>
                            <button class="action-btn secondary" onclick="window.print()">
                                <span class="dashicons dashicons-printer"></span>
                                <?php esc_html_e('Print Job', 'talentora'); ?>
                            </button>
                            <button class="action-btn secondary"
                                onclick="navigator.share ? navigator.share({title: '<?php echo esc_js(get_the_title()); ?>', url: window.location.href}) : alert('<?php esc_html_e('Copy this URL to share', 'talentora'); ?>')">
                                <span class="dashicons dashicons-share"></span>
                                <?php esc_html_e('Share Job', 'talentora'); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="sidebar-card summary-card">
                        <h3 class="sidebar-card-title">
                            <?php esc_html_e('Job Summary:', 'talentora'); ?>
                        </h3>
                        <div class="summary-items">
                            <?php if ($talentora_location): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-location"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Location', 'talentora'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($talentora_location); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($talentora_salary_min || $talentora_salary_max): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-money-alt"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Salary', 'talentora'); ?></span>
                                        <span class="summary-value">
                                            <?php
                                            $talentora_currency = get_option('talentora_currency_symbol', '$');
                                            if ($talentora_salary_min && $talentora_salary_max) {
                                                echo esc_html($talentora_currency . number_format($talentora_salary_min) . ' - ' . $talentora_currency . number_format($talentora_salary_max));
                                            } elseif ($talentora_salary_min) {
                                                echo esc_html('From ' . $talentora_currency . number_format($talentora_salary_min));
                                            } else {
                                                echo esc_html('Up to ' . $talentora_currency . number_format($talentora_salary_max));
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($talentora_job_types && !is_wp_error($talentora_job_types)): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-portfolio"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Job Type', 'talentora'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html($talentora_job_types[0]->name); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($talentora_experience): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-welcome-learn-more"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Experience', 'talentora'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($talentora_experience); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($talentora_working_hours): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-clock"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Working Hours', 'talentora'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($talentora_working_hours); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($talentora_working_days): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar-alt"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Working Days', 'talentora'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($talentora_working_days); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($talentora_vacancy): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-groups"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Vacancy', 'talentora'); ?></span>
                                        <span class="summary-value"><?php echo esc_html($talentora_vacancy); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($talentora_deadline): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span
                                            class="summary-label"><?php esc_html_e('Last Date of Submission', 'talentora'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html(date_i18n('Y-m-d', strtotime($talentora_deadline))); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="summary-item">
                                <div class="summary-icon">
                                    <span class="dashicons dashicons-clock"></span>
                                </div>
                                <div class="summary-content">
                                    <span class="summary-label"><?php esc_html_e('Published', 'talentora'); ?></span>
                                    <span class="summary-value">
                                        <?php echo esc_html(get_the_date() . ' ' . get_the_time()); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($talentora_joining_date): ?>
                                <div class="summary-item">
                                    <div class="summary-icon">
                                        <span class="dashicons dashicons-calendar"></span>
                                    </div>
                                    <div class="summary-content">
                                        <span class="summary-label"><?php esc_html_e('Joining Date', 'talentora'); ?></span>
                                        <span
                                            class="summary-value"><?php echo esc_html(date_i18n('Y-m-d', strtotime($talentora_joining_date))); ?></span>
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
