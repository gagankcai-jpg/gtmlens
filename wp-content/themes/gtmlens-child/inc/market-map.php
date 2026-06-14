<?php
/**
 * Market Map v4 — Scale × Momentum matrix.
 * Y-axis: composite SCALE score (public/acquired land at top, not leftmost gutter).
 * X-axis: composite MOMENTUM score (recent + big round = right).
 * Dot size: growth velocity. Color: category. Halo: 4-tier momentum.
 * Shortcode: [market_map]
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gtmlens_scale_score( $vendor_id ) {
	$stage  = (string) get_field( 'funding_stage', $vendor_id );
	$raised = (int) get_field( 'total_raised_usd_m', $vendor_id );
	$val    = (int) get_field( 'last_valuation_usd_m', $vendor_id );
	if ( in_array( $stage, [ 'Public', 'IPO' ], true ) ) return 92;
	if ( 'Acquired' === $stage ) return 78;
	if ( $val >= 5000 ) return 88;
	if ( $val >= 2000 ) return 80;
	if ( $val >= 1000 ) return 70;
	if ( $val >= 500 )  return 60;
	if ( $val >= 200 )  return 50;
	if ( $raised >= 500 ) return 65;
	if ( $raised >= 200 ) return 55;
	if ( $raised >= 100 ) return 45;
	if ( $raised >= 50 )  return 38;
	if ( $raised >= 20 )  return 28;
	if ( $raised >= 5 )   return 20;
	if ( in_array( $stage, [ 'Series F', 'Series E', 'Series D+', 'Series D', 'Growth' ], true ) ) return 50;
	if ( 'Series C' === $stage ) return 40;
	if ( 'Series B' === $stage ) return 30;
	return 14;
}

function gtmlens_momentum_score( $vendor_id ) {
	$round_d        = (string) get_field( 'last_round_date', $vendor_id );
	$last_round_m   = (int) get_field( 'last_round_size_usd_m', $vendor_id );
	$founded        = (string) get_field( 'founded', $vendor_id );
	$months = 999;
	if ( $round_d && preg_match( '/^(\d{4})-(\d{2})/', $round_d, $rd ) ) {
		$months = ( ( (int) date( 'Y' ) - (int) $rd[1] ) * 12 ) + ( (int) date( 'n' ) - (int) $rd[2] );
	}
	$recency = 8;
	if ( $months <= 6 )  $recency = 70;
	elseif ( $months <= 12 ) $recency = 58;
	elseif ( $months <= 18 ) $recency = 45;
	elseif ( $months <= 24 ) $recency = 32;
	elseif ( $months <= 36 ) $recency = 22;
	$size_bonus = 0;
	if ( $last_round_m > 0 && $months <= 36 ) {
		$size_bonus = min( 20, (int) round( log10( $last_round_m + 1 ) * 8 ) );
	}
	$founded_y = preg_match( '/(\d{4})/', $founded, $fy ) ? (int) $fy[1] : 0;
	$age_bonus = 0;
	if ( $founded_y >= 2024 ) $age_bonus = 10;
	elseif ( $founded_y >= 2022 ) $age_bonus = 6;
	elseif ( $founded_y >= 2020 ) $age_bonus = 3;
	return max( 4, min( 100, $recency + $size_bonus + $age_bonus ) );
}

function gtmlens_classify_vendor( $vendor_id ) {
	$stage = (string) get_field( 'funding_stage', $vendor_id );
	if ( in_array( $stage, [ 'Public', 'Acquired', 'IPO' ], true ) ) {
		$round_d = (string) get_field( 'last_round_date', $vendor_id );
		$months = 999;
		if ( $round_d && preg_match( '/^(\d{4})-(\d{2})/', $round_d, $rd ) ) {
			$months = ( ( (int) date( 'Y' ) - (int) $rd[1] ) * 12 ) + ( (int) date( 'n' ) - (int) $rd[2] );
		}
		return $months <= 36 ? 'established' : 'incumbent';
	}
	$s = gtmlens_scale_score( $vendor_id );
	$m = gtmlens_momentum_score( $vendor_id );
	if ( $s >= 50 && $m >= 50 ) return 'leader';
	if ( $s >= 50 && $m <  50 ) return 'established';
	if ( $s <  50 && $m >= 50 ) return 'challenger';
	if ( $m < 25 ) return 'niche';
	return 'emerging';
}

function gtmlens_momentum_bucket( $round_date ) {
	if ( ! $round_date || ! preg_match( '/^(\d{4})-(\d{2})/', $round_date, $rd ) ) return 'cold';
	$months = ( ( (int) date( 'Y' ) - (int) $rd[1] ) * 12 ) + ( (int) date( 'n' ) - (int) $rd[2] );
	if ( $months <= 6 )  return 'hot';
	if ( $months <= 18 ) return 'warm';
	if ( $months <= 36 ) return 'cool';
	return 'cold';
}

function gtmlens_velocity_score( $last_round_m, $round_date ) {
	$lrm = (int) $last_round_m;
	if ( ! $round_date || ! preg_match( '/^(\d{4})-(\d{2})/', $round_date, $rd ) ) return 0.0;
	$months = ( ( (int) date( 'Y' ) - (int) $rd[1] ) * 12 ) + ( (int) date( 'n' ) - (int) $rd[2] );
	if ( $months > 36 ) return 0.0;
	$age  = max( 0, ( 36 - $months ) / 36 );
	$size = log10( max( 1, $lrm + 1 ) ) / 3;
	return max( 0.0, min( 1.2, $age * $size ) );
}

function gtmlens_dot_px_from_velocity( $score ) {
	$s = max( 0.0, min( 1.2, (float) $score ) );
	return (int) round( 18 + $s * 30 );
}

function gtmlens_fmt_raised( $m ) {
	$m = (int) $m;
	if ( $m <= 0 ) return '';
	if ( $m >= 1000 ) return '$' . number_format( $m / 1000, 1 ) . 'B';
	return '$' . number_format( $m ) . 'M';
}

function gtmlens_scale_chip( $vendor_id ) {
	$stage  = (string) get_field( 'funding_stage', $vendor_id );
	$raised = (int) get_field( 'total_raised_usd_m', $vendor_id );
	$val    = (int) get_field( 'last_valuation_usd_m', $vendor_id );
	if ( in_array( $stage, [ 'Public', 'IPO' ], true ) ) return 'Public';
	if ( 'Acquired' === $stage ) return 'Acquired';
	if ( $val >= 1000 ) return gtmlens_fmt_raised( $val ) . ' valuation';
	if ( $raised > 0 )  return gtmlens_fmt_raised( $raised ) . ' raised';
	if ( $stage ) return $stage;
	return 'Private';
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
		'ai-sdr' => 'AI SDR', 'data-activation' => 'Data Activation', 'outbound' => 'Outbound', 'data-enrichment' => 'Data & Enrichment',
		'crm' => 'CRM', 'intent-signal' => 'Intent & Signal', 'linkedin-automation' => 'LinkedIn Automation',
		'orchestration' => 'Orchestration', 'revenue-intelligence' => 'Revenue Intelligence',
		'lead-capture' => 'Lead Capture', 'foundation-models' => 'Foundation Models',
	];
	$type_label = [
		'leader' => 'Leader', 'challenger' => 'Challenger', 'established' => 'Established',
		'emerging' => 'Emerging', 'incumbent' => 'Incumbent', 'niche' => 'Niche',
	];
	$type_def = [
		'leader' => 'High scale AND high momentum — setting the category direction right now.',
		'challenger' => 'Smaller scale but rising fast — fresh capital, AI-native, gaining share.',
		'established' => 'Top of the scale axis (public, acquired, or unicorn) but momentum has slowed.',
		'emerging' => 'Mid-scale with steady activity — building, not breaking through yet.',
		'incumbent' => 'Public/acquired with no recent activity — the legacy footprint.',
		'niche' => 'Low scale, no recent round — operating but momentum stalled.',
	];
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
	$short_label = [
		'claude-anthropic' => 'Claude', '6sense' => '6sense', 'spotlight-ai' => 'Spotlight',
		'factors-ai' => 'Factors', 'phantombuster' => 'PhantomB', 'demandbase' => 'Demandbase',
		'hockeystack' => 'HockeyStack',
	];
	$vendors = get_posts( [
		'post_type' => 'vendor', 'posts_per_page' => -1, 'post_status' => 'publish',
		'orderby' => 'title', 'order' => 'ASC',
	] );
	if ( ! $vendors ) return '';

	$dots = [];
	foreach ( $vendors as $v ) {
		$tier   = get_field( 'pricing_tier', $v->ID );
		$stage  = get_field( 'funding_stage', $v->ID );
		$logo   = get_field( 'logo', $v->ID );
		$seg    = get_field( 'target_segment', $v->ID );
		$entry  = get_field( 'entry_price', $v->ID );
		$founded = get_field( 'founded', $v->ID );
		$raised_m = (int) get_field( 'total_raised_usd_m', $v->ID );
		$round_d  = (string) get_field( 'last_round_date', $v->ID );
		$last_round_size = (int) get_field( 'last_round_size_usd_m', $v->ID );
		$val_m    = (int) get_field( 'last_valuation_usd_m', $v->ID );
		$terms  = wp_get_post_terms( $v->ID, 'vendor_category', [ 'fields' => 'slugs' ] );
		$cat    = ( ! is_wp_error( $terms ) && $terms ) ? $terms[0] : 'orchestration';
		$logo_url = ( is_array( $logo ) && ! empty( $logo['url'] ) ) ? $logo['url'] : '';
		$logo_fb  = '';
		if ( ! $logo_url && isset( $domains[ $v->post_name ] ) ) {
			$logo_url = $favicon( $domains[ $v->post_name ] );
			$logo_fb  = $favicon_fb( $domains[ $v->post_name ] );
		}
		$scale_score    = gtmlens_scale_score( $v->ID );
		$momentum_score = gtmlens_momentum_score( $v->ID );
		$type     = gtmlens_classify_vendor( $v->ID );
		$momentum = gtmlens_momentum_bucket( $round_d );
		$velocity = gtmlens_velocity_score( $last_round_size, $round_d );
		$size_px  = gtmlens_dot_px_from_velocity( $velocity );
		$scale_ch = gtmlens_scale_chip( $v->ID );
		$founded_y = preg_match( '/(\d{4})/', (string) $founded, $fy ) ? (int) $fy[1] : 0;
		$round_label = '';
		if ( $round_d && preg_match( '/^(\d{4})-(\d{2})/', $round_d, $rd ) ) {
			$mo_names = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
			$round_label = ( $stage ? $stage . ' · ' : '' ) . $mo_names[ (int) $rd[2] ] . ' ' . $rd[1];
		}
		$x_pct = 5 + ( $momentum_score / 100 ) * 90;
		$y_pct = 95 - ( $scale_score    / 100 ) * 90;
		$dots[] = [
			'id' => $v->ID, 'slug' => $v->post_name, 'name' => get_the_title( $v->ID ),
			'short' => $short_label[ $v->post_name ] ?? get_the_title( $v->ID ),
			'url' => get_permalink( $v->ID ), 'logo' => $logo_url, 'logo_fb' => $logo_fb,
			'tier' => $tier ?: '', 'stage' => $stage ?: '', 'founded' => $founded ?: '',
			'founded_y' => $founded_y, 'seg' => $seg ?: '', 'entry' => $entry ?: '',
			'cat' => $cat, 'color' => $cat_color[ $cat ] ?? '#6B7C99',
			'raised_m' => $raised_m, 'last_round_m' => $last_round_size, 'val_m' => $val_m,
			'round_label' => $round_label, 'round_date' => $round_d,
			'type' => $type, 'momentum' => $momentum, 'velocity' => $velocity,
			'size_px' => $size_px, 'scale_score' => $scale_score, 'momentum_score' => $momentum_score,
			'scale_chip' => $scale_ch, 'x' => $x_pct, 'y' => $y_pct,
		];
	}

	$cells = [];
	$cell_w = 8; $cell_h = 8;
	foreach ( $dots as $i => $d ) {
		$cx = (int) floor( $d['x'] / $cell_w );
		$cy = (int) floor( $d['y'] / $cell_h );
		$cells[ "{$cx}_{$cy}" ][] = $i;
	}
	foreach ( $cells as $key => $idxs ) {
		$n = count( $idxs );
		if ( $n < 2 ) continue;
		usort( $idxs, function ( $a, $b ) use ( $dots ) { return $dots[ $b ]['scale_score'] - $dots[ $a ]['scale_score']; } );
		$step = 3.4;
		foreach ( $idxs as $k => $i ) {
			if ( $k === 0 ) continue;
			$angle = ( $k * 137.5 ) * M_PI / 180;
			$r = $step * sqrt( $k );
			$dots[ $i ]['x'] = max( 3, min( 97, $dots[ $i ]['x'] + $r * cos( $angle ) ) );
			$dots[ $i ]['y'] = max( 4, min( 96, $dots[ $i ]['y'] + $r * sin( $angle ) ) );
		}
	}

	$ranked_scale = $dots;
	usort( $ranked_scale, function ( $a, $b ) { return $b['scale_score'] - $a['scale_score']; } );
	$top12_ids = array_flip( array_map( function ( $d ) { return $d['id']; }, array_slice( $ranked_scale, 0, 12 ) ) );
	foreach ( $dots as &$d ) {
		$d['show_amount']  = isset( $top12_ids[ $d['id'] ] );
		$d['amount_label'] = $d['scale_chip'];
	}
	unset( $d );

	$present = [];
	$type_counts = [ 'leader' => 0, 'challenger' => 0, 'established' => 0, 'emerging' => 0, 'incumbent' => 0, 'niche' => 0 ];
	$by_type = [ 'leader' => [], 'challenger' => [], 'established' => [], 'emerging' => [], 'incumbent' => [], 'niche' => [] ];
	foreach ( $dots as $d ) {
		$present[ $d['cat'] ] = ( $present[ $d['cat'] ] ?? 0 ) + 1;
		$type_counts[ $d['type'] ]++;
		$by_type[ $d['type'] ][] = $d;
	}
	arsort( $present );

	$examples = [];
	foreach ( $by_type as $t => $arr ) {
		usort( $arr, function ( $a, $b ) { return $b['scale_score'] - $a['scale_score']; } );
		$examples[ $t ] = array_slice( $arr, 0, 2 );
	}

	$top_scale = array_slice( $ranked_scale, 0, 5 );
	$top_momentum = $dots;
	usort( $top_momentum, function ( $a, $b ) { return $b['momentum_score'] - $a['momentum_score']; } );
	$top_momentum = array_slice( array_values( array_filter( $top_momentum, function ( $d ) { return $d['momentum_score'] > 35; } ) ), 0, 5 );
	$top_velocity = $dots;
	usort( $top_velocity, function ( $a, $b ) { return ( $b['velocity'] <=> $a['velocity'] ); } );
	$top_velocity = array_slice( array_values( array_filter( $top_velocity, function ( $d ) { return $d['velocity'] > 0; } ) ), 0, 5 );

	$render_rail_row = function ( $d, $metric, $rank ) {
		?>
		<li>
			<a href="<?php echo esc_url( $d['url'] ); ?>">
				<span class="gl-mm-rail__rank"><?php echo (int) $rank; ?></span>
				<span class="gl-mm-rail__chip">
					<?php if ( $d['logo'] ) : ?>
						<img src="<?php echo esc_url( $d['logo'] ); ?>" alt="" loading="lazy" referrerpolicy="no-referrer" />
					<?php else : ?>
						<?php echo esc_html( mb_substr( $d['name'], 0, 1 ) ); ?>
					<?php endif; ?>
				</span>
				<span class="gl-mm-rail__name"><?php echo esc_html( $d['short'] ?? $d['name'] ); ?></span>
				<span class="gl-mm-rail__num"><?php echo esc_html( $metric ); ?></span>
			</a>
		</li>
		<?php
	};

	ob_start();
	?>
	<section class="gl-mm2 gl-mm3 gl-mm4" aria-label="AI-native GTM market map">
		<header class="gl-mm2__head">
			<p class="gl-mm2__eyebrow">Q2 2026 · INTERACTIVE LANDSCAPE</p>
			<h2 class="gl-mm2__h2">The AI-native GTM market</h2>
			<p class="gl-mm2__sub"><?php echo (int) count( $dots ); ?> vendors on the <strong>Scale × Momentum</strong> matrix. Y = scale (public/valuation/raised, composite). X = momentum (recent round size + recency). Dot size = funding velocity. Halo = freshness. Filter by category and type.</p>
		</header>

		<div class="gl-mm2__legend" role="toolbar" aria-label="Filter by category">
			<button class="gl-mm2-leg is-active" data-cat="all" type="button">All categories <span class="gl-mm2-leg__n"><?php echo (int) count( $dots ); ?></span></button>
			<?php foreach ( $present as $slug => $n ) : ?>
				<button class="gl-mm2-leg" data-cat="<?php echo esc_attr( $slug ); ?>" type="button">
					<span class="gl-mm2-leg__sw" style="background:<?php echo esc_attr( $cat_color[ $slug ] ?? '#6B7C99' ); ?>;"></span>
					<?php echo esc_html( $cat_label[ $slug ] ?? $slug ); ?>
					<span class="gl-mm2-leg__n"><?php echo (int) $n; ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="gl-mm2__type-row" role="toolbar" aria-label="Filter by type">
			<button class="gl-mm2-type-leg is-active" data-type="all" type="button">All types <span class="gl-mm2-type-leg__n"><?php echo (int) count( $dots ); ?></span></button>
			<?php foreach ( [ 'leader', 'challenger', 'established', 'emerging', 'incumbent', 'niche' ] as $t ) :
				if ( $type_counts[ $t ] === 0 ) continue;
				?>
				<button class="gl-mm2-type-leg" data-type="<?php echo esc_attr( $t ); ?>" type="button">
					<span class="gl-mm2-type-leg__sw"></span>
					<?php echo esc_html( $type_label[ $t ] ); ?>
					<span class="gl-mm2-type-leg__n"><?php echo (int) $type_counts[ $t ]; ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="gl-mm3__layout">
			<div class="gl-mm2__plot-wrap">
				<div class="gl-mm2__y-axis" aria-hidden="true">
					<span class="gl-mm2__y-label" style="top:8%;"><em>Public · Unicorn</em></span>
					<span class="gl-mm2__y-label" style="top:30%;"><em>$200M+ raised</em></span>
					<span class="gl-mm2__y-label" style="top:52%;"><em>$50M raised</em></span>
					<span class="gl-mm2__y-label" style="top:74%;"><em>Seed–A</em></span>
					<span class="gl-mm2__y-label" style="top:92%;"><em>Bootstrapped</em></span>
					<span class="gl-mm2__y-title">Scale →</span>
				</div>

				<div class="gl-mm2__plot">
					<div class="gl-mm2__quadrants" aria-hidden="true">
						<span class="gl-mm2__qcorner gl-mm2__qcorner--tl">Established</span>
						<span class="gl-mm2__qcorner gl-mm2__qcorner--tr">Leaders</span>
						<span class="gl-mm2__qcorner gl-mm2__qcorner--bl">Niche · Sunsetting</span>
						<span class="gl-mm2__qcorner gl-mm2__qcorner--br">Challengers</span>
					</div>
					<div class="gl-mm2__grid" aria-hidden="true">
						<span class="gl-mm2__gv" style="left:50%;"></span>
						<span class="gl-mm2__gh" style="top:50%;"></span>
					</div>

					<span class="gl-mm2__x-label" style="left:8%;">Cold / no round</span>
					<span class="gl-mm2__x-label" style="left:32%;">Aging</span>
					<span class="gl-mm2__x-label" style="left:58%;">Recent round</span>
					<span class="gl-mm2__x-label" style="left:86%;">Fresh + big</span>

					<?php foreach ( $dots as $d ) :
						$initial = mb_substr( $d['name'], 0, 1 );
						?>
						<a class="gl-mm2-dot<?php if ( ! empty( $d['show_amount'] ) ) echo ' has-amount'; ?>"
						   href="<?php echo esc_url( $d['url'] ); ?>"
						   data-cat="<?php echo esc_attr( $d['cat'] ); ?>"
						   data-type="<?php echo esc_attr( $d['type'] ); ?>"
						   data-momentum="<?php echo esc_attr( $d['momentum'] ); ?>"
						   style="left:<?php echo esc_attr( number_format( $d['x'], 2 ) ); ?>%;top:<?php echo esc_attr( number_format( $d['y'], 2 ) ); ?>%;--cc:<?php echo esc_attr( $d['color'] ); ?>;--dz:<?php echo (int) $d['size_px']; ?>px;"
						   aria-label="<?php echo esc_attr( $d['name'] ); ?>">
							<span class="gl-mm2-dot__chip" style="width:<?php echo (int) $d['size_px']; ?>px;height:<?php echo (int) $d['size_px']; ?>px;">
								<?php if ( $d['logo'] ) : ?>
									<img src="<?php echo esc_url( $d['logo'] ); ?>"<?php if ( ! empty( $d['logo_fb'] ) ) : ?> data-fb="<?php echo esc_attr( $d['logo_fb'] ); ?>"<?php endif; ?> alt="" loading="lazy" referrerpolicy="no-referrer" onerror="if(this.dataset.fb&&this.src!==this.dataset.fb){this.src=this.dataset.fb;this.removeAttribute('data-fb');}else{this.style.display='none';this.nextElementSibling&&(this.nextElementSibling.style.display='');}">
									<span class="gl-mm2-dot__initial" style="display:none;"><?php echo esc_html( $initial ); ?></span>
								<?php else : ?>
									<span class="gl-mm2-dot__initial"><?php echo esc_html( $initial ); ?></span>
								<?php endif; ?>
							</span>
							<span class="gl-mm2-dot__label"><?php echo esc_html( $d['short'] ?? $d['name'] ); ?></span>
							<?php if ( ! empty( $d['show_amount'] ) ) : ?>
								<span class="gl-mm2-dot__amount"><?php echo esc_html( $d['amount_label'] ); ?></span>
							<?php endif; ?>
							<div class="gl-mm2-dot__tip">
								<div class="gl-mm2-dot__tip-head">
									<span class="gl-mm2-dot__tip-cat" style="background:<?php echo esc_attr( $d['color'] ); ?>;"><?php echo esc_html( $cat_label[ $d['cat'] ] ?? $d['cat'] ); ?></span>
									<strong><?php echo esc_html( $d['name'] ); ?></strong>
									<span class="gl-mm2-dot__tip-type"><?php echo esc_html( $type_label[ $d['type'] ] ?? $d['type'] ); ?></span>
								</div>
								<dl class="gl-mm2-dot__tip-meta">
									<div><dt>Scale</dt><dd><?php echo esc_html( $d['scale_chip'] ); ?> · <span style="color:#5b6b85;">score <?php echo (int) $d['scale_score']; ?></span></dd></div>
									<div><dt>Momentum</dt><dd><span style="color:#5b6b85;">score <?php echo (int) $d['momentum_score']; ?></span><?php if ( $d['round_label'] ) echo ' · ' . esc_html( $d['round_label'] ); ?></dd></div>
									<?php if ( $d['last_round_m'] > 0 ) : ?><div><dt>Last round</dt><dd><?php echo esc_html( gtmlens_fmt_raised( $d['last_round_m'] ) ); ?></dd></div><?php endif; ?>
									<?php if ( $d['tier'] ) : ?><div><dt>Pricing</dt><dd><?php echo esc_html( $d['tier'] ); ?><?php if ( $d['entry'] ) echo ' · ' . esc_html( $d['entry'] ); ?></dd></div><?php endif; ?>
									<?php if ( $d['founded'] ) : ?><div><dt>Founded</dt><dd><?php echo esc_html( $d['founded'] ); ?></dd></div><?php endif; ?>
									<?php if ( $d['seg'] ) : ?><div><dt>ICP</dt><dd><?php echo esc_html( wp_trim_words( $d['seg'], 14 ) ); ?></dd></div><?php endif; ?>
								</dl>
								<span class="gl-mm2-dot__tip-cta">View analyst profile →</span>
							</div>
						</a>
					<?php endforeach; ?>
				</div>

				<div class="gl-mm2__x-title">Momentum →</div>
			</div>

			<aside class="gl-mm-rail" aria-label="Leaderboards">
				<div class="gl-mm-rail__list">
					<h3><span class="gl-mm-rail__pip" style="background:#1d4ed8;"></span>Top scale</h3>
					<ol>
						<?php foreach ( $top_scale as $i => $d ) : $render_rail_row( $d, $d['scale_chip'], $i + 1 ); endforeach; ?>
					</ol>
				</div>
				<div class="gl-mm-rail__list">
					<h3><span class="gl-mm-rail__pip" style="background:#10b981;"></span>Top momentum</h3>
					<ol>
						<?php foreach ( $top_momentum as $i => $d ) :
							$metric = $d['last_round_m'] > 0 ? gtmlens_fmt_raised( $d['last_round_m'] ) : 'score ' . $d['momentum_score'];
							if ( $d['round_label'] && preg_match( '/(\w+ \d{4})$/', $d['round_label'], $rm ) ) $metric .= ' · ' . $rm[1];
							$render_rail_row( $d, $metric, $i + 1 );
						endforeach; ?>
					</ol>
				</div>
				<div class="gl-mm-rail__list">
					<h3><span class="gl-mm-rail__pip" style="background:#b45309;"></span>Top velocity</h3>
					<ol>
						<?php foreach ( $top_velocity as $i => $d ) :
							$metric = $d['last_round_m'] > 0 ? gtmlens_fmt_raised( $d['last_round_m'] ) . ' round' : '';
							$render_rail_row( $d, $metric, $i + 1 );
						endforeach; ?>
					</ol>
				</div>
			</aside>
		</div>

		<div class="gl-mm-legend-strip">
			<?php foreach ( [ 'leader', 'challenger', 'established', 'emerging', 'incumbent', 'niche' ] as $t ) :
				if ( $type_counts[ $t ] === 0 ) continue;
				?>
				<div class="gl-mm-legend-card" data-type="<?php echo esc_attr( $t ); ?>">
					<div class="gl-mm-legend-card__head">
						<span class="gl-mm-legend-card__type"><?php echo esc_html( $type_label[ $t ] ); ?></span>
						<span class="gl-mm-legend-card__count"><?php echo (int) $type_counts[ $t ]; ?> vendors</span>
					</div>
					<p class="gl-mm-legend-card__def"><?php echo esc_html( $type_def[ $t ] ); ?></p>
					<?php if ( ! empty( $examples[ $t ] ) ) : ?>
						<div class="gl-mm-legend-card__ex">
							<?php foreach ( $examples[ $t ] as $ex ) : ?>
								<a href="<?php echo esc_url( $ex['url'] ); ?>">
									<?php if ( $ex['logo'] ) : ?><img src="<?php echo esc_url( $ex['logo'] ); ?>" alt="" loading="lazy" referrerpolicy="no-referrer" /><?php endif; ?>
									<span><?php echo esc_html( $ex['short'] ?? $ex['name'] ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<footer class="gl-mm2__foot">
			<p class="gl-mm2__foot-method"><strong>How to read.</strong> Y = composite SCALE: public/acquired anchored at top; otherwise valuation > total raised > stage. X = composite MOMENTUM: recent round (≤6mo strongest), boosted by round size + young founding. Dot size = funding velocity (recent round size, decays 36mo). Halo: green ≤6mo, amber ≤18mo, grey ≤36mo. <a href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>">Methodology →</a></p>
			<a class="gl-mm2__foot-cta" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>">Browse the full directory →</a>
		</footer>
	</section>

	<script>
	(function () {
		var root = document.currentScript.previousElementSibling;
		if (!root || !root.classList.contains('gl-mm2')) return;
		var catLegs  = root.querySelectorAll('.gl-mm2-leg');
		var typeLegs = root.querySelectorAll('.gl-mm2-type-leg');
		var dots     = root.querySelectorAll('.gl-mm2-dot');
		var activeCat  = 'all';
		var activeType = 'all';
		function apply() {
			dots.forEach(function (d) {
				var matchCat  = (activeCat  === 'all') || (d.getAttribute('data-cat')  === activeCat);
				var matchType = (activeType === 'all') || (d.getAttribute('data-type') === activeType);
				d.classList.toggle('is-dim', !(matchCat && matchType));
			});
		}
		catLegs.forEach(function (b) {
			b.addEventListener('click', function () {
				catLegs.forEach(function (x) { x.classList.remove('is-active'); });
				b.classList.add('is-active');
				activeCat = b.getAttribute('data-cat');
				apply();
			});
		});
		typeLegs.forEach(function (b) {
			b.addEventListener('click', function () {
				typeLegs.forEach(function (x) { x.classList.remove('is-active'); });
				b.classList.add('is-active');
				activeType = b.getAttribute('data-type');
				apply();
			});
		});
	})();
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'market_map', 'gtmlens_market_map_render' );
