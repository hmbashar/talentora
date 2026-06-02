<?php
/**
 * Docs.php
 *
 * Handles the Docs admin page.
 *
 * @package Talentora\Admin\Pages
 * @since 1.0.0
 */

namespace Talentora\Admin\Pages;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Docs page class.
 */
class Docs
{
    /**
     * Render the Docs page.
     *
     * @since 1.0.0
     */
    public function render_docs_page()
    {
        ?>
        <div class="talentora-premium-docs-wrap">
            <div class="docs-header">
                <h1><?php esc_html_e('Talentora Docs & Support', 'talentora'); ?></h1>
                <p><?php esc_html_e('Everything you need to master Talentora, get help, or contribute to the project.', 'talentora'); ?></p>
            </div>

            <div class="docs-grid">
                
                <a href="https://youtu.be/8I1lcy1DaT4" target="_blank" class="docs-card video-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-video-alt3"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Video Tutorial', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Watch a detailed guide on how to setup and use Talentora effectively.', 'talentora'); ?></p>
                </a>

                <a href="https://talentora.pro.bd/" target="_blank" class="docs-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-admin-site-alt3"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Landing Page', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Visit our official website for more details, features, and updates.', 'talentora'); ?></p>
                </a>

                <a href="https://wordpress.org/support/plugin/talentora/reviews/#new-post" target="_blank" class="docs-card rating-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-star-filled"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Leave a Review', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Love Talentora? Please support us by giving a 5-star rating on WordPress.org.', 'talentora'); ?></p>
                </a>

                <a href="https://hmbashar.com/sponsoring" target="_blank" class="docs-card sponsor-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-heart"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Sponsor the Developer', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Support continuous development and new features by sponsoring me.', 'talentora'); ?></p>
                </a>

                <a href="https://github.com/hmbashar/talentora" target="_blank" class="docs-card github-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-editor-code"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('GitHub Repository', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Explore the source code, fork the project, or star our repository.', 'talentora'); ?></p>
                </a>

                <a href="https://github.com/hmbashar/talentora/issues" target="_blank" class="docs-card github-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-warning"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Report a Bug / Request Feature', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Found an issue or want a new feature? Let us know on GitHub Issues.', 'talentora'); ?></p>
                </a>

                <a href="https://github.com/hmbashar/talentora/pulls" target="_blank" class="docs-card github-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-randomize"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Contribute', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Submit Pull Requests and help make Talentora even better for everyone.', 'talentora'); ?></p>
                </a>

                <a href="https://hmbashar.com" target="_blank" class="docs-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-admin-users"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Developer Website', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Learn more about the developer behind Talentora and other projects.', 'talentora'); ?></p>
                </a>

                <a href="https://facebook.com/hmbashar" target="_blank" class="docs-card facebook-card">
                    <div class="icon-wrapper"><span class="dashicons dashicons-facebook"></span></div>
                    <h3 class="docs-title"><?php esc_html_e('Developer Facebook', 'talentora'); ?></h3>
                    <p class="docs-desc"><?php esc_html_e('Connect with me on Facebook for updates and professional networking.', 'talentora'); ?></p>
                </a>

            </div>
        </div>
        <?php
    }
}
