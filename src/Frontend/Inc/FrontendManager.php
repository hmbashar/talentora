<?php
/**
 * FrontendManager.php
 *
 * Coordinates all frontend functionality.
 *
 * @package HireTalent\Frontend\Inc
 * @since 1.0.0
 */

namespace HireTalent\Frontend\Inc;

if (!defined('ABSPATH')) {
    exit;
}

use HireTalent\Frontend\Shortcodes\JobsList;
use HireTalent\Frontend\Shortcodes\ApplyForm;
use HireTalent\Frontend\Templates;
use HireTalent\Frontend\Assets\Assets;

/**
 * Frontend Manager class.
 */
class FrontendManager
{
    protected $jobs_list;
    protected $apply_form;
    protected $templates;
    protected $assets;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize frontend components.
     *
     * @since 1.0.0
     */
    public function init()
    {
        $this->jobs_list = new JobsList();
        $this->apply_form = new ApplyForm();
        $this->templates = new Templates();
        $this->assets = new Assets();
    }
}
