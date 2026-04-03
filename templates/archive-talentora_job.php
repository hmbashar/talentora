<?php
/**
 * Archive Job Template
 *
 * Template for displaying job archive.
 * Can be overridden by copying to yourtheme/talentora/archive-talentora_job.php
 *
 * @package Talentora
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<div class="talentora-archive-wrapper">
    <header class="talentora-page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <span class="dashicons dashicons-portfolio"></span>
                <?php esc_html_e( 'Job Opportunities', 'talentora' ); ?>
            </h1>
            <?php
            $talentora_description = get_the_archive_description();

            if ( $talentora_description ) {
                echo '<div class="archive-description">' . wp_kses_post( $talentora_description ) . '</div>';
            } else {
                echo '<p class="archive-subtitle">' . esc_html__( 'Explore exciting career opportunities and find your next role', 'talentora' ) . '</p>';
            }
            ?>
        </div>
    </header>

    <div class="talentora-archive-content">
        <?php echo wp_kses_post( do_shortcode( '[talentora_jobs]' ) ); ?>
    </div>
</div>

<?php
get_footer();