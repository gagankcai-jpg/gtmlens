<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the "Powered by Claude AI" attribution chip.
 *
 * @param string $size    CSS modifier appended to gl-ai-chip--{size}.
 *                        Pass 'small header' for the fixed top-right instance.
 * @param string $context Optional context key. Pass 'editorial-policy' or
 *                        'claude-vendor' to suppress output on those pages.
 */
function gtmlens_ai_chip( string $size = 'small', string $context = '' ): void {

	// Suppress on the editorial-policy page.
	if ( 'editorial-policy' === $context || is_page( 'editorial-policy' ) ) {
		return;
	}

	// Suppress on the Claude vendor profile.
	if ( 'claude-vendor' === $context ) {
		return;
	}
	if ( is_singular( 'vendor' ) && is_a( get_post(), 'WP_Post' ) && in_array( get_post()->post_name, [ 'claude', 'claude-anthropic' ], true ) ) {
		return;
	}

	// Build CSS class string from $size (e.g. 'small header' → two modifiers).
	$modifiers = '';
	foreach ( explode( ' ', trim( $size ) ) as $mod ) {
		if ( $mod !== '' ) {
			$modifiers .= ' gl-ai-chip--' . esc_attr( $mod );
		}
	}

	$chip_url = esc_url( home_url( '/editorial-policy/#ai-assisted-drafting' ) );

	?>
	<a class="gl-ai-chip<?php echo $modifiers; ?>"
	   href="<?php echo $chip_url; ?>"
	   aria-label="<?php esc_attr_e( 'This site uses Claude AI for research. Read our editorial policy.', 'gtmlens-child' ); ?>"
	>
		<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
		     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
		     stroke-linejoin="round" aria-hidden="true" focusable="false">
			<!-- Four-pointed sparkle star -->
			<path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" />
			<!-- Small top-right star -->
			<path d="M19 3 L19.7 5.3 L22 6 L19.7 6.7 L19 9 L18.3 6.7 L16 6 L18.3 5.3 Z" />
			<!-- Small bottom-left star -->
			<path d="M5 15 L5.5 16.5 L7 17 L5.5 17.5 L5 19 L4.5 17.5 L3 17 L4.5 16.5 Z" />
		</svg>
		<span><?php esc_html_e( 'Powered by Claude AI', 'gtmlens-child' ); ?></span>
	</a>
	<?php
}
