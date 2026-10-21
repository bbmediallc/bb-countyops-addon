<?php
// FAQ toggle buttons (govarnex_faq widget).
//
// GovArnex prints each FAQ question as a plain <h3> inside div.acc-btn, and
// the old ADA Accordions script then turned that div into role="button",
// which made screen readers drop the heading (WCAG 1.3.1). This rewrites the
// widget's markup as it renders: the question text goes inside a real
// <button class="acc-trigger"> within the <h3>, and the plus icon is hidden
// from screen readers. assets/js/ada-accordions.js drives the buttons, and
// the button styles are at the end of assets/css/ada-styles.css.
//
// Done here rather than in a child theme template so theme and plugin
// updates can't undo it.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_faq_toggle_buttons( $content, $widget ) {
	if ( 'govarnex_faq' !== $widget->get_name() ) {
		return $content;
	}
	$content = str_replace( '<div class="icon-outer">', '<div class="icon-outer" aria-hidden="true">', $content );
	$result  = preg_replace(
		'#(<div class="acc-btn[^"]*">\s*<div class="icon-outer"[^>]*>.*?</div>\s*)<h3>(.*?)</h3>#s',
		'$1<h3><button type="button" class="acc-trigger">$2</button></h3>',
		$content
	);
	return null === $result ? $content : $result;
}
add_filter( 'elementor/widget/render_content', 'bbcoa_faq_toggle_buttons', 10, 2 );
