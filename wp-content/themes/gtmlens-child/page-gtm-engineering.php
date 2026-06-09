<?php
/*
 * Template Name: GTM Engineering Hub
 * Template Post Type: page
 *
 * Landing page for /gtm-engineering/. Surfaces 6 section cards + a featured playbook.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$page_id = get_the_ID();

$sections = [
	[
		'eyebrow' => 'Pillar',
		'title'   => 'What is GTM Engineering?',
		'desc'    => 'The category definition: what GTM engineers actually build, why the role exists now, and how it differs from RevOps.',
		'href'    => home_url( '/gtm-engineering/what-is/' ),
	],
	[
		'eyebrow' => 'Pillar',
		'title'   => 'The GTM Engineer\'s Toolkit',
		'desc'    => 'A taxonomy of the tools every GTM engineer touches — enrichment, orchestration, the LLM layer, CRM-as-database, monitoring.',
		'href'    => home_url( '/gtm-engineering/toolkit/' ),
	],
	[
		'eyebrow' => 'Curated guide',
		'title'   => 'Learning Path',
		'desc'    => 'A four-stage progression from your first Clay table to running production GTM systems. With linked resources at each stage.',
		'href'    => home_url( '/gtm-engineering/learning-path/' ),
	],
	[
		'eyebrow' => 'Library',
		'title'   => 'Playbooks',
		'desc'    => 'Step-by-step builds you can copy: waterfall enrichment, inbound routing, deanonymized visitor capture, AI SDR pipelines.',
		'href'    => home_url( '/gtm-engineering/playbooks/' ),
	],
	[
		'eyebrow' => 'Library',
		'title'   => 'Best Practices',
		'desc'    => 'Decision frameworks: how to vet a GTM tool, choosing your CRM at seed, what to measure in a stack audit, why most AI SDRs fail.',
		'href'    => home_url( '/gtm-engineering/best-practices/' ),
	],
	[
		'eyebrow' => 'Curated list',
		'title'   => 'Resources',
		'desc'    => 'Books, newsletters, podcasts, communities, courses, and conferences worth your time — each with a one-line "why."',
		'href'    => home_url( '/gtm-engineering/resources/' ),
	],
];

// Latest playbook for the featured slot
$featured = get_posts( [
	'post_type'      => 'post',
	'posts_per_page' => 1,
	'category_name'  => 'playbook',
	'orderby'        => 'date',
	'order'          => 'DESC',
] );
$featured = $featured ? $featured[0] : null;
?>

<section class="glhp-hero" style="padding-bottom: 24px;">
	<p class="glhp-hero__eyebrow"><?php esc_html_e( 'GTM Engineering', 'gtmlens-child' ); ?></p>
	<h1 class="glhp-hero__h1"><?php esc_html_e( 'Build the operating layer for AI-native revenue', 'gtmlens-child' ); ?></h1>
	<p class="glhp-hero__sub" style="max-width:720px;">
		<?php esc_html_e( 'GTM Engineering is the discipline of composing AI, data, and software into revenue systems that work without armies of SDRs. This hub is the long-form education layer: definitional pillars, a learning path, copy-able playbooks, and a curated resource list.', 'gtmlens-child' ); ?>
	</p>
</section>

<section style="padding: 16px 24px 40px; max-width: 1200px; margin: 0 auto;">
	<div class="glhp-gtmeng-grid">
		<?php foreach ( $sections as $s ) : ?>
			<a class="glhp-gtmeng-card" href="<?php echo esc_url( $s['href'] ); ?>">
				<span class="glhp-gtmeng-card__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<h2 class="glhp-gtmeng-card__title"><?php echo esc_html( $s['title'] ); ?></h2>
				<p class="glhp-gtmeng-card__desc"><?php echo esc_html( $s['desc'] ); ?></p>
				<span class="glhp-gtmeng-card__cta"><?php esc_html_e( 'Read →', 'gtmlens-child' ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<?php if ( $featured ) : ?>
<section style="max-width:1200px; margin: 32px auto 64px; padding: 0 24px;">
	<div class="glhp-featured-playbook">
		<p class="glhp-featured-playbook__eyebrow"><?php esc_html_e( 'Featured playbook', 'gtmlens-child' ); ?></p>
		<h2 class="glhp-featured-playbook__title">
			<a href="<?php echo esc_url( get_permalink( $featured->ID ) ); ?>"><?php echo esc_html( get_the_title( $featured->ID ) ); ?></a>
		</h2>
		<p class="glhp-featured-playbook__excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt( $featured->ID ), 36 ) ); ?>
		</p>
		<a href="<?php echo esc_url( get_permalink( $featured->ID ) ); ?>" class="gl-btn-primary"><?php esc_html_e( 'Read the playbook', 'gtmlens-child' ); ?> →</a>
	</div>
</section>
<?php endif; ?>

<style>
.glhp-gtmeng-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	gap: 20px;
}
.glhp-gtmeng-card {
	display: flex;
	flex-direction: column;
	background: var(--gl-white);
	border: 1px solid var(--gl-border);
	border-radius: 8px;
	padding: 22px 22px 20px;
	text-decoration: none;
	color: inherit;
	transition: transform .15s, box-shadow .15s, border-color .15s;
	min-height: 200px;
}
.glhp-gtmeng-card:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(13, 31, 60, 0.08);
	border-color: var(--gl-accent);
	color: inherit;
	text-decoration: none;
}
.glhp-gtmeng-card__eyebrow {
	font-size: .68rem;
	font-weight: 700;
	letter-spacing: .1em;
	text-transform: uppercase;
	color: var(--gl-accent);
	margin-bottom: 8px;
}
.glhp-gtmeng-card__title {
	margin: 0 0 10px;
	font-size: 1.15rem;
	font-weight: 700;
	color: var(--gl-primary);
	line-height: 1.3;
}
.glhp-gtmeng-card__desc {
	margin: 0 0 16px;
	font-size: .88rem;
	line-height: 1.55;
	color: var(--gl-text);
	flex: 1;
}
.glhp-gtmeng-card__cta {
	font-size: .82rem;
	font-weight: 600;
	color: var(--gl-accent);
}
.glhp-gtmeng-card:hover .glhp-gtmeng-card__cta {
	color: var(--gl-primary);
}

/* Featured playbook block */
.glhp-featured-playbook {
	background: var(--gl-surface);
	border: 1px solid var(--gl-border);
	border-left: 4px solid var(--gl-accent);
	border-radius: 8px;
	padding: 28px 32px 26px;
}
.glhp-featured-playbook__eyebrow {
	margin: 0 0 8px;
	font-size: .7rem;
	font-weight: 700;
	letter-spacing: .1em;
	text-transform: uppercase;
	color: var(--gl-accent);
}
.glhp-featured-playbook__title {
	margin: 0 0 12px;
	font-size: 1.5rem;
	line-height: 1.3;
	color: var(--gl-primary);
}
.glhp-featured-playbook__title a { color: inherit; text-decoration: none; }
.glhp-featured-playbook__title a:hover { color: var(--gl-accent); }
.glhp-featured-playbook__excerpt {
	margin: 0 0 18px;
	color: var(--gl-text);
	max-width: 780px;
	font-size: 1rem;
	line-height: 1.55;
}
</style>

<?php get_footer(); ?>
