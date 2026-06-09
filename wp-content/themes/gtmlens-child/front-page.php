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
	'name'           => 'state-of-ai-gtm-q2-2026',
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
	<div class="glhp-stat-strip" role="list">
		<div class="glhp-stat-strip__item" role="listitem">
			<span class="glhp-stat-strip__number"><?php echo esc_html( $vendor_total ?: '20' ); ?></span>
			<span class="glhp-stat-strip__label"><?php esc_html_e( 'VENDORS', 'gtmlens-child' ); ?></span>
		</div>
		<div class="glhp-stat-strip__divider" aria-hidden="true"></div>
		<div class="glhp-stat-strip__item" role="listitem">
			<span class="glhp-stat-strip__number"><?php echo esc_html( $comparison_total ?: '8' ); ?></span>
			<span class="glhp-stat-strip__label"><?php esc_html_e( 'COMPARISONS', 'gtmlens-child' ); ?></span>
		</div>
		<div class="glhp-stat-strip__divider" aria-hidden="true"></div>
		<div class="glhp-stat-strip__item" role="listitem">
			<span class="glhp-stat-strip__number"><?php echo esc_html( $insight_total ?: '15' ); ?></span>
			<span class="glhp-stat-strip__label"><?php esc_html_e( 'INSIGHTS', 'gtmlens-child' ); ?></span>
		</div>
		<div class="glhp-stat-strip__divider" aria-hidden="true"></div>
		<div class="glhp-stat-strip__item" role="listitem">
			<span class="glhp-stat-strip__number"><?php echo esc_html( $stack_total ?: '4' ); ?></span>
			<span class="glhp-stat-strip__label"><?php esc_html_e( 'STACK RECIPES', 'gtmlens-child' ); ?></span>
		</div>
	</div>

	<!-- CTAs -->
	<div class="glhp-hero__ctas">
		<a class="gl-btn-primary" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>">
			<?php esc_html_e( 'Explore the directory →', 'gtmlens-child' ); ?>
		</a>
		<a class="gl-btn-secondary" href="<?php echo esc_url( home_url( '/stack-builder/' ) ); ?>">
			<?php esc_html_e( 'Build your stack', 'gtmlens-child' ); ?>
		</a>
	</div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     1.5 INTERACTIVE MARKET MAP — full vendor landscape, filterable
     ══════════════════════════════════════════════════════════════════════════ -->
<?php echo do_shortcode( '[market_map]' ); ?>

<!-- ══════════════════════════════════════════════════════════════════════════
     2. FEATURED REPORT — dark navy full-bleed, 2-col grid
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="gl-fullbleed gl-fullbleed--dark glhp-report" aria-label="<?php esc_attr_e( 'Featured report', 'gtmlens-child' ); ?>">
	<div class="gl-fullbleed__inner glhp-report__grid">

		<!-- LEFT: text -->
		<div class="glhp-report__text">
			<p class="glhp-report__eyebrow"><?php esc_html_e( 'Q2 2026 FLAGSHIP REPORT', 'gtmlens-child' ); ?></p>
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
	<div class="glhp-insight-grid">
		<?php foreach ( $latest_insights as $i => $insight ) :
			$cats     = get_the_category( $insight->ID );
			$cat_name = $cats ? $cats[0]->name : '';
			$pub_date = get_the_date( 'M j, Y', $insight->ID );
			$border_color = ( 0 === $i ) ? 'var(--gl-accent)' : 'var(--gl-primary)';
		?>
			<a class="glhp-insight-card" href="<?php echo esc_url( get_permalink( $insight->ID ) ); ?>" style="border-top-color: <?php echo esc_attr( $border_color ); ?>;">
				<?php if ( $cat_name ) : ?>
					<span class="glhp-insight-card__cat"><?php echo esc_html( $cat_name ); ?></span>
				<?php endif; ?>
				<h3 class="glhp-insight-card__title"><?php echo esc_html( get_the_title( $insight->ID ) ); ?></h3>
				<p class="glhp-insight-card__excerpt">
					<?php echo esc_html( wp_trim_words( get_the_excerpt( $insight->ID ), 22 ) ); ?>
				</p>
				<?php if ( $pub_date ) : ?>
					<span class="glhp-insight-card__date"><?php echo esc_html( $pub_date ); ?></span>
				<?php endif; ?>
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
			$comp_title  = get_the_title( $comp->ID );
			$verdict_raw = get_post_meta( $comp->ID, 'verdict', true );
			$verdict     = $verdict_raw ? wp_trim_words( $verdict_raw, 22 ) : '';
			// Parse "Vendor A vs Vendor B" title
			$parts = preg_split( '/\s+vs\.?\s+/i', $comp_title, 2 );
			$vendor_a = isset( $parts[0] ) ? trim( $parts[0] ) : $comp_title;
			$vendor_b = isset( $parts[1] ) ? trim( $parts[1] ) : '';
		?>
			<article class="glhp-comp-card">
				<div class="glhp-comp-card__vs-row">
					<span class="glhp-comp-card__vendor"><?php echo esc_html( $vendor_a ); ?></span>
					<?php if ( $vendor_b ) : ?>
						<span class="glhp-comp-card__vs">vs</span>
						<span class="glhp-comp-card__vendor"><?php echo esc_html( $vendor_b ); ?></span>
					<?php endif; ?>
				</div>
				<div class="glhp-comp-card__accent-line" aria-hidden="true"></div>
				<?php if ( $verdict ) : ?>
					<p class="glhp-comp-card__verdict"><?php echo esc_html( $verdict ); ?></p>
				<?php endif; ?>
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
	<div class="glhp-cat-grid">
		<?php foreach ( array_slice( $ordered_cats, 0, 10 ) as $cat ) :
			$cat_link  = get_term_link( $cat );
			$cat_count = (int) $cat->count;
			$cat_icon  = $cat_icons[ $cat->slug ] ?? '📂';
			if ( is_wp_error( $cat_link ) ) {
				continue;
			}
		?>
			<a class="glhp-cat-tile" href="<?php echo esc_url( $cat_link ); ?>">
				<span class="glhp-cat-tile__icon" aria-hidden="true"><?php echo esc_html( $cat_icon ); ?></span>
				<span class="glhp-cat-tile__name"><?php echo esc_html( $cat->name ); ?></span>
				<span class="glhp-cat-tile__count">
					<?php
					printf(
						/* translators: %d: number of vendors */
						esc_html( _n( '%d vendor', '%d vendors', $cat_count, 'gtmlens-child' ) ),
						$cat_count
					);
					?>
				</span>
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

<?php get_footer(); ?>
