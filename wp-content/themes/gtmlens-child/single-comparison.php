<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$pid = get_the_ID();

	$vendor_a   = get_field( 'vendor_a', $pid );   // WP_Post object
	$vendor_b   = get_field( 'vendor_b', $pid );
	$vendor_c   = get_field( 'vendor_c', $pid );   // optional 3rd vendor
	$verdict    = get_field( 'verdict', $pid );
	$dimensions = get_field( 'winner_by_dimension', $pid ); // repeater
	$rule_a     = get_field( 'decision_rule_a', $pid );
	$rule_b     = get_field( 'decision_rule_b', $pid );
	$rule_c     = get_field( 'decision_rule_c', $pid );
	$last_upd   = get_field( 'last_updated', $pid );

	// Build vendor list — only include vendors that actually exist
	$vendors = array_values( array_filter( [ $vendor_a, $vendor_b, $vendor_c ] ) );

	// Per-vendor derived data, keyed by ID
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
			'segment' => get_field( 'target_segment', $vid ),
			'logo'    => get_field( 'logo', $vid ),
		];
	}

	// Backwards-compat shorthand for two-vendor sections (decision rules)
	$a_id   = $vendor_a ? $vendor_a->ID : null;
	$b_id   = $vendor_b ? $vendor_b->ID : null;
	$a_name = $a_id ? $v_data[ $a_id ]['name'] : '';
	$b_name = $b_id ? $v_data[ $b_id ]['name'] : '';
	$c_id   = $vendor_c ? $vendor_c->ID : null;
	$c_name = $c_id ? $v_data[ $c_id ]['name'] : '';
	?>

	<div class="gl-section">

		<!-- Title -->
		<h1><?php the_title(); ?></h1>
		<?php gtmlens_last_updated( $pid ); ?>

		<!-- Verdict banner -->
		<?php if ( $verdict ) : ?>
			<div class="gl-verdict">
				<strong><?php esc_html_e( 'Bottom line: ', 'gtmlens-child' ); ?></strong>
				<?php echo esc_html( $verdict ); ?>
			</div>
		<?php endif; ?>

		<!-- Vendor logo row -->
		<div style="display:flex;gap:24px;align-items:center;margin-bottom:24px;flex-wrap:wrap;">
			<?php foreach ( $vendors as $i => $v ) :
				$vd = $v_data[ $v->ID ];
				?>
				<?php if ( $i > 0 ) : ?>
					<span style="color:var(--gl-text-muted);font-size:1.2rem;">vs</span>
				<?php endif; ?>
				<a href="<?php echo esc_url( get_permalink( $vd['id'] ) ); ?>" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
					<?php if ( $vd['logo'] && ! empty( $vd['logo']['url'] ) ) : ?>
						<img
							src="<?php echo esc_url( $vd['logo']['url'] ); ?>"
							alt="<?php echo esc_attr( $vd['name'] . ' logo' ); ?>"
							width="48" height="48"
							style="object-fit:contain;border-radius:6px;border:1px solid var(--gl-border);"
							loading="lazy"
						/>
					<?php endif; ?>
					<strong style="color:var(--gl-primary);font-size:1.1rem;"><?php echo esc_html( $vd['name'] ); ?></strong>
				</a>
			<?php endforeach; ?>
		</div>

		<!-- Side-by-side data table (auto-pulled from linked vendors) -->
		<?php
		$rows = [
			'pricing' => __( 'Pricing tier', 'gtmlens-child' ),
			'entry'   => __( 'Entry price', 'gtmlens-child' ),
			'stage'   => __( 'Funding stage', 'gtmlens-child' ),
			'raised'  => __( 'Total raised', 'gtmlens-child' ),
			'segment' => __( 'Target segment', 'gtmlens-child' ),
		];
		?>
		<div style="overflow-x:auto;">
		<table class="gl-comparison-table" aria-label="<?php esc_attr_e( 'Comparison data', 'gtmlens-child' ); ?>">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Dimension', 'gtmlens-child' ); ?></th>
					<?php foreach ( $vendors as $v ) : ?>
						<th scope="col"><?php echo esc_html( $v_data[ $v->ID ]['name'] ); ?></th>
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

		<!-- Winner-by-dimension repeater -->
		<?php if ( $dimensions ) : ?>
			<h2><?php esc_html_e( 'Head-to-Head by Dimension', 'gtmlens-child' ); ?></h2>
			<table class="gl-comparison-table" aria-label="<?php esc_attr_e( 'Winner by dimension', 'gtmlens-child' ); ?>">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Dimension', 'gtmlens-child' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Winner', 'gtmlens-child' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Why', 'gtmlens-child' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $dimensions as $row ) : ?>
						<tr>
							<td><?php echo esc_html( $row['dimension'] ?? '' ); ?></td>
							<td>
								<?php echo esc_html( $row['winner'] ?? '' ); ?>
								<span class="gl-winner-badge"><?php esc_html_e( 'EDGE', 'gtmlens-child' ); ?></span>
							</td>
							<td><?php echo esc_html( $row['note'] ?? '' ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<!-- Decision rules -->
		<?php if ( $rule_a || $rule_b || $rule_c ) : ?>
			<h2><?php esc_html_e( 'When to Choose Which', 'gtmlens-child' ); ?></h2>
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

		<!-- Inline post content (optional analyst prose) -->
		<?php if ( get_the_content() ) : ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>

		<hr class="gl-divider" />
		<?php gtmlens_editorial_callout(); ?>

	</div><!-- .gl-section -->

<?php
endwhile;

get_footer();
