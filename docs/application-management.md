# Talentora – Application Management

This guide covers Talentora's built-in application management system: how candidates apply, how submissions are stored and tracked, and how administrators manage the full applicant pipeline — all without leaving WordPress.

---

## Table of Contents

1. [Overview](#overview)
2. [Built-in Application Form](#built-in-application-form)
3. [Application Post Type](#application-post-type)
4. [Application Statuses](#application-statuses)
5. [Applications List (Admin)](#applications-list-admin)
6. [Viewing an Application](#viewing-an-application)
7. [Resume Download](#resume-download)
8. [Bulk Status Actions](#bulk-status-actions)
9. [CSV Export](#csv-export)
10. [Email Notifications](#email-notifications)
11. [Activity Log](#activity-log)
12. [Spam Protection (Honeypot)](#spam-protection-honeypot)

---

## Overview

Talentora includes a **fully self-contained application pipeline**. No third-party form plugin or CRM is required:

- Candidates fill out a built-in application form on the single job page.
- Submissions are stored as a private `talentora_app` custom post type in WordPress.
- Admins manage applications from the **Talentora → Applications** list screen.
- Automated email notifications are sent to both the administrator and the applicant.
- Status changes are tracked with a per-application **Activity Log**.

> **Third-party form plugin support:** If you prefer to use Contact Form 7, WPForms, Gravity Forms, or similar, configure the global **Apply Form Shortcode** under **Talentora → Settings**. The plugin will render that shortcode on single job pages instead of the built-in form.

---

## Built-in Application Form

Shortcode: `[talentora_application_form]`

The form is automatically embedded on the single job page template. You can also place it manually on any page:

```
[talentora_application_form job_id="123"]
```

| Attribute | Default         | Description                            |
|-----------|-----------------|----------------------------------------|
| `job_id`  | Current post ID | The job post ID to associate the form  |

### Form Fields

| Field        | Required | Type     | Notes                              |
|--------------|----------|----------|------------------------------------|
| Full Name    | ✅        | Text     |                                    |
| Email        | ✅        | Email    | Must be a valid email address       |
| Phone        | ✅        | Tel      |                                    |
| Resume / CV  | ✅        | File     | PDF, DOC, DOCX — max 5 MB          |
| Cover Letter | ✅        | Textarea |                                    |

### Submission Flow

1. Candidate submits the form (supports both standard POST and AJAX).
2. Server-side validation runs (required fields, file type/size, nonce check).
3. On success: resume is uploaded to the Media Library, an application post is created, email notifications are dispatched, and the candidate sees a success message.
4. On failure: errors are shown inline with fields pre-filled so the candidate can correct and resubmit.

---

## Application Post Type

Applications are stored as a private custom post type: `talentora_app`.

| Property          | Value                                           |
|-------------------|-------------------------------------------------|
| Post type key     | `talentora_app`                                 |
| Public            | No (not accessible via front-end URLs)          |
| Shown in admin UI | Yes (under Talentora → Applications)            |
| REST API          | Disabled                                        |

### Stored Meta Fields

| Meta Key                        | Description                          |
|---------------------------------|--------------------------------------|
| `talentora_job_id`             | ID of the associated job post        |
| `talentora_applicant_name`     | Applicant's full name                |
| `talentora_applicant_email`    | Applicant's email address            |
| `talentora_applicant_phone`    | Applicant's phone number             |
| `talentora_cover_letter`       | Cover letter text                    |
| `talentora_resume_id`          | Attachment ID of the uploaded resume |
| `talentora_application_status` | Current workflow status              |

---

## Application Statuses

The default statuses are:

> **Pending → Reviewed → Shortlisted → Rejected → Hired**

You can customise this list under **Talentora → Settings → Application Statuses** by entering a comma-separated list of status names.

> **Warning:** Changing or removing a status label does not retroactively update existing applications that already carry the old label.

---

## Applications List (Admin)

Navigate to **Talentora → Applications** to see all submitted applications in a sortable table.

| Column     | Description                                     |
|------------|-------------------------------------------------|
| Applicant  | Name (links to the application detail view)     |
| Job        | Job title (links to the job edit screen)        |
| Email      | Clickable `mailto:` link                        |
| Phone      | Phone number                                    |
| Status     | Current workflow status                         |
| Date       | Submission date/time                            |

---

## Viewing an Application

Click an applicant's name to open the **Application Details** metabox, which shows:

- Applicant information (name, email, phone)
- The job applied for (with link to the job edit screen)
- Submission date and time
- Resume download button
- Cover letter
- Status selector (dropdown to change the current status)
- Activity log (full history of status changes)

---

## Resume Download

Resumes are stored as private Media Library attachments. Admins download them via a secure, nonce-protected link shown on the application detail screen:

1. Open an application.
2. In the **Application Details** metabox, click **Download Resume**.
3. The file is served as a direct download (`Content-Disposition: attachment`).

Access is restricted to users with `edit_post` capability for that application.

---

## Bulk Status Actions

On the **Talentora → Applications** list screen:

1. Select one or more applications using the checkboxes.
2. Open the **Bulk actions** dropdown — one entry per configured status (e.g., *Change status to Shortlisted*).
3. Click **Apply**.

When a status changes via bulk action, a status-change email notification is automatically sent to each affected applicant, and the change is recorded in the Activity Log.

---

## CSV Export

Export all applications to a spreadsheet-ready CSV file:

1. Go to **Talentora → Applications**.
2. Click the **Export CSV** button at the top of the list.
3. A CSV file named `applications-YYYY-MM-DD.csv` is downloaded immediately.

### CSV Columns

| Column         | Source                            |
|----------------|-----------------------------------|
| ID             | Application post ID               |
| Applicant Name | `talentora_applicant_name` meta   |
| Email          | `talentora_applicant_email` meta  |
| Phone          | `talentora_applicant_phone` meta  |
| Job Title      | Linked job post title             |
| Status         | `talentora_application_status`    |
| Date Submitted | Post creation date (site timezone)|

Access is restricted to users with the `edit_others_posts` capability.

---

## Email Notifications

Three automated emails are sent by the built-in notification system:

| Email                       | Recipient      | Trigger                          |
|-----------------------------|----------------|----------------------------------|
| New Application (Admin)     | Site admin     | Application submitted            |
| Application Received (Applicant) | Applicant | Application submitted            |
| Status Change               | Applicant      | Status updated by admin          |

### Customising Email Templates

Go to **Talentora → Settings → Email Templates** to customise the subject and body of each email.

**Available placeholders:**

| Placeholder          | Replaced with                       |
|----------------------|-------------------------------------|
| `{applicant_name}`  | Applicant's full name               |
| `{job_title}`       | Job post title                      |
| `{site_name}`       | WordPress site name                 |
| `{status}`          | New application status              |
| `{application_url}` | Admin link to view the application  |

### Email Logs

All sent emails are logged to a file at:
```
wp-content/uploads/talentora-logs/email.log
```

View and clear the log under **Talentora → Settings → Email Logs**.

---

## Activity Log

Every application has a chronological **Activity Log** metabox that records:

- Status changes (including old → new status and the acting user)
- Bulk status changes (labeled as "via bulk action")
- Timestamps in the site's configured timezone and date/time format

The log is read-only and cannot be manually edited.

---

## Spam Protection (Honeypot)

The built-in application form includes a **honeypot field** — a hidden input named `talentora_website`. Legitimate browsers leave it empty; bots typically fill it in. If the field is populated on submission, the server silently pretends the application succeeded without storing any data, protecting your application inbox from automated spam without showing CAPTCHA to real users.

---

*For general usage, see [user-guide.md](./user-guide.md). For developer hooks and extension points, see [developer-guide.md](./developer-guide.md).*
