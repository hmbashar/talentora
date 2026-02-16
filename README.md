# HireTalent – Simple & Powerful Job Board Plugin

A clean, modern WordPress job board plugin that helps you manage job listings with ease. Built with OOP architecture and WordPress best practices.

## Features

- ✅ Custom Post Type for Jobs with clean permalink structure (`/job/%postname%/`)
- ✅ Job Categories and Job Types taxonomies
- ✅ Comprehensive job meta fields (location, salary, deadline, company info)
- ✅ Job listing shortcode with filters
- ✅ Apply form integration via shortcode
- ✅ Template override support
- ✅ Developer-friendly hooks and filters
- ✅ Responsive design
- ✅ React-based admin settings

## Installation

1. Upload the `hiretalent` folder to `/wp-content/plugins/`
2. Run `composer install` in the plugin directory to generate autoloader
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Visit **HireTalent > Settings** to configure the plugin

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Composer (for development)

## Quick Start

### 1. Create Your First Job

1. Go to **HireTalent > Add New** in your WordPress admin
2. Fill in the job title and description
3. Complete the **Job Details** metabox with:
   - Location
   - Salary range
   - Application deadline
   - Company information
   - Company logo
4. Assign job categories and types
5. Publish the job

### 2. Configure Settings

Go to **HireTalent > Settings** and configure:

- **Apply Form Shortcode**: Enter your contact form shortcode (e.g., `[contact-form-7 id="123"]`)
- **Jobs Per Page**: Set how many jobs to display per page (default: 10)

### 3. Display Jobs on Your Site

Use the `[hiretalent_jobs]` shortcode on any page or post to display the job listing with filters.

## Shortcodes

### Job List Shortcode

Display a filterable list of jobs:

```
[hiretalent_jobs]
```

**Attributes:**
- `posts_per_page` - Number of jobs to display (default: from settings)

**Example:**
```
[hiretalent_jobs posts_per_page="20"]
```

### Apply Form Shortcode

Display the application form (usually used in templates):

```
[hiretalent_apply_form]
```

**Attributes:**
- `form_shortcode` - Override the global form shortcode

**Example:**
```
[hiretalent_apply_form form_shortcode="[contact-form-7 id='456']"]
```

## Permalinks

After activation, the plugin automatically sets up clean permalinks:

- **Single Job**: `yourdomain.com/job/job-title/`
- **Job Archive**: `yourdomain.com/jobs/`

If permalinks don't work, go to **Settings > Permalinks** and click "Save Changes" to flush rewrite rules.

## Template Override

You can override plugin templates by copying them to your theme:

1. Create a folder: `your-theme/hiretalent/`
2. Copy template files from `plugins/hiretalent/templates/` to your theme folder
3. Customize as needed

**Available Templates:**
- `single-hiretalent_job.php` - Single job page
- `archive-hiretalent_job.php` - Job archive page

## Developer Hooks

### Filters

**Modify job query arguments:**
```php
add_filter('hiretalent_jobs_query_args', function($args) {
    // Modify $args
    return $args;
});
```

**Modify apply form shortcode:**
```php
add_filter('hiretalent_apply_form_shortcode', function($shortcode, $job_id) {
    // Modify $shortcode based on job
    return $shortcode;
}, 10, 2);
```

### Actions

**Before job list:**
```php
add_action('hiretalent_before_job_list', function() {
    echo '<div class="custom-content">Custom content before jobs</div>';
});
```

**After job list:**
```php
add_action('hiretalent_after_job_list', function() {
    echo '<div class="custom-content">Custom content after jobs</div>';
});
```

## Job Meta Fields

All job meta fields use the `hiretalent_` prefix:

- `hiretalent_location` - Job location (text)
- `hiretalent_salary_min` - Minimum salary (number)
- `hiretalent_salary_max` - Maximum salary (number)
- `hiretalent_deadline` - Application deadline (date)
- `hiretalent_company_name` - Company name (text)
- `hiretalent_company_website` - Company website (URL)
- `hiretalent_company_logo_id` - Company logo attachment ID (number)
- `hiretalent_is_filled` - Job filled status (boolean: '1' or '0')
- `hiretalent_expiry_date` - Job expiry date (date, optional)

**Example - Get job meta:**
```php
$location = get_post_meta($job_id, 'hiretalent_location', true);
$company = get_post_meta($job_id, 'hiretalent_company_name', true);
```

## Styling

The plugin includes minimal, clean CSS that you can customize:

- `assets/css/frontend.css` - Frontend styles
- `assets/css/admin.css` - Admin styles

You can override styles in your theme's CSS file.

## Taxonomies

### Job Categories
- Taxonomy: `hiretalent_job_category`
- Hierarchical: Yes
- Slug: `job-category`

### Job Types
- Taxonomy: `hiretalent_job_type`
- Hierarchical: Yes
- Slug: `job-type`

## Troubleshooting

### Jobs return 404 error
Go to **Settings > Permalinks** and click "Save Changes" to flush rewrite rules.

### Apply form not showing
1. Make sure you've configured the apply form shortcode in **HireTalent > Settings**
2. Verify your contact form plugin is active and the shortcode is correct
3. Check that the job is not marked as "filled"

### Styles not loading
Clear your cache (browser cache and any caching plugins).

## Support

For issues, feature requests, or contributions:
- GitHub: https://github.com/hmbashar/hiretalent
- Author: Md Abul Bashar

## Changelog

### 1.0.0
- Initial release
- Custom post type for jobs
- Job categories and types
- Job meta fields
- Job listing with filters
- Apply form integration
- Template override support
- Developer hooks

## License

GPL-2.0-or-later

## Credits

Developed by Md Abul Bashar
