<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Collect all vendor_category terms for the filter chips
$all_categories = get_terms( array(
	'taxonomy'   => 'vendor_category',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
) );

// Active filter from query string (sanitized)
$active_slug = isset( $_GET['category'] ) ? sanitize_key( $_GET['category'] ) : '';

// P21: view + sort URL state
$gl_view = ( isset( $_GET['view'] ) && $_GET['view'] === 'table' ) ? 'table' : 'grid';
$gl_sort = isset( $_GET['sort'] ) ? sanitize_key( $_GET['sort'] ) : 'name';
$allowed_sort = array( 'name', 'founded', 'stage', 'raised', 'valuation', 'last_round' );
if ( ! in_array( $gl_sort, $allowed_sort, true ) ) { $gl_sort = 'name'; }
$gl_dir = ( isset( $_GET['dir'] ) && $_GET['dir'] === 'desc' ) ? 'desc' : 'asc';

// Pull ALL vendors
$all_vendors = get_posts( array(
	'post_type'      => 'vendor',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'title',
	'order'          => 'ASC',
) );

// P21: precompute sort values for table view
if ( $gl_view === 'table' && $all_vendors ) {
	$stage_rank = array( 'Bootstrapped' => 1, 'Pre-Seed' => 2, 'Seed' => 3, 'Series A' => 4, 'Series B' => 5, 'Series C' => 6, 'Series D' => 7, 'Series D+' => 7, 'Series E' => 8, 'Series F' => 9, 'Growth' => 10, 'Acquired' => 11, 'Public' => 12, 'IPO' => 12 );
	$sort_pairs = array();
	foreach ( $all_vendors as $v ) {
		$pid = $v->ID;
		$val = 0;
		if ( $gl_sort === 'founded' ) { $val = (int) get_field( 'founded', $pid ); }
		elseif ( $gl_sort === 'raised' ) { $val = (int) get_field( 'total_raised_usd_m', $pid ); }
		elseif ( $gl_sort === 'valuation' ) { $val = (int) get_field( 'last_valuation_usd_m', $pid ); }
		elseif ( $gl_sort === 'stage' ) { $stg = (string) get_field( 'funding_stage', $pid ); $val = isset( $stage_rank[ $stg ] ) ? $stage_rank[ $stg ] : 0; }
		elseif ( $gl_sort === 'last_round' ) { $val = (int) strtotime( (string) get_field( 'last_round_date', $pid ) ); }
		else { $val = strtolower( $v->post_title ); }
		$sort_pairs[] = array( 'val' => $val, 'v' => $v );
	}
	usort( $sort_pairs, function( $a, $b ) {
		$av = $a['val']; $bv = $b['val'];
		if ( is_string( $av ) || is_string( $bv ) ) return strcmp( (string) $av, (string) $bv );
		if ( $av == $bv ) return strcmp( $a['v']->post_title, $b['v']->post_title );
		return ( $av < $bv ) ? -1 : 1;
	} );
	if ( $gl_dir === 'desc' ) { $sort_pairs = array_reverse( $sort_pairs ); }
	$all_vendors = array_map( function( $p ) { return $p['v']; }, $sort_pairs );
}

if ( ! function_exists( 'gtmlens_p21_compare_pairs' ) ) {
	function gtmlens_p21_compare_pairs( $a, $b ) {
		$av = $a['val']; $bv = $b['val'];
		if ( is_string( $av ) || is_string( $bv ) ) { return strcmp( (string) $av, (string) $bv ); }
		if ( $av == $bv ) { return strcmp( $a['v']->post_title, $b['v']->post_title ); }
		return ( $av < $bv ) ? -1 : 1;
	}
}

function gtmlens_p21_fmt_m( $m ) {
	if ( $m >= 1000 ) { return '$' . number_format( $m / 1000, 1 ) . 'B'; }
	if ( $m > 0 ) { return '$' . number_format( $m ) . 'M'; }
	return '<span class="gl-empty">&mdash;</span>';
}

function gtmlens_p21_sort_url( $key, $current_sort, $current_dir ) {
	$new_dir = ( $current_sort === $key && $current_dir === 'asc' ) ? 'desc' : 'asc';
	return esc_url( add_query_arg( array( 'view' => 'table', 'sort' => $key, 'dir' => $new_dir ) ) );
}

function gtmlens_p21_sort_icon( $key, $current_sort, $current_dir ) {
	if ( $current_sort !== $key ) { return '<span class="gl-sort-icon gl-sort-icon--idle">&#8645;</span>'; }
	return $current_dir === 'asc' ? '<span class="gl-sort-icon gl-sort-icon--active">&#9650;</span>' : '<span class="gl-sort-icon gl-sort-icon--active">&#9660;</span>';
}
?>

<div class="gl-section">
	<h1><?php esc_html_e( 'GTM Vendors', 'gtmlens-child' ); ?></h1>
	<p style="color:var(--gl-text-muted);max-width:640px;">
		<?php esc_html_e( 'Independent profiles on every major vendor in the AI-native GTM stack — rated without affiliate incentives or vendor funding.', 'gtmlens-child' ); ?>
	</p>

	<?php if ( $all_categories && ! is_wp_error( $all_categories ) ) : ?>
		<nav class="gl-filter-chips" aria-label="<?php esc_attr_e( 'Filter by category', 'gtmlens-child' ); ?>">
			<a class="gl-chip<?php echo '' === $active_slug ? ' gl-chip--active' : ''; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'vendor' ) ); ?>"><?php esc_html_e( 'All', 'gtmlens-child' ); ?></a>
			<?php foreach ( $all_categories as $cat ) : ?>
				<a class="gl-chip<?php echo $active_slug === $cat->slug ? ' gl-chip--active' : ''; ?>" href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<div class="gl-view-toggle" role="tablist" aria-label="View as">
		<a href="<?php echo esc_url( remove_query_arg( array( 'view', 'sort', 'dir' ) ) ); ?>" class="gl-view-chip<?php echo $gl_view === 'grid' ? ' is-active' : ''; ?>">Grid</a>
		<a href="<?php echo esc_url( add_query_arg( array( 'view' => 'table' ), remove_query_arg( array( 'sort', 'dir' ) ) ) ); ?>" class="gl-view-chip<?php echo $gl_view === 'table' ? ' is-active' : ''; ?>">Table</a>
	</div>

	<?php if ( $all_vendors ) : ?>
		<p style="color:var(--gl-text-muted);font-size:.85rem;margin:8px 0 24px;"><?php printf( esc_html__( 'Showing all %d vendors.', 'gtmlens-child' ), count( $all_vendors ) ); ?></p>

		<?php if ( $gl_view === 'table' ) : ?>
			<div class="gl-vendor-table-wrap">
			<table class="gl-vendor-table">
				<thead><tr>
					<th class="gl-th"><a href="<?php echo gtmlens_p21_sort_url( 'name', $gl_sort, $gl_dir ); ?>">Vendor <?php echo gtmlens_p21_sort_icon( 'name', $gl_sort, $gl_dir ); ?></a></th>
					<th class="gl-th">Category</th>
					<th class="gl-th gl-th--num"><a href="<?php echo gtmlens_p21_sort_url( 'founded', $gl_sort, $gl_dir ); ?>">Founded <?php echo gtmlens_p21_sort_icon( 'founded', $gl_sort, $gl_dir ); ?></a></th>
					<th class="gl-th"><a href="<?php echo gtmlens_p21_sort_url( 'stage', $gl_sort, $gl_dir ); ?>">Stage <?php echo gtmlens_p21_sort_icon( 'stage', $gl_sort, $gl_dir ); ?></a></th>
					<th class="gl-th gl-th--num"><a href="<?php echo gtmlens_p21_sort_url( 'raised', $gl_sort, $gl_dir ); ?>">Total raised <?php echo gtmlens_p21_sort_icon( 'raised', $gl_sort, $gl_dir ); ?></a></th>
					<th class="gl-th gl-th--num"><a href="<?php echo gtmlens_p21_sort_url( 'valuation', $gl_sort, $gl_dir ); ?>">Valuation <?php echo gtmlens_p21_sort_icon( 'valuation', $gl_sort, $gl_dir ); ?></a></th>
					<th class="gl-th"><a href="<?php echo gtmlens_p21_sort_url( 'last_round', $gl_sort, $gl_dir ); ?>">Last round <?php echo gtmlens_p21_sort_icon( 'last_round', $gl_sort, $gl_dir ); ?></a></th>
				</tr></thead>
				<tbody>
				<?php foreach ( $all_vendors as $tv ) :
					$tpid = $tv->ID;
					$tcat = get_the_terms( $tpid, 'vendor_category' );
					$tcat_name = ( $tcat && ! is_wp_error( $tcat ) ) ? $tcat[0]->name : '';
					$tfnd = (int) get_field( 'founded', $tpid );
					$tstage = (string) get_field( 'funding_stage', $tpid );
					$tr = (int) get_field( 'total_raised_usd_m', $tpid );
					$tvv = (int) get_field( 'last_valuation_usd_m', $tpid );
					$tlrd = (string) get_field( 'last_round_date', $tpid );
					$tlrm = (int) get_field( 'last_round_size_usd_m', $tpid );
					$tlogo = get_field( 'logo', $tpid );
				?>
				<tr class="gl-vendor-row">
					<td class="gl-td gl-td--name"><a href="<?php echo esc_url( get_permalink( $tpid ) ); ?>" class="gl-vrow-link"><?php if ( $tlogo && ! empty( $tlogo['url'] ) ) : ?><img class="gl-vrow-logo" src="<?php echo esc_url( $tlogo['url'] ); ?>" alt="" loading="lazy" width="24" height="24" /><?php else : ?><span class="gl-vrow-dot" aria-hidden="true"></span><?php endif; ?><span class="gl-vrow-name"><?php echo esc_html( $tv->post_title ); ?></span></a></td>
					<td class="gl-td"><?php echo $tcat_name ? '<span class="gl-pill">' . esc_html( $tcat_name ) . '</span>' : '<span class="gl-empty">&mdash;</span>'; ?></td>
					<td class="gl-td gl-td--num"><?php echo $tfnd ? (int) $tfnd : '<span class="gl-empty">&mdash;</span>'; ?></td>
					<td class="gl-td"><?php echo $tstage ? '<span class="gl-pill gl-pill--stage">' . esc_html( $tstage ) . '</span>' : '<span class="gl-empty">&mdash;</span>'; ?></td>
					<td class="gl-td gl-td--num gl-td--raised"><?php echo gtmlens_p21_fmt_m( $tr ); ?></td>
					<td class="gl-td gl-td--num"><?php echo gtmlens_p21_fmt_m( $tvv ); ?></td>
					<td class="gl-td"><?php if ( $tlrm > 0 || $tlrd ) { $bits = array(); if ( $tlrm > 0 ) { $bits[] = gtmlens_p21_fmt_m( $tlrm ); } if ( $tlrd ) { $ts = strtotime( $tlrd ); if ( $ts ) { $bits[] = '<span class="gl-mute">' . esc_html( date_i18n( 'M Y', $ts ) ) . '</span>'; } } echo implode( ' ', $bits ); } else { echo '<span class="gl-empty">&mdash;</span>'; } ?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			</div>
		<?php else : ?>
			<div class="gl-vendor-grid">
				<?php foreach ( $all_vendors as $vp ) :
				$pid          = $vp->ID;
				$logo         = get_field( 'logo', $pid );
				$pricing_tier = get_field( 'pricing_tier', $pid );
				$cat_terms    = get_the_terms( $pid, 'vendor_category' );
				$cat_name     = ( $cat_terms && ! is_wp_error( $cat_terms ) ) ? $cat_terms[0]->name : '';
				$excerpt      = get_the_excerpt( $pid );
				?>
				<a class="gl-vendor-card" href="<?php echo esc_url( get_permalink( $pid ) ); ?>">
					<?php if ( $logo && ! empty( $logo['url'] ) ) : ?>
						<img
							class="gl-vendor-card__logo"
							src="<?php echo esc_url( $logo['url'] ); ?>"
							alt="<?php echo esc_attr( get_the_title( $pid ) . ' logo' ); ?>"
							width="48" height="48"
							loading="lazy"
						/>
					<?php endif; ?>
					<div class="gl-vendor-card__name"><?php echo esc_html( get_the_title( $pid ) ); ?></div>
					<div class="gl-vendor-card__excerpt"><?php echo esc_html( wp_trim_words( $excerpt, 18 ) ); ?></div>
					<div class="gl-vendor-card__meta">
						<?php if ( $cat_name ) : ?>
							<span><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<?php if ( $pricing_tier ) : ?>
							&nbsp;·&nbsp;<span><?php echo esc_html( $pricing_tier ); ?></span>
						<?php endif; ?>
					</div>
				</a>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No vendors found.', 'gtmlens-child' ); ?></p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
