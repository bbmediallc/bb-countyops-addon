# B&B Media CountyOps Addon: notes for Claude

Read README.md first. It is the source of truth for layout, the catalog of
what every file does, and releases. Keep that catalog up to date with any
change.

- Installed on live county office websites that must stay ADA / WCAG 2.1 AA compliant. Treat every change as a production release, and re-check accessibility after visual changes.
- Never rename `plugin/bb-countyops-addon.php`, the `bb-countyops-addon` zip folder, the `[ada_sitemap]` shortcode, or its classes.
- `includes/output.php` reproduces the exact order Simple Custom CSS and JS printed the shared CSS/JS. Changing the order can change which CSS rule wins.
- Anything that differs between counties is a field in `bbcoa_county_fields()` (includes/counties.php) plus `counties/*/county.php`, never hard-coded. Shared CSS/JS uses `{{placeholder}}` tokens filled in by `bbcoa_replacements()`.
- PHP must run on 7.4. CI lints on 7.4, 8.2 and 8.3. Prefix functions, options and transients with `bbcoa_`.
- Site access: SSH + WP-CLI (key and hosts in Austin's collectors_ssh_access.txt). Back up (`wp db export` outside public_html) before changing a site, and do staging sites before Stoddard and Pulaski.
- WP External Links rewrites `<a href>` in any output, including WP-CLI output; base64-encode data you export over SSH.
- Releases reach sites only while the GitHub repo is public; Austin manages its visibility by hand. Never change it yourself, and don't remind him to.
- Sister projects: BB Media Core (github.com/bbmediallc/bbmediacore) and B&B Media Elementor Add-Ons (github.com/bbmediallc/bb-elementor-addons). The updater is shared in design.
- Never run `wp ... --context=admin` on these sites: Elementor Pro fatals in that mode, and WordPress then emails a "technical issue" notice to the admin address. Keep WP-CLI work read-only on Stoddard and Pulaski (live) unless Austin asks for a change.
