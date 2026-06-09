<?php
/*
 * Template Name: GTM Engineering Pillar
 * Template Post Type: page
 *
 * Long-form pillar template for /gtm-engineering/{what-is,toolkit,learning-path,resources}/.
 * Renders proper hero (eyebrow + h1 + subtitle from excerpt) then the body content.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$subtitle = get_the_excerpt();
	?>

	<section class="glhp-hero" style="padding-bottom: 24px;">
		<p class="glhp-hero__eyebrow"><?php esc_html_e( 'GTM Engineering', 'gtmlens-child' ); ?></p>
		<h1 class="glhp-hero__h1"><?php the_title(); ?></h1>
		<?php if ( $subtitle ) : ?>
			<p class="glhp-hero__sub" style="max-width:760px;"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
	</section>

	<div class="glhp-pillar-wrap" style="max-width: 1200px; margin: 0 auto; padding: 16px 24px 80px;">
		<article class="glhp-pillar-body" style="max-width: 780px; margin: 0;">
			<?php the_content(); ?>

			<hr class="gl-divider" style="margin: 48px 0 32px;" />

			<nav class="glhp-pillar-nav" aria-label="<?php esc_attr_e( 'GTM Engineering hub navigation', 'gtmlens-child' ); ?>">
				<p class="glhp-pillar-nav__eyebrow"><?php esc_html_e( 'More from GTM Engineering', 'gtmlens-child' ); ?></p>
				<ul class="glhp-pillar-nav__list">
					<li><a href="<?php echo esc_url( home_url( '/gtm-engineering/' ) ); ?>"><?php esc_html_e( 'Hub', 'gtmlens-child' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/gtm-engineering/what-is/' ) ); ?>"><?php esc_html_e( 'What is', 'gtmlens-child' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/gtm-engineering/toolkit/' ) ); ?>"><?php esc_html_e( 'Toolkit', 'gtmlens-child' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/gtm-engineering/learning-path/' ) ); ?>"><?php esc_html_e( 'Learning Path', 'gtmlens-child' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/gtm-engineering/playbooks/' ) ); ?>"><?php esc_html_e( 'Playbooks', 'gtmlens-child' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/gtm-engineering/best-practices/' ) ); ?>"><?php esc_html_e( 'Best Practices', 'gtmlens-child' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/gtm-engineering/resources/' ) ); ?>"><?php esc_html_e( 'Resources', 'gtmlens-child' ); ?></a></li>
				</ul>
			</nav>
		</article>
	</div>

<?php endwhile; ?>

<style>
.glhp-pillar-body p,
.glhp-pillar-body ul,
.glhp-pillar-body ol {
	font-size: 1.02rem;
	line-height: 1.7;
	color: var(--gl-text);
}
.glhp-pillar-body h2 {
	margin-top: 40px;
	margin-bottom: 12px;
	font-size: 1.45rem;
	color: var(--gl-primary);
	line-height: 1.3;
}
.glhp-pillar-body h3 {
	margin-top: 28px;
	margin-bottom: 8px;
	font-size: 1.15rem;
	color: var(--gl-primary);
}
.glhp-pillar-body .glhp-lede,
.glhp-pillar-body p:first-child {
	font-size: 1.18rem;
	line-height: 1.6;
	color: var(--gl-primary);
	font-weight: 500;
	margin-bottom: 24px;
	padding-bottom: 20px;
	border-bottom: 1px solid var(--gl-border);
}
.glhp-pillar-body ul, .glhp-pillar-body ol { padding-left: 1.4em; }
.glhp-pillar-body li { margin-bottom: 6px; }
.glhp-pillar-body a { color: var(--gl-accent); text-decoration: underline; text-underline-offset: 3px; }
.glhp-pillar-body a:hover { color: var(--gl-primary); }
.glhp-pillar-body strong { color: var(--gl-primary); }

.glhp-pillar-nav__eyebrow {
	font-size: .7rem;
	font-weight: 700;
	letter-spacing: .1em;
	text-transform: uppercase;
	color: var(--gl-text-muted);
	margin: 0 0 10px;
}
.glhp-pillar-nav__list {
	list-style: none;
	padding: 0;
	margin: 0;
	display: flex;
	flex-wrap: wrap;
	gap: 8px 14px;
	font-size: .92rem;
}
.glhp-pillar-nav__list li::before { content: ""; }
.glhp-pillar-nav__list a {
	color: var(--gl-text);
	text-decoration: none;
	border-bottom: 1px solid transparent;
}
.glhp-pillar-nav__list a:hover {
	color: var(--gl-accent);
	border-bottom-color: var(--gl-accent);
}
</style>

<?php get_footer(); ?>
