# Changelog

Each `## [version]` section becomes the release notes on GitHub.

## [1.5.9] - 2026-10-23

### Fixed
- Two unnamed search landmarks on pages with an Elementor Search widget
  (WCAG 1.3.1, 2.4.1): screen readers announced both as just "search". The
  header search form is now "Header search" and Elementor Search widgets are
  "Page search" (numbered if a page has more than one).

## [1.5.8] - 2026-10-23

### Added
- Search Widget ARIA Fix (`search-widget-aria-fix.js`): Elementor Pro Search
  widgets with live results turned off no longer claim to be a combobox
  with a popup, and their empty results box is no longer a Tab stop
  (WCAG 4.1.2). Widgets with live results on are untouched.

## [1.5.7] - 2026-10-22

### Fixed
- The Updates screen said "Compatibility with WordPress 7.1.3: Not tested".
  "Tested up to: 7.1" covers every 7.1.x release, but WordPress compared it
  with the full version. On any 7.1.x site the update now reports that
  version as tested; a new branch (7.2) still needs the header raised.

## [1.5.6] - 2026-10-22

### Changed
- Sitemap: the home page (Settings > Reading > Homepage) is always listed
  first; the other pages stay alphabetical after it.

## [1.5.5] - 2026-10-22

### Changed
- County Ops Overrides: the Search Records landing page now matches the rest
  of the site: Poppins headings and DM Sans text in black, white cards with
  15px corners and the Quick Links box shadow, Accent color icons, hover
  border and "Data Last Updated" link, and a 3px dashed Accent color focus
  outline on the cards.
- County Ops Overrides: CountyOps' remaining blue is now the county's Accent
  color: focus outlines (now dashed), the GIS link, sticky selection and
  checkout borders, checkout checkboxes, location options, payment status and
  "no payment due" badges, and the external payment note. Its pale blue
  panels use a pale Accent tint, and its near-black navy text is black.

## [1.5.4] - 2026-10-22

### Fixed
- Elementor buttons in page content had a white focus outline on a white
  page (WCAG 2.4.7). The white outline is meant for the home page banner,
  whose container has the CSS ID page-content, but Skip to Content gives
  every page's content wrapper that ID too. Outside the home page they now
  get the 3px dashed Accent color outline like other links and buttons.

### Changed
- Gravity Forms Submit buttons: line-height 1.

## [1.5.3] - 2026-10-22

### Changed
- No more hard-coded maroon: everything that was `#7f0003`, `#7f0005`,
  `#810003`, `#8b0103` or `#800000` in the plugin now uses the county's
  Accent color. That covers the dashed focus outlines, text-editor links and
  list bullets, form links, the Gravity Forms and payment Submit buttons,
  sitemap links, the home pod focus outline and the CountyOps result links.
  Most counties look the same (their Accent is `#800000`); Laclede now uses
  its own color throughout.
- County Ops Overrides: CountyOps' own maroon (Search, Reset and View Details
  buttons, Email results, sort links and active sort column, View bill and
  Back to results links, results table focus outline, payment checkboxes,
  error message border) uses the Accent color. Result links brighten on
  hover. Button hover/focus fills are
  black, like the site's other buttons; disabled buttons stay gray. Red
  status and error text is unchanged.
- Theme Overrides: Laclede's "Missouri County Collector Websites" table
  (links, focus outline, mobile row borders and labels) uses the Accent color
  instead of the page's hard-coded `#800000`.

## [1.5.2] - 2026-10-22

### Changed
- County Ops Overrides: Stoddard's tax search results and payment screen
  styles now apply to every county: dashed maroon focus outline on result
  actions and the back link, back link hover, 1rem buttons with 1.4 line
  height (payment submit stays at 1), and sized totals text. Stoddard's own
  copy ("Stoddard Overrides") is no longer needed.

## [1.5.1] - 2026-10-21

### Fixed
- County Admin still saw Dashboard > Updates: on these sites Updates sits
  under BB Media Core's "custom_dashboard" menu, not index.php. Both are now
  covered.
- County Admin still saw TablePress > Upgrade: TablePress Premium's Freemius
  SDK adds it at admin_menu priority 999999999, after the hiding ran. Hiding
  now runs at PHP_INT_MAX.

## [1.5.0] - 2026-10-21

### Changed
- County Admin now also has hidden and blocked (403): Link Checker (and its
  "Local [old]" page), Dashboard > Updates, TablePress Plugin Options,
  Modules, About and Upgrade, and every Customizer link under Appearance
  (Customize, Background). Customize and Updates are gone from the top admin
  bar. Blocking runs first thing on each admin screen.
- County Admin no longer sees the theme's "required or recommended plugins"
  notice, Enable Media Replace's "New Beta Feature" box, Disable Comments'
  survey notice, or the WP Mail SMTP dashboard widget. The Media Library's
  "Remember to add ALT text" reminder still shows.

## [1.4.4] - 2026-10-21

### Changed
- County Ops Overrides: Shortcode widgets holding a CountyOps form
  (`.elementor-widget-shortcode:has(.mid-tax-payment)`) take the full width.

## [1.4.3] - 2026-10-21

### Changed
- FAQ question text uses the county's Accent color instead of a hard-coded
  #8c0708, so it follows each county (#800000 on most, Laclede's own maroon).

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
