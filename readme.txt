=== Talentora – Simple & Powerful Job Board ===

Contributors: hmbashar
Tags: job board, job listing, recruitment, employment, careers
Requires at least: 5.8
Tested up to: 6.9
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple yet powerful job board plugin for WordPress. Post jobs, manage applications, and help employers find the right talent.

== Description ==

**Talentora** is a lightweight, developer-friendly job board plugin that makes it easy to add a professional career page to any WordPress site. Whether you run a small business or a large recruiting platform, Talentora gives you all the tools you need to post jobs, categorize them, and let candidates apply — all without leaving WordPress.

= Key Features =

* **Custom Post Type** – A dedicated `Job` post type with clean, SEO-friendly URLs (`/job/job-title/`).
* **Job Categories & Types** – Flexible hierarchical taxonomies to organize your listings (e.g., Engineering, Remote, Full-Time).
* **Rich Job Meta Fields** – Location, salary range, currency, application deadline, company name/logo, and more.
* **Shortcode-Powered** – Display a filterable job board anywhere using `[talentora_jobs]`.
* **Apply Form Integration** – Works with any shortcode-based form plugin (Contact Form 7, WPForms, Gravity Forms).
* **Template Override Support** – Copy templates to your theme and customize without editing plugin files.
* **Developer-Friendly Hooks** – Actions and filters to extend every part of the plugin.
* **React-Based Settings** – Clean, modern admin settings page.
* **Translation Ready** – Fully internationalised with `.pot` file included.

= Shortcodes =

Display the job listing:

`[talentora_jobs]`

Optional attribute: `posts_per_page` — Number of jobs to show per page.

Display the apply form:

`[talentora_apply_form]`

Optional attribute: `form_shortcode` — Override the global apply form shortcode for a specific placement.

= Template Override =

1. Create a folder `talentora/` inside your active theme directory.
2. Copy the template file(s) from `wp-content/plugins/talentora/templates/` to your theme folder.
3. Customise the copied files.

Available templates: `single-talentora_job.php`, `archive-talentora_job.php`.

= Developer Hooks =

**Filters**

* `talentora_jobs_query_args` – Modify the WP_Query arguments for the job listing.
* `talentora_apply_form_shortcode` – Modify the apply form shortcode string per job.
* `talentora_currency_symbol` – Filter the currency symbol.

**Actions**

* `talentora_before_job_list` – Fires before the job listing is rendered.
* `talentora_after_job_list` – Fires after the job listing is rendered.

= Privacy =

This plugin does not collect or store any personal data. It does not set any cookies. It stores only the job-related data that site administrators explicitly enter. For any GDPR considerations related to job application forms, please refer to the respective form plugin's privacy documentation.

== Installation ==

1. Upload the `talentora` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **Settings > Permalinks** and click **Save Changes** to flush rewrite rules.
4. Visit **Talentora > Settings** to configure your apply form shortcode and other options.
5. Add job listings under **Talentora > Add New**.
6. Place the `[talentora_jobs]` shortcode on any page to display your job board.

== Frequently Asked Questions ==

= Do I need a specific form plugin to use Talentora? =

No. You can use any shortcode-based form plugin such as Contact Form 7, WPForms, Gravity Forms, or others. Simply paste the form shortcode into **Talentora > Settings > Apply Form Shortcode**.

= How do I customise the job listing page design? =

You can override the plugin templates from your theme. Create a `talentora/` folder in your active theme and copy templates from `wp-content/plugins/talentora/templates/` there. You can also add custom CSS in your theme's stylesheet.

= Can I display the job board in multiple places? =

Yes. Simply add the `[talentora_jobs]` shortcode to any page or post.

= My job pages return a 404 error. How do I fix it? =

Go to **Settings > Permalinks** and click **Save Changes** to flush WordPress rewrite rules.

= How do I change the currency symbol? =

Go to **Talentora > Settings** and update the **Currency Symbol** field. The symbol will be displayed alongside salary information across all job listings.

= Can themes override plugin templates? =

Yes. Copy the template file(s) to a `talentora/` folder in your theme. The plugin will automatically use your theme's version instead of its own.

= Is Talentora compatible with the latest version of WordPress? =

Yes. Talentora is tested with WordPress 6.9 and kept up to date with each WordPress release.

= Will Talentora slow down my site? =

No. Talentora only loads its assets on pages where they are needed (job listing and single job pages). The plugin is lightweight by design.

== Screenshots ==

1. Job listing page with filterable shortcode output.
2. Single job detail page with company info and apply form.
3. Add/edit job screen with Job Details metabox in the WordPress admin.
4. Plugin settings page (Apply Form Shortcode, Jobs Per Page, Currency Symbol).
5. Job Categories and Job Types management screens.

== Changelog ==

= 1.0.0 – 2025-03-01 =
* Initial release.
* Custom post type `talentora_job` with clean permalink structure.
* Job Categories (`talentora_job_category`) and Job Types (`talentora_job_type`) taxonomies.
* Job details meta: location, salary range, currency symbol, deadline, company info, logo.
* `[talentora_jobs]` shortcode with `posts_per_page` attribute.
* `[talentora_apply_form]` shortcode with `form_shortcode` attribute.
* Template override support for `single-talentora_job.php` and `archive-talentora_job.php`.
* React-based admin settings page (apply form shortcode, jobs per page, currency symbol).
* Developer hooks: `talentora_before_job_list`, `talentora_after_job_list`, `talentora_jobs_query_args`, `talentora_apply_form_shortcode`, `talentora_currency_symbol`.
* Translation-ready with `.pot` file.

== Upgrade Notice ==

= 1.0.0 =
Initial release. No upgrade steps required.
