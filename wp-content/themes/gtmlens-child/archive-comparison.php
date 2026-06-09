<?php
/**
 * Archive template for the `comparison` CPT.
 * Renders all comparisons as compact cards on a single page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$comparisons = get_posts( [
	'post_type'      => 'comparison',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
] );
?>

<section class="glhp-hero" style="padding-bottom: 24px;">
	<p class="glhp-hero__eyebrow"><?php esc_html_e( 'Battle cards', 'gtmlens-child' ); ?></p>
	<h1 class="glhp-hero__h1"><?php esc_html_e( 'Comparisons', 'gtmlens-child' ); ?></h1>
	<p class="glhp-hero__sub" style="max-width:720px;">
		<?php printf(
			/* translators: %d: number of comparison pages */
			esc_html__( 'Head-to-head analyst views on %d vendor pairings across the AI-native GTM stack. Each comparison includes a one-sentence verdict, an 8-dimension scorecard, and decision rules for both sides.', 'gtmlens-child' ),
			count( $comparisons )
		); ?>
	</p>
</section>

<section style="padding: 16px 24px 80px; max-width: 1200px; margin: 0 auto;">
	<?php if ( $comparisons ) : ?>
		<div class="glhp-compare-grid">
			<?php foreach ( $comparisons as $cmp ) :
				$verdict = get_field( 'verdict', $cmp->ID );
				$verdict_short = $verdict ? wp_trim_words( wp_strip_all_tags( $verdict ), 28, '…' ) : '';

				// Parse "X vs Y" or "X vs Y vs Z" from title
				$title = get_the_title( $cmp->ID );
				$parts = preg_split( '/\s+vs\.?\s+/i', $title );

				$last_updated = get_field( 'last_updated', $cmp->ID );
				$pretty_date  = $last_updated ? date_i18n( 'M Y', strtotime( $last_updated ) ) : '';
			?>
				<a class="glhp-compare-card" href="<?php echo esc_url( get_permalink( $cmp->ID ) ); ?>">
					<div class="glhp-compare-card__matchup">
						<?php foreach ( $parts as $i => $name ) : ?>
							<?php if ( $i > 0 ) : ?><span class="glhp-compare-card__vs">vs</span><?php endif; ?>
							<span class="glhp-compare-card__vendor"><?php echo esc_html( trim( $name ) ); ?></span>
						<?php endforeach; ?>
					</div>
					<div class="glhp-compare-card__rule"></div>
					<?php if ( $verdict_short ) : ?>
						<p class="glhp-compare-card__verdict"><?php echo esc_html( $verdict_short ); ?></p>
					<?php endif; ?>
					<div class="glhp-compare-card__footer">
						<?php if ( $pretty_date ) : ?>
							<span class="glhp-compare-card__date"><?php echo esc_html( $pretty_date ); ?></span>
						<?php endif; ?>
						<span class="glhp-compare-card__cta"><?php esc_html_e( 'See verdict', 'gtmlens-child' ); ?> →</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p style="text-align:center;color:var(--gl-text-muted);"><?php esc_html_e( 'No comparisons yet.', 'gtmlens-child' ); ?></p>
	<?php endif; ?>
</section>

<style>
.glhp-compare-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	gap: 20px;
}
.glhp-compare-card {
	display: flex;
	flex-direction: column;
	background: var(--gl-white);
	border: 1px solid var(--gl-border);
	border-radius: 8px;
	padding: 20px 22px;
	text-decoration: none;
	color: inherit;
	transition: transform .15s, box-shadow .15s, border-color .15s;
	min-height: 180px;
}
.glhp-compare-card:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(13, 31, 60, 0.08);
	border-color: var(--gl-accent);
	color: inherit;
	text-decoration: none;
}
.glhp-compare-card__matchup {
	display: flex;
	flex-wrap: wrap;
	align-items: baseline;
	gap: 8px;
	margin-bottom: 10px;
}
.glhp-compare-card__vendor {
	font-size: 1.05rem;
	font-weight: 700;
	color: var(--gl-primary);
	line-height: 1.25;
}
.glhp-compare-card__vs {
	font-size: 0.7rem;
	font-weight: 600;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	color: var(--gl-text-muted);
}
.glhp-compare-card__rule {
	width: 32px;
	height: 2px;
	background: var(--gl-accent);
	border-radius: 2px;
	margin: 4px 0 12px;
}
.glhp-compare-card__verdict {
	margin: 0 0 16px;
	font-size: 0.88rem;
	line-height: 1.5;
	color: var(--gl-text);
	flex: 1;
	display: -webkit-box;
	-webkit-line-clamp: 4;
	-webkit-box-orient: vertical;
	overflow: hidden;
}
.glhp-compare-card__footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding-top: 12px;
	border-top: 1px solid var(--gl-border);
	font-size: 0.78rem;
}
.glhp-compare-card__date {
	color: var(--gl-text-muted);
	font-weight: 500;
}
.glhp-compare-card__cta {
	color: var(--gl-accent);
	font-weight: 600;
}
.glhp-compare-card:hover .glhp-compare-card__cta {
	color: var(--gl-primary);
}
@media (max-width: 560px) {
	.glhp-compare-grid { grid-template-columns: 1fr; }
}
</style>

<?php get_footer(); ?>
