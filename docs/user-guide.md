# Talentora – User Guide

Welcome to **Talentora**, the simple and powerful job board plugin for WordPress. This guide will help you get up and running quickly.

---

## Table of Contents

1. [Installation](#installation)
2. [Getting Started](#getting-started)
3. [Creating a Job Listing](#creating-a-job-listing)
4. [Managing Job Categories & Types](#managing-job-categories--types)
5. [Plugin Settings](#plugin-settings)
6. [Displaying Jobs on Your Site](#displaying-jobs-on-your-site)
7. [Template Override](#template-override)
8. [Troubleshooting](#troubleshooting)
9. [FAQ](#faq)

---

## Installation

### Option A: Install from WordPress.org

1. In your WordPress admin, go to **Plugins → Add New**.
2. Search for **Talentora**.
3. Click **Install Now** and then **Activate**.

### Option B: Manual Installation

1. Download the plugin `.zip` file.
2. Go to **Plugins → Add New → Upload Plugin**.
3. Select the `.zip` file and click **Install Now**.
4. Activate the plugin.

> **Note:** After activation, visit **Settings → Permalinks** and click **Save Changes** to flush rewrite rules so job URLs work correctly.

---

## Getting Started

After activating the plugin, a new **Talentora** menu item will appear in your WordPress admin sidebar. From there you can:

- **Jobs** – View, add, edit, and delete job listings.
- **Job Categories** – Manage categories like "Engineering", "Marketing", etc.
- **Job Types** – Manage types like "Full-Time", "Part-Time", "Remote", etc.
- **Settings** – Configure global plugin options.

---

## Creating a Job Listing

1. Go to **Talentora → Add New**.
2. Enter the **Job Title** and write the full **Job Description** in the editor.
3. Fill out the **Job Details** metabox on the right:

| Field               | Description                                           |
|---------------------|-------------------------------------------------------|
| Location            | City, country, or "Remote"                            |
| Minimum Salary      | Minimum salary value (numeric)                        |
| Maximum Salary      | Maximum salary value (numeric)                        |
| Currency Symbol     | Symbol to display (e.g., `$`, `£`, `€`)               |
| Application Deadline| Last date to apply (date picker)                      |
| Company Name        | Name of the hiring company                            |
| Company Website     | URL of the company website                            |
| Company Logo        | Upload or select a logo from the Media Library        |
| Job Filled          | Check this to mark the job as no longer accepting applications |

4. Assign **Job Categories** and **Job Types** from the taxonomy panels.
5. Click **Publish** to make the job live.

---

## Managing Job Categories & Types

### Job Categories

- Go to **Talentora → Job Categories**.
- Add categories like "Engineering", "Design", "Sales", etc.
- These are hierarchical (parent/child supported).

### Job Types

- Go to **Talentora → Job Types**.
- Add types like "Full-Time", "Part-Time", "Freelance", "Remote".
- These are also hierarchical.

---

## Plugin Settings

Navigate to **Talentora → Settings** to configure:

| Setting                  | Description                                                   |
|--------------------------|---------------------------------------------------------------|
| Apply Form Shortcode     | Paste your contact form shortcode (e.g., from CF7, WPForms)  |
| Jobs Per Page            | How many jobs to list per page (default: 10)                  |
| Currency Symbol          | Global currency symbol shown with salary figures              |

---

## Displaying Jobs on Your Site

### Job Listing Shortcode

Paste the following shortcode on any Page or Post to show the filterable job board:

```
[talentora_jobs]
```

**Optional attributes:**

| Attribute       | Default          | Description                         |
|-----------------|------------------|-------------------------------------|
| posts_per_page  | From settings    | Number of jobs to display per page  |

**Example:**
```
[talentora_jobs posts_per_page="15"]
```

### Apply Form Shortcode

The apply form is automatically embedded on the single job page template. You can also place it manually:

```
[talentora_apply_form]
```

**Optional attributes:**

| Attribute      | Description                               |
|----------------|-------------------------------------------|
| form_shortcode | Override the global apply form shortcode  |

---

## Template Override

You can customize how job pages look without editing plugin files:

1. Create a folder in your active theme: `your-theme/talentora/`
2. Copy the template file(s) you want to override from:
   ```
   wp-content/plugins/talentora/templates/
   ```
   into your theme folder.
3. Modify the copied files as needed.

**Available templates:**

| Template File                  | Used For                  |
|--------------------------------|---------------------------|
| `single-talentora_job.php`    | Single job detail page    |
| `archive-talentora_job.php`   | All jobs archive page     |

---

## Troubleshooting

### Job pages show a 404 error
Go to **Settings → Permalinks** and click **Save Changes**. This flushes WordPress rewrite rules.

### The apply form is not showing
1. Make sure the apply form shortcode is configured in **Talentora → Settings**.
2. Confirm your form plugin (e.g., Contact Form 7) is active and the shortcode is valid.
3. Check that the job is not marked as "filled".

### Styles are not loading correctly
Clear your browser cache and any caching plugin (e.g., WP Rocket, W3 Total Cache).

---

## FAQ

**Q: Can I display jobs in multiple places on my site?**  
A: Yes! Just add `[talentora_jobs]` to any page or post.

**Q: Can I use my own apply form?**  
A: Yes. Any shortcode-based form plugin (Contact Form 7, WPForms, Gravity Forms, etc.) works. Paste the shortcode in **Settings → Apply Form Shortcode**.

**Q: Does the plugin support multiple currencies?**  
A: You can set a global currency symbol in Settings. Per-job currency support may be added in a future update.

**Q: Will my customizations break when I update the plugin?**  
A: Not if you use the Template Override method described above. Never edit plugin files directly.

---

*For further help, visit the [GitHub repository](https://github.com/hmbashar/talentora) or the plugin's WordPress.org support forum.*
