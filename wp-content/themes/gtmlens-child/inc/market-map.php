<?php
/**
 * Market Map — 2D scatter of vendor maturity × buyer profile, colored by category.
 * Enterprise-analyst aesthetic. Logos via Google favicon service + curated domain map.
 * Shortcode: [market_map]
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gtmlens_market_map_render() {
	$cat_color = [
		'ai-sdr'               => '#8E3FBE',
		'outbound'             => '#C97A2B',
		'data-enrichment'      => '#4F8FE5',
		'crm'                  => '#0D1F3C',
		'intent-signal'        => '#1A7A4A',
		'linkedin-automation'  => '#2E6FAA',
		'orchestration'        => '#6B7C99',
		'revenue-intelligence' => '#A3291C',
		'lead-capture'         => '#D4A24A',
		'foundation-models'    => '#7BC47F',
	];
	$cat_label = [
		'ai-sdr' => 'AI SDR', 'outbound' => 'Outbound', 'data-enrichment' => 'Data & Enrichment',
		'crm' => 'CRM', 'intent-signal' => 'Intent & Signal', 'linkedin-automation' => 'LinkedIn Automation',
		'orchestration' => 'Orchestration', 'revenue-intelligence' => 'Revenue Intelligence',
		'lead-capture' => 'Lead Capture', 'foundation-models' => 'Foundation Models',
	];

	// Curated slug → domain map for favicon lookup (covers all 31 v1 vendors)
	$domains = [
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
	];
	$favicon = function ( $domain ) {
		return 'https://t3.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=' . rawurlencode( 'https://' . $domain ) . '&size=64';
	};
	$favicon_fb = function ( $domain ) {
		return 'https://icons.duckduckgo.com/ip3/' . rawurlencode( $domain ) . '.ico';
	};

	// Short display labels (override long titles for chip caption)
	$short_label = [
		'claude-anthropic' => 'Claude', '6sense' => '6sense', 'spotlight-ai' => 'Spotlight',
		'factors-ai' => 'Factors', 'phantombuster' => 'PhantomB', 'demandbase' => 'Demandbase',
		'hockeystack' => 'HockeyStack',
	];

	// X bucket index (0..3) and pixel center
	$x_buckets = [ 'Free' => 0, '$' => 0, '$$' => 1, '$$$' => 2, 'Enterprise' => 3 ];
	$x_centers = [ 0 => 14, 1 => 38, 2 => 62, 3 => 86 ];
	// Y bucket index (0=emerging,1=growth,2=established) and pixel center
	$y_centers = [ 0 => 78, 1 => 48, 2 => 18 ]; // 0=incumbents bottom, 2=recent entrants top

	// Y bucket from founded year — separates AI-native entrants from incumbents
	$bucket_for_y = function ( $founded ) {
		if ( ! preg_match( '/(\d{4})/', (string) $founded, $m ) ) return 1; // unknown → middle
		$yr = (int) $m[1];
		if ( $yr >= 2021 ) return 2; // recent entrants — top
		if ( $yr >= 2016 ) return 1; // established — middle
		return 0;                    // incumbents — bottom
	};

	$vendors = get_posts( [
		'post_type' => 'vendor', 'posts_per_page' => -1, 'post_status' => 'publish',
		'orderby' => 'title', 'order' => 'ASC',
	] );
	if ( ! $vendors ) return '';

	// Pass 1: collect raw vendor data + bucket assignment
	$raw = [];
	foreach ( $vendors as $v ) {
		$tier   = get_field( 'pricing_tier', $v->ID );
		$stage  = get_field( 'funding_stage', $v->ID );
		$logo   = get_field( 'logo', $v->ID );
		$seg    = get_field( 'target_segment', $v->ID );
		$entry  = get_field( 'entry_price', $v->ID );
		$terms  = wp_get_post_terms( $v->ID, 'vendor_category', [ 'fields' => 'slugs' ] );
		$cat    = ( ! is_wp_error( $terms ) && $terms ) ? $terms[0] : 'orchestration';

		$founded = get_field( 'founded', $v->ID );
		$xb = isset( $x_buckets[ $tier ] ) ? $x_buckets[ $tier ] : 1;
		$yb = $bucket_for_y( $founded );

		$logo_url = ( is_array( $logo ) && ! empty( $logo['url'] ) ) ? $logo['url'] : '';
		$logo_fb  = '';
		if ( ! $logo_url && isset( $domains[ $v->post_name ] ) ) {
			$logo_url = $favicon( $domains[ $v->post_name ] );
			$logo_fb  = $favicon_fb( $domains[ $v->post_name ] );
		}

		$raw[] = [
			'id'      => $v->ID,
			'slug'    => $v->post_name,
			'name'    => get_the_title( $v->ID ),
			'short'   => $short_label[ $v->post_name ] ?? get_the_title( $v->ID ),
			'url'     => get_permalink( $v->ID ),
			'logo'    => $logo_url,
			'logo_fb' => $logo_fb,
			'tier'    => $tier ?: '',
			'stage'   => $stage ?: '',
			'founded' => $founded ?: '',
			'seg'   => $seg ?: '',
			'entry' => $entry ?: '',
			'cat'   => $cat,
			'color' => $cat_color[ $cat ] ?? '#6B7C99',
			'xb'    => $xb,
			'yb'    => $yb,
		];
	}

	// Pass 2: bucket cells → distribute in mini-grid to avoid overlap
	$cells = [];
	foreach ( $raw as $i => $d ) {
		$key = $d['xb'] . '_' . $d['yb'];
		$cells[ $key ][] = $i;
	}
	$cell_w = 24; // % width per X bucket
	$cell_h = 30; // % height per Y bucket
	$dots = [];
	foreach ( $cells as $idxs ) {
		$n    = count( $idxs );
		$cols = (int) ceil( sqrt( $n ) );
		$rows = (int) ceil( $n / $cols );
		// step within cell — leave margins
		$step_x = $cols > 1 ? min( $cell_w * 0.80 / ( $cols - 1 ), 8.5 ) : 0;
		$step_y = $rows > 1 ? min( $cell_h * 0.78 / ( $rows - 1 ), 12 ) : 0;
		$start_x = -( $cols - 1 ) * $step_x / 2;
		$start_y = -( $rows - 1 ) * $step_y / 2;
		// sort within cell by name for stable layout
		usort( $idxs, function ( $a, $b ) use ( $raw ) {
			return strcmp( $raw[ $a ]['slug'], $raw[ $b ]['slug'] );
		} );
		foreach ( $idxs as $k => $i ) {
			$d = $raw[ $i ];
			$col = $k % $cols;
			$row = (int) floor( $k / $cols );
			$x = $x_centers[ $d['xb'] ] + $start_x + $col * $step_x;
			$y = $y_centers[ $d['yb'] ] + $start_y + $row * $step_y;
			$d['x'] = max( 5, min( 95, $x ) );
			$d['y'] = max( 8, min( 92, $y ) );
			$dots[] = $d;
		}
	}
	$present = [];
	foreach ( $dots as $d ) { $present[ $d['cat'] ] = ( $present[ $d['cat'] ] ?? 0 ) + 1; }
	arsort( $present );

	ob_start();
	?>
	<section class="gl-mm2" aria-label="AI-native GTM market map">
		<header class="gl-mm2__head">
			<p class="gl-mm2__eyebrow">Q2 2026 · INTERACTIVE LANDSCAPE</p>
			<h2 class="gl-mm2__h2">The AI-native GTM market</h2>
			<p class="gl-mm2__sub"><?php echo (int) count( $dots ); ?> vendors plotted by <strong>buyer profile</strong> (x) and <strong>founded year</strong> (y). The top-right quadrant is where AI-native enterprise contenders are emerging. Filter by category; click any vendor.</p>
		</header>

		<div class="gl-mm2__legend" role="toolbar" aria-label="Filter by category">
			<button class="gl-mm2-leg is-active" data-cat="all" type="button">All vendors <span class="gl-mm2-leg__n"><?php echo (int) count( $dots ); ?></span></button>
			<?php foreach ( $present as $slug => $n ) : ?>
				<button class="gl-mm2-leg" data-cat="<?php echo esc_attr( $slug ); ?>" type="button">
					<span class="gl-mm2-leg__sw" style="background:<?php echo esc_attr( $cat_color[ $slug ] ?? '#6B7C99' ); ?>;"></span>
					<?php echo esc_html( $cat_label[ $slug ] ?? $slug ); ?>
					<span class="gl-mm2-leg__n"><?php echo (int) $n; ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="gl-mm2__plot-wrap">
			<div class="gl-mm2__y-axis" aria-hidden="true">
				<span class="gl-mm2__y-label" style="top:18%;">Recent entrants<br><em>2021+</em></span>
				<span class="gl-mm2__y-label" style="top:48%;">Established<br><em>2016–20</em></span>
				<span class="gl-mm2__y-label" style="top:78%;">Incumbents<br><em>pre-2016</em></span>
				<span class="gl-mm2__y-title">Founded</span>
			</div>

			<div class="gl-mm2__plot">
				<div class="gl-mm2__quadrants" aria-hidden="true">
					<span class="gl-mm2__qcorner gl-mm2__qcorner--tl">AI-native challengers</span>
					<span class="gl-mm2__qcorner gl-mm2__qcorner--tr">AI-native enterprise</span>
					<span class="gl-mm2__qcorner gl-mm2__qcorner--bl">Self-serve incumbents</span>
					<span class="gl-mm2__qcorner gl-mm2__qcorner--br">Enterprise incumbents</span>
				</div>
				<div class="gl-mm2__grid" aria-hidden="true">
					<span class="gl-mm2__gv" style="left:25%;"></span>
					<span class="gl-mm2__gv" style="left:50%;"></span>
					<span class="gl-mm2__gv" style="left:75%;"></span>
					<span class="gl-mm2__gh" style="top:33%;"></span>
					<span class="gl-mm2__gh" style="top:66%;"></span>
				</div>

				<span class="gl-mm2__x-label" style="left:14%;">Self-serve / SMB</span>
				<span class="gl-mm2__x-label" style="left:38%;">SMB / Mid-market</span>
				<span class="gl-mm2__x-label" style="left:62%;">Mid-market / Upper</span>
				<span class="gl-mm2__x-label" style="left:86%;">Enterprise</span>

				<?php foreach ( $dots as $d ) :
					$initial = mb_substr( $d['name'], 0, 1 );
					?>
					<a class="gl-mm2-dot"
					   href="<?php echo esc_url( $d['url'] ); ?>"
					   data-cat="<?php echo esc_attr( $d['cat'] ); ?>"
					   style="left:<?php echo esc_attr( $d['x'] ); ?>%;top:<?php echo esc_attr( $d['y'] ); ?>%;--cc:<?php echo esc_attr( $d['color'] ); ?>;"
					   aria-label="<?php echo esc_attr( $d['name'] ); ?>">
						<span class="gl-mm2-dot__chip">
							<?php if ( $d['logo'] ) : ?>
								<img src="<?php echo esc_url( $d['logo'] ); ?>"<?php if ( ! empty( $d['logo_fb'] ) ) : ?> data-fb="<?php echo esc_attr( $d['logo_fb'] ); ?>"<?php endif; ?> alt="" loading="lazy" referrerpolicy="no-referrer" onerror="if(this.dataset.fb&&this.src!==this.dataset.fb){this.src=this.dataset.fb;this.removeAttribute('data-fb');}else{this.style.display='none';this.nextElementSibling&&(this.nextElementSibling.style.display='');}">
								<span class="gl-mm2-dot__initial" style="display:none;"><?php echo esc_html( $initial ); ?></span>
							<?php else : ?>
								<span class="gl-mm2-dot__initial"><?php echo esc_html( $initial ); ?></span>
							<?php endif; ?>
						</span>
						<span class="gl-mm2-dot__label"><?php echo esc_html( $d['short'] ?? $d['name'] ); ?></span>
						<div class="gl-mm2-dot__tip">
							<div class="gl-mm2-dot__tip-head">
								<span class="gl-mm2-dot__tip-cat" style="background:<?php echo esc_attr( $d['color'] ); ?>;"><?php echo esc_html( $cat_label[ $d['cat'] ] ?? $d['cat'] ); ?></span>
								<strong><?php echo esc_html( $d['name'] ); ?></strong>
							</div>
							<dl class="gl-mm2-dot__tip-meta">
								<?php if ( $d['tier'] ) : ?><div><dt>Pricing</dt><dd><?php echo esc_html( $d['tier'] ); ?><?php if ( $d['entry'] ) echo ' · ' . esc_html( $d['entry'] ); ?></dd></div><?php endif; ?>
								<?php if ( $d['founded'] ) : ?><div><dt>Founded</dt><dd><?php echo esc_html( $d['founded'] ); ?><?php if ( $d['stage'] ) echo ' · ' . esc_html( $d['stage'] ); ?></dd></div><?php endif; ?>
								<?php if ( $d['seg'] ) : ?><div><dt>ICP</dt><dd><?php echo esc_html( wp_trim_words( $d['seg'], 16 ) ); ?></dd></div><?php endif; ?>
							</dl>
							<span class="gl-mm2-dot__tip-cta">View analyst profile →</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>

			<div class="gl-mm2__x-title">Buyer profile / pricing tier</div>
		</div>

		<footer class="gl-mm2__foot">
			<p class="gl-mm2__foot-method"><strong>How to read.</strong> X-axis: pricing tier as proxy for buyer profile (Self-serve → Enterprise). Y-axis: founded year (incumbents pre-2016 at bottom, AI-native entrants 2021+ at top). Color = category; dot border-color matches. The AI-native enterprise quadrant (top-right) is where the next wave of incumbents is being formed. <a href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>">Methodology →</a></p>
			<a class="gl-mm2__foot-cta" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>">Browse the full directory →</a>
		</footer>
	</section>

	<script>
	(function () {
		var root = document.currentScript.previousElementSibling;
		if (!root || !root.classList.contains('gl-mm2')) return;
		var legs = root.querySelectorAll('.gl-mm2-leg');
		var dots = root.querySelectorAll('.gl-mm2-dot');
		legs.forEach(function (b) {
			b.addEventListener('click', function () {
				legs.forEach(function (x) { x.classList.remove('is-active'); });
				b.classList.add('is-active');
				var cat = b.getAttribute('data-cat');
				dots.forEach(function (d) {
					var match = (cat === 'all') || (d.getAttribute('data-cat') === cat);
					d.classList.toggle('is-dim', !match);
				});
			});
		});
	})();
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'market_map', 'gtmlens_market_map_render' );
