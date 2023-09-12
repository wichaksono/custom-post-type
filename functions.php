<?php

require_once __DIR__ . '/cpt/class.base-cpt.php';
require_once __DIR__ . '/cpt/class.recipe-menu.php';

function the_current_taxonomy($taxonomy) {
	global $post;
	$taxonomies = get_the_terms($post->ID, $taxonomy);

	if ($taxonomies && !is_wp_error($taxonomies)) {
		$taxonomy_links = array();
		foreach ($taxonomies as $taxonomy) {
			$taxonomy_links[] = '<a href="' . get_term_link($taxonomy) . '">' . $taxonomy->name . '</a>';
		}
		echo implode(' ', $taxonomy_links);
	}
}

/**
 * contoh dipasang di single.php
 * if ( function_exists('the_current_taxonomy') && is_singular('resep') ) {
 *   	the_current_taxonomy('hashtag');
 * }
 */
