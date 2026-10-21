# Changelog

Each `## [version]` section becomes the release notes on GitHub.

## [1.4.2] - 2026-10-21

### Fixed
- Mobile menu: opening it left focus on the hamburger behind the drawer
  (WCAG 2.4.3). The close X becomes visible about 100ms after the panel, so
  the single focus attempt failed; it now retries until focus lands on the X.

## [1.4.1] - 2026-10-21

### Removed
- Sitemap: the "Posts by Category" section, which was empty on every county.
  The sitemap now lists pages only.

## [1.4.0] - 2026-10-21

### Fixed
- Mobile menu (WCAG 2.4.3, 2.4.7, 4.1.2), now one script (`mobile-menu.js`)
  instead of the theme plus four overlapping ones:
  - Opening it puts focus on the visible close X, not an invisible 1px button.
  - Focus stays inside the open menu; Escape, the X and the overlay close it
    and return focus to the hamburger, whose aria-expanded follows.
  - Submenu buttons sit beside their links, announced as "[Name] submenu"
    with a working aria-expanded; parent links no longer claim a popup.
  - No duplicate IDs, and no menu/menuitem roles without arrow-key support.
  - Submenu buttons look the same as before (black/white arrow closed,
    white/Accent color arrow open), with a dashed white focus outline.
- Logo images were announced as "Logo" (GovArnex hard-codes it); they now use
  the site's name, e.g. "Wright County Collector of Revenue" (WCAG 1.1.1).

### Removed
- Mobile Menu Tabbing, Injected Aria Labels, the Fix Mobile Menu Button
  Issues snippet, and the mobile part of Aria Labels for Drop Downs (all
  replaced by `mobile-menu.js`, which also keeps the scroll-to-top label).

## [1.3.2] - 2026-10-21

### Fixed
- Table of Contents toggle (WCAG 4.1.2): the header was a focusable div with
  no role, name or state, and the chevron buttons were unreachable. The title
  now contains a real button with aria-expanded (still an H2), Enter and
  Space work natively, clicking anywhere on the header toggles it once, and
  the chevrons are decorative. Dashed black focus outline on the button.

## [1.3.1] - 2026-10-21

### Fixed
- Table of Contents links had the browser's solid blue focus outline; they
  now get the site's 3px dashed maroon outline like other links.

## [1.3.0] - 2026-10-21

### Added
- County Admin menu, built in (replaces the Hide Admin Menu plugin): the same
  22 menus and 107 submenus hidden for County Admins as on Stoddard and
  Pulaski. Those screens are now also blocked if opened by address.

### Fixed
- FAQ toggles (GovArnex FAQ widget), WCAG 4.1.2 and 1.3.1:
  - Clicking an open question now closes it, opening one closes the others,
    and aria-expanded always matches what's on screen. The new ADA
    Accordions script is the only thing driving the toggles.
  - Each question is a real button inside its <h3>, so screen readers keep
    the headings (the bar is no longer role="button"). Enter and Space work
    natively. Dashed black focus outline on the button.

## [1.2.0] - 2026-10-21

### Added
- County Admin role, built in. Created on any site that doesn't have it and
  kept in sync on every update, so User Role Editor is no longer needed for
  it. Same permissions as the hand-made role on all 7 collector sites (an
  Editor plus Gravity Forms, TablePress, Yoast SEO, menus/widgets and
  settings). Permissions other plugins add (e.g. Wordfence 2FA) are left
  alone, and deleting the plugin leaves the role in place.

### Changed
- Header search: focus outlines are 3px dashed, like the rest of the site's,
  in white so they show on the dark top bar (also the top bar menu links).

## [1.1.0] - 2026-10-21

### Changed
- Search in header is now built into the plugin: turning it on in County
  Settings is all it takes. It no longer needs an Elementor search template,
  and the "Search template" setting is gone. The form matches Laclede's
  original (white box, light gray border, Accent color button with the
  theme's search icon) and submits to the normal search results page. Unlike
  Laclede's Elementor widget, it has no live-results dropdown.

## [1.0.2] - 2026-10-21

### Fixed
- Home page feature pods: keyboard focus showed two outlines (a dashed one
  on the title link and one on the pod). Now there's a single solid maroon
  (`#7f0003`) outline with rounded corners around the whole pod, nothing on
  the title, and no outline on mouse clicks. Removed the title link's focus
  rule from ADA Styles; the pod's focus styles live in Clickable Home Pods.

## [1.0.1] - 2026-10-21

### Added
- County Ops Overrides: the CountyOps records status page
  (`.mid-tax-payment.mid-tax-records-status`) uses the full content width.
- County Ops Overrides: CountyOps search titles
  (`.mid-tax-payment .mid-tax-search-title`) are bold.

## [1.0.0] - 2026-10-21

First release. Replaces the code that was copied into every county
collector site's Code Snippets and Simple Custom CSS and JS plugins.

### Added
- The 7 snippets active on all 7 collector sites (ADA Sitemap, pinch-zoom,
  Elementor link options, Icon Box duplicate links, mobile menu button,
  clickable home pods, FAQ answer editor).
- The 22 Simple Custom CSS and JS items shared by all 7 sites (18 scripts,
  4 stylesheets), printed in the same place and order as before.
- The `.page-content { overflow: unset; }` rule from every site's Customizer.
- Counties: Wright, Dent, Perry, Laclede, Ralls, Stoddard and Pulaski built
  in, each with its own colors, page header banner and website addresses.
- Dashboard > County Settings (support@bandbmedia.com only): choose this
  site's county, edit any county, or add a new one.
- The site's county is picked automatically from its website address on
  activation.
- Search in header (from Laclede's "Add Search to Header" snippet), now a
  per-county setting with its own Elementor template.
- Updates from GitHub, like BB Media Core and Elementor Add-Ons.
