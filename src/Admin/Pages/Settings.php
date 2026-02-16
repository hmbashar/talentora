<?php
/**
 * Settings.php
 *
 * Handles plugin settings page.
 *
 * @package HireTalent\Admin\Pages
 * @since 1.0.0
 */

namespace HireTalent\Admin\Pages;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings page class.
 */
class Settings
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Register plugin settings.
     *
     * @since 1.0.0
     */
    public function register_settings()
    {
        // Register settings
        register_setting('hiretalent_settings', 'hiretalent_apply_form_shortcode', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ));

        register_setting('hiretalent_settings', 'hiretalent_jobs_per_page', array(
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 10,
        ));

        // Add settings section
        add_settings_section(
            'hiretalent_general_section',
            __('General Settings', 'hiretalent'),
            array($this, 'general_section_callback'),
            'hiretalent_settings'
        );

        // Add settings fields
        add_settings_field(
            'hiretalent_apply_form_shortcode',
            __('Apply Form Shortcode', 'hiretalent'),
            array($this, 'apply_form_shortcode_callback'),
            'hiretalent_settings',
            'hiretalent_general_section'
        );

        add_settings_field(
            'hiretalent_jobs_per_page',
            __('Jobs Per Page', 'hiretalent'),
            array($this, 'jobs_per_page_callback'),
            'hiretalent_settings',
            'hiretalent_general_section'
        );
    }

    /**
     * General section callback.
     *
     * @since 1.0.0
     */
    public function general_section_callback()
    {
        echo '<p>' . esc_html__('Configure general settings for HireTalent.', 'hiretalent') . '</p>';
    }

    /**
     * Apply form shortcode field callback.
     *
     * @since 1.0.0
     */
    public function apply_form_shortcode_callback()
    {
        $value = get_option('hiretalent_apply_form_shortcode', '');
        ?>
        <input type="text" id="hiretalent_apply_form_shortcode" name="hiretalent_apply_form_shortcode"
            value="<?php echo esc_attr($value); ?>" class="regular-text" placeholder="<?php esc_attr_e('[contact-form-7 id="123"]', 'hiretalent'); ?>">
        <p class="description">
            <?php esc_html_e('Enter the shortcode of your contact/application form plugin. This will be displayed on single job pages. Leave empty to hide the apply section.', 'hiretalent'); ?>
        </p>
        <?php
    }

    /**
     * Jobs per page field callback.
     *
     * @since 1.0.0
     */
    public function jobs_per_page_callback()
    {
        $value = get_option('hiretalent_jobs_per_page', 10);
        ?>
        <input type="number" id="hiretalent_jobs_per_page" name="hiretalent_jobs_per_page"
            value="<?php echo esc_attr($value); ?>" min="1" max="100" step="1">
        <p class="description">
            <?php esc_html_e('Number of jobs to display per page in the job list.', 'hiretalent'); ?>
        </p>
        <?php
    }

    /**
     * Render settings page.
     *
     * @since 1.0.0
     */
    public function render_settings_page()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Add error/update messages
        settings_errors('hiretalent_messages');
        ?>
        <div class="wrap">
            <h1>
                <?php echo esc_html(get_admin_page_title()); ?>
            </h1>

            <div id="hiretalent-settings-root"></div>

            <!-- Fallback form if React doesn't load -->
            <noscript>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('hiretalent_settings');
                    do_settings_sections('hiretalent_settings');
                    submit_button(__('Save Settings', 'hiretalent'));
                    ?>
                </form>
            </noscript>

            <div class="hiretalent-settings-fallback" style="display:none;">
                <form action="options.php" method="post">
                    <?php
                    settings_fields('hiretalent_settings');
                    do_settings_sections('hiretalent_settings');
                    submit_button(__('Save Settings', 'hiretalent'));
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}
