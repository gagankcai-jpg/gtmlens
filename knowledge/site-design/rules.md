# Site design — RULES (confirmed; apply by default)

## Writing ACF data
1. **ACF is writable over the core REST API** on this install: `POST /wp-json/wp/v2/{vendor|funding_event|comparison}/{id}` with `{acf:{...}}` plus an `X-WP-Nonce` header. Prefer this to DOM editing.
2. **`vendor` writes accept a partial `acf` object, but `last_updated` is required** on every one — omit it and the write fails `rest_invalid_param`.
3. **`funding_event` and `comparison` writes require the full `acf` object**: GET with `?context=edit`, merge your changes, POST the whole thing back.
4. **Enum traps:** the vendor `funding_stage` and the funding_event `stage` both require **`"Series D+"`** — plain `"Series D"` is rejected. The two enums are otherwise different (event stage also has Pre-seed/Growth/IPO/Other).
5. **REST cannot write an empty `funding_stage`** (enum validation). To blank it for an unlabelled round, use the admin UI select ("- Select -") then `wp.data.dispatch('core/editor').savePost()`.
6. **`funding_event.vendor` cannot be empty over REST** ("must contain at least 1 item"), yet every untracked-target M&A on the site has an empty vendor. Create or edit those in the **classic editor** (`post-new.php?post_type=funding_event`, set `#title` and `acf[field_fe_*]`, leave vendor blank, click `#publish`).
7. **`amount_usd_m` is an integer field (`step=1`)** — decimals fail validation ("the two nearest valid values are 266 and 267"). Store the rounded integer and put the exact figure in the analyst note.
8. **`analyst_note` is capped at 280 characters.** Over-length silently bounces the publish back to `post-new.php?...&wp-post-new-reload=true`; check `.acf-error-message`.
9. **A bounce to `wp-post-new-reload=true` is not always a validation failure** — check for `#message` "Post published" first; it can be an intermediate redirect.
10. **ACF repeaters are invisible to REST** — not readable, not writable, and the repeater UI does not render in wp-admin (`acf.getFieldType('repeater')` is false). Use the theme's own endpoint: `POST /wp-json/gtmlens/v1/acf-repeater` with `{post_id, field, rows[]}` (functions.php §5b). It replaces the **whole** row set, so reconstruct existing rows first — parsing the rendered `.gl-dim-bars` block back into `{dimension,winner,note}` works and keeps payloads small.
11. **The REST nonce dies on page reload.** Re-fetch `admin-ajax.php?action=rest-nonce` after any navigation.

## Pushing theme files
12. **Theme files are editable through wp-admin → Appearance → Theme File Editor**, including `assets/data/*.json`, `style.css`, `front-page.php` and `functions.php`. No zip repackaging needed for files that already exist.
13. **The editor is a CodeMirror instance — setting `document.getElementById('newcontent').value` silently does nothing.** On submit CodeMirror writes its own unchanged document back and WordPress still reports "File edited successfully." Correct method: `document.querySelector('.CodeMirror').CodeMirror.setValue(s); cm.save();` then click `#submit`. **Always verify by re-fetching the file URL** — never trust the success notice.
14. **Before editing `functions.php`, sanity-check that your edit does not change the `<?php` tag count**, then immediately verify the homepage and the affected page return 200 with no "Fatal error".
15. **Adding a `vendor_category` requires four touches:** the seed list (functions.php), `$cat_order` + `$cat_icons` (front-page.php), and `$cat_label` (market-map.php).

## Cache
16. **Purge LiteSpeed after template, JSON or bulk data changes** — fetch `/wp-admin/`, find the `#wpadminbar` link whose text is "Purge All", fetch its href. Content saves auto-purge; template and asset edits do not.
17. **Verify with a cache-busting query string.** A first check immediately after a purge can still return the cached page.

## Data shape
18. `total_raised` is free-form text and cannot be summed; `founded` is a clean integer year; `funding_stage` and `pricing_tier` are enums.
19. The **interactive market map is data-driven and always current**; the market-map *posts* are dated narrative snapshots. Do not rewrite the posts to "refresh" the map — annotate them and point forward.
20. The homepage funding sparkline counts **all events**, so label it "deals", not "rounds" (fixed 2026-10-19; it had been overstating round counts).
