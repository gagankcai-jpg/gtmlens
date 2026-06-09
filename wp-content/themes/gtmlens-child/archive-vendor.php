<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Collect all vendor_category terms for the filter chips
$all_categories = get_terms( [
	'taxonomy'   => 'vendor_category',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );

// Active filter from query string (sanitized)
$active_slug = isset( $_GET['category'] ) ? sanitize_key( $_GET['category'] ) : '';

// Pull ALL vendors on a single page (no pagination)
$all_vendors = get_posts( [
	'post_type'      => 'vendor',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'title',
	'order'          => 'ASC',
] );
?>

<div class="gl-section">
	<h1><?php esc_html_e( 'GTM Vendors', 'gtmlens-child' ); ?></h1>
	<p style="color:var(--gl-text-muted);max-width:640px;">
		<?php esc_html_e( 'Independent profiles on every major vendor in the AI-native GTM stack — rated without affiliate incentives or vendor funding.', 'gtmlens-child' ); ?>
	</p>

	<!-- Category filter chips -->
	<?php if ( $all_categories && ! is_wp_error( $all_categories ) ) : ?>
		<nav class="gl-filter-chips" aria-label="<?php esc_attr_e( 'Filter by category', 'gtmlens-child' ); ?>">
			<a
				class="gl-chip<?php echo '' === $active_slug ? ' gl-chip--active' : ''; ?>"
				href="<?php echo esc_url( get_post_type_archive_link( 'vendor' ) ); ?>"
			><?php esc_html_e( 'All', 'gtmlens-child' ); ?></a>
			<?php foreach ( $all_categories as $cat ) : ?>
				<a
					class="gl-chip<?php echo $active_slug === $cat->slug ? ' gl-chip--active' : ''; ?>"
					href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
				><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<!-- Vendor grid (all on a single page) -->
	<?php if ( $all_vendors ) : ?>
		<p style="color:var(--gl-text-muted);font-size:.85rem;margin:8px 0 24px;">
			<?php printf( esc_html__( 'Showing all %d vendors.', 'gtmlens-child' ), count( $all_vendors ) ); ?>
		</p>
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
	<?php else : ?>
		<p><?php esc_html_e( 'No vendors found.', 'gtmlens-child' ); ?></p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
