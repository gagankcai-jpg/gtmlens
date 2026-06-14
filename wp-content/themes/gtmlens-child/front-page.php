<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// ── Live stat counts ────────────────────────────────────────────────────────
$vendor_counts     = wp_count_posts( 'vendor' );
$vendor_total      = isset( $vendor_counts->publish ) ? (int) $vendor_counts->publish : 0;

$comparison_counts = wp_count_posts( 'comparison' );
$comparison_total  = isset( $comparison_counts->publish ) ? (int) $comparison_counts->publish : 0;

$stack_counts      = wp_count_posts( 'stack' );
$stack_total       = isset( $stack_counts->publish ) ? (int) $stack_counts->publish : 0;

$insight_counts    = wp_count_posts( 'post' );
$insight_total     = isset( $insight_counts->publish ) ? (int) $insight_counts->publish : 0;

// ── Market map post ─────────────────────────────────────────────────────────
$market_map_posts = get_posts( [
	'post_type'      => 'post',
	'name'           => 'ai-gtm-market-map-q2-2026',
	'posts_per_page' => 1,
	'post_status'    => 'publish',
] );
$market_map = $market_map_posts ? $market_map_posts[0] : null;

// ── Featured report post ─────────────────────────────────────────────────────
$flagship_posts = get_posts( [
	'post_type'      => 'post',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'posts_per_page' => 1,
	'post_status'    => 'publish',
] );
$flagship = $flagship_posts ? $flagship_posts[0] : null;

// ── Market map SVG URL ───────────────────────────────────────────────────────
$map_img_url = '';
if ( $market_map ) {
	$map_img_id  = get_post_thumbnail_id( $market_map->ID );
	$map_img_url = $map_img_id
		? wp_get_attachment_image_url( $map_img_id, 'full' )
		: home_url( '/wp-content/uploads/2026/04/ai-gtm-market-map-q2-2026.svg' );
} else {
	$map_img_url = home_url( '/wp-content/uploads/2026/04/ai-gtm-market-map-q2-2026.svg' );
}

// ── Latest 3 insights ───────────────────────────────────────────────────────
$latest_insights = get_posts( [
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
] );

// ── Latest 3 comparisons ────────────────────────────────────────────────────
$latest_comparisons = get_posts( [
	'post_type'      => 'comparison',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
] );

// ── Vendor categories (ordered) ──────────────────────────────────────────────
$cat_order = [
	'ai-sdr',
	'outbound',
	'data-enrichment',
	'crm',
	'intent-signal',
	'linkedin-automation',
	'orchestration',
	'revenue-intelligence',
	'lead-capture',
	'foundation-models',
];

$cat_icons = [
	'ai-sdr'              => '🤖',
	'outbound'            => '📤',
	'data-enrichment'     => '🔍',
	'data-activation'     => '⇄',
	'crm'                 => '🗂️',
	'intent-signal'       => '📡',
	'linkedin-automation' => '🔗',
	'orchestration'       => '⚙️',
	'revenue-intelligence'=> '📊',
	'lead-capture'        => '🎯',
	'foundation-models'   => '🧠',
];

$all_vendor_cats = get_terms( [
	'taxonomy'   => 'vendor_category',
	'hide_empty' => true,
	'number'     => 0,
] );

$ordered_cats = [];
$gl_primary_counts = [];
foreach ( get_posts( [ 'post_type' => 'vendor', 'posts_per_page' => -1, 'post_status' => 'publish', 'fields' => 'ids' ] ) as $gl_pc_vid ) {
	$gl_pc_terms = wp_get_post_terms( $gl_pc_vid, 'vendor_category' );
	if ( ! empty( $gl_pc_terms ) && ! is_wp_error( $gl_pc_terms ) ) {
		$gl_pc_slug = $gl_pc_terms[0]->slug;
		$gl_primary_counts[ $gl_pc_slug ] = ( $gl_primary_counts[ $gl_pc_slug ] ?? 0 ) + 1;
	}
}
if ( ! is_wp_error( $all_vendor_cats ) && $all_vendor_cats ) {
	$cats_by_slug = [];
	foreach ( $all_vendor_cats as $cat ) {
		$cats_by_slug[ $cat->slug ] = $cat;
	}
	foreach ( $cat_order as $slug ) {
		if ( isset( $cats_by_slug[ $slug ] ) ) {
			$ordered_cats[] = $cats_by_slug[ $slug ];
			unset( $cats_by_slug[ $slug ] );
		}
	}
	foreach ( $cats_by_slug as $leftover ) {
		$ordered_cats[] = $leftover;
	}
}

// ── Stack tier chips ─────────────────────────────────────────────────────────
$stack_tiers = [
	'pre-seed'   => [ 'label' => 'Pre-Seed', 'sub' => 'under $500/mo' ],
	'seed'       => [ 'label' => 'Seed',     'sub' => '$500–$2k/mo'  ],
	'series-a'   => [ 'label' => 'Series A', 'sub' => '$2k–$10k/mo' ],
	'enterprise' => [ 'label' => 'Enterprise','sub' => '$10k+/mo'    ],
];
?>

<!-- ══════════════════════════════════════════════════════════════════════════
     1. HERO — light surface background, left-aligned, tight information density
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="glhp-hero" aria-label="<?php esc_attr_e( 'Site introduction', 'gtmlens-child' ); ?>">
	<p class="glhp-hero__eyebrow"><?php esc_html_e( 'INDEPENDENT ANALYST PUBLICATION', 'gtmlens-child' ); ?></p>
	<h1 class="glhp-hero__h1">
		<?php esc_html_e( 'The independent analyst\'s view of the AI-native GTM stack.', 'gtmlens-child' ); ?>
	</h1>
	<p class="glhp-hero__sub">
		<?php esc_html_e( 'No vendor money. No affiliate links. No paid placements. Just analyst-grade intelligence for GTM engineers and revenue leaders.', 'gtmlens-child' ); ?>
	</p>

	<!-- Stat strip — horizontal flex row -->
	<?php
	$gl_last_mod_ts = strtotime( get_lastpostmodified( 'gmt' ) );
	$gl_now_ts_h = current_time( 'timestamp' );
	$gl_days_since = $gl_last_mod_ts > 0 ? floor( ( $gl_now_ts_h - $gl_last_mod_ts ) / 86400 ) : 99;
	$gl_days_since = (int) $gl_days_since;
	$gl_updated_lbl = $gl_days_since <= 0 ? 'today' : ( $gl_days_since === 1 ? 'yesterday' : ( $gl_days_since < 7 ? $gl_days_since . ' days ago' : 'this week' ) );
	$gl_p25_cap = 0;
	if ( function_exists( 'gtmlens_get_funding_events' ) ) {
		$gl_p25_fe = gtmlens_get_funding_events();
		$gl_p25_30 = $gl_now_ts_h - 90 * DAY_IN_SECONDS;
		foreach ( (array) $gl_p25_fe as $f ) {
			$t = isset( $f['event_type'] ) ? strtolower( $f['event_type'] ) : '';
			if ( $t !== 'round' && $t !== 'funding' ) continue;
			if ( isset( $f['category'] ) && 'Foundation Models' === $f['category'] ) continue;
			$ts = strtotime( isset( $f['date'] ) ? $f['date'] : '' );
			if ( $ts && $ts >= $gl_p25_30 ) $gl_p25_cap += (float) ( isset( $f['amount_m'] ) ? $f['amount_m'] : 0 );
		}
	}
	$gl_p25_cap_lbl = $gl_p25_cap >= 1000 ? '$' . number_format( $gl_p25_cap / 1000, 1 ) . 'B' : ( $gl_p25_cap > 0 ? '$' . number_format( $gl_p25_cap ) . 'M' : '$0' );
	?>
	<div class="gl-hero-meta" role="list">
		<span class="gl-hero-meta__item"><strong><?php echo esc_html( $vendor_total ?: '53' ); ?></strong> vendors</span>
		<span class="gl-hero-meta__sep">&middot;</span>
		<span class="gl-hero-meta__item"><strong><?php echo esc_html( $insight_total ?: '28' ); ?></strong> insights</span>
		<span class="gl-hero-meta__sep">&middot;</span>
		<span class="gl-hero-meta__item"><strong><?php echo esc_html( $comparison_total ?: '23' ); ?></strong> comparisons</span>
		<span class="gl-hero-meta__sep">&middot;</span>
		<span class="gl-hero-meta__live"><strong><?php echo esc_html( $gl_p25_cap_lbl ); ?></strong> in GTM rounds &middot; last 90d</span>
		<span class="gl-hero-meta__sep">&middot;</span>
		<span class="gl-hero-meta__item">Updated <strong><?php echo esc_html( $gl_updated_lbl ); ?></strong></span>
	</div>
	<div class="glhp-hero__cta" style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
		<a href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>" style="display:inline-block;padding:11px 22px;border-radius:8px;background:#0d1f3c;color:#fff;font-weight:600;font-size:.9rem;text-decoration:none;">Browse all vendors &rarr;</a>
		<a href="<?php echo esc_url( home_url( '/funding-tracker/' ) ); ?>" style="display:inline-block;padding:11px 22px;border-radius:8px;border:1px solid #d1d9e6;color:#0d1f3c;font-weight:600;font-size:.9rem;text-decoration:none;">Funding tracker</a>
	</div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     1.5 INTERACTIVE MARKET MAP — full vendor landscape, filterable
     ══════════════════════════════════════════════════════════════════════════ -->
<?php echo do_shortcode( '[market_map]' ); ?>

<!-- P12: This week in GTM (Pulse strip) -->
<?php
$pulse_events = function_exists( 'gtmlens_get_funding_events' )
	? gtmlens_get_funding_events( [ 'exclude_public' => true ] )
	: [];
$pulse_events = array_slice(
	array_values( array_filter( $pulse_events, function ( $e ) { return ! empty( $e['date'] ); } ) ),
	0, 4
);
if ( $pulse_events ) :
	$mo_names = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	?>
	<section class="glhp-boxed gl-pulse-strip" aria-label="<?php esc_attr_e( 'This week in GTM', 'gtmlens-child' ); ?>">
		<div class="gl-pulse-strip__head">
			<span class="gl-pulse-strip__dot" aria-hidden="true"></span>
			<span class="gl-pulse-strip__label"><?php esc_html_e( 'This week in GTM', 'gtmlens-child' ); ?></span>
			<a class="gl-pulse-strip__more" href="<?php echo esc_url( home_url( '/funding-tracker/' ) ); ?>"><?php esc_html_e( 'See all rounds →', 'gtmlens-child' ); ?></a>
		</div>
		<div class="gl-pulse-strip__items">
			<?php foreach ( $pulse_events as $ev ) :
				$nm   = $ev['company'] ?? '';
				$stg  = $ev['stage'] ?? '';
				$amt  = ! empty( $ev['amount_disp'] ) ? $ev['amount_disp'] : ( ! empty( $ev['amount_m'] ) ? '$' . number_format( $ev['amount_m'] ) . 'M' : '' );
				$dt   = $ev['date'] ?? '';
				$dlab = '';
				if ( $dt && preg_match( '/^(\d{4})-(\d{2})/', $dt, $rd ) ) {
					$dlab = $mo_names[ (int) $rd[2] ] . ' ' . $rd[1];
				}
				$etype = $ev['event_type'] ?? 'round';
				$href  = ! empty( $ev['slug'] ) ? home_url( '/vendors/' . $ev['slug'] . '/' ) : ( $ev['url'] ?? '#' );
				$logo  = $ev['logo'] ?? '';
				?>
				<a class="gl-pulse-item gl-pulse-item--<?php echo esc_attr( $etype ); ?>" href="<?php echo esc_url( $href ); ?>">
					<span class="gl-pulse-item__logo">
						<?php if ( $logo ) : ?>
							<img src="<?php echo esc_url( $logo ); ?>" alt="" loading="lazy" referrerpolicy="no-referrer" />
						<?php else : ?>
							<span class="gl-pulse-item__initial"><?php echo esc_html( mb_substr( $nm, 0, 1 ) ); ?></span>
						<?php endif; ?>
					</span>
					<span class="gl-pulse-item__body">
						<span class="gl-pulse-item__name"><?php echo esc_html( $nm ); ?></span>
						<span class="gl-pulse-item__meta">
							<?php if ( 'ma' === $etype ) : ?>
								<span class="gl-pulse-item__badge">M&amp;A</span>
							<?php elseif ( 'ipo' === $etype ) : ?>
								<span class="gl-pulse-item__badge">IPO</span>
							<?php elseif ( $stg ) : ?>
								<span class="gl-pulse-item__stage"><?php echo esc_html( $stg ); ?></span>
							<?php endif; ?>
							<?php if ( $amt ) : ?>· <strong><?php echo esc_html( $amt ); ?></strong><?php endif; ?>
							<?php if ( $dlab ) : ?>· <?php echo esc_html( $dlab ); ?><?php endif; ?>
						</span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
endif;
?>

<!-- ══════════════════════════════════════════════════════════════════════════
     2. FEATURED REPORT — dark navy full-bleed, 2-col grid
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="gl-fullbleed gl-fullbleed--dark glhp-report" aria-label="<?php esc_attr_e( 'Featured report', 'gtmlens-child' ); ?>">
	<div class="gl-fullbleed__inner glhp-report__grid">

		<!-- LEFT: text -->
		<div class="glhp-report__text">
			<p class="glhp-report__eyebrow"><?php esc_html_e( 'Q2 2026 FLAGSHIP REPORT', 'gtmlens-child' ); ?></p>

			<!-- P12: Funding-volume sparkline (last 8 quarters) -->
			<?php
			$spark_events = function_exists( 'gtmlens_get_funding_events' )
				? gtmlens_get_funding_events( [ 'exclude_public' => true ] )
				: [];
			$spark_counts = [];
			foreach ( $spark_events as $ev ) {
				if ( empty( $ev['date'] ) || ! preg_match( '/^(\d{4})-(\d{2})/', $ev['date'], $rd ) ) continue;
				$y = (int) $rd[1]; $m = (int) $rd[2]; $q = (int) ceil( $m / 3 );
				$k = sprintf( '%04d-%d', $y, $q );
				$spark_counts[ $k ] = ( $spark_counts[ $k ] ?? 0 ) + 1;
			}
			$spark_data = [];
			if ( $spark_counts ) {
				$ny = (int) date( 'Y' );
				$nq = (int) ceil( ( (int) date( 'n' ) ) / 3 );
				for ( $i = 7; $i >= 0; $i-- ) {
					$yy = $ny; $qq = $nq - $i;
					while ( $qq < 1 ) { $qq += 4; $yy--; }
					$k = sprintf( '%04d-%d', $yy, $qq );
					$spark_data[] = [ 'q' => "Q{$qq} {$yy}", 'n' => $spark_counts[ $k ] ?? 0 ];
				}
			}
			if ( $spark_data ) :
				$max_n   = max( 1, max( array_column( $spark_data, 'n' ) ) );
				$total_n = array_sum( array_column( $spark_data, 'n' ) );
				$w = 220; $h = 56; $pad = 4;
				$bar_w = ( $w - $pad * 2 ) / count( $spark_data );
				?>
				<div class="gl-spark-mini" aria-label="Funding deals per quarter, last 8 quarters">
					<svg width="<?php echo (int) $w; ?>" height="<?php echo (int) $h; ?>" viewBox="0 0 <?php echo (int) $w; ?> <?php echo (int) $h; ?>" role="img" aria-hidden="true">
						<?php foreach ( $spark_data as $i => $pt ) :
							$bh = (int) round( ( $pt['n'] / $max_n ) * ( $h - $pad * 2 - 4 ) );
							$bx = (int) round( $pad + $i * $bar_w );
							$by = $h - $pad - $bh;
							$bw = (int) round( $bar_w - 3 );
							$is_last = ( $i === count( $spark_data ) - 1 );
							$fill = $is_last ? '#1d4ed8' : '#a8b3c7';
							?>
							<rect x="<?php echo $bx; ?>" y="<?php echo $by; ?>" width="<?php echo max(2,$bw); ?>" height="<?php echo max(2,$bh); ?>" rx="2" fill="<?php echo $fill; ?>"><title><?php echo esc_html( $pt['q'] . ': ' . $pt['n'] . ' rounds' ); ?></title></rect>
						<?php endforeach; ?>
					</svg>
					<span class="gl-spark-mini__label">
						<strong><?php echo (int) $total_n; ?></strong> rounds tracked · last 8 quarters
					</span>
				</div>
			<?php endif; ?>

			<h2 class="glhp-report__h2">
				<?php
				if ( $flagship ) {
					echo esc_html( get_the_title( $flagship->ID ) );
				} else {
					esc_html_e( 'State of AI GTM Q2 2026: The Year the Harness Cracked', 'gtmlens-child' );
				}
				?>
			</h2>
			<p class="glhp-report__sub">
				<?php esc_html_e( '7,000-word category report. 5 structural shifts. 6 predictions for Q3.', 'gtmlens-child' ); ?>
			</p>
			<a class="gl-btn-primary" href="<?php echo esc_url( $flagship ? get_permalink( $flagship->ID ) : home_url( '/state-of-ai-gtm-q2-2026/' ) ); ?>">
				<?php esc_html_e( 'Read the report →', 'gtmlens-child' ); ?>
			</a>
		</div>

		<!-- RIGHT: market map SVG thumbnail -->
		<div class="glhp-report__visual">
			<a href="<?php echo esc_url( $market_map ? get_permalink( $market_map->ID ) : home_url( '/ai-gtm-market-map-q2-2026/' ) ); ?>" class="glhp-report__map-link" aria-label="<?php esc_attr_e( 'View AI GTM Market Map Q2 2026', 'gtmlens-child' ); ?>">
				<img
					src="<?php echo esc_url( $map_img_url ); ?>"
					alt="<?php esc_attr_e( 'AI GTM Market Map Q2 2026', 'gtmlens-child' ); ?>"
					class="glhp-report__map-img"
					loading="eager"
				>
			</a>
		</div>

	</div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     3. STACK BUILDER CTA — full-bleed amber-tinted surface, centered
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="gl-fullbleed glhp-stack-cta" aria-label="<?php esc_attr_e( 'Stack builder', 'gtmlens-child' ); ?>">
	<div class="gl-fullbleed__inner glhp-stack-cta__inner">
		<p class="glhp-stack-cta__eyebrow"><?php esc_html_e( 'INTERACTIVE TOOL', 'gtmlens-child' ); ?></p>
		<h2 class="glhp-stack-cta__h2">
			<?php esc_html_e( 'Build your GTM stack in 5 minutes.', 'gtmlens-child' ); ?>
		</h2>
		<p class="glhp-stack-cta__sub">
			<?php esc_html_e( 'Tell us your stage, ICP, and budget. We return a personalized stack with vendors, monthly cost, and migration path.', 'gtmlens-child' ); ?>
		</p>
		<div class="glhp-stack-cta__tiers">
			<?php foreach ( $stack_tiers as $slug => $tier ) : ?>
				<a class="glhp-stack-cta__chip" href="<?php echo esc_url( home_url( '/stack-builder/' . $slug . '/' ) ); ?>">
					<span class="glhp-stack-cta__chip-label"><?php echo esc_html( $tier['label'] ); ?></span>
					<span class="glhp-stack-cta__chip-sub"><?php echo esc_html( $tier['sub'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
		<a class="gl-btn-primary" href="<?php echo esc_url( home_url( '/stack-finder/' ) ); ?>">
			<?php esc_html_e( 'Start the quiz →', 'gtmlens-child' ); ?>
		</a>
	</div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     4. LATEST INSIGHTS — light surface, 3-column card grid
     ══════════════════════════════════════════════════════════════════════════ -->
<?php if ( $latest_insights ) : ?>
<section class="glhp-boxed" aria-label="<?php esc_attr_e( 'Latest insights', 'gtmlens-child' ); ?>">
	<div class="glhp-section-header">
		<h2 class="glhp-section-header__title"><?php esc_html_e( 'Latest Insights', 'gtmlens-child' ); ?></h2>
		<a class="glhp-section-header__link" href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">
			<?php esc_html_e( 'View all →', 'gtmlens-child' ); ?>
		</a>
	</div>
	<?php
	$gradient_for_cat = [
		'deep-dive'      => 'gradient-purple',
		'market-map'     => 'gradient-blue',
		'battle-card'    => 'gradient-amber',
		'funding'        => 'gradient-emerald',
		'state-of'       => 'gradient-slate',
		'claude-for-gtm' => 'gradient-purple',
	];
	?>
	<div class="gl-card-row">
		<?php foreach ( $latest_insights as $i => $insight ) :
			$cats     = get_the_category( $insight->ID );
			$cat_name = $cats ? $cats[0]->name : 'Insight';
			$cat_slug = $cats ? $cats[0]->slug : '';
			$grad     = $gradient_for_cat[ $cat_slug ] ?? ( ['gradient-blue','gradient-purple','gradient-amber','gradient-emerald','gradient-slate'][ $i % 5 ] );
			$pub_date = get_the_date( 'M j, Y', $insight->ID );
			$has_thumb= has_post_thumbnail( $insight->ID );
			$thumb_url= $has_thumb ? get_the_post_thumbnail_url( $insight->ID, 'medium_large' ) : '';
		?>
			<a class="gl-card" href="<?php echo esc_url( get_permalink( $insight->ID ) ); ?>">
				<div class="gl-card-cover <?php echo esc_attr( $grad ); ?>"<?php if ( $has_thumb ) : ?> style="background-image:linear-gradient(180deg, rgba(13,31,60,.30), rgba(13,31,60,.65)), url('<?php echo esc_url( $thumb_url ); ?>'); background-size:cover; background-position:center;"<?php endif; ?>>
					<span class="gl-cover-cat"><?php echo esc_html( $cat_name ); ?></span>
					<div class="gl-cover-title"><?php echo esc_html( get_the_title( $insight->ID ) ); ?></div>
				</div>
				<div class="gl-card-body">
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $insight->ID ), 22 ) ); ?></p>
					<div class="gl-card-foot">
						<?php if ( $pub_date ) : ?><span><?php echo esc_html( $pub_date ); ?></span><?php endif; ?>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════════════════════
     5. LATEST COMPARISONS — white bg cards, 3-column rail
     ══════════════════════════════════════════════════════════════════════════ -->
<?php if ( $latest_comparisons ) : ?>
<section class="glhp-boxed glhp-boxed--white" aria-label="<?php esc_attr_e( 'Latest comparisons', 'gtmlens-child' ); ?>">
	<div class="glhp-section-header">
		<h2 class="glhp-section-header__title"><?php esc_html_e( 'Latest Comparisons', 'gtmlens-child' ); ?></h2>
		<a class="glhp-section-header__link" href="<?php echo esc_url( get_post_type_archive_link( 'comparison' ) ?: home_url( '/comparisons/' ) ); ?>">
			<?php esc_html_e( 'View all →', 'gtmlens-child' ); ?>
		</a>
	</div>
	<div class="glhp-comp-grid">
		<?php foreach ( $latest_comparisons as $comp ) :
			$comp_url    = get_permalink( $comp->ID );
			$verdict_raw = get_post_meta( $comp->ID, 'verdict', true );
			$verdict     = $verdict_raw ? wp_trim_words( $verdict_raw, 22 ) : '';
			// Try ACF vendor_a / vendor_b post objects first; fallback to title parsing
			$va_obj = get_field( 'vendor_a', $comp->ID );
			$vb_obj = get_field( 'vendor_b', $comp->ID );
			if ( $va_obj && $vb_obj ) {
				$va_name = get_the_title( $va_obj->ID );
				$vb_name = get_the_title( $vb_obj->ID );
				$va_logo = get_field( 'logo', $va_obj->ID );
				$vb_logo = get_field( 'logo', $vb_obj->ID );
				$va_logo_url = ( $va_logo && ! empty( $va_logo['url'] ) ) ? $va_logo['url'] : '';
				$vb_logo_url = ( $vb_logo && ! empty( $vb_logo['url'] ) ) ? $vb_logo['url'] : '';
			} else {
				$comp_title  = get_the_title( $comp->ID );
				$parts = preg_split( '/\\s+vs\\.?\\s+/i', $comp_title, 2 );
				$va_name = isset( $parts[0] ) ? trim( $parts[0] ) : $comp_title;
				$vb_name = isset( $parts[1] ) ? trim( $parts[1] ) : '';
				$va_logo_url = $vb_logo_url = '';
			}
		?>
			<article class="glhp-comp-card">
				<div class="glhp-comp-card__vs-row">
					<span class="glhp-comp-card__vendor">
						<?php if ( $va_logo_url ) : ?><img src="<?php echo esc_url( $va_logo_url ); ?>" alt="" style="width:18px;height:18px;border-radius:4px;background:#f6f7fb;object-fit:contain;vertical-align:middle;margin-right:.35rem;"/><?php endif; ?>
						<?php echo esc_html( $va_name ); ?>
					</span>
					<?php if ( $vb_name ) : ?>
						<span class="glhp-comp-card__vs">vs</span>
						<span class="glhp-comp-card__vendor">
							<?php if ( $vb_logo_url ) : ?><img src="<?php echo esc_url( $vb_logo_url ); ?>" alt="" style="width:18px;height:18px;border-radius:4px;background:#f6f7fb;object-fit:contain;vertical-align:middle;margin-right:.35rem;"/><?php endif; ?>
							<?php echo esc_html( $vb_name ); ?>
						</span>
					<?php endif; ?>
				</div>
				<div class="glhp-comp-card__accent-line" aria-hidden="true"></div>
				<?php if ( $verdict ) : ?>
					<p class="glhp-comp-card__verdict"><?php echo esc_html( $verdict ); ?></p>
				<?php endif; ?>
				<?php $gl_clu = get_post_meta( $comp->ID, 'last_updated', true ); $gl_cdt = ( $gl_clu && preg_match( '/^\\d{8}$/', $gl_clu ) ) ? date_i18n( 'M j, Y', strtotime( substr( $gl_clu, 0, 4 ) . '-' . substr( $gl_clu, 4, 2 ) . '-' . substr( $gl_clu, 6, 2 ) ) ) : get_the_date( 'M j, Y', $comp->ID ); ?><span class="glhp-comp-card__date" style="display:block;font-size:.72rem;color:#6b7280;margin:0 0 .5rem;">Updated <?php echo esc_html( $gl_cdt ); ?></span>
				<a class="glhp-comp-card__cta" href="<?php echo esc_url( $comp_url ); ?>">
					<?php esc_html_e( 'See the verdict →', 'gtmlens-child' ); ?>
				</a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════════════════════
     6. VENDOR CATEGORIES — 5-column grid, hover navy fill
     ══════════════════════════════════════════════════════════════════════════ -->
<?php if ( $ordered_cats ) : ?>
<section class="glhp-boxed" aria-label="<?php esc_attr_e( 'Vendor categories', 'gtmlens-child' ); ?>">
	<div class="glhp-section-header">
		<h2 class="glhp-section-header__title"><?php esc_html_e( 'Vendor Categories', 'gtmlens-child' ); ?></h2>
		<a class="glhp-section-header__link" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>">
			<?php esc_html_e( 'Full directory →', 'gtmlens-child' ); ?>
		</a>
	</div>
	<div class="glhp-cat-grid gl-cat-tile-v2">
			<?php
			// Category-keyed gradient
			$cat_grad = [
				'ai-sdr'               => 'linear-gradient(135deg,#4c1d95 0%,#6d28d9 60%,#ec4899 100%)',
				'outbound'             => 'linear-gradient(135deg,#78350f 0%,#b45309 60%,#fbbf24 100%)',
				'data-enrichment'      => 'linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 60%,#06b6d4 100%)',
				'data-activation'      => 'linear-gradient(135deg,#0ea5e9 0%,#06b6d4 60%,#22d3ee 100%)',
				'crm'                  => 'linear-gradient(135deg,#0f172a 0%,#334155 60%,#64748b 100%)',
				'intent-signal'        => 'linear-gradient(135deg,#064e3b 0%,#047857 60%,#34d399 100%)',
				'linkedin-automation'  => 'linear-gradient(135deg,#1e293b 0%,#2e6faa 60%,#60a5fa 100%)',
				'orchestration'        => 'linear-gradient(135deg,#374151 0%,#6b7c99 60%,#94a3b8 100%)',
				'revenue-intelligence' => 'linear-gradient(135deg,#7f1d1d 0%,#a3291c 60%,#f87171 100%)',
				'lead-capture'         => 'linear-gradient(135deg,#92400e 0%,#d4a24a 60%,#fde68a 100%)',
				'foundation-models'    => 'linear-gradient(135deg,#14532d 0%,#7bc47f 60%,#bbf7d0 100%)',
			];
			?>
			<?php foreach ( array_slice( $ordered_cats, 0, 12 ) as $cat ) :
				$cat_link  = get_term_link( $cat );
				$cat_count = isset( $gl_primary_counts[ $cat->slug ] ) ? (int) $gl_primary_counts[ $cat->slug ] : (int) $cat->count;
				$cat_icon  = $cat_icons[ $cat->slug ] ?? '📂';
				$grad      = $cat_grad[ $cat->slug ] ?? 'linear-gradient(135deg,#0d1f3c 0%,#475569 60%,#94a3b8 100%)';
				if ( is_wp_error( $cat_link ) ) continue;
				// Top 3 vendors in this category by total raised
				$cat_vendors = get_posts( [
					'post_type' => 'vendor', 'posts_per_page' => 3, 'post_status' => 'publish',
					'tax_query' => [ [ 'taxonomy' => 'vendor_category', 'field' => 'term_id', 'terms' => $cat->term_id ] ],
					'orderby' => 'date', 'order' => 'DESC',
				] );
				?>
				<a class="glhp-cat-tile" href="<?php echo esc_url( $cat_link ); ?>" style="--cat-grad:<?php echo esc_attr( $grad ); ?>;">
					<span class="glhp-cat-tile__top" aria-hidden="true"></span>
					<span class="glhp-cat-tile__icon" aria-hidden="true"><?php echo esc_html( $cat_icon ); ?></span>
					<span class="glhp-cat-tile__name"><?php echo esc_html( $cat->name ); ?></span>
					<span class="glhp-cat-tile__count">
						<?php printf( esc_html( _n( '%d vendor', '%d vendors', $cat_count, 'gtmlens-child' ) ), $cat_count ); ?>
					</span>
					<?php if ( $cat_vendors ) : ?>
						<span class="glhp-cat-tile__stack">
							<?php foreach ( $cat_vendors as $cv ) :
								$cvl = get_field( 'logo', $cv->ID );
								$cvu = ( is_array( $cvl ) && ! empty( $cvl['url'] ) ) ? $cvl['url'] : '';
								if ( ! $cvu ) {
									$slug_to_dom = [
										'pipedrive' => 'pipedrive.com', 'spotlight-ai' => 'spotlight.ai', 'zapier' => 'zapier.com',
										'lemlist' => 'lemlist.com', 'typeform' => 'typeform.com', 'tally' => 'tally.so',
										'demandbase' => 'demandbase.com', 'phantombuster' => 'phantombuster.com',
										'chorus' => 'chorus.ai', 'hockeystack' => 'hockeystack.com', 'attio' => 'attio.com',
										'salesloft' => 'salesloft.com', 'expandi' => 'expandi.io', 'factors-ai' => 'factors.ai',
										'warmly' => 'warmly.ai', 'heyreach' => 'heyreach.io', '11x' => '11x.ai',
										'gong' => 'gong.io', '6sense' => '6sense.com', 'outreach' => 'outreach.io',
										'zoominfo' => 'zoominfo.com', 'salesforce' => 'salesforce.com', 'n8n' => 'n8n.io',
										'artisan' => 'artisan.co', 'rb2b' => 'rb2b.com', 'instantly' => 'instantly.ai',
										'hubspot' => 'hubspot.com', 'apollo' => 'apollo.io', 'claude-anthropic' => 'anthropic.com',
										'smartlead' => 'smartlead.ai', 'clay' => 'clay.com',
						'sierra' => 'sierra.ai', 'decagon' => 'decagon.ai', 'cresta' => 'cresta.com',
						'glean' => 'glean.com', 'bland-ai' => 'bland.ai', 'lyzr' => 'lyzr.ai',
						'crescendo' => 'crescendo.ai', 'highspot' => 'highspot.com', 'sybill' => 'sybill.ai',
						'spekit' => 'spekit.com', 'bardeen-ai' => 'bardeen.ai', 'default' => 'default.com',
						'attio' => 'attio.com', 'monaco' => 'monaco.app', 'aurasell' => 'aurasell.com',
						'pylon' => 'usepylon.com', 'unify' => 'unifygtm.com', 'reo-dev' => 'reo.dev',
						'dreamdata' => 'dreamdata.io', 'actively-ai' => 'actively.ai', 'gpt-openai' => 'openai.com',
						'pipedrive' => 'pipedrive.com',
									];
									if ( isset( $slug_to_dom[ $cv->post_name ] ) ) {
										$cvu = 'https://t3.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=' . rawurlencode( 'https://' . $slug_to_dom[ $cv->post_name ] ) . '&size=64';
									} else {
										$vurl = get_field( 'vendor_url', $cv->ID );
										if ( $vurl ) {
											$host = parse_url( $vurl, PHP_URL_HOST );
											if ( $host ) {
												$host = preg_replace( '/^www\\./', '', $host );
												$cvu = 'https://t3.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=' . rawurlencode( 'https://' . $host ) . '&size=64';
											}
										}
									}
								}
								?>
								<span class="glhp-cat-tile__chip" title="<?php echo esc_attr( get_the_title( $cv->ID ) ); ?>">
									<?php if ( $cvu ) : ?>
										<img src="<?php echo esc_url( $cvu ); ?>" alt="" loading="lazy" referrerpolicy="no-referrer" />
									<?php else : ?>
										<?php echo esc_html( mb_substr( get_the_title( $cv->ID ), 0, 1 ) ); ?>
									<?php endif; ?>
								</span>
							<?php endforeach; ?>
						</span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════════════════════
     7. EDITORIAL + NEWSLETTER — full-bleed surface, 2-col grid
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="gl-fullbleed glhp-editorial" aria-label="<?php esc_attr_e( 'Editorial policy and newsletter', 'gtmlens-child' ); ?>">
	<div class="gl-fullbleed__inner glhp-editorial__grid">

		<!-- LEFT: editorial independence -->
		<div class="glhp-editorial__text">
			<h3 class="glhp-editorial__h3"><?php esc_html_e( 'Independent by design', 'gtmlens-child' ); ?></h3>
			<p class="glhp-editorial__body">
				<?php esc_html_e( 'We don\'t take vendor money. No paid placements, no affiliate links, no sponsored content. Every rating, verdict, and recommendation is editorial — produced to the same standard as an institutional analyst report.', 'gtmlens-child' ); ?>
			</p>
			<a class="glhp-editorial__policy-link" href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>">
				<?php esc_html_e( 'Read editorial policy →', 'gtmlens-child' ); ?>
			</a>
		</div>

		<!-- RIGHT: newsletter signup -->
		<div class="glhp-newsletter">
			<h3 class="glhp-newsletter__h3"><?php esc_html_e( 'Subscribe to the report', 'gtmlens-child' ); ?></h3>
			<p class="glhp-newsletter__sub">
				<?php esc_html_e( 'Bi-weekly intelligence on the AI-native GTM stack — vendor moves, funding rounds, and analyst takes. No fluff.', 'gtmlens-child' ); ?>
			</p>
			<form
				class="glhp-newsletter__form"
				id="glhp-newsletter-form"
				action="#"
				method="post"
				novalidate
				aria-label="<?php esc_attr_e( 'Newsletter subscription', 'gtmlens-child' ); ?>"
			>
				<div class="glhp-newsletter__row">
					<label class="glhp-newsletter__label" for="glhp-email">
						<?php esc_html_e( 'Email address', 'gtmlens-child' ); ?>
					</label>
					<div class="glhp-newsletter__input-group">
						<input
							class="glhp-newsletter__input"
							type="email"
							id="glhp-email"
							name="glhp-email"
							placeholder="you@company.com"
							required
							autocomplete="email"
						>
						<button class="gl-btn-primary glhp-newsletter__btn" type="submit">
							<?php esc_html_e( 'Subscribe', 'gtmlens-child' ); ?>
						</button>
					</div>
				</div>
				<p class="glhp-newsletter__note" role="status" id="glhp-newsletter-msg" aria-live="polite">
					<?php esc_html_e( 'Bi-weekly. No spam. Unsubscribe in one click.', 'gtmlens-child' ); ?>
				</p>
			</form>
		</div>

	</div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     Footer strip — single line, centered
     ══════════════════════════════════════════════════════════════════════════ -->
<div class="glhp-footer-strip">
	<span class="glhp-footer-strip__copy">
		<?php
		printf(
			/* translators: %d: current year */
			esc_html__( '© %d GTMLens — Independent analyst intelligence for the AI-native GTM stack.', 'gtmlens-child' ),
			(int) date( 'Y' )
		);
		?>
	</span>
	<nav class="glhp-footer-strip__nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'gtmlens-child' ); ?>">
		<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'gtmlens-child' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/team/' ) ); ?>"><?php esc_html_e( 'Team', 'gtmlens-child' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'gtmlens-child' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>"><?php esc_html_e( 'Editorial Policy', 'gtmlens-child' ); ?></a>
		<a class="gl-contact-email" href="mailto:info@gtmlens.com">info@gtmlens.com</a>
	</nav>
</div>

<script>
(function () {
	var form  = document.getElementById('glhp-newsletter-form');
	var input = document.getElementById('glhp-email');
	var msg   = document.getElementById('glhp-newsletter-msg');
	if (!form || !input || !msg) return;

	var btn = form.querySelector('button[type=submit]');
	form.addEventListener('submit', function (e) {
		e.preventDefault();
		var email = input.value.trim();
		if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
			msg.textContent = 'Please enter a valid email address.';
			msg.style.color = '#A3291C';
			return;
		}
		btn.disabled = true;
		msg.textContent = 'Subscribing…';
		msg.style.color = '';
		fetch('/wp-json/gtmlens/v1/subscribe', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ email: email, source: 'home' })
		}).then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
		  .then(function (res) {
			if (res.ok) {
				msg.textContent = 'Subscribed. Check your inbox for confirmation.';
				msg.style.color = '#1A7A4A';
				input.value = '';
			} else {
				msg.textContent = (res.d && res.d.message) || 'Subscription failed. Please try again.';
				msg.style.color = '#A3291C';
			}
		  })
		  .catch(function () {
			msg.textContent = 'Network error. Please try again or email info@gtmlens.com.';
			msg.style.color = '#A3291C';
		  })
		  .finally(function () { btn.disabled = false; });
	});
})();
</script>

<?php
	/* P25: Tail strip */
	$gl_p25_tail_ts = strtotime( get_lastpostmodified( 'gmt' ) );
	$gl_p25_tail_date = $gl_p25_tail_ts > 0 ? date_i18n( 'M j, Y', $gl_p25_tail_ts ) : date_i18n( 'M j, Y' );
	$gl_p25_tail_cats = (int) wp_count_terms( array( 'taxonomy' => 'vendor_category', 'hide_empty' => true ) );
	$gl_p25_tail_evq = 0; if ( function_exists( 'gtmlens_get_funding_events' ) ) { $gl_tqs = mktime( 0, 0, 0, ( ( (int) ceil( (int) date( 'n' ) / 3 ) - 1 ) * 3 ) + 1, 1, (int) date( 'Y' ) ); foreach ( (array) gtmlens_get_funding_events() as $gl_tf ) { $gl_tt = isset( $gl_tf['event_type'] ) ? strtolower( $gl_tf['event_type'] ) : ''; if ( $gl_tt !== 'round' && $gl_tt !== 'funding' ) continue; $gl_tts = isset( $gl_tf['date'] ) ? strtotime( $gl_tf['date'] ) : 0; if ( $gl_tts && $gl_tts >= $gl_tqs ) $gl_p25_tail_evq++; } }
	?>
	<aside class="gl-tail-strip" aria-label="Site freshness">
		<span>Last updated <strong><?php echo esc_html( $gl_p25_tail_date ); ?></strong></span>
		<span class="gl-tail-strip__sep">&middot;</span>
		<span>Tracking <strong><?php echo esc_html( $vendor_total ?: '53' ); ?></strong> vendors across <strong><?php echo (int) $gl_p25_tail_cats; ?></strong> categories</span>
		<span class="gl-tail-strip__sep">&middot;</span>
		<span><strong><?php echo (int) $gl_p25_tail_evq; ?></strong> funding events this quarter</span>
		<span class="gl-tail-strip__sep">&middot;</span>
		<a href="<?php echo esc_url( home_url( '/funding-tracker/?win=ytd' ) ); ?>">YTD</a>
		<span class="gl-tail-strip__sep">&middot;</span>
		<a href="<?php echo esc_url( home_url( '/feed/' ) ); ?>">RSS</a>
	</aside>
	<?php get_footer(); ?>
