# Buffalo Cannabis Network WordPress Theme

This repository contains the Buffalo Cannabis Network WordPress theme that powers https://buffalocannabisnetwork.com. Follow this guide to build a clean distributable ZIP, install it on a staging site, and configure the required functionality.

## 1. Prerequisites

Before packaging or installing the theme, make sure the environment meets these requirements:

- WordPress 6.4 or later (tested on 6.5)
- PHP 8.0 or later
- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) 6.x
- Recommended: [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/) (for notification deliverability)
- Optional: any membership or form plugins you already rely on in production

> **Why ACF Pro?** The theme ships with a large collection of local field groups (see `includes/acf-fields`). Without ACF Pro the custom post types and dashboards will not expose their structured fields.

## 2. Audit summary

The `bcn-wp-theme-clean` folder now includes every dependency referenced in `functions.php`, including:

- `admin-theme/` for the custom branded WordPress admin experience
- `includes/custom-post-types/` with additional CPT helpers
- `includes/automation/` with scheduled and event-driven workflows

Missing directories previously triggered fatal errors when the theme loaded. These files are back in place and safeguarded by `bcn_require_theme_file()` so WordPress will log a warning instead of crashing if something goes missing in the future.

Script and style enqueues are now versioned automatically from the theme header which prevents WordPress from serving stale browser caches after updates.

## 3. Build a distributable ZIP

Run the packaging script from the repository root:

```bash
./scripts/package-theme.sh
```

The script performs the following:

1. Copies the contents of `bcn-wp-theme-clean` into `dist/buffalo-cannabis-network/`
2. Removes development artefacts (`.git`, `node_modules`, `vendor`, macOS files)
3. Generates `dist/buffalo-cannabis-network.zip`

This ZIP file can be uploaded directly via **Appearance → Themes → Add New → Upload Theme**.

## 4. Install on a staging site

1. Ensure ACF Pro is installed and activated.
2. Upload and activate `buffalo-cannabis-network.zip` through the WordPress theme installer.
3. Visit **ACF → Tools → Sync** to import the included JSON field groups if prompted.
4. Navigate to **Settings → Permalinks** and click **Save** to register the custom post type slugs (`events`, `members`).
5. Assign menus under **Appearance → Menus** to the theme locations (Primary, Footer, Community).
6. Configure widgets in the Sidebar, Footer, and Community widget areas as needed.
7. Visit **Appearance → Customize** to update hero imagery, brand colors, and footer content.
8. Test the member portal (`/member-portal`) and member submission workflows to confirm email notifications fire from your staging SMTP provider.

## 5. Launch checklist

- [ ] Confirm branded admin styles are visible inside `/wp-admin`
- [ ] Verify custom post types (Events, Members) load their meta boxes
- [ ] Submit a test member registration and ensure the CPT entry is created
- [ ] Review community features and testimonials for any missing assets
- [ ] Clear caches/CDNs before pointing DNS at the new staging site

With the ZIP package and checklist above, the theme is ready for plug-and-play deployment on your staging instance.
