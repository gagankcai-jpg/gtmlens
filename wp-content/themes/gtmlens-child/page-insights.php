<?php
/*
 * Template Name: Insights Archive
 * Template Post Type: page
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

// Category -> gradient class map
$gradient_for_cat = [
	'deep-dive'      => 'gradient-purple',
	'market-map'     => 'gradient-blue',
	'battle-card'    => 'gradient-amber',
	'funding'        => 'gradient-emerald',
	'state-of'       => 'gradient-slate',
	'claude-for-gtm' => 'gradient-purple',
];
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
	<div class="gl-card-row" style="grid-template-columns:repeat(auto-fill,minmax(280px,1fr));">
		<?php foreach ( $posts as $i => $p ) :
			$cats        = get_the_category( $p->ID );
			$cat_name    = $cats ? $cats[0]->name : 'Insight';
			$cat_slug    = $cats ? $cats[0]->slug : '';
			$grad_class  = $gradient_for_cat[ $cat_slug ] ?? ( ['gradient-blue','gradient-purple','gradient-amber','gradient-emerald','gradient-slate'][ $i % 5 ] );
			$pub_date    = get_the_date( 'M j, Y', $p->ID );
			$has_thumb   = has_post_thumbnail( $p->ID );
			$thumb_url   = $has_thumb ? get_the_post_thumbnail_url( $p->ID, 'medium_large' ) : '';
			$title       = get_the_title( $p->ID );
			$excerpt     = wp_trim_words( get_the_excerpt( $p->ID ), 22 );
			?>
			<a class="gl-card" href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">
				<div class="gl-card-cover <?php echo esc_attr( $grad_class ); ?>"<?php if ( $has_thumb ) : ?> style="background-image:linear-gradient(180deg, rgba(13,31,60,.30), rgba(13,31,60,.65)), url('<?php echo esc_url( $thumb_url ); ?>'); background-size:cover; background-position:center;"<?php endif; ?>>
					<span class="gl-cover-cat"><?php echo esc_html( $cat_name ); ?></span>
					<div class="gl-cover-title"><?php echo esc_html( $title ); ?></div>
				</div>
				<div class="gl-card-body">
					<p><?php echo esc_html( $excerpt ); ?></p>
					<div class="gl-card-foot">
						<span><?php echo esc_html( $pub_date ); ?></span>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
