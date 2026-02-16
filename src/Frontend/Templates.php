<?php
/**
 * Templates.php
 *
 * Handles template loading and overrides.
 *
 * @package HireTalent\Frontend
 * @since 1.0.0
 */

namespace HireTalent\Frontend;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Templates loader class.
 */
class Templates
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_filter('template_include', array($this, 'load_job_templates'));
    }

    /**
     * Load job templates.
     *
     * @param string $template Template path.
     * @return string
     * @since 1.0.0
     */
    public function load_job_templates($template)
    {
        if (is_singular('hiretalent_job')) {
            return $this->get_template('single-hiretalent_job.php', $template);
        }

        if (is_post_type_archive('hiretalent_job')) {
            return $this->get_template('archive-hiretalent_job.php', $template);
        }

        return $template;
    }

    /**
     * Get template file.
     *
     * Checks theme directory first, then plugin templates directory.
     *
     * @param string $template_name Template filename.
     * @param string $default_template Default template path.
     * @return string
     * @since 1.0.0
     */
    private function get_template($template_name, $default_template = '')
    {
        // Check if template exists in theme
        $theme_template = locate_template(array(
            'hiretalent/' . $template_name,
            $template_name,
        ));

        if ($theme_template) {
            return $theme_template;
        }

        // Use plugin template
        $plugin_template = HIRETALENT_PATH . 'templates/' . $template_name;
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }

        return $default_template;
    }
}
