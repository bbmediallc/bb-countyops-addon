# B&B Media CountyOps Addon

Shared code for B&B Media's county office websites: the accessibility (ADA /
WCAG 2.1 AA) fixes, theme styling and small features that used to be copied
into every site's **Code Snippets** and **Simple Custom CSS and JS** plugins.

The sites it was built from (October 2026) are the Missouri county collector
sites: Wright, Dent, Perry, Laclede, Ralls, Stoddard and Pulaski. They all run
the **GovArnex** theme, Elementor/Elementor Pro, Gravity Forms, TablePress, BB
Media Core, and **CountyOps** (the MID Tax Payment plugin that powers the tax
search and payment shortcodes; classes starting `mid-tax-`). The plugin
assumes that stack.

Anything that's truly specific to one site stays on that site, in Code
Snippets or Simple Custom CSS and JS. See [What stays on each site](#what-stays-on-each-site).

## Folder layout

```
plugin/                        Everything that ships. Becomes bb-countyops-addon/ in the zip.
  bb-countyops-addon.php         Main file: version number and loader. Never rename.
  counties/<id>/county.php       Built-in settings for each county (colors, banner, URLs...).
  includes/
    counties.php                 The list of county settings and how a site finds its county.
    settings-page.php            Dashboard > County Settings.
    setup.php                    Picks the county on activation.
    output.php                   Prints the shared CSS/JS in <head>.
    access.php                   Who can see County Settings.
    updater.php                  Updates from GitHub releases.
    snippets/                    The former Code Snippets snippets, one file each.
  assets/css/, assets/js/        The former Simple Custom CSS and JS items, one file each.
  assets/admin/                  County Settings page styles and script.
scripts/build.sh               Builds dist/bb-countyops-addon.zip.
.github/workflows/             Checks the code and publishes releases on GitHub.
CHANGELOG.md                   What changed in each version (becomes the release notes).
```

## Counties and County Settings

**Dashboard > County Settings** (under BB Media Core's Dashboard menu; under
Settings on a site without BB Media Core). Only **support@bandbmedia.com**
sees it. To allow another account on one site, add to its `wp-config.php`:

```php
define( 'BBCOA_ADMIN_EMAILS', 'support@bandbmedia.com, someone@example.com' );
```

Each site uses one county:

1. The county chosen at the top of County Settings, or
2. if none is chosen, the county whose **Website addresses** include this
   site's address (`www.` and `https://` don't matter).

On activation the plugin saves the match from step 2 so the site remembers its
county even if its address later changes. A site with no match sends
support@bandbmedia.com to County Settings, and until a county is set the shared
code runs with the default colors and no banner or header search.

### County settings

| Setting | Default | What it changes |
|---|---|---|
| County name | | Label only. |
| Website addresses | | One per line. List staging **and** live, e.g. `wright.bandbdev.com` and `wrightcountycollector.com`. |
| Accent color | `#800000` | TablePress table header background, service card hover background, skip-to-content link outline and hover. |
| Accordion link color | `#8c0708` | Links inside accordion/FAQ answers. |
| Footer link hover color | `#AF8C5D` | Footer heading links on hover. |
| Banner image | none | Background of the page header banner (`.page-breadcrumb`) and the search results header. Stored as `/wp-content/uploads/...` when it's on the same site, so it survives the move from staging to live. |
| Banner focal point | `50% 50%` | `background-position` of the banner, e.g. `50% 40%`. |
| Search in header | off | Puts an Elementor search template in the header top bar. |
| Search template | | Which Elementor saved template holds the search form. Each site needs its own (Templates > Saved Templates). |

Every other color in the shared CSS (the `#7f0003` focus outlines etc.) is the
same on all counties and fixed in the files.

### Adding a county

On the new county's site: install and activate the plugin, then County
Settings > **Add a county**. Its Website addresses are pre-filled with the
site's address, and saving makes it that site's county.

Edits in County Settings are saved on that site only. To make them the
built-in settings that ship with the plugin, copy the text at the bottom of
the county's edit page into `plugin/counties/<id>/county.php` and release an
update.

### Going live

Add the live address to the county's **Website addresses** (keep the staging
one too). The site also remembers its county from activation, so forgetting
this doesn't break anything; it just keeps things tidy.

## What each piece does

Everything below runs on every county. "WCAG" references are to WCAG 2.1.

### Former Code Snippets (`plugin/includes/snippets/`)

| File | Was | What it does |
|---|---|---|
| `ada-sitemap.php` | ADA Sitemap | `[ada_sitemap]` shortcode: an accessible HTML sitemap of all published pages (nested) and posts by category, with its own list styles and an `ada-sitemap` body class. Its link color is `#800000` everywhere (Laclede too). |
| `viewport-zoom.php` | Re-Enable Theme Pinch/Zoom | GovArnex's viewport tag blocks pinch-zoom, which fails WCAG 1.4.4 (Resize Text). Rewrites it to `width=device-width, initial-scale=1.0`. Side effects carried over as-is: removes WordPress's resource hints (dns-prefetch/preconnect), and unhooks `wp_no_robots` (deprecated, no-op). |
| `elementor-link-options-open.php` | Keep Elementor Button Settings Open | Elementor editor only: keeps link options (new window, nofollow, attributes) visible instead of behind the gear icon. B&B Media Elementor Add-Ons does the same; both together is harmless. |
| `icon-box-duplicate-links.php` | Fix Duplicate Elmentor Icon Links | A linked Icon Box links both the icon and the title. Hides the icon's link from screen readers and the tab order so there's one link (WCAG 2.4.4). |
| `mobile-menu-button.php` | Fix Mobile Menu Button Issues | Gives the mobile menu opener `role="button"`, a label, `aria-controls` and a live `aria-expanded`; Space opens/closes it; hides the overlay from screen readers (WCAG 4.1.2, 2.1.1). |
| `clickable-home-pods.php` | Clickable Home Pods | Makes the whole GovArnex Features box ("home pod") clickable and draws the focus outline around the whole box (WCAG 2.4.7). |
| `faq-answer-editor.php` | Govarnex FAQ Answer WYSIWYG Editor | GovArnex FAQ widget: rich text editor for answers instead of a plain box, panel items labeled by question, and empty paragraphs hidden. |
| `header-search.php` | Add Search to Header (Laclede) | The **Search in header** county setting. Renders the county's Elementor search template and inserts it after the header's top-left menu. Styles in `assets/css/header-search.css`. |

### Former Simple Custom CSS and JS items (`plugin/assets/`)

All print inline in `<head>` in the same order Simple Custom CSS and JS used,
so the cascade is unchanged: every script first, then the CSS (see
`includes/output.php`).

**JavaScript** (`assets/js/`)

| File | Was | What it does |
|---|---|---|
| `home-pods-match-height.js` | Home Pods Match Height | Evens out the text heights inside each row of home pods so the boxes line up. |
| `fix-duplicate-header-issues.js` | Fix Duplicate Header Issues | GovArnex clones the header for its sticky version. Hides the clone from screen readers and the tab order so nothing is read or tabbed twice. |
| `fix-tax-search-results-headings.js` | Fix Tax Search Results Headings | CountyOps tax search results use `h4`s that skip a level; turns them into `h3`s (WCAG 1.3.1), including results loaded later. |
| `fix-duplicate-nav-label.js` | Fix Duplicate Nav Label | Labels the two `nav.main-menu`s "Main Menu" and "Sticky Main Menu" so screen readers can tell them apart. |
| `fix-accordion-generic-role.js` | Fix Accordion Generic Role | Gives labeled Elementor nested accordions `role="region"`. **Known issue:** runs in `<head>` and calls `observe(document.body)` before `<body>` exists, which throws an error; only its `load` handler does anything. Carried over unchanged. |
| `fix-sub-menu-roles.js` | Fix Sub Menu Roles for Menu Items | Adds `role="menuitem"` to sub-menu links. **Known issue:** runs in `<head>` before the menu exists, so it finds nothing (Aria Labels for Drop Downs does this job). Carried over unchanged. |
| `breadcrumb-h1.js` | Breadcrumb H1 | GovArnex's page title in the banner is an `h2`; makes it the page's `h1` (WCAG 1.3.1, 2.4.6). |
| `fix-resource-box-h3-tags.js` | Fix Resource Box H3 Tags | Home pod titles are `h3`s that break the heading outline; turns them into `span`s. |
| `elementor-toc-header-link-fix.js` | Elementor Table of Contents Header Link Fix | Makes the whole Table of Contents header a keyboard-operable toggle (Enter/Space) with correct `aria-expanded`. |
| `move-button-box-links-to-container.js` | Move Button Box Links to Container | GovArnex service cards had two links (title and button). Wraps the whole card in one link labeled with the title and removes the inner links. |
| `aria-labels-for-drop-downs.js` | Aria Labels for Drop Downs | Desktop: `menuitem` roles and `aria-expanded` on hover/focus for dropdowns. Mobile: labels and `aria-expanded`/`aria-controls` on submenu buttons. |
| `mobile-menu-tabbing.js` | Mobile Menu Tabbing | Mobile menu focus handling: moves focus into the menu when it opens, back to the opener when it closes, Escape closes it, labels the close/scroll-to-top buttons (WCAG 2.4.3, 2.1.2). |
| `injected-aria-labels.js` | Injected Aria Labels | Labels for the mobile menu open/close buttons and scroll-to-top. Overlaps with Mobile Menu Tabbing and `mobile-menu-button.php`; harmless, but could be merged someday. |
| `anchor-scroll-offset.js` | Anchor Scroll Offset | Jump links (`#section`) stop 160px short so the sticky header doesn't cover the target; also handles links arriving with a `#` in the URL. |
| `ada-gravity-forms.js` | ADA Gravity Forms | Hides the honeypot field from screen readers, adds native `required`, makes phone fields `type="tel"`, links error messages to their fields with `aria-describedby`/`aria-invalid` (WCAG 3.3.1, 1.3.5). |
| `ada-accordions.js` | ADA Accordions | GovArnex accordions: keyboard-operable buttons with `aria-expanded`/`aria-controls`, panels with `aria-hidden` (WCAG 4.1.2, 2.1.1). |
| `ada-tables.js` | ADA Tables | TablePress tables: turns the table's heading into a screen-reader `caption`, adds `scope` to column headers, and makes each row's first cell a row header (WCAG 1.3.1). |
| `skip-to-content.js` | Skip to Content | Adds a "Skip to main content" link that appears on focus and jumps to `.page-content` (WCAG 2.4.1). Uses the county's **Accent color**. |

**CSS** (`assets/css/`)

| File | Was | What it does |
|---|---|---|
| `county-ops-overrides.css` | County Ops Overrides | CountyOps tax search/payment forms: visible focus outlines, button hover colors, result name size. |
| `backend-overrides.css` | Backend Overrides | wp-admin (and front-end toolbar): hides unused toolbar items and the media modal's alt-text hint, and shows a large "Remember to add ALT text" reminder in the media library. |
| `ada-styles.css` | ADA Styles | Focus-visible outlines, contrast and underline fixes across the theme, widgets and forms. |
| `theme-overrides.css` | Theme Overrides | GovArnex look-and-feel fixes: header/logo sizing, menus, banner, footer, buttons, TablePress, service cards, accordions, search results. Uses the county's colors and banner. Prints last so it wins ties. |
| `header-search.css` | Laclede's Theme Overrides | Layout of the top bar when **Search in header** is on. Only prints for counties using it. |

The plugin also prints `.page-content { overflow: unset; }` at the same point
WordPress prints Additional CSS. It was in every site's Customizer
(Appearance > Customize > Additional CSS). Why it was added wasn't recorded;
`overflow: unset` usually stops a wrapper from clipping sticky elements or
focus outlines, so check those if you ever remove it.

## What stays on each site

Left in Code Snippets / Simple Custom CSS and JS because only one site uses
them. They print after this plugin's CSS, so they still override it.

| Site | Kept on the site |
|---|---|
| Laclede | Logo sizing (sticky header max-width, top margins on tablet/phone). TextMyGov widget. |
| Stoddard | Tax payment form styles ("Max Impact Forms"). TextMyGov widget. Customizer: hide scroll-to-top on tax pages. |
| Pulaski | Its search page banner (search results only; the others also cover the page header). |

Two leftover rules were never actually in effect (a later shared rule
overrode them), so they were left out when moving them, to keep each site
looking exactly the same:

- Laclede: the tablet/phone logo widths (160px/130px). The shared
  `width: 290px !important` wins.
- Stoddard: `line-height: 1.4` on the tax payment submit button. The shared
  `line-height: 1` wins.

## Releasing a change

1. Make the change in `plugin/`.
2. Pick the new version number (1.0.0 → 1.0.1 for fixes, 1.1.0 for features)
   and set it in both places in `plugin/bb-countyops-addon.php`: the
   `Version:` header and `BBCOA_VERSION`.
3. Add a `## [1.0.1]` section to `CHANGELOG.md`.
4. Commit, then tag and push:
   ```
   git tag v1.0.1
   git push origin main v1.0.1
   ```
5. GitHub checks the code on PHP 7.4, 8.2 and 8.3, builds
   `bb-countyops-addon.zip` and publishes the release.
6. Sites only see releases while the repository is public. On each site:
   **Purge SG Cache**, then Dashboard > Updates > **Check again**.

### Testing on one site first

Add to that site's `wp-config.php`:

```php
define( 'BBCOA_UPDATE_CHANNEL', 'beta' );
```

Release a version like `1.0.1-beta.1` (tag `v1.0.1-beta.1`). Tags with a dash
become pre-releases, offered only to sites with that line.

To build the zip yourself: `bash scripts/build.sh` (output in `dist/`).

## Things that must not change

- The folder name `bb-countyops-addon` and main file `bb-countyops-addon.php`.
- The `[ada_sitemap]` shortcode name and the `ada-sitemap` / `html-sitemap`
  classes. Pages use them.
- The order of files in `includes/output.php`. It reproduces the order Simple
  Custom CSS and JS printed them, which decides which rule wins.
- The GitHub repository name `bbmediallc/bb-countyops-addon`, and a
  `bb-countyops-addon.zip` file on each release.
