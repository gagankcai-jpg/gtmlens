<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();
?>

<div class="gl-section">

	<!-- Editorial intro (term description) -->
	<h1><?php echo esc_html( $term->name ); ?></h1>

	<?php if ( $term->description ) : ?>
		<div class="gl-editorial-intro" style="max-width:720px;color:var(--gl-text-muted);font-size:1rem;line-height:1.7;margin-bottom:32px;">
			<?php echo wp_kses_post( $term->description ); ?>
		</div>
	<?php else : ?>
		<p style="color:var(--gl-text-muted);max-width:640px;margin-bottom:32px;">
			<?php printf(
				esc_html__( 'Independent coverage of every major vendor in the %s category — no affiliate incentives, no paid placements.', 'gtmlens-child' ),
				esc_html( $term->name )
			); ?>
		</p>
	<?php endif; ?>

	<!-- Vendor grid filtered to this category -->
	<?php if ( have_posts() ) : ?>
		<div class="gl-vendor-grid">
			<?php while ( have_posts() ) : the_post();
				$pid          = get_the_ID();
				$logo         = get_field( 'logo', $pid );
				$pricing_tier = get_field( 'pricing_tier', $pid );
				$target       = get_field( 'target_segment', $pid );
				?>
				<a class="gl-vendor-card" href="<?php the_permalink(); ?>">
					<?php if ( $logo && ! empty( $logo['url'] ) ) : ?>
						<img
							class="gl-vendor-card__logo"
							src="<?php echo esc_url( $logo['url'] ); ?>"
							alt="<?php echo esc_attr( get_the_title() . ' logo' ); ?>"
							width="48" height="48"
							loading="lazy"
						/>
					<?php endif; ?>
					<div class="gl-vendor-card__name"><?php the_title(); ?></div>
					<div class="gl-vendor-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></div>
					<div class="gl-vendor-card__meta">
						<?php if ( $pricing_tier ) : ?>
							<span><?php echo esc_html( $pricing_tier ); ?></span>
						<?php endif; ?>
						<?php if ( $target ) : ?>
							&nbsp;·&nbsp;<span><?php echo esc_html( $target ); ?></span>
						<?php endif; ?>
					</div>
				</a>
			<?php endwhile; ?>
		</div>

		<div style="margin-top:40px;">
			<?php the_posts_pagination( [
				'prev_text' => '← ' . __( 'Previous', 'gtmlens-child' ),
				'next_text' => __( 'Next', 'gtmlens-child' ) . ' →',
			] ); ?>
		</div>

	<?php else : ?>
		<p><?php esc_html_e( 'No vendors in this category yet.', 'gtmlens-child' ); ?></p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
