<?php
/*
 * Template Name: GTM Engineering Playbooks Index
 * Template Post Type: page
 *
 * Renders all posts in the `playbook` category as a single-page grid.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$category_slug = 'playbook';
$heading       = __( 'Playbooks', 'gtmlens-child' );
$subtitle      = __( 'Step-by-step builds you can copy. Each playbook ships with the tools, the data flow, the integration steps, and the gotchas.', 'gtmlens-child' );

$posts = get_posts( [
	'post_type'      => 'post',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'category_name'  => $category_slug,
	'orderby'        => 'date',
	'order'          => 'DESC',
] );
?>

<section class="glhp-hero" style="padding-bottom: 32px;">
	<p class="glhp-hero__eyebrow"><?php esc_html_e( 'GTM Engineering', 'gtmlens-child' ); ?></p>
	<h1 class="glhp-hero__h1"><?php echo esc_html( $heading ); ?></h1>
	<p class="glhp-hero__sub" style="max-width:720px;"><?php echo esc_html( $subtitle ); ?></p>
</section>

<?php if ( $posts ) : ?>
<section class="glhp-boxed glhp-boxed--white" style="padding: 32px 24px 80px; max-width:1200px; margin: 0 auto;">
	<div class="glhp-insight-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;">
		<?php foreach ( $posts as $i => $p ) :
			$pub_date = get_the_date( 'M j, Y', $p->ID );
			$border_color = ( 0 === $i % 3 ) ? 'var(--gl-accent)' : 'var(--gl-primary)';
			?>
			<a class="glhp-insight-card" href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"
			   style="display:flex;flex-direction:column;background:var(--gl-white);border:1px solid var(--gl-border);border-top:4px solid <?php echo esc_attr( $border_color ); ?>;border-radius:8px;padding:24px;text-decoration:none;color:inherit;transition:transform .15s,box-shadow .15s;">
				<span style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gl-text-muted);margin-bottom:8px;"><?php esc_html_e( 'Playbook', 'gtmlens-child' ); ?></span>
				<h3 style="margin:0 0 12px;font-size:1.1rem;line-height:1.35;color:var(--gl-primary);"><?php echo esc_html( get_the_title( $p->ID ) ); ?></h3>
				<p style="margin:0 0 16px;color:var(--gl-text-muted);font-size:.9rem;line-height:1.5;flex:1;">
					<?php echo esc_html( wp_trim_words( get_the_excerpt( $p->ID ), 24 ) ); ?>
				</p>
				<span style="font-size:.8rem;color:var(--gl-text-muted);"><?php echo esc_html( $pub_date ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php else : ?>
	<section style="max-width:720px;margin:0 auto;padding:32px 24px 80px;">
		<p style="color:var(--gl-text-muted);"><?php esc_html_e( 'No playbooks published yet.', 'gtmlens-child' ); ?></p>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
