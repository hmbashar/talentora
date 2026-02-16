<?php
/**
 * Archive Job Template
 *
 * Template for displaying job archive.
 * Can be overridden by copying to yourtheme/hiretalent/archive-hiretalent_job.php
 *
 * @package HireTalent
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="hiretalent-archive-wrapper">
    <header class="page-header">
        <h1 class="page-title">
            <?php esc_html_e('Job Listings', 'hiretalent'); ?>
        </h1>
        <?php
        $description = get_the_archive_description();
        if ($description) {
            echo '<div class="archive-description">' . wp_kses_post($description) . '</div>';
        }
        ?>
    </header>

    <?php echo do_shortcode('[hiretalent_jobs]'); ?>
</div>

<?php
get_footer();
