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

// Aggregate views (quarter totals, sparkline, pace) exclude Foundation Models —
// a single FM mega-round (e.g. Anthropic) otherwise swamps GTM-tool capital and
// distorts quarter-over-quarter pace. The detailed rolling list keeps them.
// FM detection matches the category tag OR the company name — some foundation-model
// event records (e.g. an older OpenAI/Anthropic round) ship without the category set.
if ( ! function_exists( 'gtmlens_event_is_fm' ) ) {
	function gtmlens_event_is_fm( $e ) {
		if ( isset( $e['category'] ) && 'Foundation Models' === $e['category'] ) return true;
		$co = isset( $e['company'] ) ? $e['company'] : '';
		return (bool) preg_match( '/Anthropic|OpenAI|\bGPT\b|Gemini|Mistral|DeepMind/i', $co );
	}
}
$events_gtm = array_values( array_filter( $events, function ( $e ) {
	return ! gtmlens_event_is_fm( $e );
} ) );

$dates      = array_filter( array_column( $events, 'date' ) );
$last_mod   = $dates ? max( $dates ) : '';
$buckets    = gtmlens_bucket_events_by_quarter( $events_gtm );
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
$spark = gtmlens_build_sparkline_buckets( $events_gtm );
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
		Rolling list of funding rounds, M&amp;A, and IPOs across the AI-native GTM stack. Independent. No paywall. Sourced from press releases, SEC filings, and Crunchbase. <span style="opacity:.85">Methodology: rolling 7/30/90-day and YTD windows are bounded by event (announcement) date; capital totals include venture rounds, M&amp;A, and IPOs; valuations are post-money where disclosed; the quarter pace metric compares the current quarter&#8217;s daily capital run-rate against the prior full quarter.</span>
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
<div class="gl-spark-legend"><span class="gl-leg gl-leg--round">Rounds</span><span class="gl-leg gl-leg--ma">M&amp;A</span><span class="gl-leg gl-leg--ipo">IPO</span></div>
		<div class="gl-spark-bars">
			<?php
/* gl-qbk-buckets: per-quarter type breakdown + $ raised */
$gl_qbk = array();
foreach ( $events_gtm as $glev ) {
  $gld = isset( $glev['date'] ) ? $glev['date'] : '';
  if ( ! $gld || strlen( $gld ) < 7 ) continue;
  $gly = (int) substr( $gld, 0, 4 );
  $glm = (int) substr( $gld, 5, 2 );
  $glq = (int) ceil( $glm / 3 );
  $gllbl = sprintf( 'Q%d %d', $glq, $gly );
  if ( ! isset( $gl_qbk[ $gllbl ] ) ) $gl_qbk[ $gllbl ] = array( 'rounds'=>0, 'ma'=>0, 'ipo'=>0, 'total_m'=>0, 'count'=>0 );
  $glt = isset( $glev['event_type'] ) ? strtolower( (string) $glev['event_type'] ) : '';
  if ( strpos( $glt, 'acqu' ) !== false || $glt === 'ma' || $glt === 'merger' ) $gl_qbk[ $gllbl ]['ma']++;
  elseif ( $glt === 'ipo' || $glt === 'public' || strpos( $glt, 'ipo' ) !== false ) $gl_qbk[ $gllbl ]['ipo']++;
  else $gl_qbk[ $gllbl ]['rounds']++;
  $gl_qbk[ $gllbl ]['count']++;
  $gl_qbk[ $gllbl ]['total_m'] += (float) ( isset( $glev['amount_m'] ) ? $glev['amount_m'] : 0 );
}
$gl_fmt_qm = function( $m ) { if ( $m >= 1000 ) return '$' . number_format( $m / 1000, 1 ) . 'B'; if ( $m >= 1 ) return '$' . number_format( $m ) . 'M'; return ''; };
$gl_now_y = (int) date( 'Y' );
$gl_now_q = (int) ceil( ( (int) date( 'n' ) ) / 3 );
$spark_cap_max = 0; foreach ( $spark as $gl_cmx ) { $gl_cmv = isset( $gl_qbk[ $gl_cmx['label'] ] ) ? (float) $gl_qbk[ $gl_cmx['label'] ]['total_m'] : 0; if ( $gl_cmv > $spark_cap_max ) { $spark_cap_max = $gl_cmv; } }
foreach ( $spark as $b ) :
  $gl_bkt = isset( $gl_qbk[ $b['label'] ] ) ? $gl_qbk[ $b['label'] ] : array( 'rounds'=>0, 'ma'=>0, 'ipo'=>0, 'total_m'=>0, 'count'=>$b['count'] );
  $gl_cap_m_b = isset( $gl_qbk[ $b['label'] ] ) ? (float) $gl_qbk[ $b['label'] ]['total_m'] : 0; $gl_total_h = (int) round( ( log10( $gl_cap_m_b + 1 ) / log10( max( 10, $spark_cap_max ) + 1 ) ) * 56 );
  if ( $gl_total_h < 4 ) $gl_total_h = 4;
  $gl_rounds_h = $gl_bkt['count'] > 0 ? (int) round( ( $gl_bkt['rounds'] / $gl_bkt['count'] ) * $gl_total_h ) : $gl_total_h;
  $gl_ma_h     = $gl_bkt['count'] > 0 ? (int) round( ( $gl_bkt['ma']     / $gl_bkt['count'] ) * $gl_total_h ) : 0;
  $gl_ipo_h    = $gl_total_h - $gl_rounds_h - $gl_ma_h; if ( $gl_ipo_h < 0 ) $gl_ipo_h = 0;
  $gl_lbl_short = substr( $b['label'], 0, 2 );
  $gl_yr_short  = substr( $b['label'], -2 );
  $gl_is_current = ( (int) ('20' . $gl_yr_short) === $gl_now_y && (int) substr( $gl_lbl_short, 1, 1 ) === $gl_now_q );
  $gl_title = $b['label'] . ' — ' . $gl_bkt['count'] . ' events';
  if ( $gl_bkt['total_m'] > 0 ) $gl_title .= ', ' . call_user_func( $gl_fmt_qm, $gl_bkt['total_m'] );
  $gl_title .= ' (' . $gl_bkt['rounds'] . ' rounds';
  if ( $gl_bkt['ma'] > 0 ) $gl_title .= ', ' . $gl_bkt['ma'] . ' M&A';
  if ( $gl_bkt['ipo'] > 0 ) $gl_title .= ', ' . $gl_bkt['ipo'] . ' IPO';
  $gl_title .= ')';
?>
<a class="gl-spark-col<?php echo $gl_is_current ? ' gl-spark-col--now' : ''; ?>" href="#gl-funding-feed-anchor" title="<?php echo esc_attr( $gl_title ); ?>">
  <div class="gl-spark-amt"><?php echo esc_html( call_user_func( $gl_fmt_qm, $gl_bkt['total_m'] ) ); ?></div>
  <div class="gl-spark-stack" style="height:<?php echo $gl_total_h; ?>px;">
    <?php if ( $gl_ipo_h > 0 ) : ?><div class="gl-spark-seg gl-spark-seg--ipo" style="height:<?php echo $gl_ipo_h; ?>px;"></div><?php endif; ?>
    <?php if ( $gl_ma_h > 0 ) : ?><div class="gl-spark-seg gl-spark-seg--ma" style="height:<?php echo $gl_ma_h; ?>px;"></div><?php endif; ?>
    <?php if ( $gl_rounds_h > 0 ) : ?><div class="gl-spark-seg gl-spark-seg--round" style="height:<?php echo $gl_rounds_h; ?>px;"></div><?php endif; ?>
  </div>
  <div class="gl-spark-qlbl"><?php echo esc_html( $gl_lbl_short ); ?> <span class="gl-spark-yr">'<?php echo esc_html( $gl_yr_short ); ?></span></div>
  <div class="gl-spark-count"><?php echo (int) $gl_bkt['count']; ?> events</div>
</a>
<?php endforeach; ?>
</div>
<?php endif; ?>

		</div>
<?php
/* gl-spark-pace: current-quarter run-rate vs prior */
$gl_now_ts = current_time( 'timestamp' );
$gl_cq = $gl_now_q; $gl_cy = $gl_now_y;
$gl_pq = ( $gl_cq > 1 ) ? $gl_cq - 1 : 4;
$gl_py = ( $gl_cq > 1 ) ? $gl_cy : $gl_cy - 1;
$gl_curr_lbl_full  = sprintf( 'Q%d %d', $gl_cq, $gl_cy );
$gl_prior_lbl_full = sprintf( 'Q%d %d', $gl_pq, $gl_py );
$gl_q_start_m  = ( $gl_cq - 1 ) * 3 + 1;
$gl_q_start_ts = mktime( 0, 0, 0, $gl_q_start_m, 1, $gl_cy );
$gl_days_in   = max( 1, (int) floor( ( $gl_now_ts - $gl_q_start_ts ) / 86400 ) + 1 );
$gl_curr_b  = isset( $gl_qbk[ $gl_curr_lbl_full ] )  ? $gl_qbk[ $gl_curr_lbl_full ]  : array('count'=>0,'total_m'=>0);
$gl_prior_b = isset( $gl_qbk[ $gl_prior_lbl_full ] ) ? $gl_qbk[ $gl_prior_lbl_full ] : array('count'=>0,'total_m'=>0);
$gl_curr_rate_m  = $gl_curr_b['total_m']  / $gl_days_in;
$gl_prior_rate_m = $gl_prior_b['total_m'] / 90;
$gl_pace_pct = ( $gl_prior_rate_m > 0 ) ? (int) round( ( ( $gl_curr_rate_m - $gl_prior_rate_m ) / $gl_prior_rate_m ) * 100 ) : null;
$gl_prior_lbl_short = sprintf( "Q%d '%02d", $gl_pq, $gl_py % 100 );
$gl_curr_lbl_short  = sprintf( "Q%d '%02d", $gl_cq, $gl_cy % 100 );
if ( $gl_pace_pct !== null ) :
  $gl_dir = $gl_pace_pct > 0 ? 'up' : ( $gl_pace_pct < 0 ? 'down' : 'flat' );
  $gl_dir_word = $gl_pace_pct > 0 ? 'above' : ( $gl_pace_pct < 0 ? 'below' : 'tracking' );
?>
<div class="gl-spark-pace">
  <strong><?php echo esc_html( $gl_curr_lbl_short ); ?> pace:</strong>
  <?php if ( $gl_pace_pct === 0 ) : ?>
    tracking even with <?php echo esc_html( $gl_prior_lbl_short ); ?> daily run-rate (<?php echo (int) $gl_days_in; ?> day<?php echo $gl_days_in === 1 ? '' : 's'; ?> in)
  <?php else : ?>
    <span class="gl-pace-pct gl-pace-pct--<?php echo $gl_dir; ?>"><?php echo ( $gl_pace_pct > 0 ? '&#9650; ' : '&#9660; ' ) . abs( (int) $gl_pace_pct ); ?>%</span>
    <?php echo esc_html( $gl_dir_word ); ?> <?php echo esc_html( $gl_prior_lbl_short ); ?> daily run-rate
    <span class="gl-pace-meta">&middot; <?php echo (int) $gl_days_in; ?> day<?php echo $gl_days_in === 1 ? '' : 's'; ?> in &middot; <?php echo esc_html( call_user_func( $gl_fmt_qm, $gl_curr_b['total_m'] ) ); ?> tracked</span>
  <?php endif; ?>

	</div>
</section>
<?php endif; ?>

<?php if ( $spark ) : ?>
<section class="glhp-boxed" style="padding:8px 24px 24px;max-width:1200px;margin:0 auto;">
	<?php
	/* === P20: Funding tracker highlight pulse + top-5 rounds === */
	$gl_all_events = isset( $events ) && is_array( $events ) ? $events : ( function_exists( 'gtmlens_get_funding_events' ) ? gtmlens_get_funding_events() : array() );
	$gl_now_ts = current_time( 'timestamp' );
	$gl_win = isset( $_GET['win'] ) ? sanitize_text_field( $_GET['win'] ) : '90d';
	if ( ! in_array( $gl_win, array( '7d', '30d', '90d', 'ytd' ), true ) ) $gl_win = '90d';
	$gl_win_days = ( $gl_win === '7d' ) ? 7 : ( ( $gl_win === '90d' ) ? 90 : 30 );
	$gl_win_label = ( $gl_win === '7d' ) ? 'last 7d' : ( ( $gl_win === '90d' ) ? 'last 90d' : ( ( $gl_win === 'ytd' ) ? 'YTD' : 'last 30d' ) );
	$gl_win_prior_label = ( $gl_win === '7d' ) ? 'prior 7d' : ( ( $gl_win === '90d' ) ? 'prior 90d' : ( ( $gl_win === 'ytd' ) ? '' : 'prior 30d' ) );
	if ( $gl_win === 'ytd' ) {
		$gl_30 = mktime( 0, 0, 0, 1, 1, (int) date( 'Y', $gl_now_ts ) );
		$gl_60 = mktime( 0, 0, 0, 1, 1, (int) date( 'Y', $gl_now_ts ) - 1 );
	} else {
		$gl_30 = $gl_now_ts - $gl_win_days * DAY_IN_SECONDS;
		$gl_60 = $gl_now_ts - 2 * $gl_win_days * DAY_IN_SECONDS;
	}
	$gl_90 = $gl_now_ts - 90 * DAY_IN_SECONDS;
	$gl_rounds_30 = 0; $gl_cap_30 = 0; $gl_cap_30_gtm = 0; $gl_rounds_60_30 = 0; $gl_cap_60_30 = 0; $gl_cap_60_30_gtm = 0;
	$gl_round_pool = array();
	foreach ( $gl_all_events as $ev ) {
		$type = isset( $ev['event_type'] ) ? $ev['event_type'] : '';
		if ( 'round' !== $type && 'funding' !== $type ) continue;
		$dt = isset( $ev['date'] ) ? strtotime( $ev['date'] ) : 0;
		if ( ! $dt ) continue;
		$amt = isset( $ev['amount_m'] ) ? (float) $ev['amount_m'] : 0;
		$gl_is_fm = gtmlens_event_is_fm( $ev );
		if ( $dt >= $gl_30 ) { $gl_rounds_30++; $gl_cap_30 += $amt; if ( ! $gl_is_fm ) { $gl_cap_30_gtm += $amt; } }
		elseif ( $dt >= $gl_60 ) { $gl_rounds_60_30++; $gl_cap_60_30 += $amt; if ( ! $gl_is_fm ) { $gl_cap_60_30_gtm += $amt; } }
		if ( $dt >= $gl_90 ) $gl_round_pool[] = $ev;
	}
	usort( $gl_round_pool, function( $a, $b ) {
		$av = isset( $a['date'] ) ? strtotime( $a['date'] ) : 0;
		$bv = isset( $b['date'] ) ? strtotime( $b['date'] ) : 0;
		if ( $av === $bv ) return 0;
		return $bv > $av ? 1 : -1;
	} );
	$gl_top5 = array_slice( $gl_round_pool, 0, 5 );
	$gl_delta_rounds = $gl_rounds_60_30 > 0 ? round( ( $gl_rounds_30 - $gl_rounds_60_30 ) / max( 1, $gl_rounds_60_30 ) * 100 ) : null;
	$gl_delta_cap = $gl_cap_60_30 > 0 ? round( ( $gl_cap_30 - $gl_cap_60_30 ) / max( 1, $gl_cap_60_30 ) * 100 ) : null;
	$gl_delta_cap_gtm = $gl_cap_60_30_gtm > 0 ? round( ( $gl_cap_30_gtm - $gl_cap_60_30_gtm ) / max( 1, $gl_cap_60_30_gtm ) * 100 ) : null;
	$gl_fmt_m = function( $m ) { if ( $m >= 1000 ) return '$' . number_format( $m / 1000, 1 ) . 'B'; return '$' . number_format( $m ) . 'M'; };
	$gl_delta_chip = function( $d, $cur = 0 ) {
		if ( $d === null ) {
			if ( $cur > 0 ) return '<span class="gl-delta gl-delta--new">new</span>';
			return '';
		}
		if ( $d > 0 ) return '<span class="gl-delta gl-delta--up">&#9650; ' . $d . '%</span>';
		if ( $d < 0 ) return '<span class="gl-delta gl-delta--down">&#9660; ' . abs( $d ) . '%</span>';
		return '<span class="gl-delta gl-delta--flat">&middot; 0%</span>';
	};
	?>
	<section class="gl-funding-pulse" aria-label="Last 30 days at a glance">
		<div class="gl-pulse-window-toggle" role="tablist" aria-label="Time window">
	<?php foreach ( array( '7d'=>'7d', '30d'=>'30d', '90d'=>'90d', 'ytd'=>'YTD' ) as $wkey => $wlabel ) :
		$wurl = add_query_arg( array( 'win' => $wkey ), remove_query_arg( 'win' ) );
		$wact = ( $wkey === $gl_win ); ?>
		<a href="<?php echo esc_url( $wurl ); ?>" class="gl-pulse-window-chip<?php echo $wact ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo $wact ? 'true' : 'false'; ?>"><?php echo esc_html( $wlabel ); ?></a>
	<?php endforeach; ?>
</div>
<div class="gl-pulse-grid">
			<div class="gl-pulse-card">
				<div class="gl-pulse-label">Rounds &middot; <?php echo esc_html( $gl_win_label ); ?></div>
				<div class="gl-pulse-num"><?php echo (int) $gl_rounds_30; ?> <?php echo $gl_delta_chip( $gl_delta_rounds, $gl_rounds_30 ); ?></div>
				<?php if ( $gl_rounds_60_30 > 0 ) : ?><div class="gl-pulse-sub">vs <?php echo (int) $gl_rounds_60_30; ?> in <?php echo esc_html( $gl_win_prior_label ); ?></div><?php else : ?><div class="gl-pulse-sub">no rounds in <?php echo esc_html( $gl_win_prior_label ?: "prior window" ); ?></div><?php endif; ?>
			</div>
			<div class="gl-pulse-card">
				<div class="gl-pulse-label">GTM capital &middot; <?php echo esc_html( $gl_win_label ); ?></div>
				<div class="gl-pulse-num"><?php echo esc_html( $gl_fmt_m( $gl_cap_30_gtm ) ); ?> <?php echo $gl_delta_chip( $gl_delta_cap_gtm, $gl_cap_30_gtm ); ?></div>
				<?php if ( $gl_cap_30 > $gl_cap_30_gtm ) : ?><div class="gl-pulse-sub">incl. foundation models: <?php echo esc_html( $gl_fmt_m( $gl_cap_30 ) ); ?></div><?php endif; ?><?php if ( $gl_cap_60_30_gtm > 0 ) : ?><div class="gl-pulse-sub">vs <?php echo esc_html( $gl_fmt_m( $gl_cap_60_30_gtm ) ); ?> in <?php echo esc_html( $gl_win_prior_label ); ?></div><?php else : ?><div class="gl-pulse-sub">no GTM rounds in <?php echo esc_html( $gl_win_prior_label ?: "prior window" ); ?></div><?php endif; ?>
			</div>
			<?php if ( ! empty( $gl_top5 ) ) : ?>
			<div class="gl-pulse-top5">
				<div class="gl-pulse-label">Recent rounds &middot; last 90d</div>
				<ol class="gl-top5-list">
				<?php foreach ( $gl_top5 as $glr_i => $r ) :
  $co = isset( $r['company'] ) ? $r['company'] : ( isset( $r['label'] ) ? $r['label'] : '' );
  $amt = isset( $r['amount_m'] ) ? (float) $r['amount_m'] : 0;
  $dt = isset( $r['date'] ) ? strtotime( $r['date'] ) : 0;
  $url = isset( $r['url'] ) ? $r['url'] : '';
  $stage = isset( $r['stage'] ) ? $r['stage'] : '';
  $valm = isset( $r['val_m'] ) ? (float) $r['val_m'] : 0;
?>
<li class="gl-top5-item gl-top5-item--rank-<?php echo (int) ( $glr_i + 1 ); ?>">
  <?php if ( $url ) : ?><a href="<?php echo esc_url( $url ); ?>"><?php endif; ?>
    <span class="gl-top5-co"><?php echo esc_html( $co ); ?></span>
    <span class="gl-top5-amt"><?php echo esc_html( $gl_fmt_m( $amt ) ); ?></span>
    <?php if ( $stage ) : ?><span class="gl-top5-stage"><?php echo esc_html( $stage ); ?></span><?php endif; ?>
    <?php if ( $valm > 0 ) : ?><span class="gl-top5-val">&rarr; <?php echo esc_html( $gl_fmt_m( $valm ) ); ?></span><?php endif; ?>
    <?php if ( $dt ) : ?><span class="gl-top5-dt"><?php echo esc_html( date_i18n( 'M j', $dt ) ); ?></span><?php endif; ?>
  <?php if ( $url ) : ?></a><?php endif; ?>
</li>
<?php endforeach; ?>
				</ol>
			</div>
			<?php endif; ?>
		</div>
	
<?php
/* Stage mix across last 30d rounds */
$gl_stage_buckets = array( 'Seed'=>0, 'A'=>0, 'B'=>0, 'C'=>0, 'D+'=>0, 'Other'=>0 );
$gl_stage_total_m = 0;
foreach ( $gl_all_events as $sev ) {
  $std = isset( $sev['date'] ) ? strtotime( $sev['date'] ) : 0;
  if ( ! $std || $std < $gl_30 ) continue;
  $st = isset( $sev['event_type'] ) ? strtolower( (string) $sev['event_type'] ) : '';
  if ( $st !== 'round' && $st !== 'funding' ) continue;
  if ( isset( $sev['category'] ) && 'Foundation Models' === $sev['category'] ) continue;
  $stage = isset( $sev['stage'] ) ? strtolower( (string) $sev['stage'] ) : '';
  $bucket = 'Other';
  if ( strpos( $stage, 'seed' ) !== false || strpos( $stage, 'pre' ) !== false ) $bucket = 'Seed';
  elseif ( strpos( $stage, ' a' ) !== false || $stage === 'series a' || strpos( $stage, 'series a' ) !== false ) $bucket = 'A';
  elseif ( strpos( $stage, ' b' ) !== false || strpos( $stage, 'series b' ) !== false ) $bucket = 'B';
  elseif ( strpos( $stage, ' c' ) !== false || strpos( $stage, 'series c' ) !== false ) $bucket = 'C';
  elseif ( strpos( $stage, ' d' ) !== false || strpos( $stage, ' e' ) !== false || strpos( $stage, ' f' ) !== false || strpos( $stage, 'growth' ) !== false ) $bucket = 'D+';
  $amt = (float) ( isset( $sev['amount_m'] ) ? $sev['amount_m'] : 0 );
  $gl_stage_buckets[ $bucket ] += $amt;
  $gl_stage_total_m += $amt;
}
if ( $gl_stage_total_m > 0 ) : ?>
<div class="gl-stage-mix">
  <div class="gl-stage-mix__label">Stage mix &middot; <?php echo esc_html( $gl_win_label ); ?> (by capital, ex-foundation models)</div>
  <div class="gl-stage-mix__bar">
    <?php foreach ( $gl_stage_buckets as $sk => $sv ) : if ( $sv <= 0 ) continue; $pct = ( $sv / $gl_stage_total_m ) * 100; ?>
      <div class="gl-stage-mix__seg gl-stage-mix__seg--<?php echo esc_attr( strtolower( str_replace( '+', 'plus', $sk ) ) ); ?>" style="width:<?php echo round( $pct, 1 ); ?>%" title="<?php echo esc_attr( $sk . ': ' . round( $pct ) . '% (' . call_user_func( $gl_fmt_m, $sv ) . ')' ); ?>"></div>
    <?php endforeach; ?>
  </div>
  <div class="gl-stage-mix__legend">
    <?php foreach ( $gl_stage_buckets as $sk => $sv ) : if ( $sv <= 0 ) continue; $pct = ( $sv / $gl_stage_total_m ) * 100; ?>
      <span class="gl-stage-mix__legitem gl-stage-mix__legitem--<?php echo esc_attr( strtolower( str_replace( '+', 'plus', $sk ) ) ); ?>"><?php echo esc_html( $sk ); ?> <?php echo round( $pct ); ?>%</span>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
</section>
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

	<div id="gl-funding-feed-anchor"></div>
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
