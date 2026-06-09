<?php
/*
 * Template Name: Editorial Policy
 * Template Post Type: page
 *
 * Assign this template to the page at /about/editorial-policy/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="gl-section" style="max-width:760px;">

	<h1><?php esc_html_e( 'Editorial Policy', 'gtmlens-child' ); ?></h1>
	<p style="color:var(--gl-text-muted);">
		<?php esc_html_e( 'Effective April 2026 — GTMLens', 'gtmlens-child' ); ?>
	</p>

	<hr class="gl-divider" />

	<h2><?php esc_html_e( 'Our Independence Commitment', 'gtmlens-child' ); ?></h2>
	<p>
		<?php esc_html_e( 'GTMLens is an independent analyst publication. We accept no vendor money, no paid placements, no affiliate commissions, and no sponsored editorial. Every rating, SWOT analysis, and comparison is produced without commercial consideration from the vendors we cover.', 'gtmlens-child' ); ?>
	</p>
	<p>
		<?php esc_html_e( 'If a vendor relationship changes — for instance, if GTMLens were ever to accept advertising — it will be disclosed prominently on every relevant page and in this policy. We do not expect this to happen in v1 of the publication.', 'gtmlens-child' ); ?>
	</p>

	<h2><?php esc_html_e( 'Methodology', 'gtmlens-child' ); ?></h2>
	<p>
		<?php esc_html_e( 'Vendor profiles are produced using a consistent research framework:', 'gtmlens-child' ); ?>
	</p>
	<ol>
		<li><?php esc_html_e( 'Public data — company website, pricing pages, funding announcements, SEC/Crunchbase filings.', 'gtmlens-child' ); ?></li>
		<li><?php esc_html_e( 'Product evaluation — hands-on trial, demo walkthroughs, or verified user reviews from G2, Capterra, and Reddit.', 'gtmlens-child' ); ?></li>
		<li><?php esc_html_e( 'Technical architecture — documentation, API references, integration lists.', 'gtmlens-child' ); ?></li>
		<li><?php esc_html_e( 'Analyst framework — SWOT applied using the same rubric for every vendor in a category, scored relative to peers.', 'gtmlens-child' ); ?></li>
	</ol>
	<p>
		<?php esc_html_e( 'Every profile includes a "Last updated" date. Vendors may submit factual corrections (not editorial requests) to info@gtmlens.com. We will review and update within 30 days if errors are substantiated.', 'gtmlens-child' ); ?>
	</p>

	<h2><?php esc_html_e( 'Comparison Pages', 'gtmlens-child' ); ?></h2>
	<p>
		<?php esc_html_e( 'Head-to-head comparisons are based on publicly available data as of the last-updated date. Pricing, funding, and feature availability change frequently — readers should verify current details directly with vendors before making purchasing decisions.', 'gtmlens-child' ); ?>
	</p>

	<h2><?php esc_html_e( 'Stack Builder', 'gtmlens-child' ); ?></h2>
	<p>
		<?php esc_html_e( 'The Stack Builder quiz recommends vendor combinations based on your inputs. Recommendations are editorial — they reflect the analyst\'s assessment of fit, not commercial relationships. Estimated costs are pulled from entry-level public pricing and may not reflect volume discounts or enterprise rates.', 'gtmlens-child' ); ?>
	</p>

	<h2><?php esc_html_e( 'Corrections & Disputes', 'gtmlens-child' ); ?></h2>
	<p>
		<?php esc_html_e( 'Vendors or readers who believe published information is factually incorrect may submit corrections to ', 'gtmlens-child' ); ?>
		<a class="gl-contact-email" href="mailto:info@gtmlens.com">info@gtmlens.com</a>.
		<?php esc_html_e( 'We distinguish factual errors (corrected) from editorial disagreements (not altered at vendor request).', 'gtmlens-child' ); ?>
	</p>

	<h2 id="ai-assisted-drafting"><?php esc_html_e( 'AI-Assisted Drafting', 'gtmlens-child' ); ?></h2>
	<p>
		<?php esc_html_e( 'Some research and drafting is assisted by AI tools. All published content is reviewed, edited, and approved by a named human analyst before publication. AI-generated text that has not been reviewed is never published.', 'gtmlens-child' ); ?>
	</p>

	<h2><?php esc_html_e( 'Contact', 'gtmlens-child' ); ?></h2>
	<p>
		<?php esc_html_e( 'For editorial enquiries: ', 'gtmlens-child' ); ?>
		<a class="gl-contact-email" href="mailto:info@gtmlens.com">info@gtmlens.com</a>
	</p>

	<hr class="gl-divider" />

	<p style="color:var(--gl-text-muted);font-size:.85rem;">
		<?php esc_html_e( 'This policy was last updated April 2026. Changes will be noted with a revision date.', 'gtmlens-child' ); ?>
	</p>

</div>

<?php get_footer(); ?>
