<?php
/**
 * FrontendManager.php
 *
 * Coordinates all frontend functionality.
 *
 * @package Talentora\Frontend\Inc
 * @since 1.0.0
 */

namespace Talentora\Frontend\Inc;

if (!defined('ABSPATH')) {
    exit;
}

use Talentora\Frontend\Shortcodes\JobsList;
use Talentora\Frontend\Shortcodes\ApplyForm;
use Talentora\Frontend\Templates;
use Talentora\Frontend\Assets\Assets;
use Talentora\Frontend\Applications\ApplicationHandler;

/**
 * Frontend Manager class.
 */
class FrontendManager
{
    protected $jobs_list;
    protected $apply_form;
    protected $templates;
    protected $assets;
    protected $application_handler;

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
        $this->application_handler = new ApplicationHandler();
    }
}
