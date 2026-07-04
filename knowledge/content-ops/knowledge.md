# Content-ops knowledge

## Site audit 2026-07-04 (full crawl, 134 sitemap URLs)
- All insights carry pub date Apr 30, 2026 (bulk seed date). No new post since. Monthly column "GTM Stack Movements" had one issue (April) then stopped.
- Comparisons: 25 total; 13 last updated Apr 30, only 2 touched in June (Jun 7).
- Vendors: 55, all have analyst take + SWOT; 22 profiles are thin (<4,500 chars rendered text); 15 lack any pricing signal (mostly enterprise/CX: sierra, decagon, cresta, crescendo, zoominfo, 6sense, demandbase, chorus, unify, actively-ai, aurasell, reo-dev, lyzr, bland-ai, gemini-google).
- Rank Math category sitemap lists 4 URLs that 404: /category/uncategorized/, /category/deep-dive/, /category/market-map/, /category/state-of/.
- Default "hello-world" post still published and in sitemap.
- stack-rules.json starts with a `//` comment line (quiz JS tolerates it); pricing stamped "as of May 2026".
- "Revenue Intelligence" category (13 vendors) is overloaded — contains CX/support agents (Sierra, Decagon, Crescendo, Cresta) that arguably need their own category.

## Market events the site missed (as of 2026-07-04)
- Warmly (tracked vendor) acquired by HubSpot Jun 30, 2026 — profile still says "Series A · $23M · Feb 2024". rb2b-vs-warmly comparison also affected.
- Common Room → Zoom (Jul 2, definitive agreement), Fin/Intercom → Salesforce ~$3.6B (Jun 15). Monthly funding routine is round-focused; it structurally misses M&A (funding_event supports event_type=ma).
- Salesloft merged with Clari Dec 2025 — verify outreach-vs-salesloft comparison reflects it.

## Taxonomy split 2026-07-04
- Created vendor_category "Support & CX Agents" (term 276, seeded in functions.php). Members: Sierra, Decagon, Crescendo (moved from AI SDR — they were never SDR tools), Cresta, Pylon (from Revenue Intelligence), Netomi (new vendor #494).
- Funding events store their own category term; moving a vendor does NOT update past events. Explicitly retagged 4 Sierra/Decagon events. Events with empty category fall back to the vendor's current primary term in the feed.
- Classic-editor saves (funding_event CPT) cannot be POSTed via fetch (post.php returns 404); use the page's own #publish button click instead. Block-editor CPTs (vendor, comparison) save via wp.data savePost.
- Revenue Intelligence remains a grab-bag (Glean=enterprise search, Highspot/Spekit=enablement, Dreamdata/HockeyStack/Factors=attribution) — future split candidates.
