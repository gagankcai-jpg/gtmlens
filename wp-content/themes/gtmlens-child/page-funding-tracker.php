<?php
/*
 * Template Name: Funding Tracker
 * Template Post Type: page
 *
 * P10 v2: Card-feed layout with density sparkline, URL state filters, sort control,
 *         public-companies sidebar, event-type badges, and analyst note prominence.
 * Data:    vendor CPT (last_round_*) + funding_event CPT, public companies separated.
 * Schema:  Dataset JSON-LD emitted in functions.php §18.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Main feed: exclude public companies (HubSpot, Salesforce, ZoomInfo)
$events     = gtmlens_get_funding_events( [ 'exclude_public' => true ] );
// Public companies sidebar (show separately)
$publics    = array_values( array_filter( gtmlens_get_funding_events(), function ( $e ) {
	return 'ipo' === $e['event_type'];
} ) );

$dates      = array_filter( array_column( $events, 'date' ) );
$last_mod   = $dates ? max( $dates ) : '';
$buckets    = gtmlens_bucket_events_by_quarter( $events );
$categories = array_values( array_unique( array_filter( array_column( $events, 'category' ) ) ) );
sort( $categories );
$stages     = array_values( array_unique( array_filter( array_column( $events, 'stage' ) ) ) );
$stage_order = [ 'Pre-seed', 'Seed', 'Series A', 'Series B', 'Series C', 'Series D+', 'Growth', 'IPO', 'Acquired', 'Public', 'Bootstrapped', 'Other' ];
usort( $stages, function ( $a, $b ) use ( $stage_order ) {
	$ia = array_search( $a, $stage_order, true );
	$ib = array_search( $b, $stage_order, true );
	$ia = false === $ia ? 999 : $ia;
	$ib = false === $ib ? 999 : $ib;
	return $ia - $ib;
} );
$quarters = array_keys( $buckets );

// Build sparkline data: last 6 quarters with counts (zero-fill missing)
function gtmlens_build_sparkline_buckets( array $events ): array {
	if ( ! $events ) return [];
	$counts = [];
	foreach ( $events as $e ) {
		if ( empty( $e['date'] ) ) continue;
		$y = (int) substr( $e['date'], 0, 4 );
		$m = (int) substr( $e['date'], 5, 2 );
		$q = (int) ceil( $m / 3 );
		$key = sprintf( '%04d-%d', $y, $q );
		$counts[ $key ] = ( $counts[ $key ] ?? 0 ) + 1;
	}
	if ( ! $counts ) return [];
	// Last 6 quarters anchored to "now"
	$now_y = (int) date( 'Y' );
	$now_q = (int) ceil( ( (int) date( 'n' ) ) / 3 );
	$out = [];
	for ( $i = 5; $i >= 0; $i-- ) {
		$y = $now_y;
		$q = $now_q - $i;
		while ( $q < 1 ) { $q += 4; $y--; }
		$key = sprintf( '%04d-%d', $y, $q );
		$out[] = [
			'label' => "Q{$q} {$y}",
			'count' => $counts[ $key ] ?? 0,
		];
	}
	return $out;
}
$spark = gtmlens_build_sparkline_buckets( $events );
$spark = array_reverse( $spark ); // Newest first (left → oldest right)
$spark_max = $spark ? max( array_column( $spark, 'count' ) ) : 1;

// Quarterly summary stats (top 8 by recency)
$summary = [];
foreach ( $buckets as $q => $list ) {
	$amts = array_filter( array_column( $list, 'amount_m' ) );
	$summary[ $q ] = [
		'count'   => count( $list ),
		'total_m' => array_sum( $amts ),
	];
}

if ( ! function_exists( 'gtmlens_fmt_usd_m' ) ) {
	function gtmlens_fmt_usd_m( int $m ): string {
		if ( $m <= 0 )    return '—';
		if ( $m >= 1000 ) return '$' . number_format( $m / 1000, 1 ) . 'B';
		return '$' . number_format( $m ) . 'M';
	}
}
?>

<section class="glhp-hero" style="padding-bottom: 18px;">
	<p class="glhp-hero__eyebrow"><?php esc_html_e( 'Funding Tracker', 'gtmlens-child' ); ?></p>
	<h1 class="glhp-hero__h1"><?php esc_html_e( 'GTM Funding Tracker', 'gtmlens-child' ); ?></h1>
	<p class="glhp-hero__sub" style="max-width:760px;">
		Rolling list of funding rounds, M&amp;A, and IPOs across the AI-native GTM stack. Independent. No paywall. Sourced from press releases, SEC filings, and Crunchbase.
	</p>
	<?php if ( $last_mod ) : ?>
		<p style="margin-top:14px;font-size:.85rem;color:var(--gl-text-muted);">
			Last updated: <strong><?php echo esc_html( $last_mod ); ?></strong> ·
			<a href="<?php echo esc_url( rest_url( 'gtmlens/v1/funding-events' ) ); ?>" rel="nofollow">JSON</a> ·
			<a href="<?php echo esc_url( home_url( '/feed/?post_type=funding_event' ) ); ?>" rel="nofollow">RSS</a>
		</p>
	<?php endif; ?>
</section>

<?php if ( $spark ) : ?>
<section class="glhp-boxed" style="padding:0 24px 16px;max-width:1200px;margin:0 auto;">
	<div class="gl-spark-wrap" aria-label="Events per quarter, last 6 quarters">
		<div class="gl-spark-label">Events / quarter (last 6)</div>
		<div class="gl-spark-bars">
			<?php foreach ( $spark as $b ) : ?>
				<div class="gl-spark-col" title="<?php echo esc_attr( $b['label'] . ': ' . $b['count'] . ' events' ); ?>">
					<div class="gl-spark-bar" style="height:<?php echo $spark_max ? max( 4, (int) round( $b['count'] / $spark_max * 56 ) ) : 0; ?>px;"></div>
					<div class="gl-spark-tick"><?php echo esc_html( substr( $b['label'], 0, 2 ) ); // Q1, Q2, etc. ?></div>
					<div class="gl-spark-year"><?php echo esc_html( substr( $b['label'], -2 ) ); // 26, 25 ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $spark ) : ?>
<section class="glhp-boxed" style="padding:8px 24px 24px;max-width:1200px;margin:0 auto;">
	<div class="gl-funding-summary">
		<?php foreach ( $spark as $b ) :
			$q = $b['label'];
			$s = $summary[ $q ] ?? [ 'count' => 0, 'total_m' => 0 ];
		?>
			<div class="gl-summary-card">
				<div class="gl-summary-q"><?php echo esc_html( $q ); ?></div>
				<div class="gl-summary-count"><?php echo (int) $s['count']; ?> <span>events</span></div>
				<?php if ( $s['total_m'] > 0 ) : ?>
					<div class="gl-summary-total"><?php echo esc_html( gtmlens_fmt_usd_m( $s['total_m'] ) ); ?> tracked</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<section class="glhp-boxed glhp-boxed--white" style="padding: 24px; max-width:1200px; margin:0 auto 24px;" x-data="gtmlensFundingV2()" x-init="init()">
	<div class="gl-funding-filters">
		<input type="search" x-model="q" placeholder="Search company…" aria-label="Search">
		<select x-model="cat" aria-label="Category">
			<option value="">All categories</option>
			<?php foreach ( $categories as $c ) : ?>
				<option value="<?php echo esc_attr( $c ); ?>"><?php echo esc_html( $c ); ?></option>
			<?php endforeach; ?>
		</select>
		<select x-model="stage" aria-label="Stage">
			<option value="">All stages</option>
			<?php foreach ( $stages as $s ) : ?>
				<option value="<?php echo esc_attr( $s ); ?>"><?php echo esc_html( $s ); ?></option>
			<?php endforeach; ?>
		</select>
		<select x-model="quarter" aria-label="Quarter">
			<option value="">All quarters</option>
			<?php foreach ( $quarters as $q ) : ?>
				<option value="<?php echo esc_attr( $q ); ?>"><?php echo esc_html( $q ); ?></option>
			<?php endforeach; ?>
		</select>
		<select x-model="sort" aria-label="Sort">
			<option value="date_desc">Newest first</option>
			<option value="amount_desc">Largest amount</option>
			<option value="val_desc">Largest valuation</option>
		</select>
		<span class="gl-filter-count" x-text="'Showing ' + sortedFiltered.length + ' of ' + events.length"></span>
	</div>

	<div class="gl-funding-feed">
		<template x-for="(e, i) in sortedFiltered" :key="i">
			<article class="gl-funding-card" :class="'gl-event-' + e.event_type">
				<div class="gl-card-header">
					<a class="gl-card-title" :href="e.url" :target="e.source_kind === 'external' ? '_blank' : '_self'" :rel="e.source_kind === 'external' ? 'noopener' : ''">
						<span x-text="e.company"></span>
						<template x-if="e.event_type === 'ma'"><span class="gl-event-badge gl-badge-ma">M&amp;A</span></template>
						<template x-if="e.event_type === 'ipo'"><span class="gl-event-badge gl-badge-ipo">IPO</span></template>
						<template x-if="e.event_type === 'shutdown'"><span class="gl-event-badge gl-badge-shutdown">Shutdown</span></template>
						<template x-if="e.event_type === 'bootstrapped'"><span class="gl-event-badge gl-badge-bootstrapped">Bootstrapped</span></template>
					</a>
					<span class="gl-card-date" x-text="e.date"></span>
				</div>
				<div class="gl-card-meta" x-show="e.category || e.stage">
					<span x-show="e.category" x-text="e.category"></span>
					<template x-if="e.category && e.stage"><span class="gl-meta-sep">·</span></template>
					<span x-show="e.stage" x-text="e.stage"></span>
				</div>
				<div class="gl-card-numbers">
					<span x-show="e.amount_m > 0">
						<strong x-text="fmtUsd(e.amount_m)"></strong> raised
					</span>
					<template x-if="e.amount_m === 0 && e.amount_disp">
						<span :title="e.amount_disp" class="gl-num-soft" x-text="truncate(e.amount_disp, 24)"></span>
					</template>
					<template x-if="e.val_m > 0">
						<span><span class="gl-num-sep">·</span> <strong x-text="fmtUsd(e.val_m)"></strong> valuation</span>
					</template>
					<template x-if="e.val_m === 0 && e.val_disp">
						<span><span class="gl-num-sep">·</span><span :title="e.val_disp" class="gl-num-soft" x-text="truncate(e.val_disp, 28)"></span></span>
					</template>
					<template x-if="e.total_fmt">
						<span><span class="gl-num-sep">·</span><span x-text="e.total_fmt"></span> total raised</span>
					</template>
				</div>
				<p class="gl-card-note" x-show="e.note" x-text="e.note"></p>
				<div class="gl-card-source" x-show="e.source">
					<a :href="e.source" target="_blank" rel="noopener nofollow">Source ↗</a>
				</div>
			</article>
		</template>
		<p x-show="sortedFiltered.length === 0" style="text-align:center;padding:60px;color:var(--gl-text-muted);">No events match these filters.</p>
	</div>
</section>

<?php if ( $publics ) : ?>
<section class="glhp-boxed" style="padding:24px;max-width:1200px;margin:0 auto 24px;">
	<h3 style="margin:0 0 12px;font-size:.85rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gl-text-muted);">Public companies in our coverage</h3>
	<div class="gl-public-row">
		<?php foreach ( $publics as $p ) : ?>
			<a href="<?php echo esc_url( $p['url'] ); ?>" class="gl-public-pill">
				<strong><?php echo esc_html( $p['company'] ); ?></strong>
				<span><?php echo esc_html( $p['val_m'] > 0 ? gtmlens_fmt_usd_m( $p['val_m'] ) : ( $p['val_disp'] ?: '—' ) ); ?> mkt cap</span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<section class="glhp-boxed" style="padding:24px;max-width:760px;margin:0 auto 60px;">
	<?php echo gtmlens_newsletter_form( 'funding-tracker', 'Get the monthly funding digest', 'One email per month: every GTM round, M&A, and IPO from the prior 30 days, with one-line analyst notes. No fluff.' ); ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
window.GTMLENS_FUNDING_DATA = <?php echo wp_json_encode( $events ); ?>;
function gtmlensFundingV2() {
	return {
		events: window.GTMLENS_FUNDING_DATA || [],
		q: '', cat: '', stage: '', quarter: '', sort: 'date_desc',
		init() {
			// Read filter state from URL
			const params = new URLSearchParams(location.search);
			this.q       = params.get('q')       || '';
			this.cat     = params.get('cat')     || '';
			this.stage   = params.get('stage')   || '';
			this.quarter = params.get('quarter') || '';
			this.sort    = params.get('sort')    || 'date_desc';
			// Watch all filters and persist to URL (replaceState — no history clutter)
			['q','cat','stage','quarter','sort'].forEach(k => {
				this.$watch(k, () => this.syncUrl());
			});
		},
		syncUrl() {
			const params = new URLSearchParams();
			if (this.q)       params.set('q', this.q);
			if (this.cat)     params.set('cat', this.cat);
			if (this.stage)   params.set('stage', this.stage);
			if (this.quarter) params.set('quarter', this.quarter);
			if (this.sort && this.sort !== 'date_desc') params.set('sort', this.sort);
			const qs = params.toString();
			history.replaceState(null, '', location.pathname + (qs ? '?' + qs : ''));
		},
		fmtUsd(m) {
			if (!m || m <= 0) return '—';
			if (m >= 1000) return '$' + (m/1000).toFixed(1) + 'B';
			return '$' + Number(m).toLocaleString() + 'M';
		},
		truncate(s, n) {
			if (!s) return '';
			return s.length > n ? s.slice(0, n - 1) + '…' : s;
		},
		quarterOf(date) {
			if (!date) return '';
			const y = +date.slice(0,4), mo = +date.slice(5,7);
			return 'Q' + Math.ceil(mo/3) + ' ' + y;
		},
		get filtered() {
			const q = this.q.toLowerCase().trim();
			return this.events.filter(e => {
				if (q && !(e.company || '').toLowerCase().includes(q)) return false;
				if (this.cat   && e.category !== this.cat)   return false;
				if (this.stage && e.stage    !== this.stage) return false;
				if (this.quarter && this.quarterOf(e.date) !== this.quarter) return false;
				return true;
			});
		},
		get sortedFiltered() {
			const list = [...this.filtered];
			if (this.sort === 'amount_desc') list.sort((a,b) => (b.amount_m||0) - (a.amount_m||0));
			else if (this.sort === 'val_desc') list.sort((a,b) => (b.val_m||0) - (a.val_m||0));
			else list.sort((a,b) => (b.date||'').localeCompare(a.date||''));
			return list;
		}
	};
}
</script>

<?php get_footer(); ?>
