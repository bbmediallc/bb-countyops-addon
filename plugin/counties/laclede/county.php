<?php
// Laclede County Collector. Values left '' use the defaults in
// bbcoa_county_fields() (includes/counties.php).
// Laclede's header search template is Elementor template 10766 on its site.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	'name' => 'Laclede County Collector',
	'site_urls' => 'laclede.bandbdev.com',
	'accent_color' => '#62032c',
	'link_color' => '#62022c',
	'footer_link_hover' => '#C69863',
	'banner_image' => '/wp-content/uploads/2026/04/Laclede-County-Center-banner-1a-100_4642-B-shortpx.jpg',
	'banner_position' => '',
	'header_search' => '1',
	'header_search_template' => '10766',
];
