<?php
// FAQ answer editor (formerly Code Snippets: "Govarnex FAQ Answer WYSIWYG
// Editor").
//
// GovArnex's FAQ widget only offers a plain text box for answers. This swaps
// it for Elementor's rich text editor so answers can contain links, lists and
// formatting, and labels each FAQ in the editor panel by its question.
// It also hides the empty paragraphs the rich text editor can leave behind,
// so they don't add stray spacing or empty elements for screen readers.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_faq_answer_editor( $element ) {
	$control = $element->get_controls( 'items' );
	if ( empty( $control['fields']['answer'] ) ) {
		return;
	}
	$control['fields']['answer']['type'] = \Elementor\Controls_Manager::WYSIWYG;
	unset( $control['fields']['answer']['rows'] );
	$control['title_field'] = '{{{ question }}}';
	$element->update_control( 'items', $control );
}
add_action( 'elementor/element/govarnex_faq/item/before_section_end', 'bbcoa_faq_answer_editor' );

function bbcoa_faq_hide_empty_paragraphs() {
	echo '<style>.accordion-box .acc-content > p:empty{display:none;}</style>';
}
add_action( 'wp_head', 'bbcoa_faq_hide_empty_paragraphs' );
