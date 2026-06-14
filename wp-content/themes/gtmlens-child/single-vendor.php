<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$pid = get_the_ID();

	// Identity
	$vendor_url   = get_field( 'vendor_url', $pid );
	$logo         = get_field( 'logo', $pid );
	$hq           = get_field( 'hq', $pid );
	$founded      = get_field( 'founded', $pid );
	$founders     = get_field( 'founders', $pid );

	// Funding
	$funding_stage  = get_field( 'funding_stage', $pid );
	$last_round     = get_field( 'last_round_size', $pid );
	$total_raised   = get_field( 'total_raised', $pid );
	$last_valuation = get_field( 'last_valuation', $pid );

	// Pricing
	$pricing_tier  = get_field( 'pricing_tier', $pid );
	$entry_price   = get_field( 'entry_price', $pid );
	$pricing_url   = get_field( 'pricing_page_url', $pid );

	// Fit
	$target_segment = get_field( 'target_segment', $pid );
	$best_fit       = get_field( 'best_fit', $pid );
	$worst_fit      = get_field( 'worst_fit', $pid );

	// SWOT
	$swot_s = get_field( 'swot_strengths', $pid );
	$swot_w = get_field( 'swot_weaknesses', $pid );
	$swot_o = get_field( 'swot_opportunities', $pid );
	$swot_t = get_field( 'swot_threats', $pid );

	// Editorial
	$analyst_take  = get_field( 'analyst_take', $pid );
	$arch_diagram  = get_field( 'architecture_diagram', $pid );

	// Relations
	$alternatives      = get_field( 'alternatives', $pid );
	$related_insights  = get_field( 'related_insights', $pid );

	// Taxonomy terms
	$capabilities_terms  = get_the_terms( $pid, 'capabilities' );
	$integrations_terms  = get_the_terms( $pid, 'integrations' );
	$category_terms      = get_the_terms( $pid, 'vendor_category' );

	$primary_category = ( $category_terms && ! is_wp_error( $category_terms ) ) ? $category_terms[0] : null;
	?>

	<div class="gl-vendor-layout">

		<!-- ── Main column ─────────────────────────────────────────────── -->
		<main class="gl-vendor-main">

			<!-- Hero -->
			<div class="gl-vendor-hero">
				<?php if ( $logo && ! empty( $logo['url'] ) ) : ?>
					<img
						class="gl-vendor-hero__logo"
						src="<?php echo esc_url( $logo['url'] ); ?>"
						alt="<?php echo esc_attr( get_the_title() . ' logo' ); ?>"
						width="72" height="72"
						loading="lazy"
					/>
				<?php endif; ?>

				<div>
					<h1 class="gl-vendor-hero__name"><?php the_title(); ?></h1>
					<?php if ( $primary_category ) : ?>
						<a
							class="gl-vendor-hero__category"
							href="<?php echo esc_url( get_term_link( $primary_category ) ); ?>"
						><?php echo esc_html( $primary_category->name ); ?></a>
					<?php endif; ?>
					<?php gtmlens_last_updated( $pid ); ?>
				<?php $gl_vu = (string) get_field( 'vendor_url' ); if ( $gl_vu ) :
					$gl_host = parse_url( $gl_vu, PHP_URL_HOST );
					if ( $gl_host ) $gl_host = preg_replace( '/^www\./', '', $gl_host );
				?>
				<a class="gl-vendor-hero__website" href="<?php echo esc_url( $gl_vu ); ?>" target="_blank" rel="noopener nofollow">Visit <?php echo esc_html( $gl_host ?: 'website' ); ?> &rarr;</a>
				<?php endif; ?>
				</div>
			</div>

			<!-- P11c: Stack-position visual -->
				<?php
				$cat_slug = $primary_category ? $primary_category->slug : '';
				$stack_map = [
					'data-enrichment'        => ['Triggers / ICP list', 'Enrichment & sequencing', 'CRM / outbound tool'],
					'outbound'               => ['Lead list + data', 'Sender / sequencer', 'CRM + reporting'],
					'linkedin-automation'    => ['Targeting list', 'LinkedIn outreach', 'CRM / sequencer'],
					'crm'                    => ['Inbound + outbound', 'CRM / system of record', 'BI / revenue tools'],
					'intent-signal'          => ['Anonymous web traffic', 'Identity / intent layer', 'Sequencer / SDR'],
					'orchestration'          => ['Triggers / data sources', 'Workflow orchestration', 'Downstream actions'],
					'ai-sdr'                 => ['Lead list + ICP', 'AI agent / SDR', 'Booked meetings / CRM'],
					'revenue-intelligence'   => ['Call recordings + CRM', 'Revenue intelligence', 'Forecast / coaching'],
					'lead-capture'           => ['Website traffic', 'Capture / qualify', 'CRM / routing'],
					'foundation-models'      => ['Prompt + context', 'Foundation model', 'Application output'],
				];
				$default = ['Inputs', 'This vendor', 'Outputs'];
				$nodes = $stack_map[ $cat_slug ] ?? $default;
				?>
				<?php
	/* === P19: Vendor stats strip === */
	$gl_tr_m   = (int) get_field( 'total_raised_usd_m' );
	$gl_val_m  = (int) get_field( 'last_valuation_usd_m' );
	$gl_lr_m   = (int) get_field( 'last_round_size_usd_m' );
	$gl_lr_dt  = (string) get_field( 'last_round_date' );
	$gl_stage  = (string) get_field( 'funding_stage' );
	$gl_fnd    = (string) get_field( 'founded' );
	$gl_hq     = (string) get_field( 'hq' );
	$gl_fmt    = function( $m ) { if ( $m >= 1000 ) return '$' . number_format( $m / 1000, 1 ) . 'B'; if ( $m > 0 ) return '$' . number_format( $m ) . 'M'; return ''; };
	$gl_has    = ( $gl_tr_m || $gl_val_m || $gl_lr_m || $gl_stage || $gl_fnd || $gl_hq );
	if ( $gl_has ) : ?>
	<div class="gl-vendor-stats">
		<?php if ( $gl_tr_m ) : ?><div class="gl-vstat"><span class="gl-vstat__label">Total raised</span><span class="gl-vstat__val"><?php echo esc_html( $gl_fmt( $gl_tr_m ) ); ?></span></div><?php endif; ?>
		<?php if ( $gl_val_m ) : ?><div class="gl-vstat gl-vstat--accent"><span class="gl-vstat__label">Last valuation</span><span class="gl-vstat__val"><?php echo esc_html( $gl_fmt( $gl_val_m ) ); ?></span></div><?php endif; ?>
		<?php if ( $gl_lr_m || $gl_lr_dt ) : ?>
			<div class="gl-vstat"><span class="gl-vstat__label">Last round</span><span class="gl-vstat__val"><?php
				$bits = array();
				if ( $gl_stage )      $bits[] = esc_html( $gl_stage );
				if ( $gl_lr_m )       $bits[] = esc_html( $gl_fmt( $gl_lr_m ) );
				if ( $gl_lr_dt ) { $ts = strtotime( $gl_lr_dt ); if ( $ts ) $bits[] = esc_html( date_i18n( 'M Y', $ts ) ); }
				echo implode( ' &middot; ', $bits );
			?></span></div>
		<?php elseif ( $gl_stage ) : ?>
			<div class="gl-vstat"><span class="gl-vstat__label">Stage</span><span class="gl-vstat__val"><?php echo esc_html( $gl_stage ); ?></span></div>
		<?php endif; ?>
		<?php if ( $gl_fnd ) : ?><div class="gl-vstat"><span class="gl-vstat__label">Founded</span><span class="gl-vstat__val"><?php echo esc_html( $gl_fnd ); ?></span></div><?php endif; ?>
		<?php if ( $gl_hq ) : ?><div class="gl-vstat"><span class="gl-vstat__label">HQ</span><span class="gl-vstat__val"><?php echo esc_html( $gl_hq ); ?></span></div><?php endif; ?>
	</div>
	<?php endif; ?>
	<div class="gl-stack-pos" aria-label="<?php esc_attr_e( 'Where this vendor sits in a typical stack', 'gtmlens-child' ); ?>">
					<div class="gl-stack-node">
						<div class="gl-stack-label">Upstream</div>
						<div class="gl-stack-title"><?php echo esc_html( $nodes[0] ); ?></div>
					</div>
					<div class="gl-stack-arrow">→</div>
					<div class="gl-stack-node current">
						<div class="gl-stack-label"><?php echo esc_html( $primary_category ? $primary_category->name : 'This vendor' ); ?></div>
						<div class="gl-stack-title"><?php echo esc_html( get_the_title() ); ?></div>
						<div class="gl-stack-sub"><?php echo esc_html( $nodes[1] ); ?></div>
					</div>
					<div class="gl-stack-arrow">→</div>
					<div class="gl-stack-node">
						<div class="gl-stack-label">Downstream</div>
						<div class="gl-stack-title"><?php echo esc_html( $nodes[2] ); ?></div>
					</div>
				</div>

				<!-- P11c: Pricing tier bar -->
				<?php if ( $pricing_tier ) :
					$tier_text = trim( (string) $pricing_tier );
					$tier_lower = strtolower( $tier_text );
					$step = 1;
					if ( strpos( $tier_lower, 'free' ) !== false ) $step = 1;
					elseif ( '$' === $tier_text ) $step = 2;
					elseif ( '$$' === $tier_text ) $step = 3;
					elseif ( '$$$' === $tier_text ) $step = 4;
					elseif ( strpos( $tier_lower, 'enterprise' ) !== false || strpos( $tier_lower, '$$$$' ) !== false ) $step = 5;
					elseif ( strpos( $tier_text, '$' ) !== false ) $step = max( 2, min( 5, strlen( str_replace( ' ', '', $tier_text ) ) + 1 ) );
					?>
					<div class="gl-pricing-tier-bar-wrap">
						<span class="gl-pt-text">Pricing tier:</span>
						<div class="gl-pricing-tier-bar" style="flex:1;">
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<div class="step <?php echo ( $i <= $step ) ? 'active' : ''; ?>"></div>
							<?php endfor; ?>
						</div>
						<span class="gl-pt-text" style="min-width:auto;color:#5b6b85;font-weight:600;"><?php echo esc_html( $tier_text ); ?><?php if ( $entry_price ) echo ' · from ' . esc_html( $entry_price ); ?></span>
					</div>
				<?php endif; ?>

				<!-- Analyst take -->
			<?php if ( $analyst_take ) : ?>
				<section aria-labelledby="gl-analyst-heading">
					<h2 id="gl-analyst-heading"><?php esc_html_e( 'Analyst Take', 'gtmlens-child' ); ?></h2>
					<div class="gl-analyst-take">
						<?php echo wp_kses_post( $analyst_take ); ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Architecture diagram -->
			<?php if ( $arch_diagram && ! empty( $arch_diagram['url'] ) ) : ?>
				<figure class="gl-arch-diagram">
					<img
						src="<?php echo esc_url( $arch_diagram['url'] ); ?>"
						alt="<?php echo esc_attr( get_the_title() . ' architecture diagram' ); ?>"
						loading="lazy"
						style="max-width:100%;height:auto;"
					/>
				</figure>
			<?php endif; ?>

			<!-- SWOT -->
			<?php if ( $swot_s || $swot_w || $swot_o || $swot_t ) : ?>
				<section aria-labelledby="gl-swot-heading">
					<h2 id="gl-swot-heading"><?php esc_html_e( 'SWOT Analysis', 'gtmlens-child' ); ?></h2>
					<div class="gl-swot">
						<div class="gl-swot__box gl-swot__box--s">
							<p class="gl-swot__heading"><?php esc_html_e( 'Strengths', 'gtmlens-child' ); ?></p>
							<p class="gl-swot__text"><?php echo wp_kses_post( $swot_s ); ?></p>
						</div>
						<div class="gl-swot__box gl-swot__box--w">
							<p class="gl-swot__heading"><?php esc_html_e( 'Weaknesses', 'gtmlens-child' ); ?></p>
							<p class="gl-swot__text"><?php echo wp_kses_post( $swot_w ); ?></p>
						</div>
						<div class="gl-swot__box gl-swot__box--o">
							<p class="gl-swot__heading"><?php esc_html_e( 'Opportunities', 'gtmlens-child' ); ?></p>
							<p class="gl-swot__text"><?php echo wp_kses_post( $swot_o ); ?></p>
						</div>
						<div class="gl-swot__box gl-swot__box--t">
							<p class="gl-swot__heading"><?php esc_html_e( 'Threats', 'gtmlens-child' ); ?></p>
							<p class="gl-swot__text"><?php echo wp_kses_post( $swot_t ); ?></p>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<!-- Best fit / Worst fit -->
			<?php if ( $best_fit || $worst_fit ) : ?>
				<section>
					<h2><?php esc_html_e( 'Fit Assessment', 'gtmlens-child' ); ?></h2>
					<div class="gl-pros-cons">
						<?php if ( $best_fit ) : ?>
							<div class="gl-pros-cons__col gl-pros-cons__col--pros">
								<p class="gl-pros-cons__heading"><?php esc_html_e( 'Best For', 'gtmlens-child' ); ?></p>
								<?php echo wp_kses_post( $best_fit ); ?>
							</div>
						<?php endif; ?>
						<?php if ( $worst_fit ) : ?>
							<div class="gl-pros-cons__col gl-pros-cons__col--cons">
								<p class="gl-pros-cons__heading"><?php esc_html_e( 'Worst For', 'gtmlens-child' ); ?></p>
								<?php echo wp_kses_post( $worst_fit ); ?>
							</div>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Capabilities -->
			<?php if ( $capabilities_terms && ! is_wp_error( $capabilities_terms ) ) : ?>
				<section>
					<span class="gl-label"><?php esc_html_e( 'Capabilities', 'gtmlens-child' ); ?></span>
					<div class="gl-tags">
						<?php foreach ( $capabilities_terms as $term ) : ?>
							<a class="gl-tag" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Integrations -->
			<?php if ( $integrations_terms && ! is_wp_error( $integrations_terms ) ) : ?>
				<section>
					<span class="gl-label"><?php esc_html_e( 'Integrations', 'gtmlens-child' ); ?></span>
					<div class="gl-tags">
						<?php foreach ( $integrations_terms as $term ) : ?>
							<a class="gl-tag" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Alternatives -->
			<?php if ( $alternatives ) : ?>
				

			
			<section>
					<h2><?php esc_html_e( 'Alternatives', 'gtmlens-child' ); ?></h2>
					<div class="gl-alternatives">
						<?php foreach ( $alternatives as $alt ) :
							$alt_logo = get_field( 'logo', $alt->ID );
							?>
							<a class="gl-alt-card" href="<?php echo esc_url( get_permalink( $alt->ID ) ); ?>">
								<?php if ( $alt_logo && ! empty( $alt_logo['url'] ) ) : ?>
									<img
										class="gl-alt-card__logo"
										src="<?php echo esc_url( $alt_logo['url'] ); ?>"
										alt="<?php echo esc_attr( get_the_title( $alt->ID ) ); ?>"
										width="36" height="36"
										loading="lazy"
									/>
								<?php endif; ?>


								<span class="gl-alt-card__name"><?php echo esc_html( get_the_title( $alt->ID ) ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

<?php
			/* P26: Alternatives */
			$gl_alts = get_field( 'alternatives' );
			$gl_alt_list = array();
			if ( is_array( $gl_alts ) ) {
				foreach ( $gl_alts as $ga ) {
					if ( is_object( $ga ) && isset( $ga->ID ) ) $gl_alt_list[] = $ga;
					elseif ( is_numeric( $ga ) ) { $p = get_post( (int) $ga ); if ( $p ) $gl_alt_list[] = $p; }
				}
			} elseif ( is_string( $gl_alts ) && trim( $gl_alts ) !== '' ) {
				$names = preg_split( '/[\n,]+/', $gl_alts );
				foreach ( $names as $nm ) {
					$nm = trim( $nm );
					if ( ! $nm ) continue;
					$p = get_page_by_title( $nm, OBJECT, 'vendor' );
					if ( $p ) $gl_alt_list[] = $p;
				}
			}
			?>
			<?php if ( ! empty( $gl_alt_list ) ) : ?>
			<section class="gl-vendor-section gl-vendor-alts">
				<h2>Alternatives</h2>
				<p class="gl-vendor-alts__sub">Other vendors operators evaluate against <?php echo esc_html( get_the_title() ); ?>.</p>
				<div class="gl-vendor-alts__grid">
				<?php foreach ( $gl_alt_list as $alt ) :
					$alt_logo = get_field( 'logo', $alt->ID );
					$alt_cat_terms = get_the_terms( $alt->ID, 'vendor_category' );
					$alt_cat = ( $alt_cat_terms && ! is_wp_error( $alt_cat_terms ) ) ? $alt_cat_terms[0]->name : '';
					$alt_raised = (int) get_field( 'total_raised_usd_m', $alt->ID );
					$alt_raised_lbl = $alt_raised >= 1000 ? '$' . number_format( $alt_raised / 1000, 1 ) . 'B raised' : ( $alt_raised > 0 ? '$' . number_format( $alt_raised ) . 'M raised' : '' );
				?>
					<a class="gl-vendor-alt" href="<?php echo esc_url( get_permalink( $alt->ID ) ); ?>">
						<?php if ( $alt_logo && ! empty( $alt_logo['url'] ) ) : ?><img class="gl-vendor-alt__logo" src="<?php echo esc_url( $alt_logo['url'] ); ?>" alt="" loading="lazy" width="32" height="32" /><?php else : ?><span class="gl-vendor-alt__dot" aria-hidden="true"></span><?php endif; ?>
						<span class="gl-vendor-alt__body">
							<span class="gl-vendor-alt__name"><?php echo esc_html( $alt->post_title ); ?></span>
							<?php if ( $alt_cat || $alt_raised_lbl ) : ?><span class="gl-vendor-alt__meta"><?php echo esc_html( trim( $alt_cat . ( $alt_raised_lbl ? ' &middot; ' . $alt_raised_lbl : '' ) ) ); ?></span><?php endif; ?>
						</span>
					</a>
				<?php endforeach; ?>
				</div>
			</section>
			<?php endif; ?>

			<?php
			/* P26: Featured in comparisons */
			$gl_vname = get_the_title();
			$gl_comps = get_posts( array(
				'post_type' => 'comparison',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC',
			) );
			$gl_comps_match = array();
			foreach ( $gl_comps as $cmp ) {
				if ( preg_match( '/\b' . preg_quote( $gl_vname, '/' ) . '\b/i', $cmp->post_title ) ) $gl_comps_match[] = $cmp;
			}
			if ( ! empty( $gl_comps_match ) ) : ?>
			<section class="gl-vendor-section gl-vendor-comps">
				<h2>Featured in comparisons</h2>
				<div class="gl-vendor-comps__list">
				<?php foreach ( $gl_comps_match as $cmp ) : ?>
					<a class="gl-vendor-comp" href="<?php echo esc_url( get_permalink( $cmp->ID ) ); ?>">
						<span class="gl-vendor-comp__icon" aria-hidden="true">&#9876;</span>
						<span class="gl-vendor-comp__title"><?php echo esc_html( $cmp->post_title ); ?></span>
						<span class="gl-vendor-comp__arrow" aria-hidden="true">&rarr;</span>
					</a>
				<?php endforeach; ?>
				</div>
			</section>
			<?php endif; ?>


			<!-- Related Insights -->
			<?php if ( $related_insights ) : ?>
				<section>
					<h2><?php esc_html_e( 'Related Insights', 'gtmlens-child' ); ?></h2>
					<div class="gl-insight-grid">
						<?php foreach ( $related_insights as $insight ) : ?>
							<a class="gl-insight-card" href="<?php echo esc_url( get_permalink( $insight->ID ) ); ?>">
								<?php
								$insight_cats = get_the_category( $insight->ID );
								if ( $insight_cats ) {
									$cat_names = array_map( fn( $c ) => $c->name, $insight_cats );
									echo '<div class="gl-insight-card__cat">' . esc_html( implode( ', ', $cat_names ) ) . '</div>';
								}
								?>
								<div class="gl-insight-card__title"><?php echo esc_html( get_the_title( $insight->ID ) ); ?></div>
								<div class="gl-insight-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $insight->ID ), 18 ) ); ?></div>
							</a>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<hr class="gl-divider" />

			<!-- Editorial policy callout -->
			<?php gtmlens_editorial_callout(); ?>

		</main>

		<!-- ── Sticky sidebar ──────────────────────────────────────────── -->
		<aside class="gl-sidebar" aria-label="<?php esc_attr_e( 'Vendor quick facts', 'gtmlens-child' ); ?>">

			<?php if ( $vendor_url ) : ?>
				<a class="gl-sidebar__cta" href="<?php echo esc_url( $vendor_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php printf( esc_html__( 'Visit %s →', 'gtmlens-child' ), esc_html( get_the_title() ) ); ?>
				</a>
			<?php endif; ?>

			<?php if ( $pricing_tier ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Pricing', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $pricing_tier ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $entry_price ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Starts at', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $entry_price ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $pricing_url ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"></span>
					<span class="gl-sidebar__value">
						<a href="<?php echo esc_url( $pricing_url ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Pricing page', 'gtmlens-child' ); ?>
						</a>
					</span>
				</div>
			<?php endif; ?>

			<?php if ( $funding_stage ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Stage', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $funding_stage ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $last_round ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Last round', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $last_round ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $total_raised ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Total raised', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $total_raised ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $last_valuation ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Valuation', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $last_valuation ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $hq ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'HQ', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $hq ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $founded ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Founded', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $founded ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $target_segment ) : ?>
				<div class="gl-sidebar__row">
					<span class="gl-sidebar__label"><?php esc_html_e( 'Target', 'gtmlens-child' ); ?></span>
					<span class="gl-sidebar__value"><?php echo esc_html( $target_segment ); ?></span>
				</div>
			<?php endif; ?>

		</aside>

	</div><!-- .gl-vendor-layout -->

<?php
endwhile;

get_footer();
