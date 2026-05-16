=== Talentora – Simple & Powerful Job Board ===

Contributors: hmbashar
Tags: job board, job listing, recruitment, employment, careers
Requires at least: 5.8
Tested up to: 6.9
Stable tag: 0.0.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple yet powerful job board plugin for WordPress. Post jobs, manage applications, and help employers find the right talent.

== Description ==

**Talentora** is a lightweight, developer-friendly job board plugin that makes it easy to add a professional career page to any WordPress site. Whether you run a small business or a large recruiting platform, Talentora gives you all the tools you need to post jobs, categorize them, and let candidates apply — all without leaving WordPress.

= Key Features =

**Job Management**

* **Custom Post Type** – A dedicated `Job` post type with clean, SEO-friendly URLs (`/job/job-title/`).
* **Job Categories & Types** – Flexible hierarchical taxonomies to organize listings (e.g., Engineering, Remote, Full-Time).
* **Rich Job Meta Fields** – Location, salary range, currency symbol, application deadline, company name, company website, and company logo.
* **Job Filled Flag** – Mark any job as filled to automatically stop accepting new applications.
* **Shortcode-Powered Listings** – Display a filterable job board anywhere with `[talentora_jobs]`.

**Application Management**

* **Built-in Application Form** – No third-party form plugin required. The `[talentora_application_form]` shortcode renders a complete form (name, email, phone, cover letter, resume upload).
* **Resume Upload** – Supports PDF, DOC, DOCX files up to 5 MB; stored privately in the WordPress Media Library.
* **Applications Inbox** – All submissions are stored as a private custom post type (`talentora_app`) and viewable under Talentora → Applications.
* **Status Workflow** – Configurable application statuses (Pending, Reviewed, Shortlisted, Rejected, Hired) with per-application status selector.
* **Bulk Status Actions** – Change the status of multiple applications at once directly from the list screen.
* **CSV Export** – Export all applications to a spreadsheet-ready CSV file with one click.
* **Secure Resume Download** – Nonce-protected download link for each applicant's CV, accessible only to authorised admins.
* **Activity Log** – Per-application audit trail of every status change, timestamped and attributed to the acting user.

**Email Notifications**

* **Admin New-Application Email** – Notifies the site admin whenever a new application is submitted.
* **Applicant Confirmation Email** – Sends an acknowledgement to the candidate on successful submission.
* **Status-Change Email** – Notifies the applicant whenever their application status is updated.
* **Customisable Templates** – Edit subject lines and message bodies with dynamic placeholders (`{applicant_name}`, `{job_title}`, `{status}`, etc.).
* **Email Log** – View and clear a log of all emails sent by the plugin (Talentora → Settings → Email Logs).

**Settings**

* **General Settings** – Configure the global apply form shortcode, jobs per page, application statuses, and currency symbol.
* **Email Templates** – Customise all three notification email templates from the admin panel.
* **Third-Party Form Support** – Optionally use Contact Form 7, WPForms, Gravity Forms, or any shortcode-based form instead of the built-in form.

**Developer & Extensibility**

* **Template Override Support** – Copy templates to your theme folder and customise without touching plugin files.
* **Developer-Friendly Hooks** – Actions and filters to extend every part of the plugin.
* **Spam Protection** – Honeypot field on the built-in application form silently blocks bot submissions without CAPTCHA.
* **Translation Ready** – Fully internationalised with a `.pot` file included.

= Shortcodes =

Display the job listing:

`[talentora_jobs]`

Optional attribute: `posts_per_page` — Number of jobs to show per page.

Display the built-in application form:

`[talentora_application_form]`

Optional attribute: `job_id` — The job post ID to link the form to (defaults to the current post).

Display an external apply form (third-party plugin):

`[talentora_apply_form]`

Optional attribute: `form_shortcode` — Override the global apply form shortcode for a specific placement.

= Documentation =

Full documentation is available in the `docs/` folder inside the plugin:

* **User Guide** – Installation, job creation, settings, shortcodes, and template override.
  `docs/user-guide.md` | https://github.com/hmbashar/talentora/blob/main/docs/user-guide.md

* **Application Management** – Built-in application form, applications inbox, status workflow, bulk actions, CSV export, resume download, email notifications, activity log, and spam protection.
  `docs/application-management.md` | https://github.com/hmbashar/talentora/blob/main/docs/application-management.md

* **Developer Guide** – Architecture, namespace/autoloading, post types, meta fields reference, hooks & filters, shortcodes, Settings API, template system, assets, and release checklist.
  `docs/developer-guide.md` | https://github.com/hmbashar/talentora/blob/main/docs/developer-guide.md

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
* `talentora_job_card_classes` – Add extra CSS classes to a job card.

**Actions**

* `talentora_before_job_list` – Fires before the job listing is rendered.
* `talentora_after_job_list` – Fires after the job listing is rendered.
* `talentora_before_single_job` – Fires before single job content (passes `$job_id`).
* `talentora_after_single_job` – Fires after single job content (passes `$job_id`).

= Privacy =

This plugin stores applicant-submitted data (name, email, phone, cover letter, resume) as part of its job application processing. This data is stored in the WordPress database and the Media Library, and is accessible only to authorised administrators. Site owners are responsible for disclosing this data collection in their privacy policy. The plugin does not share data with any external service and does not set any cookies on the frontend.

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

1. **Job Post Editor** – The WordPress block editor showing a job post with the Job Details metabox: location, salary range, deadline, company name, logo, and the "Job Filled" toggle.
2. **Single Job Page** – The public-facing single job detail page displaying job meta (location, salary, deadline, company) and the built-in application form.
3. **Applications List** – The Talentora → Applications admin screen showing submitted applications with columns for applicant name, job, email, phone, status, date, and the CSV Export button.
4. **Settings Page** – The Talentora → Settings admin panel with the General Settings tab (apply form shortcode, jobs per page, application statuses, currency symbol) and the Email Templates and Email Logs tabs.

== Changelog ==

= 0.0.1 – 2025-03-01 =
* Initial release.
* Custom post type `talentora_job` with clean permalink structure (`/job/job-title/`).
* Job Categories (`talentora_job_category`) and Job Types (`talentora_job_type`) hierarchical taxonomies.
* Job details meta: location, salary min/max, currency symbol, application deadline, company name, company website, company logo, job-filled flag.
* `[talentora_jobs]` shortcode with `posts_per_page` attribute.
* `[talentora_apply_form]` shortcode with `form_shortcode` attribute (third-party form integration).
* Built-in application form shortcode `[talentora_application_form]` with name, email, phone, cover letter, and resume upload (PDF/DOC/DOCX, max 5 MB).
* Private `talentora_app` custom post type to store submitted applications.
* Applications list admin screen with columns: applicant, job, email, phone, status, date.
* Configurable application status workflow (Pending, Reviewed, Shortlisted, Rejected, Hired).
* Bulk status update actions on the applications list screen.
* CSV export of all applications.
* Secure nonce-protected resume download for administrators.
* Per-application activity log tracking status changes.
* Admin new-application email notification.
* Applicant submission confirmation email.
* Status-change email notification to applicant.
* Customisable email templates with dynamic placeholders.
* Email log viewer and clear function (Talentora → Settings → Email Logs).
* Honeypot spam protection on the built-in application form.
* Template override support for `single-talentora_job.php` and `archive-talentora_job.php`.
* Settings page with General, Email Templates, and Email Logs tabs.
* Developer hooks: `talentora_before_job_list`, `talentora_after_job_list`, `talentora_before_single_job`, `talentora_after_single_job`, `talentora_jobs_query_args`, `talentora_apply_form_shortcode`, `talentora_currency_symbol`, `talentora_job_card_classes`.
* Translation-ready with `.pot` file.

== Upgrade Notice ==

= 0.0.1 =
Initial release. No upgrade steps required.
