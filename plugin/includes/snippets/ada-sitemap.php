<?php
// ADA Sitemap (formerly Code Snippets: "ADA Sitemap").
//
// [ada_sitemap] shortcode: an accessible HTML sitemap of every published page
// (nested by parent) and every post grouped by category. Pages using it get
// an "ada-sitemap" body class and the list styles below.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_ada_sitemap_tree( $parent = 0 ) {
	$pages = get_pages( [
		'parent'      => $parent,
		'sort_column' => 'menu_order,post_title',
		'post_status' => 'publish',
	] );

	if ( ! $pages ) {
		return '';
	}
	$output = '<ul>';
	foreach ( $pages as $page ) {
		$output .= '<li>';
		$output .= '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( $page->post_title ) . '</a>';
		$output .= bbcoa_ada_sitemap_tree( $page->ID );
		$output .= '</li>';
	}
	return $output . '</ul>';
}

function bbcoa_ada_sitemap_shortcode() {
	ob_start();
	?>
	<div class="ada-sitemap html-sitemap">

		<h2>Pages</h2>
		<?php echo bbcoa_ada_sitemap_tree(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above. ?>

		<hr>

		<h2>Posts by Category</h2>

		<?php
		foreach ( get_categories( [ 'hide_empty' => true ] ) as $category ) {
			echo '<section>';
			echo '<h3>' . esc_html( $category->name ) . '</h3>';

			$posts = get_posts( [
				'category'    => $category->term_id,
				'numberposts' => -1,
				'post_status' => 'publish',
			] );

			if ( $posts ) {
				echo '<ul>';
				foreach ( $posts as $post ) {
					echo '<li><a href="' . esc_url( get_permalink( $post->ID ) ) . '">' . esc_html( $post->post_title ) . '</a></li>';
				}
				echo '</ul>';
			}

			echo '</section>';
		}
		?>

	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ada_sitemap', 'bbcoa_ada_sitemap_shortcode' );

function bbcoa_ada_sitemap_on_this_page() {
	global $post;
	return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ada_sitemap' );
}

function bbcoa_ada_sitemap_body_class( $classes ) {
	if ( bbcoa_ada_sitemap_on_this_page() ) {
		$classes[] = 'ada-sitemap';
	}
	return $classes;
}
add_filter( 'body_class', 'bbcoa_ada_sitemap_body_class' );

// Link color is #800000 on every county, Laclede included (unchanged from the
// original snippet).
function bbcoa_ada_sitemap_head_styles() {
	if ( ! bbcoa_ada_sitemap_on_this_page() ) {
		return;
	}
	?>
	<style>
		.html-sitemap ul,
		.html-sitemap li {
			list-style: none !important;
			margin: 0 !important;
		}
		.html-sitemap ul ul {
			padding-left: 1.5rem !important;
			border-left: 2px solid #e0e0e0 !important;
			margin-top: .5rem !important;
			margin-bottom: .5rem !important;
		}
		.html-sitemap li {
			margin: .35rem 0 !important;
		}
		.html-sitemap a {
			text-decoration: none !important;
		}
		.html-sitemap a {
			text-decoration: underline !important;
			color: #800000 !important;
		}
		.html-sitemap a:hover,
		.html-sitemap a:focus {
			text-decoration: none !important;
			color: #000000 !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'bbcoa_ada_sitemap_head_styles' );
