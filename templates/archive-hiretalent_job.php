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

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<div class="hiretalent-archive-wrapper">
    <header class="hiretalent-page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <span class="dashicons dashicons-portfolio"></span>
                <?php esc_html_e( 'Job Opportunities', 'hiretalent' ); ?>
            </h1>
            <?php
            $hiretalent_description = get_the_archive_description();

            if ( $hiretalent_description ) {
                echo '<div class="archive-description">' . wp_kses_post( $hiretalent_description ) . '</div>';
            } else {
                echo '<p class="archive-subtitle">' . esc_html__( 'Explore exciting career opportunities and find your next role', 'hiretalent' ) . '</p>';
            }
            ?>
        </div>
    </header>

    <div class="hiretalent-archive-content">
        <?php echo wp_kses_post( do_shortcode( '[hiretalent_jobs]' ) ); ?>
    </div>
</div>

<?php
get_footer();