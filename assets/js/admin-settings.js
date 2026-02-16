/**
 * Admin Settings React Component
 *
 * @package HireTalent
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Check if wp.element is available
    if (typeof wp === 'undefined' || typeof wp.element === 'undefined') {
        console.warn('HireTalent: wp.element not available, showing fallback form');
        document.querySelector('.hiretalent-settings-fallback').style.display = 'block';
        return;
    }

    const { createElement: h, Component, render } = wp.element;
    const { TextControl, Button, Card, CardBody, CardHeader } = wp.components || {};

    // Fallback if wp.components is not available
    if (!wp.components) {
        console.warn('HireTalent: wp.components not available, showing fallback form');
        document.querySelector('.hiretalent-settings-fallback').style.display = 'block';
        return;
    }

    class SettingsApp extends Component {
        constructor(props) {
            super(props);
            this.state = {
                applyFormShortcode: hireTalentSettings.applyFormShortcode || '',
                jobsPerPage: hireTalentSettings.jobsPerPage || 10,
                saving: false,
                message: '',
                messageType: ''
            };
        }

        handleSave = () => {
            this.setState({ saving: true, message: '', messageType: '' });

            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('option_page', 'hiretalent_settings');
            formData.append('_wpnonce', hireTalentSettings.nonce);
            formData.append('hiretalent_apply_form_shortcode', this.state.applyFormShortcode);
            formData.append('hiretalent_jobs_per_page', this.state.jobsPerPage);

            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.ok) {
                    this.setState({
                        saving: false,
                        message: 'Settings saved successfully!',
                        messageType: 'success'
                    });
                    // Reload page to show WordPress success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error('Save failed');
                }
            })
            .catch(error => {
                this.setState({
                    saving: false,
                    message: 'Failed to save settings. Please try again.',
                    messageType: 'error'
                });
            });
        };

        render() {
            return h('div', { className: 'hiretalent-settings-wrapper' },
                this.state.message && h('div', {
                    className: `notice notice-${this.state.messageType} is-dismissible`,
                    style: { marginBottom: '20px' }
                }, h('p', null, this.state.message)),

                h(Card, null,
                    h(CardHeader, null, h('h2', null, 'General Settings')),
                    h(CardBody, null,
                        h('div', { className: 'hiretalent-setting-field' },
                            h('label', { htmlFor: 'apply-form-shortcode' },
                                h('strong', null, 'Apply Form Shortcode')
                            ),
                            h('input', {
                                type: 'text',
                                id: 'apply-form-shortcode',
                                className: 'regular-text',
                                value: this.state.applyFormShortcode,
                                onChange: (e) => this.setState({ applyFormShortcode: e.target.value }),
                                placeholder: '[contact-form-7 id="123"]'
                            }),
                            h('p', { className: 'description' },
                                'Enter the shortcode of your contact/application form plugin. This will be displayed on single job pages.'
                            )
                        ),

                        h('div', { className: 'hiretalent-setting-field' },
                            h('label', { htmlFor: 'jobs-per-page' },
                                h('strong', null, 'Jobs Per Page')
                            ),
                            h('input', {
                                type: 'number',
                                id: 'jobs-per-page',
                                value: this.state.jobsPerPage,
                                onChange: (e) => this.setState({ jobsPerPage: parseInt(e.target.value) || 10 }),
                                min: 1,
                                max: 100,
                                step: 1
                            }),
                            h('p', { className: 'description' },
                                'Number of jobs to display per page in the job list.'
                            )
                        ),

                        h(Button, {
                            isPrimary: true,
                            isBusy: this.state.saving,
                            onClick: this.handleSave,
                            disabled: this.state.saving
                        }, this.state.saving ? 'Saving...' : 'Save Settings')
                    )
                )
            );
        }
    }

    // Render the app
    const rootElement = document.getElementById('hiretalent-settings-root');
    if (rootElement) {
        render(h(SettingsApp), rootElement);
    } else {
        // Fallback to regular form
        document.querySelector('.hiretalent-settings-fallback').style.display = 'block';
    }

})();
