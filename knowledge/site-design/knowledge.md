# Site design — facts learned

## Market map (`[market_map]` shortcode, `inc/market-map.php`)
- 2D scatter: X = pricing tier (proxy for buyer profile), Y = founded year (incumbents pre-2016 → AI-native entrants 2021+).
- Top-right quadrant ("AI-native enterprise") is the strategic story — that's where the next-gen incumbents are forming.
- Layout uses cell-grid distribution within (X-bucket, Y-bucket) cells (√n × √n mini-grid), not random jitter — stable + non-overlapping.
- Logos via Google's `t3.gstatic.com/faviconV2` (returns real apple-icons, not just favicons), DDG fallback in `onerror`. Curated `slug → domain` map for the 31 v1 vendors.
- Vendor `logo` ACF field is empty for all 31 v1 vendors — favicon service is what makes the map work.

## Vendor data quirks
- `total_raised` is free-form text ("~$165M", "N/A (bootstrapped)", "$12B+ (estimated)") — not numeric, can't sum/compare without parsing.
- `founded` is a clean integer year on every vendor — reliable axis source.
- `funding_stage` enum: Bootstrapped, Pre-seed, Seed, Series A/B/C/D+, Public, Acquired.
- `pricing_tier` enum: Free, $, $$, $$$, Enterprise.

## ACF + REST gotcha
- Updating a single ACF field via REST POST requires sending the **full ACF object** including required fields (e.g. vendor_a, vendor_b on comparisons). Partial updates fail with `rest_property_required`.
- `?context=edit` on REST GET returns raw ACF values; `vendor_X` fields come back as integer post IDs, not embedded objects. Coerce dict→ID before re-posting.

## Theme deploy flow
- All theme changes must be repackaged into `gtmlens-child-{label}.zip` and uploaded via WP Admin → Themes → Add New → Upload (replace existing). Hostinger has no SSH for this user.
- Cache-bust homepage with `?v=N` — page often shows old content otherwise.

## LiteSpeed cache (Hostinger)
- Server returns `x-litespeed-cache: hit` on archive pages. After CPT content changes the cache does NOT auto-purge — non-Chrome browsers will keep seeing old HTML while Chrome (which often force-revalidates) shows new.
- Fix is `do_action( 'litespeed_purge_all' )` inside a `save_post` hook gated on `defined('LSCWP_V')` and the relevant CPTs (stack/vendor/comparison). Lives in `functions.php` section 12.
- After ACF JSON or template changes that don't trigger save_post, manually purge via WP Admin → LiteSpeed Cache → Toolbox → Purge All.

## ACF JSON sync (Free)
- Editing `acf-json/group_*.json` does NOT auto-apply on the server. ACF detects changes and surfaces them under Custom Fields → "Sync available". User must click sync once after each deploy or new field-group choices won't validate via REST (e.g. adding `series-b` to budget_tier choices).
