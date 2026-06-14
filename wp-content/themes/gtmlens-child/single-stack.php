<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$pid = get_the_ID();

	$tier          = get_field( 'budget_tier', $pid );
	$icp           = get_field( 'icp_use_case', $pid );
	$tools         = get_field( 'tools', $pid );       // relationship → vendor
	$arch_diagram  = get_field( 'architecture_diagram', $pid );
	$pros_cons     = get_field( 'pros_cons_tradeoffs', $pid ); // repeater: type, text
	$migration     = get_field( 'migration_path', $pid );      // post object → stack
	$monthly_cost  = get_field( 'monthly_cost_estimate', $pid ); // optional manual override

	// Compute monthly cost from linked vendor entry prices when not manually set
	$computed_cost = 0;
	if ( $tools ) {
		foreach ( $tools as $tool ) {
			$price_str = get_field( 'entry_price', $tool->ID );
			// Strip non-numeric chars (e.g. "$149/mo" → 149)
			$price_num = (float) preg_replace( '/[^0-9.]/', '', $price_str ?? '' );
			$computed_cost += $price_num;
		}
	}
	// Format display cost: if manual override is purely numeric, format it; otherwise use as-is
	if ( $monthly_cost ) {
		if ( is_numeric( $monthly_cost ) ) {
			$display_cost = '$' . number_format( (float) $monthly_cost ) . '/mo';
		} else {
			$display_cost = $monthly_cost;
		}
	} elseif ( $computed_cost > 0 ) {
		$display_cost = '$' . number_format( $computed_cost ) . '/mo';
	} else {
		$display_cost = '';
	}

	$pros_list = [];
	$cons_list = [];
	if ( $pros_cons ) {
		foreach ( $pros_cons as $row ) {
			if ( 'pro' === ( $row['type'] ?? '' ) ) {
				$pros_list[] = $row['text'];
			} elseif ( 'con' === ( $row['type'] ?? '' ) ) {
				$cons_list[] = $row['text'];
			}
		}
	}
	?>

	<div class="gl-section">

		<!-- Tier badge + title -->
		<?php if ( $tier ) : ?>
			<div class="gl-tier-badge"><?php echo esc_html( $tier ); ?></div>
		<?php endif; ?>

		<h1><?php the_title(); ?></h1>

		<?php if ( $icp ) : ?>
			<p style="color:var(--gl-text-muted);font-size:1rem;margin-top:4px;"><?php echo esc_html( $icp ); ?></p>
		<?php endif; ?>

		<!-- P11d: Grouped-stage stack flow -->
		<?php if ( $tools ) :
			// Map vendor_category slug -> stage label/order
			$stage_for_cat = [
				'data-enrichment'      => 'Data & Sources',
				'intent-signal'        => 'Data & Sources',
				'lead-capture'         => 'Data & Sources',
				'foundation-models'    => 'Intelligence Layer',
				'outbound'             => 'Engagement',
				'linkedin-automation'  => 'Engagement',
				'ai-sdr'               => 'Engagement',
				'crm'                  => 'System of Record',
				'orchestration'        => 'System of Record',
				'revenue-intelligence' => 'Intelligence Layer',
			];
			$stage_order = ['Data & Sources', 'Engagement', 'System of Record', 'Intelligence Layer'];
			$by_stage = [];
			foreach ( $stage_order as $s ) { $by_stage[ $s ] = []; }
			foreach ( $tools as $tool ) {
				$cats = get_the_terms( $tool->ID, 'vendor_category' );
				$slug = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->slug : '';
				$stage = $stage_for_cat[ $slug ] ?? 'Engagement';
				$by_stage[ $stage ][] = $tool;
			}
			?>
			<h2><?php esc_html_e( 'Stack architecture', 'gtmlens-child' ); ?></h2>
			<?php if ( $display_cost ) : ?>
				<div class="gl-stack-cost-pill"><?php echo esc_html( str_replace('$', '', $display_cost) ); ?></div>
			<?php endif; ?>
			<div class="gl-stack-flow">
				<?php foreach ( $stage_order as $stage_label ) :
					$stage_tools = $by_stage[ $stage_label ];
					?>
					<div class="gl-stack-stage">
						<div class="gl-stack-stage__label"><?php echo esc_html( $stage_label ); ?></div>
						<div class="gl-stack-stage__tools">
							<?php if ( empty( $stage_tools ) ) : ?>
								<div class="gl-stack-stage__empty">— not in this stack —</div>
							<?php else : ?>
								<?php foreach ( $stage_tools as $tool ) :
									$t_logo = get_field( 'logo', $tool->ID );
									$t_price = get_field( 'entry_price', $tool->ID );
									?>
									<a class="gl-stack-tool" href="<?php echo esc_url( get_permalink( $tool->ID ) ); ?>">
										<?php if ( $t_logo && ! empty( $t_logo['url'] ) ) : ?>
											<img src="<?php echo esc_url( $t_logo['url'] ); ?>" alt="" loading="lazy" />
										<?php endif; ?>
										<span><?php echo esc_html( get_the_title( $tool->ID ) ); ?></span>
										<?php if ( $t_price ) : ?>
											<span class="gl-stack-tool__price"><?php echo esc_html( $t_price ); ?></span>
										<?php endif; ?>
									</a>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<details style="margin-bottom:1.5rem;">
				<summary style="cursor:pointer;font-size:.85rem;font-weight:600;color:#5b6b85;"><?php esc_html_e( 'Show flat tools list', 'gtmlens-child' ); ?></summary>
				<ul class="gl-tools-list" style="margin-top:.75rem;">
					<?php foreach ( $tools as $tool ) :
						$t_logo  = get_field( 'logo', $tool->ID );
						$t_price = get_field( 'entry_price', $tool->ID );
						?>
						<li class="gl-tools-list__item">
							<?php if ( $t_logo && ! empty( $t_logo['url'] ) ) : ?>
								<img class="gl-tools-list__logo" src="<?php echo esc_url( $t_logo['url'] ); ?>" alt="<?php echo esc_attr( get_the_title( $tool->ID ) . ' logo' ); ?>" width="32" height="32" loading="lazy" />
							<?php endif; ?>
							<a class="gl-tools-list__name" href="<?php echo esc_url( get_permalink( $tool->ID ) ); ?>"><?php echo esc_html( get_the_title( $tool->ID ) ); ?></a>
							<?php if ( $t_price ) : ?><span class="gl-tools-list__price"><?php echo esc_html( $t_price ); ?></span><?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</details>
		<?php endif; ?>

		<!-- Monthly cost summary -->
		<?php if ( $display_cost ) : ?>
			<div class="gl-cost-total">
				<span class="gl-cost-total__label"><?php esc_html_e( 'Estimated monthly cost', 'gtmlens-child' ); ?></span>
				<span class="gl-cost-total__amount"><?php echo esc_html( $display_cost ); ?></span>
			</div>
		<?php endif; ?>

		<!-- Architecture diagram -->
		<?php if ( $arch_diagram && ! empty( $arch_diagram['url'] ) ) : ?>
			<h2><?php esc_html_e( 'Architecture', 'gtmlens-child' ); ?></h2>
			<figure>
				<img
					src="<?php echo esc_url( $arch_diagram['url'] ); ?>"
					alt="<?php echo esc_attr( get_the_title() . ' architecture diagram' ); ?>"
					loading="lazy"
					style="max-width:100%;height:auto;"
				/>
			</figure>
		<?php endif; ?>

		<!-- Pros / cons / tradeoffs -->
		<?php if ( $pros_list || $cons_list ) : ?>
			<h2><?php esc_html_e( 'Pros, Cons & Tradeoffs', 'gtmlens-child' ); ?></h2>
			<div class="gl-pros-cons">
				<?php if ( $pros_list ) : ?>
					<div class="gl-pros-cons__col gl-pros-cons__col--pros">
						<p class="gl-pros-cons__heading"><?php esc_html_e( 'Pros', 'gtmlens-child' ); ?></p>
						<ul>
							<?php foreach ( $pros_list as $pro ) : ?>
								<li><?php echo esc_html( $pro ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if ( $cons_list ) : ?>
					<div class="gl-pros-cons__col gl-pros-cons__col--cons">
						<p class="gl-pros-cons__heading"><?php esc_html_e( 'Cons', 'gtmlens-child' ); ?></p>
						<ul>
							<?php foreach ( $cons_list as $con ) : ?>
								<li><?php echo esc_html( $con ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- Inline post content -->
		<?php if ( get_the_content() ) : ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>

		<!-- Migration path -->
		<?php if ( $migration ) : ?>
			<div style="margin-top:32px;padding:20px;background:var(--gl-surface);border:1px solid var(--gl-border);border-radius:var(--gl-radius);">
				<p style="margin:0 0 8px;font-size:.8rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--gl-text-muted);"><?php esc_html_e( 'Ready to scale up?', 'gtmlens-child' ); ?></p>
				<a href="<?php echo esc_url( get_permalink( $migration->ID ) ); ?>" style="font-weight:700;color:var(--gl-accent);">
					<?php printf( esc_html__( 'Next tier: %s →', 'gtmlens-child' ), esc_html( get_the_title( $migration->ID ) ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<hr class="gl-divider" />
		<?php gtmlens_editorial_callout(); ?>

	</div>

<?php
endwhile;

get_footer();
