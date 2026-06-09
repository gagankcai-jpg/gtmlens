<?php
/**
 * Archive template for the `stack` CPT.
 * Renders the Stack Builder hub: hero, ordered grid of 4 tiers, comparison table.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$all_stacks = get_posts( [
	'post_type'      => 'stack',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
] );

// Order by budget_tier enum (pre-seed → enterprise)
$tier_order = [
	'pre-seed'   => 1,
	'seed'       => 2,
	'series-a'   => 3,
	'series-b'   => 4,
	'series-c'   => 5,
	'enterprise' => 6,
];
usort( $all_stacks, function ( $a, $b ) use ( $tier_order ) {
	$ta = get_field( 'budget_tier', $a->ID );
	$tb = get_field( 'budget_tier', $b->ID );
	$oa = $tier_order[ $ta ] ?? 99;
	$ob = $tier_order[ $tb ] ?? 99;
	return $oa <=> $ob;
} );

// Pre-compute card data so we can reuse it for the comparison table
$stack_cards = [];
foreach ( $all_stacks as $s ) {
	$tier  = get_field( 'budget_tier', $s->ID );
	$icp   = get_field( 'icp_use_case', $s->ID );
	$tools = get_field( 'tools', $s->ID );
	$manual_cost = get_field( 'monthly_cost_estimate', $s->ID );

	$computed = 0;
	$tool_names = [];
	$tool_logos = [];
	if ( $tools ) {
		foreach ( $tools as $t ) {
			$price = get_field( 'entry_price', $t->ID );
			$computed += (float) preg_replace( '/[^0-9.]/', '', $price ?? '' );
			$tool_names[] = get_the_title( $t->ID );
			$logo = get_field( 'logo', $t->ID );
			if ( $logo && ! empty( $logo['url'] ) ) {
				$tool_logos[] = [
					'url'  => $logo['url'],
					'name' => get_the_title( $t->ID ),
				];
			}
		}
	}

	if ( $manual_cost ) {
		$cost_display = is_numeric( $manual_cost ) ? '$' . number_format( (float) $manual_cost ) . '/mo' : $manual_cost;
	} elseif ( $computed > 0 ) {
		$cost_display = '$' . number_format( $computed ) . '/mo';
	} else {
		$cost_display = '—';
	}

	$tier_labels = [
		'pre-seed'   => 'Pre-Seed',
		'seed'       => 'Seed',
		'series-a'   => 'Series A',
		'series-b'   => 'Series B',
		'series-c'   => 'Series C',
		'enterprise' => 'Enterprise',
	];

	$stack_cards[] = [
		'id'           => $s->ID,
		'title'        => get_the_title( $s->ID ),
		'permalink'    => get_permalink( $s->ID ),
		'tier'         => $tier,
		'tier_label'   => $tier_labels[ $tier ] ?? ucfirst( str_replace( '-', ' ', $tier ?? '' ) ),
		'icp'          => $icp,
		'tool_count'   => count( $tool_names ),
		'tool_names'   => $tool_names,
		'tool_logos'   => array_slice( $tool_logos, 0, 4 ),
		'cost_display' => $cost_display,
	];
}
?>

<section class="glhp-hero" style="padding-bottom: 24px;">
	<p class="glhp-hero__eyebrow"><?php esc_html_e( 'Stack Builder', 'gtmlens-child' ); ?></p>
	<h1 class="glhp-hero__h1"><?php esc_html_e( 'Build the right GTM stack for your stage', 'gtmlens-child' ); ?></h1>
	<p class="glhp-hero__sub" style="max-width:720px;">
		<?php esc_html_e( 'Six reference stacks — pre-seed to enterprise — with vendor picks, monthly cost math, architecture, and a graduation path between tiers. Pick yours and see exactly what to buy and what to skip.', 'gtmlens-child' ); ?>
	</p>
	<p style="margin-top:18px;">
		<a href="<?php echo esc_url( home_url( '/stack-finder/' ) ); ?>" class="glhp-cta-primary" style="display:inline-block;background:var(--gl-primary);color:#fff;padding:12px 22px;border-radius:8px;text-decoration:none;font-weight:600;">
			<?php esc_html_e( 'Or get a personalized stack in 90 seconds →', 'gtmlens-child' ); ?>
		</a>
	</p>
</section>

<section style="padding: 16px 24px 40px; max-width: 1200px; margin: 0 auto;">
	<?php if ( $stack_cards ) : ?>
		<div class="glhp-stack-grid">
			<?php foreach ( $stack_cards as $card ) : ?>
				<a class="glhp-stack-card" href="<?php echo esc_url( $card['permalink'] ); ?>">
					<div class="glhp-stack-card__head">
						<span class="glhp-stack-card__tier glhp-stack-card__tier--<?php echo esc_attr( $card['tier'] ); ?>"><?php echo esc_html( $card['tier_label'] ); ?></span>
						<span class="glhp-stack-card__cost"><?php echo esc_html( $card['cost_display'] ); ?></span>
					</div>
					<h2 class="glhp-stack-card__title"><?php echo esc_html( $card['title'] ); ?></h2>
					<?php if ( $card['icp'] ) : ?>
						<p class="glhp-stack-card__icp"><?php echo esc_html( wp_trim_words( $card['icp'], 24, '…' ) ); ?></p>
					<?php endif; ?>
					<?php if ( $card['tool_names'] ) : ?>
						<div class="glhp-stack-card__tools">
							<span class="glhp-stack-card__tools-label"><?php echo esc_html( $card['tool_count'] ); ?> tools</span>
							<span class="glhp-stack-card__tools-list"><?php echo esc_html( implode( ' · ', array_slice( $card['tool_names'], 0, 4 ) ) ); ?><?php echo $card['tool_count'] > 4 ? ' · +' . ( $card['tool_count'] - 4 ) : ''; ?></span>
						</div>
					<?php endif; ?>
					<span class="glhp-stack-card__cta"><?php esc_html_e( 'See full stack', 'gtmlens-child' ); ?> →</span>
				</a>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p style="text-align:center;color:var(--gl-text-muted);"><?php esc_html_e( 'No stacks published yet.', 'gtmlens-child' ); ?></p>
	<?php endif; ?>
</section>

<?php if ( count( $stack_cards ) > 1 ) : ?>
<section style="padding: 32px 24px 80px; max-width: 1200px; margin: 0 auto;">
	<h2 class="glhp-stack-compare__h2"><?php esc_html_e( 'All stacks at a glance', 'gtmlens-child' ); ?></h2>
	<div class="glhp-stack-compare-wrap">
		<table class="glhp-stack-compare">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Tier', 'gtmlens-child' ); ?></th>
					<th><?php esc_html_e( 'Best for', 'gtmlens-child' ); ?></th>
					<th><?php esc_html_e( 'Tools', 'gtmlens-child' ); ?></th>
					<th><?php esc_html_e( 'Monthly cost', 'gtmlens-child' ); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $stack_cards as $card ) : ?>
					<tr>
						<td><span class="glhp-stack-compare__tier glhp-stack-card__tier--<?php echo esc_attr( $card['tier'] ); ?>"><?php echo esc_html( $card['tier_label'] ); ?></span></td>
						<td><?php echo esc_html( wp_trim_words( $card['icp'] ?? '', 14, '…' ) ); ?></td>
						<td><?php echo esc_html( $card['tool_count'] ); ?> · <span style="color:var(--gl-text-muted);font-size:.85em;"><?php echo esc_html( implode( ', ', array_slice( $card['tool_names'], 0, 3 ) ) ); ?></span></td>
						<td><strong><?php echo esc_html( $card['cost_display'] ); ?></strong></td>
						<td><a href="<?php echo esc_url( $card['permalink'] ); ?>" class="glhp-stack-compare__cta"><?php esc_html_e( 'View →', 'gtmlens-child' ); ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<p style="margin-top:16px;font-size:.8rem;color:var(--gl-text-muted);">
		<?php esc_html_e( 'Costs are sums of vendor entry-price tiers from each stack\'s tool list. Real-world spend varies with seat count, usage, and credit purchases — see the single-stack pages for full breakdowns.', 'gtmlens-child' ); ?>
	</p>
</section>
<?php endif; ?>

<style>
/* Stack Builder hub */
.glhp-stack-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
	gap: 20px;
}
.glhp-stack-card {
	display: flex;
	flex-direction: column;
	background: var(--gl-white);
	border: 1px solid var(--gl-border);
	border-radius: 8px;
	padding: 22px 22px 20px;
	text-decoration: none;
	color: inherit;
	transition: transform .15s, box-shadow .15s, border-color .15s;
	min-height: 220px;
}
.glhp-stack-card:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(13, 31, 60, 0.08);
	border-color: var(--gl-accent);
	color: inherit;
	text-decoration: none;
}
.glhp-stack-card__head {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 12px;
}
.glhp-stack-card__tier {
	display: inline-block;
	padding: 3px 10px;
	border-radius: 9999px;
	font-size: .68rem;
	font-weight: 700;
	letter-spacing: .08em;
	text-transform: uppercase;
}
.glhp-stack-card__tier--pre-seed   { background: #f0f4f9; color: var(--gl-primary); }
.glhp-stack-card__tier--seed       { background: #ecf2fb; color: #1a4480; }
.glhp-stack-card__tier--series-a   { background: #fef0e0; color: #8a4915; }
.glhp-stack-card__tier--series-b   { background: #fde4d3; color: #6b3410; }
.glhp-stack-card__tier--series-c   { background: #f3d9e4; color: #6b1f3a; }
.glhp-stack-card__tier--enterprise { background: #0d1f3c; color: #fff; }
.glhp-stack-card__cost {
	font-size: .9rem;
	font-weight: 700;
	color: var(--gl-primary);
	white-space: nowrap;
}
.glhp-stack-card__title {
	margin: 0 0 8px;
	font-size: 1.1rem;
	font-weight: 700;
	color: var(--gl-primary);
	line-height: 1.3;
}
.glhp-stack-card__icp {
	margin: 0 0 14px;
	font-size: .85rem;
	line-height: 1.5;
	color: var(--gl-text);
	flex: 1;
}
.glhp-stack-card__tools {
	display: flex;
	flex-direction: column;
	gap: 2px;
	margin-bottom: 14px;
	padding-top: 12px;
	border-top: 1px solid var(--gl-border);
}
.glhp-stack-card__tools-label {
	font-size: .68rem;
	font-weight: 700;
	letter-spacing: .08em;
	text-transform: uppercase;
	color: var(--gl-text-muted);
}
.glhp-stack-card__tools-list {
	font-size: .8rem;
	color: var(--gl-text);
	line-height: 1.4;
}
.glhp-stack-card__cta {
	font-size: .82rem;
	font-weight: 600;
	color: var(--gl-accent);
}
.glhp-stack-card:hover .glhp-stack-card__cta {
	color: var(--gl-primary);
}

/* Comparison table */
.glhp-stack-compare__h2 {
	margin: 0 0 16px;
	font-size: 1.4rem;
	color: var(--gl-primary);
}
.glhp-stack-compare-wrap {
	overflow-x: auto;
	border: 1px solid var(--gl-border);
	border-radius: 8px;
	background: var(--gl-white);
}
.glhp-stack-compare {
	width: 100%;
	border-collapse: collapse;
	font-size: .9rem;
}
.glhp-stack-compare th,
.glhp-stack-compare td {
	padding: 14px 16px;
	text-align: left;
	border-bottom: 1px solid var(--gl-border);
	vertical-align: middle;
}
.glhp-stack-compare th {
	font-size: .72rem;
	font-weight: 700;
	letter-spacing: .08em;
	text-transform: uppercase;
	color: var(--gl-text-muted);
	background: var(--gl-surface);
}
.glhp-stack-compare tbody tr:last-child td {
	border-bottom: 0;
}
.glhp-stack-compare__tier {
	display: inline-block;
	padding: 2px 8px;
	border-radius: 9999px;
	font-size: .65rem;
	font-weight: 700;
	letter-spacing: .08em;
	text-transform: uppercase;
}
.glhp-stack-compare__cta {
	color: var(--gl-accent);
	font-weight: 600;
	text-decoration: none;
	white-space: nowrap;
}
.glhp-stack-compare__cta:hover {
	color: var(--gl-primary);
}
@media (max-width: 720px) {
	.glhp-stack-grid { grid-template-columns: 1fr; }
}
</style>

<?php get_footer(); ?>
