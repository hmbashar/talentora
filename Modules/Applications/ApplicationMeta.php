<?php
/**
 * ApplicationMeta.php
 *
 * Handles application meta fields and admin display.
 *
 * @package HireTalent\Modules\Applications
 * @since 1.0.0
 */

namespace HireTalent\Modules\Applications;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Application Meta class.
 */
class ApplicationMeta
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_filter('manage_hiretalent_application_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_hiretalent_application_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
    }

    /**
     * Add meta boxes.
     *
     * @since 1.0.0
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            'hiretalent_application_details',
            __('Application Details', 'hiretalent'),
            array($this, 'render_details_meta_box'),
            'hiretalent_application',
            'normal',
            'high'
        );
    }

    /**
     * Render application details meta box.
     *
     * @param \WP_Post $post The post object.
     * @since 1.0.0
     */
    public function render_details_meta_box($post)
    {
        $job_id = get_post_meta($post->ID, 'hiretalent_job_id', true);
        $name = get_post_meta($post->ID, 'hiretalent_applicant_name', true);
        $email = get_post_meta($post->ID, 'hiretalent_applicant_email', true);
        $phone = get_post_meta($post->ID, 'hiretalent_applicant_phone', true);
        $cover_letter = get_post_meta($post->ID, 'hiretalent_cover_letter', true);
        $resume_id = get_post_meta($post->ID, 'hiretalent_resume_id', true);

        ?>
        <style>
            .hiretalent-application-details .detail-row {
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid #e0e0e0;
            }

            .hiretalent-application-details .detail-row:last-child {
                border-bottom: none;
            }

            .hiretalent-application-details .detail-label {
                font-weight: 600;
                display: block;
                margin-bottom: 5px;
            }

            .hiretalent-application-details .detail-value {
                display: block;
            }

            .hiretalent-application-details .cover-letter-box {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 4px;
                white-space: pre-wrap;
                max-height: 300px;
                overflow-y: auto;
            }
        </style>

        <div class="hiretalent-application-details">
            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Applied For:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php if ($job_id): ?>
                        <a href="<?php echo esc_url(get_edit_post_link($job_id)); ?>">
                            <?php echo esc_html(get_the_title($job_id)); ?>
                        </a>
                    <?php else: ?>
                        <?php esc_html_e('N/A', 'hiretalent'); ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Name:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php echo esc_html($name); ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Email:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <a href="mailto:<?php echo esc_attr($email); ?>">
                        <?php echo esc_html($email); ?>
                    </a>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Phone:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php echo esc_html($phone); ?>
                </span>
            </div>

            <?php if ($resume_id): ?>
                <div class="detail-row">
                    <span class="detail-label">
                        <?php esc_html_e('Resume:', 'hiretalent'); ?>
                    </span>
                    <span class="detail-value">
                        <a href="<?php echo esc_url(wp_get_attachment_url($resume_id)); ?>" target="_blank" class="button">
                            <?php esc_html_e('Download Resume', 'hiretalent'); ?>
                        </a>
                    </span>
                </div>
            <?php endif; ?>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Cover Letter:', 'hiretalent'); ?>
                </span>
                <div class="cover-letter-box">
                    <?php echo esc_html($cover_letter); ?>
                </div>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <?php esc_html_e('Submitted:', 'hiretalent'); ?>
                </span>
                <span class="detail-value">
                    <?php echo esc_html(get_the_date('', $post)); ?>
                </span>
            </div>
        </div>
        <?php
    }

    /**
     * Set custom columns for applications list.
     *
     * @param array $columns Existing columns.
     * @return array
     * @since 1.0.0
     */
    public function set_custom_columns($columns)
    {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = __('Applicant', 'hiretalent');
        $new_columns['job'] = __('Job', 'hiretalent');
        $new_columns['email'] = __('Email', 'hiretalent');
        $new_columns['phone'] = __('Phone', 'hiretalent');
        $new_columns['taxonomy-hiretalent_application_status'] = __('Status', 'hiretalent');
        $new_columns['date'] = __('Date', 'hiretalent');

        return $new_columns;
    }

    /**
     * Display custom column content.
     *
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     * @since 1.0.0
     */
    public function custom_column_content($column, $post_id)
    {
        switch ($column) {
            case 'job':
                $job_id = get_post_meta($post_id, 'hiretalent_job_id', true);
                if ($job_id) {
                    echo '<a href="' . esc_url(get_edit_post_link($job_id)) . '">' . esc_html(get_the_title($job_id)) . '</a>';
                } else {
                    esc_html_e('N/A', 'hiretalent');
                }
                break;

            case 'email':
                $email = get_post_meta($post_id, 'hiretalent_applicant_email', true);
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                break;

            case 'phone':
                $phone = get_post_meta($post_id, 'hiretalent_applicant_phone', true);
                echo esc_html($phone);
                break;
        }
    }
}
