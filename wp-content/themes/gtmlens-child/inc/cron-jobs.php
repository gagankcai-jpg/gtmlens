<?php
/**
 * Tier 2 — Auto-ingest with human approval queue
 *
 * Cron jobs:
 *   1. Weekly RSS funding-event ingest (Monday)
 *   2. Weekly vendor change scanner (Monday)
 *   3. Weekly digest email to info@gtmlens.com (Monday, after 1 + 2)
 *
 * All ingested events are created as draft funding_event posts with meta `_auto_ingested=1`.
 * User approves via the admin review queue at Funding Events → Auto-ingested.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'GTMLENS_AUTO_INGEST_HOOK' ) )  define( 'GTMLENS_AUTO_INGEST_HOOK',  'gtmlens_weekly_auto_ingest' );
if ( ! defined( 'GTMLENS_AUTO_INGEST_EMAIL' ) ) define( 'GTMLENS_AUTO_INGEST_EMAIL', 'info@gtmlens.com' );

/* ─────────────────────────────────────────────────────────────────────────
   1. SCHEDULE WP-CRON ON THEME ACTIVATION
   ───────────────────────────────────────────────────────────────────────── */

add_action( 'after_switch_theme', function () {
	if ( ! wp_next_scheduled( GTMLENS_AUTO_INGEST_HOOK ) ) {
		// First Monday at 1:30 UTC (= 7:00 IST)
		$next_monday = strtotime( 'next monday 01:30 UTC' );
		wp_schedule_event( $next_monday, 'weekly', GTMLENS_AUTO_INGEST_HOOK );
	}
} );

/* Bail-safe: also ensure schedule on init in case activation hook missed */
add_action( 'init', function () {
	if ( ! wp_next_scheduled( GTMLENS_AUTO_INGEST_HOOK ) ) {
		$next_monday = strtotime( 'next monday 01:30 UTC' );
		wp_schedule_event( $next_monday, 'weekly', GTMLENS_AUTO_INGEST_HOOK );
	}
} );

/* Unschedule on theme deactivation */
add_action( 'switch_theme', function () {
	$ts = wp_next_scheduled( GTMLENS_AUTO_INGEST_HOOK );
	if ( $ts ) wp_unschedule_event( $ts, GTMLENS_AUTO_INGEST_HOOK );
} );

/* ─────────────────────────────────────────────────────────────────────────
   2. CONFIG: RSS feeds + GTM keyword filter
   ───────────────────────────────────────────────────────────────────────── */

function gtmlens_rss_feeds(): array {
	return [
		'TechCrunch Venture'    => 'https://techcrunch.com/category/venture/feed/',
		'TechCrunch Enterprise' => 'https://techcrunch.com/category/enterprise/feed/',
		'Crunchbase News'       => 'https://news.crunchbase.com/feed/',
	];
}

function gtmlens_gtm_keywords(): array {
	return [
		// Categories
		'crm', 'outbound', 'ai sdr', 'sdr', 'sales tech', 'salestech',
		'revenue intelligence', 'sales enablement', 'data enrichment', 'intent signal',
		'prospecting', 'lead generation', 'lead routing', 'sales engagement',
		'sales pipeline', 'conversational ai', 'voice ai', 'agentic outbound',
		'go-to-market', 'gtm ', 'demand generation', 'demandgen', 'lead capture',
		'sales automation', 'linkedin automation', 'cold outreach', 'email outreach',
		'account-based', 'abm', 'revenue operations', 'revops',
		'customer data platform', 'cdp', 'sales cadence', 'marketing automation',
		'pipeline generation', 'go to market', 'pipeline tools',
		// Specific company names that frequently appear in GTM-relevant news
		'salesforce', 'hubspot', 'gong', 'outreach', 'salesloft', 'apollo',
		'clay ', 'zoominfo', '6sense', 'demandbase', 'glean', 'sierra ai',
		'decagon', 'cresta', 'clari', 'attio', 'salesforce', 'pipedrive',
	];
}

/* ─────────────────────────────────────────────────────────────────────────
   3. RSS INGEST
   ───────────────────────────────────────────────────────────────────────── */

function gtmlens_rss_ingest(): array {
	$report = [ 'feeds_scanned' => 0, 'items_seen' => 0, 'matched' => 0, 'created' => 0, 'skipped' => 0, 'errors' => [] ];
	$keywords = gtmlens_gtm_keywords();

	include_once ABSPATH . WPINC . '/feed.php';

	foreach ( gtmlens_rss_feeds() as $name => $url ) {
		$feed = fetch_feed( $url );
		if ( is_wp_error( $feed ) ) {
			$report['errors'][] = "$name: " . $feed->get_error_message();
			continue;
		}
		$report['feeds_scanned']++;
		$max = $feed->get_item_quantity( 30 );
		$items = $feed->get_items( 0, $max );

		foreach ( $items as $item ) {
			$report['items_seen']++;
			$title    = trim( wp_strip_all_tags( $item->get_title() ?: '' ) );
			$desc     = trim( wp_strip_all_tags( $item->get_description() ?: '' ) );
			$link     = esc_url_raw( $item->get_link() ?: '' );
			$pub_date = $item->get_date( 'Y-m-d' ) ?: gmdate( 'Y-m-d' );
			if ( ! $title || ! $link ) continue;

			// Keyword filter
			$haystack = strtolower( $title . ' ' . $desc );
			$matched = false;
			foreach ( $keywords as $kw ) {
				if ( false !== strpos( $haystack, strtolower( $kw ) ) ) { $matched = true; break; }
			}
			if ( ! $matched ) continue;
			$report['matched']++;

			// Funding-event signal: title or description contains $X / Series / raised / acquisition / IPO
			$looks_like_funding = (bool) preg_match( '/(\$\s?\d|series\s+[a-z]\b|\braised\b|\bfunding\b|\bvaluation\b|\backqui|\bIPO\b|seed round|pre-seed)/i', $title . ' ' . $desc );
			if ( ! $looks_like_funding ) continue;

			// Dedupe by URL hash
			$url_hash = hash( 'sha256', $link );
			$existing = get_posts( [
				'post_type'      => 'funding_event',
				'post_status'    => 'any',
				'meta_key'       => '_rss_url_hash',
				'meta_value'     => $url_hash,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			] );
			if ( $existing ) { $report['skipped']++; continue; }

			$post_id = wp_insert_post( [
				'post_type'   => 'funding_event',
				'post_status' => 'draft',
				'post_title'  => sprintf( '[Auto] %s', $title ),
				'post_name'   => sanitize_title( substr( $title, 0, 80 ) . '-' . substr( $url_hash, 0, 8 ) ),
			], true );
			if ( is_wp_error( $post_id ) ) {
				$report['errors'][] = $post_id->get_error_message();
				continue;
			}

			update_field( 'company_name', wp_html_excerpt( $title, 80, '' ), $post_id );
			update_field( 'event_date',   $pub_date,    $post_id );
			update_field( 'event_type',   'round',      $post_id );
			update_field( 'source_url',   $link,        $post_id );
			update_post_meta( $post_id, '_auto_ingested', 1 );
			update_post_meta( $post_id, '_rss_source',    $name );
			update_post_meta( $post_id, '_rss_url_hash',  $url_hash );
			update_post_meta( $post_id, '_rss_summary',   wp_html_excerpt( $desc, 280, '…' ) );
			$report['created']++;
		}
	}
	return $report;
}

/* ─────────────────────────────────────────────────────────────────────────
   4. VENDOR CHANGE SCANNER
   ───────────────────────────────────────────────────────────────────────── */

function gtmlens_scan_vendor_changes(): array {
	$report = [ 'scanned' => 0, 'changed' => [], 'errors' => [] ];
	$vendors = get_posts( [
		'post_type'      => 'vendor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
	] );
	foreach ( $vendors as $vid ) {
		$url = (string) get_field( 'vendor_url', $vid );
		if ( ! $url ) continue;
		$report['scanned']++;

		$resp = wp_remote_get( $url, [
			'timeout'    => 12,
			'user-agent' => 'GTMLens-Bot/1.0 (+https://gtmlens.com)',
			'redirection'=> 3,
		] );
		if ( is_wp_error( $resp ) ) {
			$report['errors'][] = get_post_field( 'post_name', $vid ) . ': ' . $resp->get_error_message();
			continue;
		}
		$code = wp_remote_retrieve_response_code( $resp );
		if ( $code !== 200 ) continue;

		$body = wp_remote_retrieve_body( $resp );
		// Strip variable noise (timestamps, csrf tokens) before hashing
		$normalized = preg_replace( '/(nonce|csrf|timestamp|cache-bust)[^"\']{0,40}["\']/i', '', $body );
		$hash       = hash( 'sha256', $normalized );

		$prev = get_post_meta( $vid, '_url_hash_v1', true );
		if ( $prev && $prev !== $hash ) {
			$report['changed'][] = [
				'slug'  => get_post_field( 'post_name', $vid ),
				'title' => get_the_title( $vid ),
				'url'   => $url,
				'edit'  => admin_url( "post.php?post=$vid&action=edit" ),
			];
		}
		update_post_meta( $vid, '_url_hash_v1',     $hash );
		update_post_meta( $vid, '_url_hash_v1_at', current_time( 'mysql', 1 ) );
	}
	return $report;
}

/* ─────────────────────────────────────────────────────────────────────────
   5. EMAIL DIGEST
   ───────────────────────────────────────────────────────────────────────── */

function gtmlens_send_weekly_digest( array $rss_report, array $vendor_report ): bool {
	$queue_url = admin_url( 'edit.php?post_type=funding_event&gtmlens_queue=1' );
	$lines = [];
	$lines[] = 'GTMLens — weekly auto-ingest summary';
	$lines[] = str_repeat( '─', 48 );
	$lines[] = '';
	$lines[] = 'FUNDING EVENT CANDIDATES';
	$lines[] = sprintf( '  Feeds scanned: %d   Items seen: %d   Matched: %d   Drafts created: %d   Already-known (skipped): %d',
		$rss_report['feeds_scanned'], $rss_report['items_seen'], $rss_report['matched'],
		$rss_report['created'], $rss_report['skipped'] );
	if ( $rss_report['created'] > 0 ) {
		$lines[] = '';
		$lines[] = sprintf( '  → %d new candidates ready for approval:', $rss_report['created'] );
		$lines[] = "  $queue_url";
		$lines[] = '';
		$lines[] = '  Workflow: open the link, scan titles, tick checkboxes, "Approve & publish" in bulk.';
	} else {
		$lines[] = '';
		$lines[] = '  No new candidates this week.';
	}
	$lines[] = '';
	$lines[] = 'VENDOR CHANGE SCAN';
	$lines[] = sprintf( '  Vendors scanned: %d   Material changes detected: %d',
		$vendor_report['scanned'], count( $vendor_report['changed'] ) );
	if ( $vendor_report['changed'] ) {
		$lines[] = '';
		foreach ( $vendor_report['changed'] as $c ) {
			$lines[] = sprintf( '  • %s — %s', $c['title'], $c['url'] );
			$lines[] = sprintf( '    Edit: %s', $c['edit'] );
		}
		$lines[] = '';
		$lines[] = '  Workflow: review the diff (compare current page vs your profile), update last_updated + analyst_take.';
	}
	if ( ! empty( $rss_report['errors'] ) || ! empty( $vendor_report['errors'] ) ) {
		$lines[] = '';
		$lines[] = 'ERRORS';
		foreach ( array_merge( $rss_report['errors'], $vendor_report['errors'] ) as $e ) {
			$lines[] = '  ! ' . $e;
		}
	}
	$lines[] = '';
	$lines[] = '─';
	$lines[] = sprintf( 'Sent %s · %s', current_time( 'D, j M Y H:i T' ), home_url() );

	$body = implode( "\n", $lines );
	$subject = sprintf( '[GTMLens] %d candidates · %d vendor changes',
		(int) $rss_report['created'], count( $vendor_report['changed'] ) );

	return wp_mail( GTMLENS_AUTO_INGEST_EMAIL, $subject, $body );
}

/* ─────────────────────────────────────────────────────────────────────────
   6. RUN — fired by cron OR manual trigger
   Vendor scan can take 60+ seconds (31 URL fetches). Run async via WP cron.
   ───────────────────────────────────────────────────────────────────────── */

if ( ! defined( 'GTMLENS_VENDOR_SCAN_HOOK' ) ) define( 'GTMLENS_VENDOR_SCAN_HOOK', 'gtmlens_run_vendor_scan' );
if ( ! defined( 'GTMLENS_DIGEST_HOOK' ) )      define( 'GTMLENS_DIGEST_HOOK',      'gtmlens_send_digest' );

/**
 * Manual trigger / scheduled weekly hook.
 * Runs RSS ingest synchronously (~5-10s), schedules vendor scan async.
 */
function gtmlens_run_auto_ingest(): array {
	@set_time_limit( 60 );
	$rss = [ 'feeds_scanned' => 0, 'items_seen' => 0, 'matched' => 0, 'created' => 0, 'skipped' => 0, 'errors' => [] ];
	try {
		$rss = gtmlens_rss_ingest();
	} catch ( \Throwable $e ) {
		$rss['errors'][] = 'rss_ingest_exception: ' . $e->getMessage();
		error_log( 'GTMLens rss_ingest exception: ' . $e->getMessage() );
	}
	update_option( 'gtmlens_auto_ingest_last_run', current_time( 'mysql', 1 ) );
	update_option( 'gtmlens_auto_ingest_last_report', [ 'rss' => $rss, 'vendor' => [ 'scanned' => 0, 'changed' => [], 'errors' => [], 'status' => 'queued' ] ] );

	if ( ! wp_next_scheduled( GTMLENS_VENDOR_SCAN_HOOK ) ) {
		wp_schedule_single_event( time() + 5, GTMLENS_VENDOR_SCAN_HOOK );
	}
	if ( ! wp_next_scheduled( GTMLENS_DIGEST_HOOK ) ) {
		wp_schedule_single_event( time() + 90, GTMLENS_DIGEST_HOOK );
	}
	return [ 'rss' => $rss, 'vendor_scan' => 'scheduled' ];
}
add_action( GTMLENS_AUTO_INGEST_HOOK, 'gtmlens_run_auto_ingest' );

add_action( GTMLENS_VENDOR_SCAN_HOOK, function () {
	@set_time_limit( 300 );
	try {
		$vendor = gtmlens_scan_vendor_changes();
	} catch ( \Throwable $e ) {
		$vendor = [ 'scanned' => 0, 'changed' => [], 'errors' => [ 'vendor_scan_exception: ' . $e->getMessage() ] ];
		error_log( 'GTMLens vendor_scan exception: ' . $e->getMessage() );
	}
	$prev   = (array) get_option( 'gtmlens_auto_ingest_last_report', [] );
	$prev['vendor'] = $vendor;
	update_option( 'gtmlens_auto_ingest_last_report', $prev );
} );

add_action( GTMLENS_DIGEST_HOOK, function () {
	try {
		$report = (array) get_option( 'gtmlens_auto_ingest_last_report', [] );
		$rss    = isset( $report['rss'] )    ? $report['rss']    : [ 'feeds_scanned' => 0, 'items_seen' => 0, 'matched' => 0, 'created' => 0, 'skipped' => 0, 'errors' => [] ];
		$vendor = isset( $report['vendor'] ) ? $report['vendor'] : [ 'scanned' => 0, 'changed' => [], 'errors' => [] ];
		gtmlens_send_weekly_digest( $rss, $vendor );
	} catch ( \Throwable $e ) {
		error_log( 'GTMLens digest exception: ' . $e->getMessage() );
	}
} );

/* ─────────────────────────────────────────────────────────────────────────
   7. ADMIN — Review queue + manual run button
   ───────────────────────────────────────────────────────────────────────── */

/* Submenu under Funding Events */
add_action( 'admin_menu', function () {
	add_submenu_page(
		'edit.php?post_type=funding_event',
		__( 'Auto-ingested', 'gtmlens-child' ),
		__( 'Auto-ingested', 'gtmlens-child' ),
		'edit_posts',
		'gtmlens-auto-ingested',
		'gtmlens_auto_ingested_page'
	);
} );

function gtmlens_auto_ingested_page(): void {
	if ( ! current_user_can( 'edit_posts' ) ) return;

	// Handle bulk approve
	if ( isset( $_POST['gtmlens_bulk'] ) && check_admin_referer( 'gtmlens_auto_ingest' ) ) {
		$ids = isset( $_POST['gl_ids'] ) ? array_map( 'intval', (array) $_POST['gl_ids'] ) : [];
		$action = sanitize_text_field( $_POST['gtmlens_bulk'] );
		$count = 0;
		foreach ( $ids as $id ) {
			if ( $action === 'approve' ) {
				wp_update_post( [ 'ID' => $id, 'post_status' => 'publish' ] );
				// Strip [Auto] prefix on approval
				$t = get_the_title( $id );
				if ( 0 === strpos( $t, '[Auto] ' ) ) {
					wp_update_post( [ 'ID' => $id, 'post_title' => trim( substr( $t, 7 ) ) ] );
				}
				delete_post_meta( $id, '_auto_ingested' );
				$count++;
			} elseif ( $action === 'trash' ) {
				wp_trash_post( $id );
				$count++;
			}
		}
		echo '<div class="notice notice-success"><p>' . sprintf( esc_html__( '%d items %s.', 'gtmlens-child' ), $count, $action === 'approve' ? 'approved & published' : 'trashed' ) . '</p></div>';
	}

	// Handle manual run
	if ( isset( $_POST['gtmlens_run_now'] ) && check_admin_referer( 'gtmlens_auto_ingest' ) ) {
		$report = gtmlens_run_auto_ingest();
		echo '<div class="notice notice-success"><p>' . sprintf(
			esc_html__( 'Ingest run complete: %d new candidates, %d vendor changes detected.', 'gtmlens-child' ),
			(int) $report['rss']['created'], count( $report['vendor']['changed'] )
		) . '</p></div>';
	}

	$pending = get_posts( [
		'post_type'      => 'funding_event',
		'post_status'    => 'draft',
		'meta_key'       => '_auto_ingested',
		'meta_value'     => '1',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	] );
	$last_run    = (string) get_option( 'gtmlens_auto_ingest_last_run', 'never' );
	$last_report = (array)  get_option( 'gtmlens_auto_ingest_last_report', [] );
	$next        = wp_next_scheduled( GTMLENS_AUTO_INGEST_HOOK );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Auto-ingested funding event candidates', 'gtmlens-child' ); ?></h1>
		<p>RSS-ingested drafts pending your approval. Tick checkboxes → Bulk approve. Edit individual rows to fill in amount / valuation / analyst note before approving.</p>

		<div style="background:#f0f6fc;border-left:4px solid #2271b1;padding:12px 16px;margin:16px 0;">
			<strong>Last cron run:</strong> <code><?php echo esc_html( $last_run ); ?></code><br>
			<strong>Next scheduled run:</strong> <code><?php echo $next ? esc_html( gmdate( 'D, j M Y H:i', $next ) . ' UTC' ) : 'not scheduled'; ?></code>
			<?php if ( $last_report ) : ?>
				<br><strong>Last report:</strong> <?php echo (int) ( $last_report['rss']['created'] ?? 0 ); ?> candidates created · <?php echo (int) ( $last_report['rss']['skipped'] ?? 0 ); ?> dupes skipped · <?php echo count( $last_report['vendor']['changed'] ?? [] ); ?> vendor changes
			<?php endif; ?>
		</div>

		<form method="post" style="display:inline-block;margin-bottom:14px;">
			<?php wp_nonce_field( 'gtmlens_auto_ingest' ); ?>
			<input type="hidden" name="gtmlens_run_now" value="1">
			<button type="submit" class="button" onclick="return confirm('Run ingest now? Will fetch RSS feeds and email digest.');">Run ingest now</button>
		</form>

		<?php if ( ! $pending ) : ?>
			<p style="padding:24px;background:#fff;border:1px solid #ccd0d4;text-align:center;color:#646970;">No pending candidates. Check back next Monday.</p>
		<?php else : ?>
			<form method="post">
				<?php wp_nonce_field( 'gtmlens_auto_ingest' ); ?>
				<table class="widefat striped">
					<thead>
						<tr>
							<td style="width:36px;"><input type="checkbox" onclick="document.querySelectorAll('input[name=&quot;gl_ids[]&quot;]').forEach(c => c.checked = this.checked);"></td>
							<th>Title</th>
							<th style="width:140px;">RSS source</th>
							<th style="width:100px;">Date</th>
							<th style="width:160px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $pending as $p ) :
							$src = (string) get_post_meta( $p->ID, '_rss_source', true );
							$summary = (string) get_post_meta( $p->ID, '_rss_summary', true );
							$source_url = (string) get_field( 'source_url', $p->ID );
							$event_date = (string) get_field( 'event_date', $p->ID );
							?>
							<tr>
								<td><input type="checkbox" name="gl_ids[]" value="<?php echo (int) $p->ID; ?>"></td>
								<td>
									<strong><?php echo esc_html( str_replace( '[Auto] ', '', $p->post_title ) ); ?></strong>
									<?php if ( $summary ) : ?>
										<br><span style="color:#646970;font-size:.9em;"><?php echo esc_html( $summary ); ?></span>
									<?php endif; ?>
								</td>
								<td><?php echo esc_html( $src ); ?></td>
								<td><?php echo esc_html( $event_date ); ?></td>
								<td>
									<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $p->ID . '&action=edit' ) ); ?>" class="button button-small">Edit</a>
									<?php if ( $source_url ) : ?>
										<a href="<?php echo esc_url( $source_url ); ?>" target="_blank" rel="noopener" class="button button-small">Source ↗</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p style="margin-top:16px;">
					<button type="submit" name="gtmlens_bulk" value="approve" class="button button-primary" onclick="return confirm('Approve selected candidates and publish them?');">Approve &amp; publish selected</button>
					<button type="submit" name="gtmlens_bulk" value="trash"  class="button" onclick="return confirm('Move selected candidates to trash?');">Trash selected</button>
				</p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}
