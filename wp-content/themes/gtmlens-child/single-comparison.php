<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$pid = get_the_ID();

	$vendor_a   = get_field( 'vendor_a', $pid );
	$vendor_b   = get_field( 'vendor_b', $pid );
	$vendor_c   = get_field( 'vendor_c', $pid );
	$verdict    = get_field( 'verdict', $pid );
	$dimensions = get_field( 'winner_by_dimension', $pid );
	$rule_a     = get_field( 'decision_rule_a', $pid );
	$rule_b     = get_field( 'decision_rule_b', $pid );
	$rule_c     = get_field( 'decision_rule_c', $pid );
	$last_upd   = get_field( 'last_updated', $pid );

	$vendors = array_values( array_filter( [ $vendor_a, $vendor_b, $vendor_c ] ) );

	$v_data = [];
	foreach ( $vendors as $v ) {
		$vid = $v->ID;
		$v_data[ $vid ] = [
			'id'      => $vid,
			'name'    => get_the_title( $vid ),
			'pricing' => get_field( 'pricing_tier', $vid ),
			'entry'   => get_field( 'entry_price', $vid ),
			'stage'   => get_field( 'funding_stage', $vid ),
			'raised'  => get_field( 'total_raised', $vid ),
			'val'     => get_field( 'last_valuation', $vid ),
			'segment' => get_field( 'target_segment', $vid ),
			'logo'    => get_field( 'logo', $vid ),
			'founded' => get_field( 'founded', $vid ),
			'category'=> '',
		];
		$cats = get_the_terms( $vid, 'vendor_category' );
		if ( $cats && ! is_wp_error( $cats ) ) {
			$v_data[ $vid ]['category'] = $cats[0]->name;
		}
	}

	$a_id   = $vendor_a ? $vendor_a->ID : null;
	$b_id   = $vendor_b ? $vendor_b->ID : null;
	$a_name = $a_id ? $v_data[ $a_id ]['name'] : '';
	$b_name = $b_id ? $v_data[ $b_id ]['name'] : '';
	$c_id   = $vendor_c ? $vendor_c->ID : null;
	$c_name = $c_id ? $v_data[ $c_id ]['name'] : '';

	// Helper: infer winner direction for a 2-vendor dimension
	$infer_winner = function( $winner_text, $a_name, $b_name ) {
		if ( ! $winner_text ) return 'tie';
		$w = strtolower( $winner_text );
		$an = strtolower( $a_name );
		$bn = strtolower( $b_name );
		if ( $an && strpos( $w, $an ) !== false ) return 'a';
		if ( $bn && strpos( $w, $bn ) !== false ) return 'b';
		if ( strpos( $w, 'tie' ) !== false || strpos( $w, 'even' ) !== false || strpos( $w, 'both' ) !== false ) return 'tie';
		return 'tie';
	};
	?>

	<div class="gl-section">

		<span class="gl-section-eyebrow"><?php esc_html_e( 'Comparison', 'gtmlens-child' ); ?></span>
		<h1 style="margin-top:.25rem;"><?php the_title(); ?></h1>
		<?php gtmlens_last_updated( $pid ); ?>

		<?php if ( count( $vendors ) >= 2 ) : ?>
		<div class="gl-compare-hero" style="<?php echo count($vendors) === 3 ? 'grid-template-columns:1fr auto 1fr auto 1fr;' : ''; ?>">
			<?php foreach ( $vendors as $i => $v ) :
				$vd = $v_data[ $v->ID ];
				?>
				<?php if ( $i > 0 ) : ?>
					<div class="gl-compare-vs">vs</div>
				<?php endif; ?>
				<a class="gl-compare-card" href="<?php echo esc_url( get_permalink( $vd['id'] ) ); ?>" style="text-decoration:none;color:inherit;">
					<div class="gl-logo-wrap">
						<?php if ( ! empty( $vd['logo']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $vd['logo']['url'] ); ?>" alt="<?php echo esc_attr( $vd['name'] ); ?> logo" loading="lazy" />
						<?php else : ?>
							<div style="font-size:1.4rem;font-weight:700;color:var(--gl-text-muted,#5b6b85);"><?php echo esc_html( mb_substr( $vd['name'], 0, 1 ) ); ?></div>
						<?php endif; ?>
					</div>
					<h3><?php echo esc_html( $vd['name'] ); ?></h3>
					<?php if ( $vd['category'] ) : ?>
						<div class="gl-card-cat"><?php echo esc_html( $vd['category'] ); ?></div>
					<?php endif; ?>
					<div class="gl-card-meta">
						<?php if ( $vd['stage'] ) : ?><span><?php echo esc_html( $vd['stage'] ); ?></span><?php endif; ?>
						<?php if ( $vd['pricing'] ) : ?><span><?php echo esc_html( $vd['pricing'] ); ?></span><?php endif; ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( $verdict ) : ?>
			<div class="gl-compare-verdict">
				<div class="gl-verdict-label">Analyst verdict</div>
				<p><?php echo esc_html( $verdict ); ?></p>
			</div>
		<?php endif; ?>

		<!-- P11b: Side-by-side pricing/stage visual -->
		<?php if ( count($vendors) === 2 && ( $v_data[$a_id]['entry'] || $v_data[$b_id]['entry'] ) ) :
			$va = $v_data[$a_id]; $vb = $v_data[$b_id]; ?>
		<h2 style="margin-top:2rem;"><?php esc_html_e( 'At a glance', 'gtmlens-child' ); ?></h2>
		<div class="gl-pricing-bar" style="grid-template-columns: 1fr 1fr;">
			<div class="gl-pricing-tier tier-entry">
				<div class="gl-pt-label"><?php echo esc_html( $va['name'] ); ?> · entry price</div>
				<div class="gl-pt-value"><?php echo esc_html( $va['entry'] ?: '—' ); ?></div>
			</div>
			<div class="gl-pricing-tier tier-ent">
				<div class="gl-pt-label"><?php echo esc_html( $vb['name'] ); ?> · entry price</div>
				<div class="gl-pt-value"><?php echo esc_html( $vb['entry'] ?: '—' ); ?></div>
			</div>
		</div>
		<div class="gl-pricing-bar" style="grid-template-columns: 1fr 1fr;">
			<div class="gl-pricing-tier tier-mid">
				<div class="gl-pt-label"><?php echo esc_html( $va['name'] ); ?> · raised</div>
				<div class="gl-pt-value"><?php echo esc_html( $va['raised'] ?: '—' ); ?></div>
			</div>
			<div class="gl-pricing-tier tier-mid">
				<div class="gl-pt-label"><?php echo esc_html( $vb['name'] ); ?> · raised</div>
				<div class="gl-pt-value"><?php echo esc_html( $vb['raised'] ?: '—' ); ?></div>
			</div>
		</div>
		<?php endif; ?>

		<!-- P11b: Dimension bars (visual head-to-head) -->
		<?php if ( $dimensions && count($vendors) === 2 ) : ?>
			<h2><?php esc_html_e( 'Head-to-head by dimension', 'gtmlens-child' ); ?></h2>
			<div style="display:grid;grid-template-columns: minmax(100px,140px) 1fr minmax(80px,120px); gap:.5rem; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#5b6b85; margin-bottom:.5rem;">
				<div><?php echo esc_html( $a_name ); ?></div>
				<div style="text-align:center;"><?php esc_html_e( 'Dimension', 'gtmlens-child' ); ?></div>
				<div style="text-align:right;"><?php echo esc_html( $b_name ); ?></div>
			</div>
			<div class="gl-dim-bars">
				<?php foreach ( $dimensions as $row ) :
					$dim    = $row['dimension'] ?? '';
					$winner = $row['winner'] ?? '';
					$note   = $row['note'] ?? '';
					$dir    = $infer_winner( $winner, $a_name, $b_name );
					$a_pct  = ( 'a' === $dir ) ? 90 : ( ( 'tie' === $dir ) ? 50 : 25 );
					$b_pct  = ( 'b' === $dir ) ? 90 : ( ( 'tie' === $dir ) ? 50 : 25 );
					$win_cls= ( 'a' === $dir ) ? 'gl-dim-win-left' : ( ( 'b' === $dir ) ? 'gl-dim-win-right' : '' );
					?>
					<div class="gl-dim-row <?php echo esc_attr( $win_cls ); ?>">
						<div class="gl-dim-bar-left"><span style="width:<?php echo esc_attr( $a_pct ); ?>%;"></span></div>
						<div class="gl-dim-label"><?php echo esc_html( $dim ); ?></div>
						<div class="gl-dim-bar-right"><span style="width:<?php echo esc_attr( $b_pct ); ?>%;"></span></div>
					</div>
					<?php if ( $note ) : ?>
						<div style="margin:.15rem 0 .75rem; padding:0 .5rem; font-size:.85rem; line-height:1.5; color:#5b6b85;">
							<span style="font-weight:700; color:#0d1f3c;"><?php echo esc_html( $winner ?: 'Tie' ); ?>:</span>
							<?php echo esc_html( $note ); ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php elseif ( $dimensions ) : ?>
			<!-- 3-vendor fallback: keep tabular -->
			<h2><?php esc_html_e( 'Head-to-Head by Dimension', 'gtmlens-child' ); ?></h2>
			<table class="gl-comparison-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Dimension', 'gtmlens-child' ); ?></th>
						<th><?php esc_html_e( 'Winner', 'gtmlens-child' ); ?></th>
						<th><?php esc_html_e( 'Why', 'gtmlens-child' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $dimensions as $row ) : ?>
						<tr>
							<td><?php echo esc_html( $row['dimension'] ?? '' ); ?></td>
							<td><?php echo esc_html( $row['winner'] ?? '' ); ?> <span class="gl-winner-badge"><?php esc_html_e( 'EDGE', 'gtmlens-child' ); ?></span></td>
							<td><?php echo esc_html( $row['note'] ?? '' ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<!-- Reference data table (full set) -->
		<h2><?php esc_html_e( 'Reference data', 'gtmlens-child' ); ?></h2>
		<?php
		$rows = [
			'pricing' => __( 'Pricing tier', 'gtmlens-child' ),
			'entry'   => __( 'Entry price', 'gtmlens-child' ),
			'stage'   => __( 'Funding stage', 'gtmlens-child' ),
			'raised'  => __( 'Total raised', 'gtmlens-child' ),
			'val'     => __( 'Valuation', 'gtmlens-child' ),
			'segment' => __( 'Target segment', 'gtmlens-child' ),
			'founded' => __( 'Founded', 'gtmlens-child' ),
		];
		?>
		<div style="overflow-x:auto;">
		<table class="gl-comparison-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Dimension', 'gtmlens-child' ); ?></th>
					<?php foreach ( $vendors as $v ) : ?>
						<th><?php echo esc_html( $v_data[ $v->ID ]['name'] ); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $key => $label ) :
					$has_value = false;
					foreach ( $vendors as $v ) {
						if ( ! empty( $v_data[ $v->ID ][ $key ] ) ) { $has_value = true; break; }
					}
					if ( ! $has_value ) continue;
					?>
					<tr>
						<td><?php echo esc_html( $label ); ?></td>
						<?php foreach ( $vendors as $v ) : ?>
							<td><?php echo esc_html( $v_data[ $v->ID ][ $key ] ); ?></td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>

		<?php if ( $rule_a || $rule_b || $rule_c ) : ?>
			<h2><?php esc_html_e( 'When to choose which', 'gtmlens-child' ); ?></h2>
			<div class="gl-decision-rules">
				<?php if ( $rule_a && $a_name ) : ?>
					<div class="gl-decision-rule">
						<p class="gl-decision-rule__label"><?php printf( esc_html__( 'Choose %s if…', 'gtmlens-child' ), esc_html( $a_name ) ); ?></p>
						<?php echo wp_kses_post( $rule_a ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $rule_b && $b_name ) : ?>
					<div class="gl-decision-rule">
						<p class="gl-decision-rule__label"><?php printf( esc_html__( 'Choose %s if…', 'gtmlens-child' ), esc_html( $b_name ) ); ?></p>
						<?php echo wp_kses_post( $rule_b ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $rule_c && $c_name ) : ?>
					<div class="gl-decision-rule">
						<p class="gl-decision-rule__label"><?php printf( esc_html__( 'Choose %s if…', 'gtmlens-child' ), esc_html( $c_name ) ); ?></p>
						<?php echo wp_kses_post( $rule_c ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( get_the_content() ) : ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>

		<hr class="gl-divider" />
		<?php gtmlens_editorial_callout(); ?>

	</div>

<?php
endwhile;

get_footer();
