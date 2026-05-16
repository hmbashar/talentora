# Talentora – Simple & Powerful Job Board Plugin for WordPress

> A lightweight, self-contained WordPress job board plugin. Publish jobs, collect applications, manage the applicant pipeline, export CSV, customise email notifications, and override templates — all without leaving WordPress.

**🌐 [Plugin Landing Page](https://hmbashar.github.io/talentora/) &nbsp;|&nbsp; 🔌 [WordPress.org](https://wordpress.org/plugins/talentora/) &nbsp;|&nbsp; 📖 [User Guide](docs/user-guide.md) &nbsp;|&nbsp; 🛠 [Developer Guide](docs/developer-guide.md) &nbsp;|&nbsp; 📋 [Application Management](docs/application-management.md)**

---

## Features

**Job Management**
- ✅ Custom post type `talentora_job` with clean SEO-friendly URLs (`/job/job-title/`)
- ✅ Hierarchical **Job Categories** and **Job Types** taxonomies
- ✅ Rich job meta: location, salary min/max, currency, deadline, company name, website, logo, filled flag
- ✅ `[talentora_jobs]` shortcode with `posts_per_page` attribute

**Application Management**
- ✅ Built-in application form (`[talentora_application_form]`) — name, email, phone, cover letter, resume upload
- ✅ Resume upload: PDF / DOC / DOCX, max 5 MB — stored privately in the Media Library
- ✅ Private `talentora_app` post type — applications inbox under Talentora → Applications
- ✅ Configurable status workflow: Pending, Reviewed, Shortlisted, Rejected, Hired
- ✅ Bulk status actions on the applications list screen
- ✅ CSV export of all applications
- ✅ Secure nonce-protected resume download
- ✅ Per-application activity log

**Email Notifications**
- ✅ Admin new-application email
- ✅ Applicant confirmation email
- ✅ Status-change email to applicant
- ✅ Customisable email templates with dynamic placeholders
- ✅ Email log viewer (Talentora → Settings → Email Logs)

**Developer & Extensibility**
- ✅ Template override support (`your-theme/talentora/`)
- ✅ Actions & filters throughout
- ✅ Namespaced OOP architecture with PSR-4 autoloading
- ✅ Honeypot spam protection on the built-in form
- ✅ Translation-ready with `.pot` file

---

## Requirements

| Requirement | Version |
|-------------|---------|
| WordPress   | 5.8+    |
| PHP         | 7.4+    |
| Composer    | Any (dev only) |

---

## Installation

### From WordPress.org
1. Go to **Plugins → Add New**, search for **Talentora**, click **Install Now** then **Activate**.

### Manual
1. Download the `.zip` and go to **Plugins → Add New → Upload Plugin**.
2. Select the file, install, and activate.

**After activation:** Go to **Settings → Permalinks** and click **Save Changes** to flush rewrite rules.

---

## Quick Start

```
1. Activate the plugin
2. Settings → Permalinks → Save Changes
3. Talentora → Settings → configure currency, statuses, email templates
4. Talentora → Add New → publish your first job
5. Add [talentora_jobs] to any page
```

---

## Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[talentora_jobs]` | Filterable job listing |
| `[talentora_jobs posts_per_page="15"]` | Limit jobs per page |
| `[talentora_application_form]` | Built-in application form |
| `[talentora_application_form job_id="123"]` | Form linked to a specific job |
| `[talentora_apply_form]` | Third-party form shortcode wrapper |
| `[talentora_apply_form form_shortcode="[cf7 id='1']"]` | Override per placement |

---

## Developer Hooks

### Filters

```php
// Modify job listing query
add_filter('talentora_jobs_query_args', function($args) {
    $args['tax_query'] = [['taxonomy' => 'talentora_job_type', 'field' => 'slug', 'terms' => 'remote']];
    return $args;
});

// Swap apply form per job
add_filter('talentora_apply_form_shortcode', function($shortcode, $job_id) {
    return $job_id === 42 ? '[contact-form-7 id="99"]' : $shortcode;
}, 10, 2);

// Filter currency symbol
add_filter('talentora_currency_symbol', fn($s) => '£');

// Add classes to job cards
add_filter('talentora_job_card_classes', fn($classes, $id) => $classes . ' featured', 10, 2);
```

### Actions

```php
add_action('talentora_before_job_list', fn() => print '<div class="notice">We are hiring!</div>');
add_action('talentora_after_job_list',  fn() => print '</div>');
add_action('talentora_before_single_job', fn($id) => print '<p>Job #' . $id . '</p>');
add_action('talentora_after_single_job',  fn($id) => print '<!-- end job -->');
```

---

## Template Override

```
1. Create:  your-theme/talentora/
2. Copy:    wp-content/plugins/talentora/templates/single-talentora_job.php
            wp-content/plugins/talentora/templates/archive-talentora_job.php
3. Edit the copied files freely
```

---

## Documentation

| Guide | Contents |
|-------|----------|
| [User Guide](docs/user-guide.md) | Installation, job creation, settings, shortcodes, template override |
| [Application Management](docs/application-management.md) | Built-in form, applications inbox, status workflow, CSV export, email notifications, activity log |
| [Developer Guide](docs/developer-guide.md) | Architecture, PSR-4 autoloading, meta fields, hooks, Settings API, release checklist |

---

## Changelog

### 0.0.1
- Initial release — full feature set including job post type, taxonomies, meta fields, built-in application form, applications inbox, status workflow, bulk actions, CSV export, resume download, activity log, email notifications with customisable templates, email logs, honeypot spam protection, template overrides, developer hooks, and translation support.

---

## License

GPL-2.0-or-later — see [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html)

## Author

**Md Abul Bashar** — [github.com/hmbashar](https://github.com/hmbashar)
