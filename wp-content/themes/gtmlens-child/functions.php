<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Tier 2 — RSS ingest + vendor scanner cron jobs */
require_once get_stylesheet_directory() . '/inc/cron-jobs.php';

/* ═══════════════════════════════════════════════════════════════════════════
   1. ENQUEUE PARENT + CHILD STYLES
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'wp_enqueue_scripts', 'gtmlens_enqueue_styles' );
function gtmlens_enqueue_styles() {
	wp_enqueue_style(
		'kadence-parent',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( 'kadence' )->get( 'Version' )
	);

	wp_enqueue_style(
		'gtmlens-child',
		get_stylesheet_uri(),
		[ 'kadence-parent' ],
		filemtime( get_stylesheet_directory() . '/style.css' )
	);
}

/* ═══════════════════════════════════════════════════════════════════════════
   2. CUSTOM POST TYPES
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'init', 'gtmlens_register_cpts' );
function gtmlens_register_cpts() {

	/* ── Vendor ── */
	register_post_type( 'vendor', [
		'labels' => [
			'name'               => __( 'Vendors', 'gtmlens-child' ),
			'singular_name'      => __( 'Vendor', 'gtmlens-child' ),
			'add_new_item'       => __( 'Add New Vendor', 'gtmlens-child' ),
			'edit_item'          => __( 'Edit Vendor', 'gtmlens-child' ),
			'all_items'          => __( 'All Vendors', 'gtmlens-child' ),
			'search_items'       => __( 'Search Vendors', 'gtmlens-child' ),
			'not_found'          => __( 'No vendors found.', 'gtmlens-child' ),
		],
		'public'             => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-building',
		'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ],
		'has_archive'        => 'vendors',
		'rewrite'            => [ 'slug' => 'vendors', 'with_front' => false ],
		'taxonomies'         => [ 'vendor_category', 'capabilities', 'integrations' ],
	] );

	/* ── Stack ── */
	register_post_type( 'stack', [
		'labels' => [
			'name'          => __( 'Stacks', 'gtmlens-child' ),
			'singular_name' => __( 'Stack', 'gtmlens-child' ),
			'add_new_item'  => __( 'Add New Stack', 'gtmlens-child' ),
			'edit_item'     => __( 'Edit Stack', 'gtmlens-child' ),
			'all_items'     => __( 'All Stacks', 'gtmlens-child' ),
		],
		'public'      => true,
		'show_ui'     => true,
		'show_in_menu'=> true,
		'show_in_rest'=> true,
		'menu_icon'   => 'dashicons-networking',
		'supports'    => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ],
		'has_archive' => 'stack-builder',
		'rewrite'     => [ 'slug' => 'stack-builder', 'with_front' => false ],
	] );

	/* ── Comparison ── */
	register_post_type( 'comparison', [
		'labels' => [
			'name'          => __( 'Comparisons', 'gtmlens-child' ),
			'singular_name' => __( 'Comparison', 'gtmlens-child' ),
			'add_new_item'  => __( 'Add New Comparison', 'gtmlens-child' ),
			'edit_item'     => __( 'Edit Comparison', 'gtmlens-child' ),
			'all_items'     => __( 'All Comparisons', 'gtmlens-child' ),
		],
		'public'      => true,
		'show_ui'     => true,
		'show_in_menu'=> true,
		'show_in_rest'=> true,
		'menu_icon'   => 'dashicons-chart-bar',
		'supports'    => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ],
		'has_archive' => 'compare',
		'rewrite'     => [ 'slug' => 'compare', 'with_front' => false ],
	] );

	/* ── Funding Event (P9: tracker for events not tied to a vendor profile) ── */
	register_post_type( 'funding_event', [
		'labels' => [
			'name'          => __( 'Funding Events', 'gtmlens-child' ),
			'singular_name' => __( 'Funding Event', 'gtmlens-child' ),
			'add_new_item'  => __( 'Add New Funding Event', 'gtmlens-child' ),
			'edit_item'     => __( 'Edit Funding Event', 'gtmlens-child' ),
			'all_items'     => __( 'All Funding Events', 'gtmlens-child' ),
		],
		'public'       => true,
		'show_ui'      => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-money-alt',
		'supports'     => [ 'title', 'custom-fields', 'revisions' ],
		'has_archive'  => false,
		'rewrite'      => [ 'slug' => 'funding-events', 'with_front' => false ],
	] );

	/* ── Quiz Subscriber (private; stores quiz email captures) ── */
	register_post_type( 'quiz_subscriber', [
		'labels' => [
			'name'          => __( 'Quiz Subscribers', 'gtmlens-child' ),
			'singular_name' => __( 'Quiz Subscriber', 'gtmlens-child' ),
			'all_items'     => __( 'All Subscribers', 'gtmlens-child' ),
		],
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => false,
		'menu_icon'          => 'dashicons-email',
		'capability_type'    => 'post',
		'supports'           => [ 'title', 'custom-fields' ],
		'has_archive'        => false,
		'rewrite'            => false,
		'exclude_from_search'=> true,
	] );
}

/* ═══════════════════════════════════════════════════════════════════════════
   3. TAXONOMIES
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'init', 'gtmlens_register_taxonomies' );
function gtmlens_register_taxonomies() {

	/* ── Vendor Category (hierarchical, like categories) ── */
	register_taxonomy( 'vendor_category', [ 'vendor' ], [
		'labels' => [
			'name'          => __( 'Vendor Categories', 'gtmlens-child' ),
			'singular_name' => __( 'Vendor Category', 'gtmlens-child' ),
			'all_items'     => __( 'All Categories', 'gtmlens-child' ),
			'edit_item'     => __( 'Edit Category', 'gtmlens-child' ),
			'add_new_item'  => __( 'Add New Category', 'gtmlens-child' ),
		],
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'category', 'with_front' => false, 'hierarchical' => true ],
	] );

	/* ── Capabilities (flat tag-cloud) ── */
	register_taxonomy( 'capabilities', [ 'vendor' ], [
		'labels' => [
			'name'          => __( 'Capabilities', 'gtmlens-child' ),
			'singular_name' => __( 'Capability', 'gtmlens-child' ),
			'all_items'     => __( 'All Capabilities', 'gtmlens-child' ),
		],
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'capability', 'with_front' => false ],
	] );

	/* ── Integrations (flat tag-cloud) ── */
	register_taxonomy( 'integrations', [ 'vendor' ], [
		'labels' => [
			'name'          => __( 'Integrations', 'gtmlens-child' ),
			'singular_name' => __( 'Integration', 'gtmlens-child' ),
			'all_items'     => __( 'All Integrations', 'gtmlens-child' ),
		],
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'integration', 'with_front' => false ],
	] );
}

/* ═══════════════════════════════════════════════════════════════════════════
   4. SEED VENDOR_CATEGORY TERMS
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'init', 'gtmlens_seed_vendor_category_terms', 99 );
function gtmlens_seed_vendor_category_terms(): void {
	$terms = [
		[
			'name' => 'Foundation Models',
			'slug' => 'foundation-models',
		],
	];

	foreach ( $terms as $term ) {
		if ( ! term_exists( $term['slug'], 'vendor_category' ) ) {
			wp_insert_term( $term['name'], 'vendor_category', [ 'slug' => $term['slug'] ] );
		}
	}
}

/* ═══════════════════════════════════════════════════════════════════════════
   5. AI ATTRIBUTION CHIP
   ═══════════════════════════════════════════════════════════════════════════ */

require_once get_stylesheet_directory() . '/inc/ai-attribution-chip.php';
require_once get_stylesheet_directory() . '/inc/market-map.php';

// Header chip removed per editorial preference — chip remains in footer only.

// Allow SVG uploads (used for site logo).
add_filter( 'upload_mimes', function ( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
} );
add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename, $mimes ) {
	if ( substr( $filename, -4 ) === '.svg' ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	return $data;
}, 10, 4 );

// Footer instance — medium size. Suppressed entirely on editorial-policy + Claude vendor.
add_action( 'wp_footer', 'gtmlens_chip_footer' );
function gtmlens_chip_footer(): void {
	if ( is_page( 'editorial-policy' ) ) {
		return;
	}
	if ( is_singular( 'vendor' ) && is_a( get_post(), 'WP_Post' ) && in_array( get_post()->post_name, [ 'claude', 'claude-anthropic' ], true ) ) {
		return;
	}
	echo '<div class="gl-ai-chip-footer-wrap" style="text-align:center;padding:12px 0;">';
	gtmlens_ai_chip( 'medium' );
	echo '</div>';
}

/* ═══════════════════════════════════════════════════════════════════════════
   5b. CUSTOM REST ENDPOINT — apply ACF repeater rows
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'rest_api_init', function () {
	register_rest_route( 'gtmlens/v1', '/acf-repeater', [
		'methods'             => 'POST',
		'permission_callback' => function () { return current_user_can( 'edit_posts' ); },
		'callback'            => function ( WP_REST_Request $r ) {
			$post_id    = (int) $r->get_param( 'post_id' );
			$field_name = sanitize_key( $r->get_param( 'field' ) );
			$rows       = $r->get_param( 'rows' );
			if ( ! $post_id || ! $field_name || ! is_array( $rows ) ) {
				return new WP_Error( 'bad_request', 'Need post_id, field, rows[]', [ 'status' => 400 ] );
			}
			$ok = update_field( $field_name, $rows, $post_id );
			return [ 'ok' => (bool) $ok, 'post_id' => $post_id, 'field' => $field_name, 'rows' => count( $rows ) ];
		},
	] );

	// Authenticated funding-update endpoint for the monthly gtmlens-funding-refresh
	// routine. Auth = WP Application Password (Basic) of a user who can edit_posts.
	// Applies a single confirmed round to a vendor, with a prior-value audit trail
	// (meta _auto_applied_log) for rollback, a newer-date guard, and a matching
	// funding_event record. ACF writes go through update_field() server-side.
	register_rest_route( 'gtmlens/v1', '/apply-funding', [
		'methods'             => 'POST',
		'permission_callback' => function () { return current_user_can( 'edit_posts' ); },
		'callback'            => function ( WP_REST_Request $r ) {
			$vid    = (int) $r->get_param( 'vendor_id' );
			$round  = (int) $r->get_param( 'round_size_usd_m' );
			$valm   = (int) $r->get_param( 'valuation_usd_m' );
			$rdate  = preg_replace( '/\D/', '', (string) $r->get_param( 'round_date' ) ); // YYYYMMDD
			$stage  = sanitize_text_field( (string) $r->get_param( 'funding_stage' ) );
			$src    = esc_url_raw( (string) $r->get_param( 'source_url' ) );
			if ( ! $vid || get_post_type( $vid ) !== 'vendor' || get_post_status( $vid ) !== 'publish' ) {
				return new WP_Error( 'bad_vendor', 'vendor_id must be a published vendor', [ 'status' => 400 ] );
			}
			if ( strlen( $rdate ) !== 8 ) {
				return new WP_Error( 'bad_date', 'round_date must be YYYYMMDD', [ 'status' => 400 ] );
			}
			// Newer-date guard: never overwrite a newer round with an older one.
			$prev_date = preg_replace( '/\D/', '', (string) get_field( 'last_round_date', $vid ) );
			if ( strlen( $prev_date ) === 8 && $rdate <= $prev_date ) {
				return new WP_Error( 'stale', 'round_date not newer than current last_round_date', [ 'status' => 409 ] );
			}
			// Audit: snapshot prior values for rollback.
			$prior = [
				'last_round_size_usd_m' => get_field( 'last_round_size_usd_m', $vid ),
				'last_valuation_usd_m'  => get_field( 'last_valuation_usd_m', $vid ),
				'total_raised_usd_m'    => get_field( 'total_raised_usd_m', $vid ),
				'last_round_date'       => get_field( 'last_round_date', $vid ),
				'funding_stage'         => get_field( 'funding_stage', $vid ),
			];
			$log = (array) get_post_meta( $vid, '_auto_applied_log', true );
			$log[] = [ 'at' => current_time( 'mysql', 1 ), 'src' => $src, 'prior' => $prior ];
			update_post_meta( $vid, '_auto_applied_log', $log );
			// Apply.
			$new_total = (int) $prior['total_raised_usd_m'] + $round;
			if ( $round > 0 ) update_field( 'last_round_size_usd_m', $round, $vid );
			if ( $valm > 0 )  update_field( 'last_valuation_usd_m', $valm, $vid );
			update_field( 'total_raised_usd_m', $new_total, $vid );
			update_field( 'last_round_date', $rdate, $vid );
			if ( $stage ) update_field( 'funding_stage', $stage, $vid );
			update_field( 'last_updated', date( 'Ymd' ), $vid );
			// Matching funding_event record.
			$company = get_the_title( $vid );
			$ev_id = wp_insert_post( [
				'post_type'   => 'funding_event',
				'post_status' => 'publish',
				'post_title'  => $company . ' — ' . $stage . ' ' . substr( $rdate, 0, 4 ),
			] );
			if ( $ev_id && ! is_wp_error( $ev_id ) ) {
				$vterms = wp_get_post_terms( $vid, 'vendor_category', [ 'fields' => 'names' ] );
				$vcat   = ( ! is_wp_error( $vterms ) && $vterms ) ? $vterms[0] : '';
				update_field( 'company_name', $company, $ev_id );
				update_field( 'event_date', $rdate, $ev_id );
				update_field( 'event_type', 'round', $ev_id );
				update_field( 'source_url', $src, $ev_id );
				if ( $round > 0 ) update_field( 'amount_usd_m', $round, $ev_id );
				if ( $valm > 0 )  update_field( 'valuation_usd_m', $valm, $ev_id );
				if ( $stage ) update_field( 'stage', $stage, $ev_id );
				if ( $vcat )  update_field( 'category', $vcat, $ev_id );
				update_field( 'vendor', $vid, $ev_id );
				update_post_meta( $ev_id, '_auto_ingested', 1 );
			}
			return [
				'ok' => true, 'vendor_id' => $vid, 'event_id' => (int) $ev_id,
				'applied' => [ 'round' => $round, 'valuation' => $valm, 'total' => $new_total, 'date' => $rdate, 'stage' => $stage ],
				'prior' => $prior,
			];
		},
	] );
} );

/* ═══════════════════════════════════════════════════════════════════════════
   6. REMOVE KADENCE FOOTER CREDIT
   ═══════════════════════════════════════════════════════════════════════════ */

add_filter( 'kadence_footer_html_default_credit', '__return_empty_string', 99 );
add_filter( 'kadence_footer_credit', '__return_empty_string', 99 );

add_action( 'wp_head', function () {
	echo '<style>.site-info, .site-info-wrap, .footer-html, .footer-html-credit { display: none !important; }</style>';
}, 100 );

/* Favicon — override WordPress default */
add_action( 'wp_head', function () {
	$svg = esc_url( get_stylesheet_directory_uri() . '/assets/images/favicon.svg' );
	echo '<link rel="icon" href="' . $svg . '" type="image/svg+xml">' . "\n";
	echo '<link rel="shortcut icon" href="' . $svg . '" type="image/svg+xml">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . $svg . '">' . "\n";
}, 1 );

/* Google Analytics 4 — gtag snippet (skip for logged-in admins) */
add_action( 'wp_head', function () {
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}
	$id = 'G-LSGZ05V6HS';
	?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $id ); ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo esc_js( $id ); ?>');
</script>
	<?php
}, 2 );

/* One-time rewrite flush after deploy — fixes /vendors/{category}/ 404s */
add_action( 'init', function () {
	if ( get_option( 'gtmlens_rewrite_flush_v3' ) !== 'done' ) {
		flush_rewrite_rules( false );
		update_option( 'gtmlens_rewrite_flush_v3', 'done' );
	}
}, 999 );

/* 301 redirect: legacy What-is post URL → new pillar page */
add_action( 'template_redirect', function () {
	$path = trim( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ?? '', '/' );
	$redirects = [
		'what-is-gtm-engineering'        => '/gtm-engineering/what-is/',
		'compare/n8n-vs-make-vs-zapier'  => '/compare/n8n-vs-zapier/',
		'insights/funding-tracker'       => '/funding-tracker/',
	];
	if ( isset( $redirects[ $path ] ) ) {
		wp_safe_redirect( home_url( $redirects[ $path ] ), 301 );
		exit;
	}
} );

/* Auto-callout — surface GTM Engineering hub on playbook + best-practice posts */
add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$cats = wp_get_post_categories( get_the_ID(), [ 'fields' => 'slugs' ] );
	if ( ! array_intersect( $cats, [ 'playbook', 'best-practice' ] ) ) {
		return $content;
	}
	$callout = '<aside class="gl-hub-callout" style="margin:32px 0;padding:20px 22px;background:var(--gl-surface);border:1px solid var(--gl-border);border-left:4px solid var(--gl-accent);border-radius:6px;">'
		. '<p style="margin:0 0 6px;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gl-text-muted);">More from GTM Engineering</p>'
		. '<p style="margin:0;font-size:.95rem;line-height:1.55;">This piece is part of the <a href="' . esc_url( home_url( '/gtm-engineering/' ) ) . '" style="font-weight:600;">GTM Engineering hub</a> — long-form education on building AI-native revenue systems. See the <a href="' . esc_url( home_url( '/gtm-engineering/toolkit/' ) ) . '">Toolkit</a> for the slot taxonomy or the <a href="' . esc_url( home_url( '/gtm-engineering/learning-path/' ) ) . '">Learning Path</a> for a structured progression.</p>'
		. '</aside>';
	return $content . $callout;
}, 20 );

/* Schema.org — Article on pillar pages, CollectionPage on hub, BreadcrumbList for /gtm-engineering/* */
add_action( 'wp_head', function () {
	if ( ! is_page() ) {
		return;
	}
	$slug    = get_post_field( 'post_name', get_the_ID() );
	$parent  = wp_get_post_parent_id( get_the_ID() );
	$is_hub  = ( 'gtm-engineering' === $slug && 0 === $parent );
	$is_pillar = in_array( $slug, [ 'what-is', 'toolkit', 'learning-path', 'resources' ], true );
	$parent_slug = $parent ? get_post_field( 'post_name', $parent ) : '';
	if ( ! $is_hub && ! ( $is_pillar && 'gtm-engineering' === $parent_slug ) ) {
		return;
	}

	$site_name = get_bloginfo( 'name' );
	$home      = home_url( '/' );
	$hub_url   = home_url( '/gtm-engineering/' );
	$page_url  = get_permalink();
	$title     = get_the_title();

	// Breadcrumbs (always — both hub and pillars)
	$crumbs = [
		[ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ],
		[ '@type' => 'ListItem', 'position' => 2, 'name' => 'GTM Engineering', 'item' => $hub_url ],
	];
	if ( $is_pillar ) {
		$crumbs[] = [ '@type' => 'ListItem', 'position' => 3, 'name' => $title, 'item' => $page_url ];
	}
	$breadcrumb = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $crumbs,
	];
	echo "<script type=\"application/ld+json\">\n" . wp_json_encode( $breadcrumb, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n</script>\n";

	if ( $is_hub ) {
		$collection = [
			'@context' => 'https://schema.org',
			'@type'    => 'CollectionPage',
			'name'     => $title,
			'url'      => $page_url,
			'description' => 'GTM Engineering education hub: pillars, learning path, playbooks, best practices, and resources.',
			'isPartOf' => [ '@type' => 'WebSite', 'name' => $site_name, 'url' => $home ],
			'hasPart'  => [
				[ '@type' => 'WebPage', 'name' => 'What Is GTM Engineering?', 'url' => home_url( '/gtm-engineering/what-is/' ) ],
				[ '@type' => 'WebPage', 'name' => "The GTM Engineer's Toolkit", 'url' => home_url( '/gtm-engineering/toolkit/' ) ],
				[ '@type' => 'WebPage', 'name' => 'Learning Path', 'url' => home_url( '/gtm-engineering/learning-path/' ) ],
				[ '@type' => 'WebPage', 'name' => 'Playbooks', 'url' => home_url( '/gtm-engineering/playbooks/' ) ],
				[ '@type' => 'WebPage', 'name' => 'Best Practices', 'url' => home_url( '/gtm-engineering/best-practices/' ) ],
				[ '@type' => 'WebPage', 'name' => 'Resources', 'url' => home_url( '/gtm-engineering/resources/' ) ],
			],
		];
		echo "<script type=\"application/ld+json\">\n" . wp_json_encode( $collection, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n</script>\n";
	} elseif ( $is_pillar ) {
		$post = get_post();
		$article = [
			'@context'         => 'https://schema.org',
			'@type'            => 'Article',
			'headline'         => $title,
			'url'              => $page_url,
			'mainEntityOfPage' => $page_url,
			'datePublished'    => mysql2date( 'c', $post->post_date_gmt, false ),
			'dateModified'     => mysql2date( 'c', $post->post_modified_gmt, false ),
			'author'           => [ '@type' => 'Person', 'name' => 'Gagan Chawla' ],
			'publisher'        => [
				'@type' => 'Organization',
				'name'  => $site_name,
				'url'   => $home,
				'logo'  => [
					'@type' => 'ImageObject',
					'url'   => esc_url( get_stylesheet_directory_uri() . '/assets/images/logo.svg' ),
				],
			],
			'description'      => wp_strip_all_tags( get_the_excerpt() ),
			'isPartOf'         => [ '@type' => 'WebPage', 'name' => 'GTM Engineering', 'url' => $hub_url ],
		];
		echo "<script type=\"application/ld+json\">\n" . wp_json_encode( $article, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n</script>\n";
	}
}, 30 );

/* ═══════════════════════════════════════════════════════════════════════════
   7. ACF JSON — LOAD FROM THEME FOLDER
   ═══════════════════════════════════════════════════════════════════════════ */

add_filter( 'acf/settings/save_json', 'gtmlens_acf_json_save_path' );
function gtmlens_acf_json_save_path( string $path ): string {
	return get_stylesheet_directory() . '/acf-json';
}

add_filter( 'acf/settings/load_json', 'gtmlens_acf_json_load_paths' );
function gtmlens_acf_json_load_paths( array $paths ): array {
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
}

/* ═══════════════════════════════════════════════════════════════════════════
   8. STACK QUIZ SHORTCODE
   ═══════════════════════════════════════════════════════════════════════════ */

add_shortcode( 'stack_quiz', 'gtmlens_stack_quiz_shortcode' );
function gtmlens_stack_quiz_shortcode(): string {
	// Order matters: quiz JS defines `stackQuiz()` first, Alpine reads it second.
	// Both deferred → execute in document order. Alpine declares `gtmlens-stack-quiz`
	// as a dependency so WP places the quiz script tag before Alpine's.
	wp_enqueue_script(
		'gtmlens-stack-quiz',
		get_stylesheet_directory_uri() . '/assets/js/stack-quiz.js',
		[],
		filemtime( get_stylesheet_directory() . '/assets/js/stack-quiz.js' ),
		[ 'in_footer' => true, 'strategy' => 'defer' ]
	);

	wp_enqueue_script(
		'alpinejs',
		'https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js',
		[ 'gtmlens-stack-quiz' ],
		'3.13.5',
		[ 'in_footer' => true, 'strategy' => 'defer' ]
	);

	wp_localize_script( 'gtmlens-stack-quiz', 'stackQuizData', [
		'rulesUrl' => get_stylesheet_directory_uri() . '/assets/data/stack-rules.json',
	] );

	ob_start(); ?>
	<div id="stack-quiz" class="gl-quiz" x-data="stackQuiz()" x-init="init()" role="region" aria-label="<?php esc_attr_e( 'GTM Stack Finder', 'gtmlens-child' ); ?>">
		<template x-if="loading">
			<p class="gl-quiz__loading"><?php esc_html_e( 'Loading rules…', 'gtmlens-child' ); ?></p>
		</template>
		<template x-if="error">
			<p class="gl-quiz__error" x-text="error"></p>
		</template>

		<!-- Progress + question (steps 0-4) -->
		<template x-if="!loading && !error && step < 5">
			<div class="gl-quiz__step">
				<div class="gl-quiz__progress" aria-hidden="true">
					<div class="gl-quiz__progress-bar" :style="`width:${progressPercent}%`"></div>
				</div>
				<p class="gl-quiz__step-meta"><?php esc_html_e( 'Question', 'gtmlens-child' ); ?> <span x-text="step + 1"></span> <?php esc_html_e( 'of', 'gtmlens-child' ); ?> <span x-text="questions.length"></span></p>
				<h2 class="gl-quiz__h2" x-text="currentQuestion.label"></h2>
				<p class="gl-quiz__hint" x-text="currentQuestion.hint"></p>

				<div class="gl-quiz__options">
					<template x-for="opt in currentQuestion.options" :key="opt.value">
						<button type="button" class="gl-quiz__option"
							:class="{ 'is-selected': answers[currentQuestion.key] === opt.value }"
							@click="select(opt.value)">
							<span class="gl-quiz__option-label" x-text="opt.label"></span>
							<span class="gl-quiz__option-desc" x-text="opt.desc"></span>
						</button>
					</template>
				</div>

				<div class="gl-quiz__nav">
					<button type="button" class="gl-quiz__back" @click="back()" :disabled="step === 0"><?php esc_html_e( '← Back', 'gtmlens-child' ); ?></button>
					<button type="button" class="gl-quiz__next" @click="advance()" :disabled="!canAdvance()">
						<span x-show="step < questions.length - 1"><?php esc_html_e( 'Next →', 'gtmlens-child' ); ?></span>
						<span x-show="step === questions.length - 1"><?php esc_html_e( 'See my stack →', 'gtmlens-child' ); ?></span>
					</button>
				</div>
			</div>
		</template>

		<!-- Results (step 5) -->
		<template x-if="!loading && !error && step === 5">
			<div class="gl-quiz__results">
				<p class="gl-quiz__step-meta"><?php esc_html_e( 'Your recommended stack', 'gtmlens-child' ); ?></p>
				<h2 class="gl-quiz__h2"><?php esc_html_e( 'Here\'s the stack that matches your inputs', 'gtmlens-child' ); ?></h2>
				<p class="gl-quiz__results-meta">
					<?php esc_html_e( 'Total entry-tier cost:', 'gtmlens-child' ); ?>
					<strong>$<span x-text="totalCost.toLocaleString()"></span>/mo</strong>
					<span class="gl-quiz__results-meta-sep">·</span>
					<span x-text="`${stack.length} tools across ${stack.length} categories`"></span>
				</p>

				<div class="gl-quiz__stack">
					<template x-for="row in stack" :key="row.vendorSlug">
						<a class="gl-quiz__row" :href="row.url">
							<span class="gl-quiz__row-cat" x-text="row.categoryLabel"></span>
							<span class="gl-quiz__row-vendor" x-text="row.name"></span>
							<span class="gl-quiz__row-price" x-text="row.price ? '$' + row.price.toLocaleString() + '/mo' : 'Free / usage-based'"></span>
							<span class="gl-quiz__row-cta">View profile →</span>
						</a>
					</template>
				</div>

				<div class="gl-quiz__share">
					<label for="gl-quiz-share-url"><?php esc_html_e( 'Share this stack:', 'gtmlens-child' ); ?></label>
					<div class="gl-quiz__share-row">
						<input id="gl-quiz-share-url" type="text" :value="shareUrl" readonly>
						<button type="button" @click="copyShareUrl()" class="gl-quiz__copy">
							<span x-show="!copied"><?php esc_html_e( 'Copy', 'gtmlens-child' ); ?></span>
							<span x-show="copied"><?php esc_html_e( 'Copied ✓', 'gtmlens-child' ); ?></span>
						</button>
					</div>
				</div>

				<form class="gl-quiz__email" @submit.prevent="submitEmail()" x-show="!emailForm.submitted">
					<h3><?php esc_html_e( 'Get the PDF + monthly stack-update digest', 'gtmlens-child' ); ?></h3>
					<p class="gl-quiz__email-sub"><?php esc_html_e( 'We\'ll email this stack as a one-page PDF and add you to the monthly GTMLens digest. No vendor pitches — independent analysis only.', 'gtmlens-child' ); ?></p>
					<div class="gl-quiz__email-row">
						<input type="email" x-model="emailForm.value" placeholder="you@company.com" required>
						<button type="submit" :disabled="emailForm.submitting">
							<span x-show="!emailForm.submitting"><?php esc_html_e( 'Email me the stack', 'gtmlens-child' ); ?></span>
							<span x-show="emailForm.submitting"><?php esc_html_e( 'Sending…', 'gtmlens-child' ); ?></span>
						</button>
					</div>
					<p class="gl-quiz__email-error" x-show="emailForm.error" x-text="emailForm.error"></p>
				</form>
				<p class="gl-quiz__email-thanks" x-show="emailForm.submitted"><?php esc_html_e( 'Sent. Check your inbox in a minute or two.', 'gtmlens-child' ); ?></p>

				<div class="gl-quiz__nav">
					<button type="button" class="gl-quiz__back" @click="restart()"><?php esc_html_e( '↺ Start over', 'gtmlens-child' ); ?></button>
					<a class="gl-quiz__next" href="<?php echo esc_url( home_url( '/stack-builder/' ) ); ?>"><?php esc_html_e( 'See all reference stacks →', 'gtmlens-child' ); ?></a>
				</div>
			</div>
		</template>
	</div>
	<?php
	return (string) ob_get_clean();
}

/* ═══════════════════════════════════════════════════════════════════════════
   9. SCHEMA.ORG JSON-LD
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'wp_head', 'gtmlens_schema_output' );
function gtmlens_schema_output(): void {
	if ( is_singular( 'vendor' ) ) {
		gtmlens_schema_vendor();
	} elseif ( is_singular( 'comparison' ) ) {
		gtmlens_schema_comparison();
	} elseif ( is_singular( 'post' ) ) {
		gtmlens_schema_insight();
	} elseif ( is_front_page() ) {
		gtmlens_schema_organization();
	}
}

function gtmlens_schema_vendor(): void {
	$post_id      = get_the_ID();
	$name         = get_the_title();
	$vendor_url   = get_field( 'vendor_url', $post_id );
	$logo         = get_field( 'logo', $post_id );
	$logo_url     = is_array( $logo ) ? esc_url( $logo['url'] ) : '';
	$analyst_take = get_field( 'analyst_take', $post_id );
	$reviewer     = get_field( 'reviewer', $post_id );
	$last_upd     = get_field( 'last_updated', $post_id );
	$pub_date     = get_the_date( 'c', $post_id );
	$mod_date     = $last_upd ? gmdate( 'c', strtotime( $last_upd ) ) : get_the_modified_date( 'c', $post_id );
	$author_name  = $reviewer ?: 'Gagan Chawla';

	// Editorial Article schema — vendor profile is analyst coverage, not a product listing.
	$schema = [
		'@context'      => 'https://schema.org',
		'@type'         => 'Article',
		'headline'      => $name . ' — Analyst Profile',
		'mainEntityOfPage' => [
			'@type' => 'WebPage',
			'@id'   => get_permalink( $post_id ),
		],
		'image'         => $logo_url ?: 'https://gtmlens.com/wp-content/uploads/2026/05/gtmlens-og-default.png',
		'datePublished' => $pub_date,
		'dateModified'  => $mod_date,
		'author'        => [
			'@type' => 'Person',
			'name'  => $author_name,
			'url'   => 'https://www.linkedin.com/in/gagankchawla/',
		],
		'publisher'     => [
			'@type' => 'Organization',
			'name'  => 'GTMLens',
			'url'   => 'https://gtmlens.com',
			'logo'  => [
				'@type' => 'ImageObject',
				'url'   => 'https://gtmlens.com/wp-content/uploads/2026/05/gtmlens-og-default.png',
			],
		],
		'description'   => wp_trim_words( wp_strip_all_tags( $analyst_take ?: '' ), 30 ),
		'about'         => [
			'@type' => 'Organization',
			'name'  => $name,
			'url'   => $vendor_url ?: get_permalink( $post_id ),
			'logo'  => $logo_url,
		],
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

function gtmlens_schema_comparison(): void {
	$post_id  = get_the_ID();
	$vendor_a = get_field( 'vendor_a', $post_id );
	$vendor_b = get_field( 'vendor_b', $post_id );

	$items = [];
	foreach ( [ $vendor_a, $vendor_b ] as $position => $v ) {
		if ( ! $v ) {
			continue;
		}
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position + 1,
			'item'     => [
				'@type' => 'SoftwareApplication',
				'name'  => get_the_title( $v->ID ),
				'url'   => get_permalink( $v->ID ),
				'applicationCategory' => 'BusinessApplication',
			],
		];
	}

	$schema = [
		'@context'        => 'https://schema.org',
		'@type'           => 'ItemList',
		'name'            => get_the_title(),
		'url'             => get_permalink(),
		'numberOfItems'   => count( $items ),
		'itemListElement' => $items,
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

function gtmlens_schema_insight(): void {
	$post_id     = get_the_ID();
	$author_name = get_field( 'author_byline', $post_id );
	if ( ! $author_name ) {
		$author_name = get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
	}

	$schema = [
		'@context'      => 'https://schema.org',
		'@type'         => 'Article',
		'headline'      => get_the_title(),
		'url'           => get_permalink(),
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
		'author'        => [
			'@type' => 'Person',
			'name'  => $author_name,
		],
		'publisher'     => [
			'@type' => 'Organization',
			'name'  => 'GTMLens',
			'url'   => 'https://gtmlens.com',
			'logo'  => [
				'@type' => 'ImageObject',
				'url'   => get_stylesheet_directory_uri() . '/assets/images/og-image.png',
			],
		],
		'description'   => get_the_excerpt(),
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

function gtmlens_schema_organization(): void {
	$schema = [
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => 'GTMLens',
		'url'      => 'https://gtmlens.com',
		'email'    => 'info@gtmlens.com',
		'logo'     => get_stylesheet_directory_uri() . '/assets/images/og-image.png',
		'sameAs'   => [],
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

/* ═══════════════════════════════════════════════════════════════════════════
   10. LAST-UPDATED DISPLAY HELPER
   Called from vendor templates: gtmlens_last_updated( get_the_ID() )
   ═══════════════════════════════════════════════════════════════════════════ */

function gtmlens_last_updated( int $post_id ): void {
	$date = get_field( 'last_updated', $post_id );
	if ( ! $date ) {
		return;
	}
	// ACF date fields return YYYYMMDD when stored as date picker
	$timestamp = strtotime( $date );
	if ( ! $timestamp ) {
		return;
	}
	$formatted = date_i18n( 'M j, Y', $timestamp );
	echo '<p class="gl-last-updated">' . esc_html__( 'Last updated: ', 'gtmlens-child' ) . '<time datetime="' . esc_attr( date( 'Y-m-d', $timestamp ) ) . '">' . esc_html( $formatted ) . '</time></p>';
}

/* ═══════════════════════════════════════════════════════════════════════════
   11. EDITORIAL POLICY CALLOUT HELPER
   ═══════════════════════════════════════════════════════════════════════════ */

function gtmlens_editorial_callout(): void {
	$policy_url = home_url( '/about/editorial-policy/' );
	echo '<div class="gl-editorial-callout">';
	echo '<strong>' . esc_html__( 'Editorial independence:', 'gtmlens-child' ) . '</strong> ';
	echo esc_html__( 'GTMLens accepts no vendor money, paid placements, or affiliate commissions. Our ratings and analysis are based solely on independent research. ', 'gtmlens-child' );
	echo '<a href="' . esc_url( $policy_url ) . '">' . esc_html__( 'Read our editorial policy →', 'gtmlens-child' ) . '</a>';
	echo '</div>';
}

/* ═══════════════════════════════════════════════════════════════════════════
   12. LITESPEED CACHE AUTO-PURGE
   When a stack, vendor, or comparison is updated, purge the LiteSpeed
   server cache so the archive pages don't serve stale HTML to non-Chrome
   browsers (which lack a forced cache-bypass on revisit).
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'save_post', 'gtmlens_purge_cache_on_save', 10, 3 );
function gtmlens_purge_cache_on_save( $post_id, $post, $update ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( ! in_array( $post->post_type, [ 'stack', 'vendor', 'comparison' ], true ) ) {
		return;
	}
	if ( defined( 'LSCWP_V' ) ) {
		do_action( 'litespeed_purge_all' );
	}
}

/* ═══════════════════════════════════════════════════════════════════════════
   13. QUIZ SUBSCRIBE REST ENDPOINT
   POST /wp-json/gtmlens/v1/quiz-subscribe
   Body (JSON): { email, stage, motion, icp, team, budget, stack_url }
   Stores as `quiz_subscriber` CPT. Optionally forwards to MailerLite if
   GTMLENS_MAILERLITE_API_KEY + GTMLENS_MAILERLITE_GROUP_ID are defined in wp-config.php.
   Rate-limited via transient: max 5 subs/hour per IP.
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'rest_api_init', function () {
	register_rest_route( 'gtmlens/v1', '/quiz-subscribe', [
		'methods'             => 'POST',
		'callback'            => 'gtmlens_quiz_subscribe_handler',
		'permission_callback' => '__return_true',
		'args' => [
			'email'     => [ 'required' => true, 'type' => 'string' ],
			'stage'     => [ 'required' => false, 'type' => 'string' ],
			'motion'    => [ 'required' => false, 'type' => 'string' ],
			'icp'       => [ 'required' => false, 'type' => 'string' ],
			'team'      => [ 'required' => false, 'type' => 'string' ],
			'budget'    => [ 'required' => false, 'type' => 'string' ],
			'stack_url' => [ 'required' => false, 'type' => 'string' ],
		],
	] );
} );

function gtmlens_quiz_subscribe_handler( WP_REST_Request $request ) {
	$email = sanitize_email( (string) $request->get_param( 'email' ) );
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'invalid_email', __( 'Please enter a valid email address.', 'gtmlens-child' ), [ 'status' => 400 ] );
	}

	// Rate limit per IP — 5 submissions / hour
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : 'unknown';
	$rate_key = 'gtmlens_quiz_rate_' . md5( $ip );
	$count    = (int) get_transient( $rate_key );
	if ( $count >= 5 ) {
		return new WP_Error( 'rate_limited', __( 'Too many submissions. Please try again later.', 'gtmlens-child' ), [ 'status' => 429 ] );
	}
	set_transient( $rate_key, $count + 1, HOUR_IN_SECONDS );

	$fields = [
		'stage'     => sanitize_text_field( (string) $request->get_param( 'stage' ) ),
		'motion'    => sanitize_text_field( (string) $request->get_param( 'motion' ) ),
		'icp'       => sanitize_text_field( (string) $request->get_param( 'icp' ) ),
		'team'      => sanitize_text_field( (string) $request->get_param( 'team' ) ),
		'budget'    => sanitize_text_field( (string) $request->get_param( 'budget' ) ),
		'stack_url' => esc_url_raw( (string) $request->get_param( 'stack_url' ) ),
	];

	// Dedupe: if subscriber with this email exists, update meta + bump date
	$existing = get_posts( [
		'post_type'   => 'quiz_subscriber',
		'title'       => $email,
		'numberposts' => 1,
		'fields'      => 'ids',
	] );

	if ( $existing ) {
		$post_id = (int) $existing[0];
		wp_update_post( [ 'ID' => $post_id, 'post_modified' => current_time( 'mysql' ) ] );
	} else {
		$post_id = wp_insert_post( [
			'post_type'   => 'quiz_subscriber',
			'post_status' => 'publish',
			'post_title'  => $email,
			'meta_input'  => [
				'email'  => $email,
				'source' => 'stack-finder',
			],
		] );
	}

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return new WP_Error( 'save_failed', __( 'Could not save subscription.', 'gtmlens-child' ), [ 'status' => 500 ] );
	}

	foreach ( $fields as $key => $value ) {
		if ( $value !== '' ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	// Optional MailerLite forward — only if API key constant is defined
	$ml_status = 'skipped';
	if ( gtmlens_get_mailerlite_api_key() ) {
		$ml_status = gtmlens_forward_to_mailerlite( $email, $fields );
	}

	// Hook for any other listeners (e.g. SMTP notification, Slack webhook)
	do_action( 'gtmlens_quiz_subscribed', $email, $fields, $post_id );

	return rest_ensure_response( [
		'ok'         => true,
		'mailerlite' => $ml_status,
	] );
}

function gtmlens_get_mailerlite_api_key(): string {
	if ( defined( 'GTMLENS_MAILERLITE_API_KEY' ) && GTMLENS_MAILERLITE_API_KEY ) {
		return (string) GTMLENS_MAILERLITE_API_KEY;
	}
	return (string) get_option( 'gtmlens_mailerlite_api_key', '' );
}

function gtmlens_get_mailerlite_group_id(): string {
	if ( defined( 'GTMLENS_MAILERLITE_GROUP_ID' ) && GTMLENS_MAILERLITE_GROUP_ID ) {
		return (string) GTMLENS_MAILERLITE_GROUP_ID;
	}
	return (string) get_option( 'gtmlens_mailerlite_group_id', '' );
}

function gtmlens_forward_to_mailerlite( string $email, array $fields ): string {
	$api_key = gtmlens_get_mailerlite_api_key();
	$group   = gtmlens_get_mailerlite_group_id();
	if ( ! $api_key ) {
		return 'skipped:no_key';
	}

	$body = [
		'email'  => $email,
		'fields' => [
			'stack_stage'  => $fields['stage'] ?? '',
			'stack_motion' => $fields['motion'] ?? '',
			'stack_icp'    => $fields['icp'] ?? '',
			'stack_team'   => $fields['team'] ?? '',
			'stack_budget' => $fields['budget'] ?? '',
			'stack_url'    => $fields['stack_url'] ?? '',
		],
	];
	if ( $group ) {
		$body['groups'] = [ $group ];
	}

	$response = wp_remote_post( 'https://connect.mailerlite.com/api/subscribers', [
		'timeout' => 8,
		'headers' => [
			'Authorization' => 'Bearer ' . $api_key,
			'Content-Type'  => 'application/json',
			'Accept'        => 'application/json',
		],
		'body'    => wp_json_encode( $body ),
	] );

	if ( is_wp_error( $response ) ) {
		return 'error:' . $response->get_error_code();
	}
	$code = (int) wp_remote_retrieve_response_code( $response );
	return $code >= 200 && $code < 300 ? 'forwarded' : ( 'error:http_' . $code );
}

/* ═══════════════════════════════════════════════════════════════════════════
   14. GENERIC NEWSLETTER SUBSCRIBE ENDPOINT
   POST /wp-json/gtmlens/v1/subscribe
   Body (JSON): { email, source }
   Stores as `quiz_subscriber` CPT with source meta. Forwards to MailerLite if
   GTMLENS_MAILERLITE_API_KEY is defined. Rate-limited: 5/hour per IP.
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'rest_api_init', function () {
	register_rest_route( 'gtmlens/v1', '/subscribe', [
		'methods'             => 'POST',
		'callback'            => 'gtmlens_subscribe_handler',
		'permission_callback' => '__return_true',
		'args' => [
			'email'  => [ 'required' => true,  'type' => 'string' ],
			'source' => [ 'required' => false, 'type' => 'string' ],
		],
	] );
} );

function gtmlens_subscribe_handler( WP_REST_Request $request ) {
	$email = sanitize_email( (string) $request->get_param( 'email' ) );
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'invalid_email', __( 'Please enter a valid email address.', 'gtmlens-child' ), [ 'status' => 400 ] );
	}

	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : 'unknown';
	$rate_key = 'gtmlens_sub_rate_' . md5( $ip );
	$count    = (int) get_transient( $rate_key );
	if ( $count >= 5 ) {
		return new WP_Error( 'rate_limited', __( 'Too many submissions. Please try again later.', 'gtmlens-child' ), [ 'status' => 429 ] );
	}
	set_transient( $rate_key, $count + 1, HOUR_IN_SECONDS );

	$source = sanitize_text_field( (string) $request->get_param( 'source' ) ) ?: 'newsletter';

	$existing = get_posts( [
		'post_type'   => 'quiz_subscriber',
		'title'       => $email,
		'numberposts' => 1,
		'fields'      => 'ids',
	] );

	if ( $existing ) {
		$post_id = (int) $existing[0];
		wp_update_post( [ 'ID' => $post_id, 'post_modified' => current_time( 'mysql' ) ] );
	} else {
		$post_id = wp_insert_post( [
			'post_type'   => 'quiz_subscriber',
			'post_status' => 'publish',
			'post_title'  => $email,
			'meta_input'  => [
				'email'  => $email,
				'source' => $source,
			],
		] );
	}

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return new WP_Error( 'save_failed', __( 'Could not save subscription.', 'gtmlens-child' ), [ 'status' => 500 ] );
	}

	$ml_status = 'skipped';
	if ( gtmlens_get_mailerlite_api_key() ) {
		$ml_status = gtmlens_forward_to_mailerlite( $email, [ 'source' => $source ] );
	}

	do_action( 'gtmlens_subscribed', $email, $source, $post_id );

	return rest_ensure_response( [ 'ok' => true, 'mailerlite' => $ml_status ] );
}

/* ═══════════════════════════════════════════════════════════════════════════
   15. NEWSLETTER FORM PARTIAL + INSIGHT FOOTER INJECTION
   ═══════════════════════════════════════════════════════════════════════════ */

function gtmlens_newsletter_form( string $source = 'newsletter', string $heading = '', string $sub = '' ): string {
	$heading = $heading ?: __( 'Subscribe to the report', 'gtmlens-child' );
	$sub     = $sub     ?: __( 'Bi-weekly intelligence on the AI-native GTM stack — vendor moves, funding rounds, and analyst takes. No fluff.', 'gtmlens-child' );
	$id      = 'gl-nl-' . wp_generate_uuid4();
	ob_start(); ?>
	<div class="gl-newsletter" data-source="<?php echo esc_attr( $source ); ?>">
		<h3 class="gl-newsletter__h3"><?php echo esc_html( $heading ); ?></h3>
		<p class="gl-newsletter__sub"><?php echo esc_html( $sub ); ?></p>
		<form class="gl-newsletter__form" id="<?php echo esc_attr( $id ); ?>" novalidate>
			<div class="gl-newsletter__row">
				<input class="gl-newsletter__input" type="email" placeholder="you@company.com" required autocomplete="email" aria-label="<?php esc_attr_e( 'Email address', 'gtmlens-child' ); ?>">
				<button class="gl-btn-primary gl-newsletter__btn" type="submit"><?php esc_html_e( 'Subscribe', 'gtmlens-child' ); ?></button>
			</div>
			<p class="gl-newsletter__note" role="status" aria-live="polite"><?php esc_html_e( 'Bi-weekly. No spam. Unsubscribe in one click.', 'gtmlens-child' ); ?></p>
		</form>
	</div>
	<script>
	(function(){
		var f = document.getElementById(<?php echo wp_json_encode( $id ); ?>);
		if (!f) return;
		var input = f.querySelector('input[type=email]');
		var note  = f.querySelector('.gl-newsletter__note');
		var btn   = f.querySelector('button[type=submit]');
		var src   = f.parentNode.getAttribute('data-source') || 'newsletter';
		f.addEventListener('submit', function(e){
			e.preventDefault();
			var email = (input.value || '').trim();
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
				note.textContent = 'Please enter a valid email address.';
				note.style.color = '#A3291C';
				return;
			}
			btn.disabled = true; note.textContent = 'Subscribing…'; note.style.color = '';
			fetch('/wp-json/gtmlens/v1/subscribe', {
				method: 'POST',
				headers: {'Content-Type':'application/json'},
				body: JSON.stringify({ email: email, source: src })
			}).then(function(r){ return r.json().then(function(d){ return {ok:r.ok, d:d}; }); })
			  .then(function(res){
				if (res.ok) {
					note.textContent = 'Subscribed. Check your inbox for confirmation.';
					note.style.color = '#1A7A4A';
					input.value = '';
				} else {
					note.textContent = (res.d && res.d.message) || 'Subscription failed. Please try again.';
					note.style.color = '#A3291C';
				}
			  })
			  .catch(function(){
				note.textContent = 'Network error. Please try again or email info@gtmlens.com.';
				note.style.color = '#A3291C';
			  })
			  .finally(function(){ btn.disabled = false; });
		});
	})();
	</script>
	<?php
	return ob_get_clean();
}

// Render related vendors rail
function gtmlens_related_vendors_html( int $post_id ): string {
	$vendors = get_field( 'related_vendors', $post_id );
	if ( ! is_array( $vendors ) || empty( $vendors ) ) {
		return '';
	}
	$items = '';
	foreach ( $vendors as $v ) {
		$vid = is_object( $v ) ? $v->ID : ( is_array( $v ) ? ( $v['ID'] ?? 0 ) : (int) $v );
		if ( ! $vid ) continue;
		$items .= sprintf(
			'<li class="gl-related-vendor"><a href="%s">%s</a></li>',
			esc_url( get_permalink( $vid ) ),
			esc_html( get_the_title( $vid ) )
		);
	}
	if ( ! $items ) return '';
	return sprintf(
		'<aside class="gl-related-vendors" aria-label="%s"><h3 class="gl-related-vendors__h3">%s</h3><ul class="gl-related-vendors__list">%s</ul></aside>',
		esc_attr__( 'Vendors mentioned in this analysis', 'gtmlens-child' ),
		esc_html__( 'Vendors covered in this analysis', 'gtmlens-child' ),
		$items
	);
}

// Append related vendors + newsletter to insight content
add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'post' ) || ! is_main_query() || is_admin() ) {
		return $content;
	}
	$related = gtmlens_related_vendors_html( get_the_ID() );
	$form = gtmlens_newsletter_form(
		'insight-footer',
		__( 'Get the next analyst take in your inbox', 'gtmlens-child' ),
		__( 'Bi-weekly. Independent intelligence on the AI-native GTM stack.', 'gtmlens-child' )
	);
	return $content . $related . '<div class="gl-newsletter-wrap">' . $form . '</div>';
}, 20 );

// Shortcode for manual placement
add_shortcode( 'newsletter_signup', function ( $atts ) {
	$a = shortcode_atts( [ 'source' => 'shortcode', 'heading' => '', 'sub' => '' ], $atts );
	return gtmlens_newsletter_form( $a['source'], $a['heading'], $a['sub'] );
} );

/* ═══════════════════════════════════════════════════════════════════════════
   16. MAILERLITE SETTINGS PAGE (Settings → GTMLens MailerLite)
   Stores API key + group ID in wp_options. Only used when wp-config.php
   constants aren't defined (constants take precedence).
   ═══════════════════════════════════════════════════════════════════════════ */

add_action( 'admin_menu', function () {
	add_options_page(
		__( 'GTMLens MailerLite', 'gtmlens-child' ),
		__( 'GTMLens MailerLite', 'gtmlens-child' ),
		'manage_options',
		'gtmlens-mailerlite',
		'gtmlens_mailerlite_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'gtmlens_mailerlite', 'gtmlens_mailerlite_api_key', [ 'sanitize_callback' => 'trim' ] );
	register_setting( 'gtmlens_mailerlite', 'gtmlens_mailerlite_group_id', [ 'sanitize_callback' => 'sanitize_text_field' ] );
} );

function gtmlens_mailerlite_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$key      = (string) get_option( 'gtmlens_mailerlite_api_key', '' );
	$group    = (string) get_option( 'gtmlens_mailerlite_group_id', '' );
	$const_key = defined( 'GTMLENS_MAILERLITE_API_KEY' ) && GTMLENS_MAILERLITE_API_KEY;
	$active_key = gtmlens_get_mailerlite_api_key();
	$key_masked = $active_key ? substr( $active_key, 0, 12 ) . '…' . substr( $active_key, -8 ) : '(not set)';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'GTMLens MailerLite', 'gtmlens-child' ); ?></h1>
		<p><?php esc_html_e( 'API key for the MailerLite forwarder. Subscribers from the home form, insight footers, and Stack Builder quiz are POSTed to MailerLite when this is set.', 'gtmlens-child' ); ?></p>
		<div style="background:#f0f6fc;border-left:4px solid #2271b1;padding:12px 16px;margin:16px 0;">
			<strong><?php esc_html_e( 'Active key:', 'gtmlens-child' ); ?></strong>
			<code style="font-family:monospace;"><?php echo esc_html( $key_masked ); ?></code>
			<?php if ( $const_key ) : ?>
				<br><em><?php esc_html_e( 'Sourced from wp-config.php constant (overrides this page).', 'gtmlens-child' ); ?></em>
			<?php endif; ?>
		</div>
		<form method="post" action="options.php">
			<?php settings_fields( 'gtmlens_mailerlite' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="gtmlens_mailerlite_api_key"><?php esc_html_e( 'API key', 'gtmlens-child' ); ?></label></th>
					<td>
						<textarea id="gtmlens_mailerlite_api_key" name="gtmlens_mailerlite_api_key" rows="4" cols="80" style="font-family:monospace;font-size:11px;" autocomplete="off"><?php echo esc_textarea( $key ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Paste the JWT from MailerLite → Integrations → Developer API. Only saved on this server (not exposed publicly).', 'gtmlens-child' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="gtmlens_mailerlite_group_id"><?php esc_html_e( 'Group ID (optional)', 'gtmlens-child' ); ?></label></th>
					<td>
						<input id="gtmlens_mailerlite_group_id" name="gtmlens_mailerlite_group_id" type="text" value="<?php echo esc_attr( $group ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'Leave blank to add subscribers without a group. From MailerLite → Subscribers → Groups → URL slug.', 'gtmlens-child' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/* ═══════════════════════════════════════════════════════════════════════════
   17. SEO + DISTRIBUTION HARDENING (P5+P6 sprint)
   - Default og:image fallback (1200x630 branded PNG)
   - Sharper home meta description (RankMath uses post excerpt > tagline)
   - GSC / Bing verification meta tags via wp_options
   - Social share row + canonical mailto append on insight posts
   ═══════════════════════════════════════════════════════════════════════════ */

/* Default OG image — used when RankMath has no per-post featured image / OG override */
function gtmlens_default_og_image_url(): string {
	return get_stylesheet_directory_uri() . '/assets/images/og-image.png';
}

add_filter( 'rank_math/opengraph/facebook/image', function ( $image ) {
	return $image ? $image : gtmlens_default_og_image_url();
}, 10 );
add_filter( 'rank_math/opengraph/twitter/image', function ( $image ) {
	return $image ? $image : gtmlens_default_og_image_url();
}, 10 );

/* Belt-and-suspenders: if the page rendered with NO og:image at all, inject one.
   Runs late so RankMath outputs first. */
add_action( 'wp_head', function () {
	// Output buffer trick is overkill; instead just always output a fallback meta.
	// Multiple og:image tags are valid per OGP spec; the first one with a valid URL wins.
	$url = esc_url( gtmlens_default_og_image_url() );
	echo '<meta property="og:image" content="' . $url . '" />' . "\n";
	echo '<meta property="og:image:width" content="1200" />' . "\n";
	echo '<meta property="og:image:height" content="630" />' . "\n";
	echo '<meta property="og:image:type" content="image/png" />' . "\n";
	echo '<meta name="twitter:image" content="' . $url . '" />' . "\n";
}, 5 );

/* Home meta description — replace the thin "tagline only" fallback.
   RankMath honors the document title/description filters when no explicit override. */
add_filter( 'rank_math/frontend/description', function ( $desc ) {
	if ( is_front_page() ) {
		return 'Independent analyst coverage of the AI-native GTM stack: 31 vendor profiles, 20 head-to-head comparisons, 6 stack recipes, and weekly deep-dives. No vendor money. No affiliate links.';
	}
	return $desc;
} );
add_filter( 'rank_math/frontend/title', function ( $title ) {
	if ( is_front_page() ) {
		return 'GTMLens — Independent analyst coverage of the AI-native GTM stack';
	}
	return $title;
} );

/* Search Console / Bing verification meta tags — set via Settings → GTMLens MailerLite (extended) */
function gtmlens_get_gsc_token(): string {
	return (string) get_option( 'gtmlens_gsc_verification', '' );
}
function gtmlens_get_bing_token(): string {
	return (string) get_option( 'gtmlens_bing_verification', '' );
}
add_action( 'wp_head', function () {
	$gsc  = gtmlens_get_gsc_token();
	$bing = gtmlens_get_bing_token();
	if ( $gsc )  echo '<meta name="google-site-verification" content="' . esc_attr( $gsc )  . '" />' . "\n";
	if ( $bing ) echo '<meta name="msvalidate.01" content="'             . esc_attr( $bing ) . '" />' . "\n";
}, 1 );

/* Register the new option fields on the existing settings page */
add_action( 'admin_init', function () {
	register_setting( 'gtmlens_mailerlite', 'gtmlens_gsc_verification',  [ 'sanitize_callback' => 'sanitize_text_field' ] );
	register_setting( 'gtmlens_mailerlite', 'gtmlens_bing_verification', [ 'sanitize_callback' => 'sanitize_text_field' ] );
} );

/* Append two extra rows to the settings form via filter — simpler: a separate small admin notice block.
   Add a second options page section by hooking into admin_footer on our screen. */
add_action( 'admin_footer-settings_page_gtmlens-mailerlite', function () {
	$gsc  = esc_attr( gtmlens_get_gsc_token() );
	$bing = esc_attr( gtmlens_get_bing_token() );
	?>
	<script>
	(function(){
		var form = document.querySelector('.wrap form[action="options.php"]');
		if (!form) return;
		var table = form.querySelector('table.form-table');
		if (!table) return;
		var html = ''
			+ '<tr><th colspan="2" style="padding-top:24px;border-top:1px solid #ccd0d4;"><h2 style="margin:0;font-size:1.1em;">Search engine verification</h2></th></tr>'
			+ '<tr><th scope="row"><label for="gtmlens_gsc_verification">Google Search Console</label></th>'
			+ '<td><input type="text" id="gtmlens_gsc_verification" name="gtmlens_gsc_verification" value="<?php echo $gsc; ?>" class="regular-text" placeholder="abc123…">'
			+ '<p class="description">Paste only the <code>content</code> value from the meta-tag verification method.</p></td></tr>'
			+ '<tr><th scope="row"><label for="gtmlens_bing_verification">Bing Webmaster Tools</label></th>'
			+ '<td><input type="text" id="gtmlens_bing_verification" name="gtmlens_bing_verification" value="<?php echo $bing; ?>" class="regular-text" placeholder="abc123…">'
			+ '<p class="description">Optional. Paste the <code>content</code> from <code>&lt;meta name="msvalidate.01"&gt;</code>.</p></td></tr>';
		table.insertAdjacentHTML('beforeend', html);
	})();
	</script>
	<?php
} );

/* Social share row — append to insight posts (after newsletter form) */
function gtmlens_social_share_html( int $post_id ): string {
	$url   = rawurlencode( get_permalink( $post_id ) );
	$title = rawurlencode( get_the_title( $post_id ) );
	$x     = "https://twitter.com/intent/tweet?text={$title}&url={$url}";
	$li    = "https://www.linkedin.com/sharing/share-offsite/?url={$url}";
	$em    = "mailto:?subject={$title}&body=Thought%20you%27d%20find%20this%20useful%3A%20{$url}";
	return '<aside class="gl-share" aria-label="Share this article">'
		. '<span class="gl-share__label">Share</span>'
		. '<a class="gl-share__btn" href="' . esc_url( $x )  . '" target="_blank" rel="noopener" aria-label="Share on X">X / Twitter</a>'
		. '<a class="gl-share__btn" href="' . esc_url( $li ) . '" target="_blank" rel="noopener" aria-label="Share on LinkedIn">LinkedIn</a>'
		. '<a class="gl-share__btn" href="' . esc_url( $em ) . '" aria-label="Share via email">Email</a>'
		. '</aside>';
}
add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) return $content;
	return $content . gtmlens_social_share_html( get_the_ID() );
}, 19 ); // before newsletter (priority 20+) so order is: content → share → related → newsletter

/* ═══════════════════════════════════════════════════════════════════════════
   18. P9 — FUNDING TRACKER
   - Data migration: parse legacy text funding fields → numeric USD-millions
   - Normalize last_round_date to ISO Y-m-d
   - REST endpoint: /wp-json/gtmlens/v1/funding-events
   - Schema.org Dataset JSON-LD on /insights/funding-tracker/
   - Admin tool: Settings → Funding Tracker Migration
   ═══════════════════════════════════════════════════════════════════════════ */

/**
 * Parse a free-text funding amount like "$100M", "~$165M", "$3.1B", "1.5B" → integer USD millions.
 * Returns null if unparseable.
 */
function gtmlens_parse_usd_millions( $raw ): ?int {
	if ( empty( $raw ) || ! is_string( $raw ) ) return null;
	$s = strtolower( trim( $raw ) );
	// Strip leading approximations and currency symbols
	$s = preg_replace( '/^[~≈≃\s\$]+/', '', $s );
	// Match number + optional unit
	if ( ! preg_match( '/([0-9]+(?:\.[0-9]+)?)\s*([kmb])?/', $s, $m ) ) return null;
	$n    = (float) $m[1];
	$unit = $m[2] ?? 'm';
	switch ( $unit ) {
		case 'b': $n *= 1000; break;        // $1B = 1000M
		case 'k': $n /= 1000; break;        // $500K = 0.5M
		case 'm': default:    break;
	}
	return (int) round( $n );
}

/**
 * Normalize a date value to ISO Y-m-d. Accepts Ymd, Y-m-d, m/d/Y, Unix timestamps, etc.
 * Returns '' if unparseable.
 */
function gtmlens_normalize_date( $raw ): string {
	if ( empty( $raw ) ) return '';
	if ( is_numeric( $raw ) && strlen( (string) $raw ) === 8 ) {
		// ACF stored Ymd
		$y = substr( $raw, 0, 4 );
		$m = substr( $raw, 4, 2 );
		$d = substr( $raw, 6, 2 );
		if ( checkdate( (int) $m, (int) $d, (int) $y ) ) return "$y-$m-$d";
	}
	$ts = strtotime( (string) $raw );
	return $ts ? gmdate( 'Y-m-d', $ts ) : '';
}

/**
 * One-time migration: for every vendor, populate numeric funding fields from text fields,
 * and normalize last_round_date.
 */
function gtmlens_migrate_funding_fields( bool $dry_run = false ): array {
	$report = [ 'processed' => 0, 'updated' => [], 'skipped' => [], 'errors' => [] ];
	$vendors = get_posts( [
		'post_type'      => 'vendor',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'fields'         => 'ids',
	] );
	foreach ( $vendors as $vid ) {
		$report['processed']++;
		$slug = get_post_field( 'post_name', $vid );

		$txt_size  = get_field( 'last_round_size',  $vid );
		$txt_total = get_field( 'total_raised',     $vid );
		$txt_val   = get_field( 'last_valuation',   $vid );
		$raw_date  = get_field( 'last_round_date',  $vid, false ); // raw, no formatting

		$num_size  = gtmlens_parse_usd_millions( $txt_size );
		$num_total = gtmlens_parse_usd_millions( $txt_total );
		$num_val   = gtmlens_parse_usd_millions( $txt_val );
		$iso_date  = gtmlens_normalize_date( $raw_date );

		$changes = [];
		if ( null !== $num_size  && get_field( 'last_round_size_usd_m',  $vid ) !== $num_size  ) $changes['last_round_size_usd_m']  = $num_size;
		if ( null !== $num_total && get_field( 'total_raised_usd_m',     $vid ) !== $num_total ) $changes['total_raised_usd_m']     = $num_total;
		if ( null !== $num_val   && get_field( 'last_valuation_usd_m',   $vid ) !== $num_val   ) $changes['last_valuation_usd_m']   = $num_val;
		if ( $iso_date && $iso_date !== $raw_date ) $changes['last_round_date'] = $iso_date;

		if ( ! $changes ) {
			$report['skipped'][] = $slug;
			continue;
		}

		if ( ! $dry_run ) {
			foreach ( $changes as $field => $val ) {
				update_field( $field, $val, $vid );
			}
		}
		$report['updated'][ $slug ] = $changes;
	}
	if ( ! $dry_run ) {
		update_option( 'gtmlens_funding_migration_v1', 'done_' . current_time( 'Y-m-d_H:i:s' ) );
	}
	return $report;
}

/**
 * Admin page: Settings → Funding Tracker Migration.
 * Lets you preview (dry-run) and then commit the migration.
 */
add_action( 'admin_menu', function () {
	add_options_page(
		__( 'Funding Tracker Migration', 'gtmlens-child' ),
		__( 'Funding Migration', 'gtmlens-child' ),
		'manage_options',
		'gtmlens-funding-migration',
		'gtmlens_funding_migration_page'
	);
} );

/**
 * Bulk backfill: parse "slug=YYYY-MM-DD[|amount|stage|valuation]" lines and write to vendor ACF.
 * Returns array of [slug, status, message] tuples.
 */
function gtmlens_bulk_backfill_funding( string $payload ): array {
	$results = [];
	$lines   = preg_split( '/\r?\n/', trim( $payload ) );
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line || '#' === substr( $line, 0, 1 ) ) continue;
		$parts = explode( '=', $line, 2 );
		if ( count( $parts ) !== 2 ) {
			$results[] = [ $line, 'error', 'malformed (expected slug=YYYY-MM-DD)' ];
			continue;
		}
		$slug   = trim( $parts[0] );
		$values = array_map( 'trim', explode( '|', $parts[1] ) );
		// Lookup vendor by slug
		$posts = get_posts( [
			'post_type'      => 'vendor',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		] );
		if ( ! $posts ) {
			$results[] = [ $slug, 'error', 'vendor not found' ];
			continue;
		}
		$vid = $posts[0];
		$changes = [];
		// Field 1: date
		if ( ! empty( $values[0] ) ) {
			$iso = gtmlens_normalize_date( $values[0] );
			if ( $iso ) {
				update_field( 'last_round_date', $iso, $vid );
				$changes[] = "date=$iso";
			} else {
				$results[] = [ $slug, 'error', 'bad date: ' . $values[0] ];
				continue;
			}
		}
		// Field 2: round size USD millions (optional)
		if ( isset( $values[1] ) && '' !== $values[1] && is_numeric( $values[1] ) ) {
			update_field( 'last_round_size_usd_m', (int) $values[1], $vid );
			$changes[] = 'size=' . (int) $values[1] . 'M';
		}
		// Field 3: stage (optional, overrides if provided)
		if ( isset( $values[2] ) && '' !== $values[2] ) {
			update_field( 'funding_stage', $values[2], $vid );
			$changes[] = 'stage=' . $values[2];
		}
		// Field 4: valuation USD millions (optional)
		if ( isset( $values[3] ) && '' !== $values[3] && is_numeric( $values[3] ) ) {
			update_field( 'last_valuation_usd_m', (int) $values[3], $vid );
			$changes[] = 'val=' . (int) $values[3] . 'M';
		}
		$results[] = [ $slug, 'ok', implode( ', ', $changes ) ];
	}
	return $results;
}

/**
 * Bulk-add funding_event posts. One per line:
 *   company|YYYY-MM-DD|amount_M|stage|valuation_M|category_slug|event_type|source_url|note
 * Required: company, date, source_url, event_type. Others optional.
 * Lines starting with # are skipped.
 */
function gtmlens_bulk_add_funding_events( string $payload ): array {
	$results = [];
	$lines   = preg_split( '/\r?\n/', trim( $payload ) );
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line || '#' === substr( $line, 0, 1 ) ) continue;
		$parts = array_map( 'trim', explode( '|', $line ) );
		if ( count( $parts ) < 4 ) {
			$results[] = [ $line, 'error', 'need at least company|date|amount|...|event_type|source_url' ];
			continue;
		}
		$company    = $parts[0];
		$date       = $parts[1] ?? '';
		$amount     = $parts[2] ?? '';
		$stage      = $parts[3] ?? '';
		$valuation  = $parts[4] ?? '';
		$cat_slug   = $parts[5] ?? '';
		$event_type = $parts[6] ?? 'round';
		$source_url = $parts[7] ?? '';
		$note       = $parts[8] ?? '';
		if ( ! $company || ! $date || ! $event_type ) {
			$results[] = [ $company ?: $line, 'error', 'company, date, event_type required' ];
			continue;
		}
		$iso_date = gtmlens_normalize_date( $date );
		if ( ! $iso_date ) {
			$results[] = [ $company, 'error', "bad date: $date" ];
			continue;
		}
		// Try to find linked vendor by slug match
		$linked_id = 0;
		$slug_guess = sanitize_title( $company );
		$match = get_posts( [
			'post_type'      => 'vendor',
			'name'           => $slug_guess,
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		] );
		if ( $match ) $linked_id = $match[0];

		// Slug uniqueness for funding_event
		$post_slug = sanitize_title( $company . '-' . $iso_date );
		$post_id = wp_insert_post( [
			'post_type'   => 'funding_event',
			'post_title'  => $company . ' — ' . $event_type . ' — ' . $iso_date,
			'post_name'   => $post_slug,
			'post_status' => 'publish',
		], true );
		if ( is_wp_error( $post_id ) ) {
			$results[] = [ $company, 'error', $post_id->get_error_message() ];
			continue;
		}
		// Set ACF fields
		update_field( 'company_name', $company,    $post_id );
		update_field( 'event_date',   $iso_date,   $post_id );
		update_field( 'event_type',   $event_type, $post_id );
		update_field( 'source_url',   $source_url, $post_id );
		if ( $stage )      update_field( 'stage',           $stage,            $post_id );
		if ( is_numeric( $amount ) && $amount )    update_field( 'amount_usd_m',    (int) $amount,    $post_id );
		if ( is_numeric( $valuation ) && $valuation ) update_field( 'valuation_usd_m', (int) $valuation, $post_id );
		if ( $note )       update_field( 'analyst_note',    $note,             $post_id );
		if ( $linked_id )  update_field( 'vendor',          $linked_id,        $post_id );
		if ( $cat_slug ) {
			$term = get_term_by( 'slug', $cat_slug, 'vendor_category' );
			if ( $term ) update_field( 'category', $term->term_id, $post_id );
		}
		$results[] = [
			$company, 'ok',
			"id=$post_id, date=$iso_date, type=$event_type" . ( $linked_id ? ", linked=#$linked_id" : '' ) . ( $note ? ', has note' : '' )
		];
	}
	return $results;
}

function gtmlens_funding_migration_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$status = (string) get_option( 'gtmlens_funding_migration_v1', 'not_run' );
	$action = isset( $_POST['gtmlens_action'] ) ? sanitize_text_field( $_POST['gtmlens_action'] ) : '';
	$report = null;
	$backfill_results = null;
	$events_results   = null;
	if ( $action && check_admin_referer( 'gtmlens_funding_migration' ) ) {
		if ( 'dry_run' === $action ) {
			$report = gtmlens_migrate_funding_fields( true );
		} elseif ( 'commit' === $action ) {
			$report = gtmlens_migrate_funding_fields( false );
			$status = (string) get_option( 'gtmlens_funding_migration_v1', '' );
		} elseif ( 'backfill' === $action ) {
			$payload = isset( $_POST['gtmlens_backfill'] ) ? wp_unslash( $_POST['gtmlens_backfill'] ) : '';
			$backfill_results = gtmlens_bulk_backfill_funding( (string) $payload );
		} elseif ( 'add_events' === $action ) {
			$payload = isset( $_POST['gtmlens_add_events'] ) ? wp_unslash( $_POST['gtmlens_add_events'] ) : '';
			$events_results = gtmlens_bulk_add_funding_events( (string) $payload );
		}
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Funding Tracker Migration', 'gtmlens-child' ); ?></h1>
		<p>Parses the existing text funding fields (<code>last_round_size</code>, <code>total_raised</code>, <code>last_valuation</code>) into numeric USD-millions fields, and normalizes <code>last_round_date</code> to ISO format.</p>
		<div style="background:#f0f6fc;border-left:4px solid #2271b1;padding:12px 16px;margin:16px 0;">
			<strong>Status:</strong> <code><?php echo esc_html( $status ); ?></code><br>
			<em>Existing text fields are NOT removed — they remain available for display use.</em>
		</div>

		<form method="post" style="display:inline-block;margin-right:8px;">
			<?php wp_nonce_field( 'gtmlens_funding_migration' ); ?>
			<input type="hidden" name="gtmlens_action" value="dry_run">
			<button type="submit" class="button">Run dry-run preview</button>
		</form>
		<form method="post" style="display:inline-block;">
			<?php wp_nonce_field( 'gtmlens_funding_migration' ); ?>
			<input type="hidden" name="gtmlens_action" value="commit">
			<button type="submit" class="button button-primary" onclick="return confirm('Run migration for real? Make sure you have a backup.');">Commit migration</button>
		</form>

		<hr style="margin:32px 0;">
		<h2>Bulk backfill: <code>last_round_date</code></h2>
		<p>One vendor per line. Format: <code>slug=YYYY-MM-DD</code>. Optional extra fields piped after: <code>slug=YYYY-MM-DD|round_size_M|stage|valuation_M</code>. Lines starting with <code>#</code> are skipped.</p>
		<form method="post">
			<?php wp_nonce_field( 'gtmlens_funding_migration' ); ?>
			<input type="hidden" name="gtmlens_action" value="backfill">
			<textarea name="gtmlens_backfill" rows="14" style="width:100%;max-width:900px;font-family:monospace;font-size:12px;" placeholder="# Examples:&#10;pipedrive=2020-12-01|90&#10;zapier=2021-01-26|140|Series E|5000&#10;# next vendor..."></textarea>
			<p><button type="submit" class="button button-primary" onclick="return confirm('Apply backfill?');">Apply backfill</button></p>
		</form>
		<?php if ( $backfill_results ) : ?>
			<h3>Backfill results</h3>
			<table class="widefat striped" style="max-width:900px;">
				<thead><tr><th>Vendor</th><th>Status</th><th>Changes / message</th></tr></thead>
				<tbody>
				<?php foreach ( $backfill_results as $r ) : ?>
					<tr>
						<td><code><?php echo esc_html( $r[0] ); ?></code></td>
						<td><?php echo $r[1] === 'ok' ? '<span style="color:#2c7a2e;">✓ ok</span>' : '<span style="color:#b71c1c;">✗ error</span>'; ?></td>
						<td><?php echo esc_html( $r[2] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<hr style="margin:32px 0;">
		<h2>Bulk-add funding events</h2>
		<p>One event per line. Format: <code>company|YYYY-MM-DD|amount_M|stage|val_M|category_slug|event_type|source_url|note</code></p>
		<p style="font-size:12px;color:#646970;">Required: company, date, event_type, source_url. <code>event_type</code> = round | ma | ipo | shutdown | bootstrapped. <code>category_slug</code> uses the vendor_category slug (e.g. <code>ai-sdr</code>, <code>data-enrichment</code>). Lines starting with <code>#</code> are skipped.</p>
		<form method="post">
			<?php wp_nonce_field( 'gtmlens_funding_migration' ); ?>
			<input type="hidden" name="gtmlens_action" value="add_events">
			<textarea name="gtmlens_add_events" rows="14" style="width:100%;max-width:1100px;font-family:monospace;font-size:11px;" placeholder="# Examples:&#10;Glean|2024-09-10|260|Series E|4600|||round|https://example.com/glean-series-e|Pure category-defining round.&#10;Sierra|2024-10-29|175|Series B|4000|ai-sdr|round|https://example.com/sierra|Bret Taylor priced into the round more than the technology.&#10;# next..."></textarea>
			<p><button type="submit" class="button button-primary" onclick="return confirm('Create funding events?');">Create events</button></p>
		</form>
		<?php if ( $events_results ) : ?>
			<h3>Bulk-add results</h3>
			<table class="widefat striped" style="max-width:1100px;">
				<thead><tr><th>Company</th><th>Status</th><th>Details</th></tr></thead>
				<tbody>
				<?php foreach ( $events_results as $r ) : ?>
					<tr>
						<td><?php echo esc_html( $r[0] ); ?></td>
						<td><?php echo $r[1] === 'ok' ? '<span style="color:#2c7a2e;">✓ ok</span>' : '<span style="color:#b71c1c;">✗ error</span>'; ?></td>
						<td><?php echo esc_html( $r[2] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( $report ) : ?>
			<h2><?php echo $action === 'dry_run' ? 'Dry-run report' : 'Commit report'; ?></h2>
			<p><strong><?php echo (int) $report['processed']; ?></strong> vendors processed; <strong><?php echo count( $report['updated'] ); ?></strong> changed; <strong><?php echo count( $report['skipped'] ); ?></strong> unchanged.</p>
			<?php if ( $report['updated'] ) : ?>
				<h3>Changes</h3>
				<table class="widefat striped" style="max-width:900px;">
					<thead><tr><th>Vendor</th><th>Field</th><th>New value</th></tr></thead>
					<tbody>
					<?php foreach ( $report['updated'] as $slug => $changes ) : ?>
						<?php foreach ( $changes as $field => $val ) : ?>
							<tr>
								<td><code><?php echo esc_html( $slug ); ?></code></td>
								<td><code><?php echo esc_html( $field ); ?></code></td>
								<td><code><?php echo esc_html( (string) $val ); ?></code></td>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Strip "no real info" placeholder strings from legacy display text fields.
 * Keeps actually-informative strings (e.g., "$2.3B (acquisition price, 2024)").
 */
function gtmlens_clean_disp( string $s ): string {
	$s = trim( $s );
	if ( '' === $s ) return '';
	$lower = strtolower( $s );
	$noise = [ 'n/a', 'na', '—', '-', 'not publicly disclosed', 'undisclosed', 'unknown', 'tbd', '(undisclosed)', '(not disclosed)' ];
	foreach ( $noise as $needle ) {
		if ( $lower === $needle ) return '';
	}
	// Strip leading "N/A" prefix when followed by content
	$s = preg_replace( '/^(n\/a|na|undisclosed)\s*[\(\s]+/i', '', $s );
	return trim( $s );
}

/**
 * Resolve a vendor's event_type from its funding_stage.
 * "Acquired" → ma; "Public" or "IPO" → ipo; "Bootstrapped" → bootstrapped; else round.
 */
function gtmlens_resolve_event_type( string $stage ): string {
	$s = strtolower( trim( $stage ) );
	if ( '' === $s ) return 'round';
	if ( 'acquired'      === $s ) return 'ma';
	if ( 'public'        === $s || 'ipo' === $s ) return 'ipo';
	if ( 'bootstrapped'  === $s ) return 'bootstrapped';
	return 'round';
}

/**
 * Build the merged funding-event list (vendors with last_round_date + funding_event posts).
 * Returns array sorted DESC by date.
 *
 * @param array $args Optional: ['exclude_public' => true|false]
 */
function gtmlens_get_funding_events( array $args = [] ): array {
	$exclude_public = ! empty( $args['exclude_public'] );
	$out = [];

	// 1. Build funding_event rows first, indexed by (vendor_id|date) so we can
	//    suppress duplicate vendor rows when a funding_event covers the same round.
	//    The funding_event wins because it carries the analyst note + curated source.
	$event_rows = [];
	$dedup_keys = [];
	$events = get_posts( [
		'post_type'      => 'funding_event',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
	] );
	foreach ( $events as $eid ) {
		$event_type = (string) get_field( 'event_type', $eid );
		if ( $exclude_public && 'ipo' === $event_type ) continue;

		$linked     = (int)    get_field( 'vendor', $eid );
		$cat_obj    = get_field( 'category', $eid );
		$cat_name   = ( is_object( $cat_obj ) && isset( $cat_obj->name ) ) ? html_entity_decode( $cat_obj->name, ENT_QUOTES, 'UTF-8' ) : '';
		$source_url = (string) get_field( 'source_url', $eid );
		$event_date = gtmlens_normalize_date( get_field( 'event_date', $eid, false ) );
		if ( $linked && $event_date ) {
			$dedup_keys[ $linked . '|' . $event_date ] = true;
		}
		$event_rows[] = [
			'company'     => html_entity_decode( (string) get_field( 'company_name', $eid ), ENT_QUOTES, 'UTF-8' ),
			'slug'        => '',
			'category'    => $cat_name,
			'stage'       => (string) get_field( 'stage', $eid ),
			'amount_m'    => (int)    get_field( 'amount_usd_m', $eid ),
			'amount_disp' => '',
			'val_m'       => (int)    get_field( 'valuation_usd_m', $eid ),
			'val_disp'    => '',
			'total_m'     => 0,
			'total_fmt'   => '',
			'total_disp'  => '',
			'date'        => $event_date,
			'event_type'  => $event_type,
			'note'        => (string) get_field( 'analyst_note', $eid ),
			'url'         => $linked ? get_permalink( $linked ) : $source_url,
			'source'      => $source_url,
			'source_kind' => $linked ? 'vendor' : 'external',
		];
	}

	// 2. Vendors with funding data — skip any (vendor, date) already covered by a funding_event.
	$vendors = get_posts( [
		'post_type'      => 'vendor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_query'     => [
			[ 'key' => 'last_round_date', 'compare' => '!=', 'value' => '' ],
		],
		'fields' => 'ids',
	] );
	foreach ( $vendors as $vid ) {
		$vendor_date = gtmlens_normalize_date( get_field( 'last_round_date', $vid, false ) );
		if ( $vendor_date && isset( $dedup_keys[ $vid . '|' . $vendor_date ] ) ) {
			continue; // funding_event covers this round
		}

		$cats       = wp_get_post_terms( $vid, 'vendor_category', [ 'fields' => 'names' ] );
		$stage      = (string) get_field( 'funding_stage', $vid );
		$event_type = gtmlens_resolve_event_type( $stage );
		if ( $exclude_public && 'ipo' === $event_type ) continue;

		$total_m   = (int) get_field( 'total_raised_usd_m', $vid );
		$out[] = [
			'company'     => html_entity_decode( get_the_title( $vid ), ENT_QUOTES, 'UTF-8' ),
			'slug'        => get_post_field( 'post_name', $vid ),
			'category'    => $cats ? html_entity_decode( $cats[0], ENT_QUOTES, 'UTF-8' ) : '',
			'stage'       => $stage,
			'amount_m'    => (int)    get_field( 'last_round_size_usd_m', $vid ),
			'amount_disp' => gtmlens_clean_disp( (string) get_field( 'last_round_size', $vid ) ),
			'val_m'       => (int)    get_field( 'last_valuation_usd_m',  $vid ),
			'val_disp'    => gtmlens_clean_disp( (string) get_field( 'last_valuation', $vid ) ),
			'total_m'     => $total_m,
			'total_fmt'   => $total_m > 0 ? gtmlens_fmt_usd_m_php( $total_m ) : '',
			'total_disp'  => gtmlens_clean_disp( (string) get_field( 'total_raised', $vid ) ),
			'date'        => $vendor_date,
			'event_type'  => $event_type,
			'note'        => '',
			'url'         => get_permalink( $vid ),
			'source'      => (string) get_field( 'last_round_source_url', $vid ),
			'source_kind' => 'vendor',
		];
	}

	// 3. Append all funding_event rows (already built above)
	foreach ( $event_rows as $row ) {
		$out[] = $row;
	}

	// Sort DESC by date
	usort( $out, function ( $a, $b ) {
		return strcmp( $b['date'] ?: '', $a['date'] ?: '' );
	} );
	return $out;
}

/**
 * Format USD millions for PHP-side display (parallel to JS fmtUsd).
 * E.g. 7300 → "$7.3B", 100 → "$100M".
 */
function gtmlens_fmt_usd_m_php( int $m ): string {
	if ( $m <= 0 )    return '—';
	if ( $m >= 1000 ) return '$' . number_format( $m / 1000, 1 ) . 'B';
	return '$' . number_format( $m ) . 'M';
}

/* REST endpoint: /wp-json/gtmlens/v1/funding-events */
add_action( 'rest_api_init', function () {
	register_rest_route( 'gtmlens/v1', '/funding-events', [
		'methods'  => 'GET',
		'permission_callback' => '__return_true',
		'callback' => function () {
			$resp = new WP_REST_Response( [
				'updated_at' => current_time( 'c' ),
				'count'      => 0,
				'events'     => gtmlens_get_funding_events(),
			] );
			$events = $resp->get_data();
			$events['count'] = count( $events['events'] );
			$resp->set_data( $events );
			$resp->header( 'Cache-Control', 'public, max-age=3600' );
			return $resp;
		},
	] );
} );

/* Schema.org Dataset JSON-LD on /insights/funding-tracker/ */
add_action( 'wp_head', function () {
	if ( ! is_page() ) return;
	if ( get_post_field( 'post_name', get_the_ID() ) !== 'funding-tracker' ) return;

	$events    = gtmlens_get_funding_events();
	$dates     = array_filter( array_column( $events, 'date' ) );
	$last_mod  = $dates ? max( $dates ) : current_time( 'Y-m-d' );

	$dataset = [
		'@context'    => 'https://schema.org',
		'@type'       => 'Dataset',
		'name'        => 'GTMLens Funding Tracker',
		'description' => 'Independent rolling list of funding rounds, M&A, and IPOs across the AI-native GTM stack — vendors we cover plus events outside our directory. Updated continuously.',
		'url'         => home_url( '/funding-tracker/' ),
		'creator'     => [
			'@type' => 'Organization',
			'name'  => 'GTMLens',
			'url'   => home_url( '/' ),
		],
		'license'        => home_url( '/editorial-policy/' ),
		'dateModified'   => $last_mod,
		'keywords'       => 'GTM funding, AI SDR funding, sales tech rounds, RevOps M&A, GTM IPO',
		'distribution'   => [
			[
				'@type'         => 'DataDownload',
				'encodingFormat' => 'application/json',
				'contentUrl'    => home_url( '/wp-json/gtmlens/v1/funding-events' ),
			],
		],
	];
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $dataset, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "</script>\n";
}, 7 );

/* Helper for template: bucket events by quarter */
function gtmlens_bucket_events_by_quarter( array $events ): array {
	$buckets = [];
	foreach ( $events as $e ) {
		if ( empty( $e['date'] ) ) continue;
		$y = (int) substr( $e['date'], 0, 4 );
		$m = (int) substr( $e['date'], 5, 2 );
		$q = (int) ceil( $m / 3 );
		$key = sprintf( 'Q%d %d', $q, $y );
		$buckets[ $key ][] = $e;
	}
	return $buckets;
}

/**
 * Bulk-add vendor profiles from a JSON array.
 * Each object: slug, title (required); category_slug (string or array); plus any
 * vendor ACF fields. Existing vendors (matched by slug) are skipped.
 */
function gtmlens_bulk_add_vendors( string $json ): array {
	$results = [];
	$data    = json_decode( $json, true );
	if ( ! is_array( $data ) ) {
		return [ [ 'json', 'error', 'invalid JSON: ' . json_last_error_msg() ] ];
	}
	$field_keys = [
		'vendor_url', 'hq', 'founded', 'founders',
		'funding_stage', 'last_round_size', 'total_raised', 'last_valuation',
		'last_round_size_usd_m', 'total_raised_usd_m', 'last_valuation_usd_m',
		'last_round_date', 'last_round_source_url',
		'pricing_tier', 'entry_price', 'pricing_page_url',
		'target_segment', 'best_fit', 'worst_fit',
		'swot_strengths', 'swot_weaknesses', 'swot_opportunities', 'swot_threats',
		'analyst_take', 'last_updated', 'reviewer',
	];
	foreach ( $data as $idx => $v ) {
		if ( ! is_array( $v ) ) {
			$results[] = [ "[$idx]", 'error', 'not an object' ];
			continue;
		}
		$slug  = sanitize_title( (string) ( $v['slug'] ?? '' ) );
		$title = trim( (string) ( $v['title'] ?? '' ) );
		if ( ! $slug || ! $title ) {
			$results[] = [ "[$idx]", 'error', 'slug and title required' ];
			continue;
		}
		$existing = get_posts( [
			'post_type'      => 'vendor',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		] );
		if ( $existing ) {
			$results[] = [ $slug, 'skip', 'exists id=' . (int) $existing[0] ];
			continue;
		}
		$post_id = wp_insert_post( [
			'post_type'    => 'vendor',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_content' => (string) ( $v['content'] ?? $v['analyst_take'] ?? '' ),
		], true );
		if ( is_wp_error( $post_id ) ) {
			$results[] = [ $slug, 'error', $post_id->get_error_message() ];
			continue;
		}
		$set = 0;
		foreach ( $field_keys as $k ) {
			if ( isset( $v[ $k ] ) && '' !== $v[ $k ] ) {
				update_field( $k, $v[ $k ], $post_id );
				$set++;
			}
		}
		if ( ! empty( $v['category_slug'] ) ) {
			$cats     = is_array( $v['category_slug'] ) ? $v['category_slug'] : [ $v['category_slug'] ];
			$term_ids = [];
			foreach ( $cats as $cs ) {
				$term = get_term_by( 'slug', sanitize_title( (string) $cs ), 'vendor_category' );
				if ( $term ) $term_ids[] = (int) $term->term_id;
			}
			if ( $term_ids ) wp_set_object_terms( $post_id, $term_ids, 'vendor_category' );
		}
		$results[] = [ $slug, 'ok', "id=$post_id, $set ACF fields set" ];
	}
	return $results;
}

add_action( 'admin_menu', function () {
	add_options_page(
		__( 'Vendor Bulk Add', 'gtmlens-child' ),
		__( 'Vendor Bulk Add', 'gtmlens-child' ),
		'manage_options',
		'gtmlens-vendor-bulk-add',
		'gtmlens_vendor_bulk_add_page'
	);
} );

function gtmlens_vendor_bulk_add_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$action  = isset( $_POST['gtmlens_action'] ) ? sanitize_text_field( $_POST['gtmlens_action'] ) : '';
	$results = null;
	if ( 'add_vendors' === $action && check_admin_referer( 'gtmlens_vendor_bulk_add' ) ) {
		$payload = isset( $_POST['gtmlens_vendor_json'] ) ? wp_unslash( $_POST['gtmlens_vendor_json'] ) : '';
		$results = gtmlens_bulk_add_vendors( (string) $payload );
	}
	?>
	<div class="wrap">
		<h1>Vendor Bulk Add</h1>
		<p>Paste a JSON array of vendor objects. Required per object: <code>slug</code>, <code>title</code>. Optional: <code>category_slug</code> plus any vendor ACF field. Existing slugs are skipped.</p>
		<form method="post">
			<?php wp_nonce_field( 'gtmlens_vendor_bulk_add' ); ?>
			<input type="hidden" name="gtmlens_action" value="add_vendors">
			<textarea name="gtmlens_vendor_json" rows="22" style="width:100%;max-width:1100px;font-family:monospace;font-size:11px;" placeholder='[{"slug":"sierra","title":"Sierra","category_slug":"ai-sdr","vendor_url":"https://sierra.ai","analyst_take":"..."}]'></textarea>
			<p><button type="submit" class="button button-primary">Add vendors</button></p>
		</form>
		<?php if ( $results ) : ?>
			<h2>Results</h2>
			<table class="widefat striped" style="max-width:900px;">
				<thead><tr><th>Slug</th><th>Status</th><th>Message</th></tr></thead>
				<tbody>
				<?php foreach ( $results as $r ) : ?>
					<tr>
						<td><code><?php echo esc_html( (string) $r[0] ); ?></code></td>
						<td><strong><?php echo esc_html( (string) $r[1] ); ?></strong></td>
						<td><?php echo esc_html( (string) $r[2] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
	<?php
}


/* === P11 Phase 6c: Insight post category gradient hero banner === */
add_filter( 'the_content', 'gtmlens_insight_hero_banner', 1 );
function gtmlens_insight_hero_banner( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) return $content;
	static $injected = false;
	if ( $injected ) return $content;
	$injected = true;
	$pid = get_the_ID();
	$cats = get_the_category( $pid );
	$cat_name = $cats ? $cats[0]->name : 'Insight';
	$cat_slug = $cats ? $cats[0]->slug : '';
	$gradient_for_cat = [
		'deep-dive'      => 'gradient-purple',
		'market-map'     => '',
		'battle-card'    => 'gradient-amber',
		'funding'        => 'gradient-emerald',
		'state-of'       => 'gradient-slate',
		'claude-for-gtm' => 'gradient-purple',
	];
	$grad_class = $gradient_for_cat[ $cat_slug ] ?? '';
	$pub_date = get_the_date( 'M j, Y', $pid );
	$author = get_the_author_meta( 'display_name', get_post_field( 'post_author', $pid ) );
	$banner = '<div class="gl-insight-hero ' . esc_attr( $grad_class ) . '">'
		. '<span class="gl-ih-eyebrow">' . esc_html( $cat_name ) . '</span>'
		. '<h1 class="gl-ih-title">' . esc_html( get_the_title( $pid ) ) . '</h1>'
		. '<div class="gl-ih-meta">'
		. ( $author ? esc_html( $author ) . ' · ' : '' )
		. esc_html( $pub_date )
		. '</div>'
		. '</div>';
	return $banner . $content;
}

// Hide default H1 on single post (banner has its own title)
add_action( 'wp_head', 'gtmlens_insight_hide_default_title' );
function gtmlens_insight_hide_default_title() {
	if ( ! is_singular( 'post' ) ) return;
	echo "<style>body.single-post .entry-header .entry-title, body.single-post .single-post-title, body.single-post header.entry-header h1.entry-title { display: none !important; }</style>";
}
/* === /P11 Phase 6c === */


/* === P16: Show category archives on a single page (no pagination) === */
add_action( 'pre_get_posts', 'gtmlens_unpaginate_category_archives' );
function gtmlens_unpaginate_category_archives( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) return;
	if ( $query->is_category() || $query->is_tag() || $query->is_tax() ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'nopaging', true );
	}
}
/* === /P16 === */


/* === P16b: Unpaginate Query Loop blocks on category/tag archives === */
add_filter( 'query_loop_block_query_vars', 'gtmlens_unpaginate_block_query', 10, 2 );
function gtmlens_unpaginate_block_query( $query, $block ) {
	if ( is_category() || is_tag() || is_tax() ) {
		$query['posts_per_page'] = -1;
		$query['nopaging'] = true;
		unset( $query['paged'] );
	}
	return $query;
}
/* === /P16b === */


/* === P17: Auto-generated SVG thumbnails for posts without featured image === */

function gtmlens_auto_thumb_svg( $post_id ) {
    $post = get_post( $post_id );
    if ( ! $post ) return '';
    $title = wp_strip_all_tags( $post->post_title );
    $cats = wp_get_post_terms( $post_id, array( 'category', 'vendor_category' ) );
    $colors = array(
        'market-map'           => array( '#0b1320', '#3b82f6' ),
        'ai-sdr'               => array( '#0b1320', '#06b6d4' ),
        'outbound'             => array( '#1a0b20', '#a855f7' ),
        'revenue-intelligence' => array( '#0b201a', '#10b981' ),
        'enablement'           => array( '#201a0b', '#f59e0b' ),
        'data'                 => array( '#0b1820', '#0ea5e9' ),
        'analysis'             => array( '#1f0b20', '#ec4899' ),
        'editorial'            => array( '#200b0b', '#ef4444' ),
        'default'              => array( '#0b1320', '#3b82f6' ),
    );
    $key = 'default'; $cat_label = 'GTMLENS';
    if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
        foreach ( $cats as $c ) {
            if ( $cat_label === 'GTMLENS' ) $cat_label = strtoupper( $c->name );
            if ( isset( $colors[ $c->slug ] ) ) { $key = $c->slug; break; }
        }
    }
    list( $bg, $accent ) = $colors[ $key ];
    $words = explode( ' ', $title );
    $lines = array( '' ); $maxChar = 26;
    foreach ( $words as $wd ) {
        $idx = count( $lines ) - 1;
        $cand = trim( $lines[ $idx ] . ' ' . $wd );
        if ( strlen( $cand ) <= $maxChar || $lines[ $idx ] === '' ) {
            $lines[ $idx ] = $cand;
        } else {
            if ( count( $lines ) >= 3 ) { $lines[ $idx ] .= '...'; break; }
            $lines[] = $wd;
        }
    }
    $cat_e = htmlspecialchars( $cat_label, ENT_QUOTES, 'UTF-8' );
    $tspans = '';
    $y0 = 190;
    foreach ( $lines as $i => $ln ) {
        $tspans .= '<tspan x="60" y="' . ( $y0 + $i * 58 ) . '">' . htmlspecialchars( $ln, ENT_QUOTES, 'UTF-8' ) . '</tspan>';
    }
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" preserveAspectRatio="xMidYMid slice">'
        . '<defs><linearGradient id="g' . $post_id . '" x1="0" y1="0" x2="1" y2="1">'
        . '<stop offset="0" stop-color="' . $bg . '"/>'
        . '<stop offset="1" stop-color="' . $accent . '" stop-opacity="0.45"/>'
        . '</linearGradient></defs>'
        . '<rect width="800" height="450" fill="url(#g' . $post_id . ')"/>'
        . '<rect x="60" y="60" width="56" height="4" fill="' . $accent . '"/>'
        . '<text x="60" y="98" font-family="Inter,system-ui,sans-serif" font-size="13" font-weight="700" fill="' . $accent . '" letter-spacing="2.5">' . $cat_e . '</text>'
        . '<text font-family="Inter,system-ui,sans-serif" font-size="42" font-weight="700" fill="#ffffff">' . $tspans . '</text>'
        . '<text x="60" y="405" font-family="Inter,system-ui,sans-serif" font-size="14" fill="#94a3b8" letter-spacing="2">GTMLENS . INDEPENDENT ANALYST</text>'
        . '</svg>';
    return $svg;
}

function gtmlens_auto_thumb_data_uri( $post_id ) {
    $svg = gtmlens_auto_thumb_svg( $post_id );
    if ( ! $svg ) return '';
    return 'data:image/svg+xml;base64,' . base64_encode( $svg );
}

add_filter( 'post_thumbnail_html', 'gtmlens_auto_thumb_html_filter', 10, 5 );
function gtmlens_auto_thumb_html_filter( $html, $post_id, $thumb_id, $size, $attr ) {
    if ( $html ) return $html;
    if ( ! $post_id ) return $html;
    if ( get_post_type( $post_id ) !== 'post' ) return $html;
    $uri = gtmlens_auto_thumb_data_uri( $post_id );
    if ( ! $uri ) return $html;
    $title = esc_attr( get_the_title( $post_id ) );
    $size_class = is_array( $size ) ? 'custom' : esc_attr( $size );
    return '<img src="' . esc_attr( $uri ) . '" alt="' . $title . '" class="attachment-' . $size_class . ' size-' . $size_class . ' wp-post-image gtmlens-auto-thumb" loading="lazy" decoding="async" width="800" height="450" />';
}

add_filter( 'has_post_thumbnail', 'gtmlens_auto_thumb_has_filter', 10, 3 );
function gtmlens_auto_thumb_has_filter( $has, $post, $thumb_id ) {
    if ( $has ) return $has;
    $pid = is_object( $post ) ? $post->ID : ( $post ? (int) $post : get_the_ID() );
    if ( ! $pid ) return $has;
    if ( get_post_type( $pid ) !== 'post' ) return $has;
    return true;
}

add_action( 'wp_head', 'gtmlens_auto_thumb_og', 6 );
function gtmlens_auto_thumb_og() {
    if ( ! is_singular( 'post' ) ) return;
    $pid = get_queried_object_id();
    if ( get_post_meta( $pid, '_thumbnail_id', true ) ) return;
    $uri = gtmlens_auto_thumb_data_uri( $pid );
    if ( ! $uri ) return;
    echo '<meta property="og:image" content="' . esc_attr( $uri ) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_attr( $uri ) . '">' . "\n";
}


/* === P18: Insight post reading surface — sticky TOC + inline vendor chips === */

add_filter( 'the_content', 'gtmlens_post_inject_toc_and_chips', 8 );
function gtmlens_post_inject_toc_and_chips( $content ) {
    if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) return $content;
    if ( strpos( $content, 'gl-toc__inner' ) !== false ) return $content;

    // 1. Add IDs to h2/h3 and collect headings
    $headings = array();
    $content = preg_replace_callback(
        '/<(h2|h3)([^>]*)>(.*?)<\/\1>/is',
        function( $m ) use ( &$headings ) {
            $tag = $m[1]; $attrs = $m[2]; $inner = $m[3];
            $text = trim( wp_strip_all_tags( $inner ) );
            if ( ! $text ) return $m[0];
            $slug = sanitize_title( $text );
            if ( ! $slug ) return $m[0];
            $base = $slug; $i = 2;
            $existing = array_column( $headings, 'slug' );
            while ( in_array( $slug, $existing, true ) ) { $slug = $base . '-' . $i; $i++; }
            $headings[] = array( 'tag' => $tag, 'slug' => $slug, 'text' => $text );
            if ( preg_match( '/\sid=/', $attrs ) ) return $m[0];
            return '<' . $tag . ' id="' . esc_attr( $slug ) . '"' . $attrs . '>' . $inner . '</' . $tag . '>';
        },
        $content
    );

    // 2. Vendor chips: link first mention of each vendor in text nodes between tags
    $vendors = get_posts( array(
        'post_type' => 'vendor', 'posts_per_page' => -1,
        'no_found_rows' => true, 'orderby' => 'title', 'order' => 'ASC',
    ) );
    if ( $vendors ) {
        $linked = array();
        // Sort by name length DESC so longer names match first (e.g., "Bland AI" before "Bland")
        usort( $vendors, function( $a, $b ) { return strlen( $b->post_title ) - strlen( $a->post_title ); } );
        foreach ( $vendors as $v ) {
            $name = trim( $v->post_title );
            if ( strlen( $name ) < 3 ) continue;
            if ( isset( $linked[ $name ] ) ) continue;
            $url = get_permalink( $v->ID );
            $pat = '/(>[^<]*?)\b(' . preg_quote( $name, '/' ) . ')\b([^<]*?<)/';
            $count = 0;
            $content = preg_replace_callback( $pat, function( $m ) use ( $url, $name, &$linked ) {
                if ( isset( $linked[ $name ] ) ) return $m[0];
                $linked[ $name ] = true;
                return $m[1] . '<a href="' . esc_url( $url ) . '" class="gl-vendor-chip-inline">' . esc_html( $m[2] ) . '</a>' . $m[3];
            }, $content, 1, $count );
        }
    }

    if ( count( $headings ) < 3 ) return $content;

    // 3. Build TOC HTML
    $toc = '<aside class="gl-toc"><div class="gl-toc__inner"><div class="gl-toc__label">On this page</div><ol class="gl-toc__list">';
    foreach ( $headings as $h ) {
        $cls = 'gl-toc__item gl-toc__item--' . $h['tag'];
        $toc .= '<li class="' . $cls . '"><a href="#' . esc_attr( $h['slug'] ) . '">' . esc_html( $h['text'] ) . '</a></li>';
    }
    $toc .= '</ol></div></aside>';

    return $toc . $content;
}


/* === P24: Permanently block /hello-world/ even if recreated === */
add_action( 'template_redirect', 'gtmlens_p24_block_hello_world', 1 );
function gtmlens_p24_block_hello_world() {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
	if ( strpos( $uri, '/hello-world' ) === 0 ) {
		status_header( 410 );
		nocache_headers();
		wp_redirect( home_url( '/' ), 301 );
		exit;
	}
}


/* === P24: Polished editorial hero for /about/ === */
add_filter( 'the_content', 'gtmlens_p24_about_hero', 5 );
function gtmlens_p24_about_hero( $content ) {
	if ( ! is_page( 'about' ) || ! in_the_loop() || ! is_main_query() ) return $content;
	$hero = '<div class="gl-about-hero">' .
		'<div class="gl-about-eyebrow">INDEPENDENT ANALYST PUBLICATION</div>' .
		'<h1 class="gl-about-title">About GTMLens</h1>' .
		'<p class="gl-about-subtitle">No vendor money. No affiliate links. No paid placements. Just analyst-grade intelligence on the AI-native GTM stack — for revenue operators, founders, and GTM leaders who need an honest read.</p>' .
		'<div class="gl-about-pledge">' .
			'<div class="gl-about-pledge__item"><div class="gl-about-pledge__num">0</div><div class="gl-about-pledge__lbl">paid placements</div></div>' .
			'<div class="gl-about-pledge__item"><div class="gl-about-pledge__num">0</div><div class="gl-about-pledge__lbl">affiliate links</div></div>' .
			'<div class="gl-about-pledge__item"><div class="gl-about-pledge__num">0</div><div class="gl-about-pledge__lbl">vendor sponsorships</div></div>' .
			'<div class="gl-about-pledge__item"><div class="gl-about-pledge__num">53</div><div class="gl-about-pledge__lbl">vendors covered</div></div>' .
		'</div>' .
	'</div>';
	/* Strip the redundant leading 'About GTMLens' h2 + first paragraph from page content */
	$content = preg_replace( '/<h2[^>]*>\s*About GTMLens\s*<\/h2>\s*<p[^>]*>.*?<\/p>/is', '', $content, 1 );
	return $hero . $content;
}

/* Hide Kadence's default centered page hero on the about page */
add_action( 'wp', 'gtmlens_p24_about_hide_header' );
function gtmlens_p24_about_hide_header() {
	if ( ! is_page( 'about' ) ) return;
	add_filter( 'kadence_page_title', '__return_false' );
	add_filter( 'kadence_disable_post_title', '__return_true' );
}


/* === P25: Homepage Just-Landed feed === */
function gtmlens_p25_homepage_events( $limit = 5 ) {
	$events = array();
	/* Funding events (last 90 days) */
	if ( function_exists( 'gtmlens_get_funding_events' ) ) {
		$fe = gtmlens_get_funding_events();
		foreach ( (array) $fe as $f ) {
			$d = isset( $f['date'] ) ? $f['date'] : '';
			if ( ! $d ) continue;
			$ts = strtotime( $d );
			if ( $ts === false ) continue;
			$type = isset( $f['event_type'] ) ? strtolower( $f['event_type'] ) : '';
			$amt = isset( $f['amount_m'] ) ? (float) $f['amount_m'] : 0;
			$stage = isset( $f['stage'] ) ? $f['stage'] : '';
			$co = isset( $f['company'] ) ? $f['company'] : '';
			$url = isset( $f['url'] ) ? $f['url'] : home_url( '/funding-tracker/' );
			$title = '';
			if ( $type === 'ma' || strpos( $type, 'acqu' ) !== false ) {
				$title = $co . ' acquired';
				$ekind = 'round';
			} elseif ( $type === 'ipo' ) {
				$title = $co . ' IPO';
				$ekind = 'round';
			} else {
				$amtStr = $amt >= 1000 ? '$' . number_format( $amt / 1000, 1 ) . 'B' : ( $amt > 0 ? '$' . number_format( $amt ) . 'M' : '' );
				$title = trim( $co . ' raised ' . $amtStr . ' ' . $stage );
				$ekind = 'round';
			}
			$events[] = array( 'kind' => $ekind, 'title' => $title, 'date' => $d, 'ts' => $ts, 'url' => $url, 'meta' => date_i18n( 'M j', $ts ) );
		}
	}
	/* Recent insights (post type 'post') */
	$posts = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 4, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC' ) );
	foreach ( $posts as $p ) {
		$ts = strtotime( $p->post_date_gmt );
		$events[] = array( 'kind' => 'insight', 'title' => 'New: ' . $p->post_title, 'date' => $p->post_date, 'ts' => $ts, 'url' => get_permalink( $p->ID ), 'meta' => human_time_diff( $ts, current_time( 'timestamp' ) ) . ' ago' );
	}
	/* Recent comparisons */
	$comps = get_posts( array( 'post_type' => 'comparison', 'posts_per_page' => 3, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC' ) );
	foreach ( $comps as $cp ) {
		$ts = strtotime( $cp->post_date_gmt );
		$events[] = array( 'kind' => 'compare', 'title' => 'Battle card: ' . $cp->post_title, 'date' => $cp->post_date, 'ts' => $ts, 'url' => get_permalink( $cp->ID ), 'meta' => human_time_diff( $ts, current_time( 'timestamp' ) ) . ' ago' );
	}
	usort( $events, function( $a, $b ) { return $b['ts'] - $a['ts']; } );
	return array_slice( $events, 0, $limit );
}

/* === P25: Live-now stats for the market map === */
function gtmlens_p25_live_now_stats() {
	$now = current_time( 'timestamp' );
	$d30 = $now - 30 * DAY_IN_SECONDS;
	$d60 = $now - 60 * DAY_IN_SECONDS;
	$d90 = $now - 90 * DAY_IN_SECONDS;
	/* Count vendors with a round in last 30d (via ACF on vendor CPT) */
	$active = 0;
	$vendors = get_posts( array( 'post_type' => 'vendor', 'posts_per_page' => -1, 'post_status' => 'publish', 'fields' => 'ids' ) );
	foreach ( (array) $vendors as $vid ) {
		$lrd = (string) get_field( 'last_round_date', $vid );
		if ( $lrd && strtotime( $lrd ) >= $d30 ) $active++;
	}
	$cap_30 = 0; $cap_30_60 = 0; $events_q = 0;
	if ( function_exists( 'gtmlens_get_funding_events' ) ) {
		$fe = gtmlens_get_funding_events();
		$qy = (int) date( 'Y', $now ); $qq = (int) ceil( (int) date( 'n', $now ) / 3 );
		$q_start = mktime( 0, 0, 0, ( $qq - 1 ) * 3 + 1, 1, $qy );
		foreach ( (array) $fe as $f ) {
			$t = isset( $f['event_type'] ) ? strtolower( $f['event_type'] ) : '';
			if ( $t !== 'round' && $t !== 'funding' ) continue;
			$ts = strtotime( isset( $f['date'] ) ? $f['date'] : '' );
			if ( ! $ts ) continue;
			$amt = isset( $f['amount_m'] ) ? (float) $f['amount_m'] : 0;
			if ( $ts >= $d30 ) $cap_30 += $amt;
			elseif ( $ts >= $d60 ) $cap_30_60 += $amt;
			if ( $ts >= $q_start ) $events_q++;
		}
	}
	$pace = $cap_30_60 > 0 ? round( ( ( $cap_30 - $cap_30_60 ) / $cap_30_60 ) * 100 ) : null;
	return array( 'active' => $active, 'cap_30' => $cap_30, 'pace' => $pace, 'events_q' => $events_q );
}


/* P29: consolidate Organization schema — align Rank Math org name + logo, drop duplicate */
if ( ! function_exists( 'gtmlens_align_org_schema' ) ) {
	function gtmlens_align_org_schema( $data, $jsonld ) {
		if ( ! is_array( $data ) ) { return $data; }
		$seen_org = false;
		foreach ( $data as $k => $v ) {
			$type = isset( $v['@type'] ) ? $v['@type'] : '';
			$is_org = ( $type === 'Organization' ) || ( is_array( $type ) && in_array( 'Organization', $type, true ) );
			if ( $is_org ) {
				if ( $seen_org ) { unset( $data[ $k ] ); continue; }
				$seen_org = true;
				$data[ $k ]['name'] = 'GTMLens';
				$data[ $k ]['logo'] = [ '@type' => 'ImageObject', 'url' => get_stylesheet_directory_uri() . '/assets/images/og-image.png' ];
			}
		}
		return $data;
	}
	add_filter( 'rank_math/json_ld', 'gtmlens_align_org_schema', 99, 2 );
}


/* P29: noindex thin single funding_event pages (aggregated in the funding tracker) */
if ( ! function_exists( 'gtmlens_noindex_funding_event' ) ) {
	function gtmlens_noindex_funding_event( $robots ) {
		if ( is_singular( 'funding_event' ) ) { $robots['index'] = 'noindex'; $robots['follow'] = 'follow'; }
		return $robots;
	}
	add_filter( 'rank_math/frontend/robots', 'gtmlens_noindex_funding_event' );
	add_filter( 'wp_robots', function( $r ) { if ( is_singular( 'funding_event' ) ) { $r['noindex'] = true; unset( $r['index'] ); } return $r; } );
}


// =====================================================================
// GSC 404 FIXES — vendor slug aliases + category redirects (2026-06)
// =====================================================================
add_action( 'template_redirect', 'gtmlens_301_redirects' );
function gtmlens_301_redirects() {
	$path = untrailingslashit( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
	$map = [
		'/vendors/anthropic'                       => '/vendors/claude-anthropic/',
		'/vendors/gpt-openai'                      => '/vendors/openai-gpt/',
		'/vendors/clari'                           => '/vendors/',
		'/vendors/glockapps'                       => '/vendors/',
		'/vendors/people-data-labs'                => '/vendors/',
		'/category/best-practice'                  => '/insights/',
		'/category/claude-for-gtm'                 => '/vendors/claude-anthropic/',
		'/category/playbook'                       => '/gtm-engineering/',
		'/compare/clari-vs-custom-ai-forecasting'   => '/compare/',
		'/compare/hubspot-vs-attio'                => '/compare/',
		'/stack-builder/quiz'                      => '/stack-finder/',
	];
	if ( isset( $map[ $path ] ) ) {
		wp_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
}
