<?php
/*
 * Template Name: Insights Archive
 * Template Post Type: page
 *
 * Lists all published posts (insights) in a grid.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$posts = get_posts( [
	'post_type'      => 'post',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
] );
?>

<section class="glhp-hero" style="padding-bottom: 32px;">
	<p class="glhp-hero__eyebrow"><?php esc_html_e( 'Editorial', 'gtmlens-child' ); ?></p>
	<h1 class="glhp-hero__h1"><?php esc_html_e( 'Insights', 'gtmlens-child' ); ?></h1>
	<p class="glhp-hero__sub" style="max-width:720px;">
		<?php esc_html_e( 'Long-form analyst writing on the AI-native GTM stack: market maps, deep dives, battle cards, funding analysis, and quarterly state-of reports.', 'gtmlens-child' ); ?>
	</p>
</section>

<?php if ( $posts ) : ?>
<section class="glhp-boxed glhp-boxed--white" style="padding: 32px 24px 80px; max-width:1200px; margin: 0 auto;">
	<div class="glhp-insight-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;">
		<?php foreach ( $posts as $i => $p ) :
			$cats = get_the_category( $p->ID );
			$cat_name = $cats ? $cats[0]->name : '';
			$pub_date = get_the_date( 'M j, Y', $p->ID );
			$border_color = ( 0 === $i % 3 ) ? 'var(--gl-accent)' : 'var(--gl-primary)';
			?>
			<a class="glhp-insight-card" href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"
			   style="display:flex;flex-direction:column;background:var(--gl-white);border:1px solid var(--gl-border);border-top:4px solid <?php echo esc_attr( $border_color ); ?>;border-radius:8px;padding:24px;text-decoration:none;color:inherit;transition:transform .15s,box-shadow .15s;">
				<?php if ( $cat_name ) : ?>
					<span style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gl-text-muted);margin-bottom:8px;"><?php echo esc_html( $cat_name ); ?></span>
				<?php endif; ?>
				<h3 style="margin:0 0 12px;font-size:1.1rem;line-height:1.35;color:var(--gl-primary);"><?php echo esc_html( get_the_title( $p->ID ) ); ?></h3>
				<p style="margin:0 0 16px;color:var(--gl-text-muted);font-size:.9rem;line-height:1.5;flex:1;">
					<?php echo esc_html( wp_trim_words( get_the_excerpt( $p->ID ), 22 ) ); ?>
				</p>
				<span style="font-size:.8rem;color:var(--gl-text-muted);"><?php echo esc_html( $pub_date ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
