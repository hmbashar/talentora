# HireTalent – Developer Guide

This guide covers the technical architecture, extension points, and development workflow for the **HireTalent** WordPress plugin.

---

## Table of Contents

1. [Project Structure](#project-structure)
2. [Architecture Overview](#architecture-overview)
3. [Namespace & Autoloading](#namespace--autoloading)
4. [Post Types & Taxonomies](#post-types--taxonomies)
5. [Meta Fields Reference](#meta-fields-reference)
6. [Hooks & Filters](#hooks--filters)
7. [Shortcodes](#shortcodes)
8. [Settings API](#settings-api)
9. [Template System](#template-system)
10. [Assets (CSS/JS)](#assets-cssjs)
11. [Adding New Features](#adding-new-features)
12. [Local Development Setup](#local-development-setup)
13. [Code Standards](#code-standards)
14. [Release Checklist](#release-checklist)

---

## Project Structure

```
hiretalent/
├── Admin/                      # Admin-side classes
│   ├── MetaBox/                # Post meta box registrations
│   ├── Pages/                  # Admin menu pages & settings
│   └── PostTypes/              # CPT & taxonomy registration
├── assets/                     # Static assets
│   ├── css/                    # Compiled CSS files
│   ├── js/                     # Compiled JS files
│   └── images/
├── docs/                       # Documentation (this folder)
│   ├── user-guide.md
│   └── developer-guide.md
├── Frontend/                   # Frontend-side classes
│   └── Shortcodes/             # Shortcode handlers
├── Inc/                        # Core includes
│   ├── Activate.php            # Activation logic
│   ├── Deactivate.php          # Deactivation logic
│   ├── Manager.php             # Central class loader
│   └── Sanitizer.php          # Input sanitization helpers
├── languages/                  # Translation .pot file
├── Modules/                    # Feature modules
├── templates/                  # Frontend template files
│   ├── index.php               # Security guard
│   ├── single-hiretalent_job.php
│   └── archive-hiretalent_job.php
├── vendor/                     # Composer dependencies
├── composer.json
├── hiretalent.php              # Plugin entry point
└── index.php                   # Root security guard
```

---

## Architecture Overview

HireTalent follows a **Singleton + OOP** pattern:

```
hiretalent.php (entry point)
    └── HireTalent (singleton)
            ├── define_constants()     → HIRETALENT_*, etc.
            ├── include_files()        → Composer autoloader
            └── init_hooks()
                    ├── plugins_loaded → new HireTalent\Manager()
                    ├── activation     → HireTalent\Activate::activate()
                    └── deactivation   → HireTalent\Deactivate::deactivate()
```

The `Manager` class is responsible for bootstrapping all sub-systems (admin, frontend, modules).

---

## Namespace & Autoloading

All classes use the `HireTalent` root namespace, following PSR-4 via Composer:

```json
// composer.json (autoload section)
"autoload": {
    "psr-4": {
        "HireTalent\\": "./"
    }
}
```

**Examples:**
- `HireTalent\Admin\Pages\Settings` → `Admin/Pages/Settings.php`
- `HireTalent\Frontend\Shortcodes\JobsList` → `Frontend/Shortcodes/JobsList.php`
- `HireTalent\Inc\Sanitizer` → `Inc/Sanitizer.php`

Always run `composer dump-autoload` after adding new classes.

---

## Post Types & Taxonomies

### Custom Post Type: `hiretalent_job`

| Property      | Value                            |
|---------------|----------------------------------|
| Post type     | `hiretalent_job`                 |
| Slug          | `job`                            |
| Archive slug  | `jobs`                           |
| Supports      | `title`, `editor`, `thumbnail`   |
| Public        | `true`                           |

### Taxonomy: `hiretalent_job_category`

| Property     | Value             |
|--------------|-------------------|
| Label        | Job Categories    |
| Slug         | `job-category`    |
| Hierarchical | Yes               |

### Taxonomy: `hiretalent_job_type`

| Property     | Value             |
|--------------|-------------------|
| Label        | Job Types         |
| Slug         | `job-type`        |
| Hierarchical | Yes               |

---

## Meta Fields Reference

All meta keys use the `hiretalent_` prefix. Always use `sanitize_*` functions when saving.

| Meta Key                     | Type    | Description                          |
|------------------------------|---------|--------------------------------------|
| `hiretalent_location`        | string  | Job location                         |
| `hiretalent_salary_min`      | number  | Minimum salary                       |
| `hiretalent_salary_max`      | number  | Maximum salary                       |
| `hiretalent_deadline`        | date    | Application deadline (Y-m-d)         |
| `hiretalent_company_name`    | string  | Company name                         |
| `hiretalent_company_website` | URL     | Company website                      |
| `hiretalent_company_logo_id` | int     | Attachment ID for company logo       |
| `hiretalent_is_filled`       | bool    | '1' = filled, '0' = open            |
| `hiretalent_expiry_date`     | date    | Optional job expiry (Y-m-d)          |

**Reading meta in templates:**
```php
$location     = get_post_meta( get_the_ID(), 'hiretalent_location', true );
$salary_min   = get_post_meta( get_the_ID(), 'hiretalent_salary_min', true );
$company_name = get_post_meta( get_the_ID(), 'hiretalent_company_name', true );
```

---

## Hooks & Filters

### Actions

| Hook                           | When it fires                          | Args     |
|--------------------------------|----------------------------------------|----------|
| `hiretalent_before_job_list`   | Before the job listing is rendered     | none     |
| `hiretalent_after_job_list`    | After the job listing is rendered      | none     |
| `hiretalent_before_single_job` | Before single job content              | `$job_id`|
| `hiretalent_after_single_job`  | After single job content               | `$job_id`|

**Example – Add content before job list:**
```php
add_action( 'hiretalent_before_job_list', function() {
    echo '<div class="ht-notice">We are hiring!</div>';
} );
```

### Filters

| Filter                              | Description                                  | Args                    |
|-------------------------------------|----------------------------------------------|-------------------------|
| `hiretalent_jobs_query_args`        | Modify the WP_Query args for job listing     | `$args` (array)         |
| `hiretalent_apply_form_shortcode`   | Modify the apply form shortcode string       | `$shortcode`, `$job_id` |
| `hiretalent_job_card_classes`       | Add extra CSS classes to a job card          | `$classes`, `$job_id`   |
| `hiretalent_currency_symbol`        | Filter the currency symbol                   | `$symbol`               |

**Example – Filter query to show only remote jobs:**
```php
add_filter( 'hiretalent_jobs_query_args', function( $args ) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'hiretalent_job_type',
            'field'    => 'slug',
            'terms'    => 'remote',
        ],
    ];
    return $args;
} );
```

**Example – Swap the apply form per job:**
```php
add_filter( 'hiretalent_apply_form_shortcode', function( $shortcode, $job_id ) {
    if ( $job_id === 42 ) {
        return '[contact-form-7 id="99"]';
    }
    return $shortcode;
}, 10, 2 );
```

---

## Shortcodes

### `[hiretalent_jobs]`

Registered in `Frontend/Shortcodes/JobsList.php`.

| Attribute       | Default | Description                    |
|-----------------|---------|--------------------------------|
| `posts_per_page`| Setting | Number of jobs per page        |

### `[hiretalent_apply_form]`

Registered in `Frontend/Shortcodes/ApplyForm.php` (or similar).

| Attribute        | Default | Description                    |
|------------------|---------|--------------------------------|
| `form_shortcode` | Setting | Override global apply shortcode|

---

## Settings API

Settings are stored as individual WordPress options (not a single array).

| Option Key                  | Description                     | Default |
|-----------------------------|---------------------------------|---------|
| `hiretalent_apply_shortcode`| Apply form shortcode string     | `''`    |
| `hiretalent_jobs_per_page`  | Jobs per page                   | `10`    |
| `hiretalent_currency_symbol`| Global currency symbol          | `$`     |

All settings are saved/retrieved via the WordPress Settings API. The settings page UI is built with React and lives under `assets/js/`.

**Reading a setting in PHP:**
```php
$currency = get_option( 'hiretalent_currency_symbol', '$' );
```

**Adding a new setting:**
1. Register it in `Admin/Pages/Settings.php` → `register_settings()` method.
2. Add sanitization callback in `Inc/Sanitizer.php`.
3. Add the field to the React settings UI in `assets/js/`.

---

## Template System

Templates are loaded with a fallback to the theme:

```php
// Theme override takes precedence
$template = locate_template( 'hiretalent/single-hiretalent_job.php' );
if ( ! $template ) {
    $template = HIRETALENT_PATH . 'templates/single-hiretalent_job.php';
}
load_template( $template );
```

**Adding a new template:**
1. Create the file in `templates/`.
2. Update the template loader to include it in the override lookup.
3. Document the new template in `docs/user-guide.md`.

---

## Assets (CSS/JS)

Assets are enqueued in the relevant Admin/Frontend class. Use the constants for paths:

```php
wp_enqueue_style(
    'hiretalent-frontend',
    HIRETALENT_URL . 'assets/css/frontend.css',
    [],
    HIRETALENT_VERSION
);

wp_enqueue_script(
    'hiretalent-frontend',
    HIRETALENT_URL . 'assets/js/frontend.js',
    [ 'jquery' ],
    HIRETALENT_VERSION,
    true
);
```

**Asset handles always use the `hiretalent-` prefix.**

---

## Adding New Features

Follow this pattern when adding a new module:

1. **Create the class** in the appropriate namespace folder (`Admin/`, `Frontend/`, `Modules/`).
2. **Register it** in `Inc/Manager.php` so it initialises on load.
3. **Add hooks** inside the class constructor or an `init()` method.
4. **Add settings** (if needed) following the Settings API section above.
5. **Write the template** (if needed) in `templates/`.
6. **Document the hooks/filters** in this file.

---

## Local Development Setup

### Requirements

- PHP 7.4+
- Composer
- WordPress 5.8+ (local install, e.g. via [Herd](https://herd.laravel.com/), LocalWP)
- Node.js + npm (only if editing React admin assets)

### Steps

```bash
# Clone the repo into your WordPress plugins directory
git clone https://github.com/hmbashar/hiretalent.git wp-content/plugins/hiretalent

# Install PHP dependencies
cd wp-content/plugins/hiretalent
composer install

# (Optional) Install JS dependencies and build assets
npm install
npm run dev
```

> **Note:** The `vendor/` and `node_modules/` directories are git-ignored. Always run `composer install` after cloning.

---

## Code Standards

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).
- All output must be escaped (`esc_html()`, `esc_attr()`, `wp_kses_post()`, etc.).
- All input must be sanitized before saving (`sanitize_text_field()`, `absint()`, etc.).
- Nonces must be used for all form submissions and AJAX requests.
- Prefix all global functions, hooks, and options with `hiretalent_`.
- Classes must be namespaced under `HireTalent\`.

---

## Release Checklist

Before tagging a release:

- [ ] Bump version in `hiretalent.php` (plugin header) and `define('HIRETALENT_VERSION', ...)`.
- [ ] Update `readme.txt` changelog section.
- [ ] Run `composer install --no-dev --optimize-autoloader`.
- [ ] Run `npm run bundle` to compile production assets.
- [ ] Verify `.distignore` excludes dev-only files (docs, node_modules, tests, etc.).
- [ ] Test activation, deactivation, and core features on a clean WordPress install.
- [ ] Ensure no PHP warnings/errors (`WP_DEBUG = true`).
- [ ] Submit to WordPress.org SVN under username `hmbashar`.

---

*For user documentation, see [user-guide.md](./user-guide.md).*
