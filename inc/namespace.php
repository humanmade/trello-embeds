<?php

namespace HM\TrelloEmbeds;

/**
 * Regular expression to match Trello card URLs.
 *
 * Trello card URLs are in the following format:
 *
 * 	 https://trello.com/c/abcAB123/title-in-slug-format
 */
const CARD_REGEX = '#https?://trello.com/c/([^/]+)/([^/]+?)(\?.+)?$#';

/**
 * Bootstrap the plugin.
 */
function bootstrap() {
	wp_embed_register_handler( 'hm_trello_embed', CARD_REGEX, __NAMESPACE__ . '\\handle_auto_embed' );
	add_shortcode( 'hm-trello-embed', __NAMESPACE__ . '\\render_card_embed' );
}

/**
 * Enqueue the embed styles.
 */
function enqueue_script() {
	wp_enqueue_script( 'hm-trello-embed', 'https://p.trellocdn.com/embed.js', [], null, true );
}

/**
 * Handle the autoembed for Trello URLs.
 *
 * This replaces the URL with our manual shortcode, which ensures that the
 * content is rendered after wpautop and family.
 *
 * @param array $matches Matches from the regular expression
 * @param array $attr
 * @param string $url
 * @return string Shortcode text to replace into content.
 */
function handle_auto_embed( $matches, $attr, $url ) {
	return sprintf( '[hm-trello-embed url="%s" /]', esc_attr( $url ) );
}

/**
 * Handle embedding a Trello card.
 *
 * @param array $args Shortcode parameters
 * @return string HTML to embed into the content
 */
function render_card_embed( $args ) {
	// Ensure the script is enqueued.
	enqueue_script();

	return sprintf(
		'<blockquote class="trello-card"><a href="%s">Trello Card</a></blockquote>',
		esc_url( $args['url'] )
	);
}
