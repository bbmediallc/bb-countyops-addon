# Changelog

Each `## [version]` section becomes the release notes on GitHub.

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
