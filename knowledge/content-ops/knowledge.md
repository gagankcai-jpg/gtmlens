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

## Pricing re-verify + profile deepening 2026-07-04
- stack-rules.json re-verified (31 tools): changed clay 149→167, lemlist 39→55, attio 34→29, warmly 700→833 (+renamed "Warmly (HubSpot)"), factors-ai 399→199, typeform 25→28, pipedrive 24→14, salesforce 165→175; normalized smartlead 39→33 and expandi 99→79 to the annual-rate convention. Header comment now says July 2026. NOT changed (needs manual eyeball): hubspot ($7 promo vs $15 list on official page), spotlight-ai ($20 vs $40 plan ambiguity).
- Deepened 6 profiles with "Update (Jul 2026)" paragraphs + pricing fields: actively-ai, sierra, decagon, cresta, crescendo, unify.
- DATA CORRECTIONS found during research: Cresta profile had wrong round date (2025-04-15 → actual 2024-11-19) and stale "~$1.0B" valuation (last disclosed $1.6B Mar 2022; Series D undisclosed) — fixed profile + funding_event #422 (also cleared its bogus 1000 valuation). Unify valuation was "Undisclosed" → verified $260M (Battery, Jul 2025).
- Remaining thin profiles (16): monaco, glean, openai-gpt, bland-ai, bardeen-ai, sybill, default, lyzr, spekit, reo-dev, relevance-ai, pylon, aurasell, dreamdata, highspot, aligned(newish, fine).

## Thin-profile batch 2 (2026-07-04) — all 15 remaining deepened
- glean, highspot, spekit, dreamdata, sybill, monaco, aurasell, lyzr, bland-ai, relevance-ai, bardeen-ai, default, reo-dev, pylon, openai-gpt: "Update (Jul 2026)" paragraphs + pricing fields + last_updated. All 21 originally-thin profiles now done (batch 1: actively-ai, sierra, decagon, cresta, crescendo, unify).
- DATA CORRECTIONS this batch: Highspot valuation $2.3B→$3.5B (Jan 2022 Series F; also fixed phantom Apr-2024 round date and set round $248M/total $654M); Aurasell round date 2026-02-12→2025-08-26 (Feb 2026 was the GTM OS product LAUNCH, not the round — fixed profile + funding_event #410, so its map momentum drops); Monaco founders filled (were "Undisclosed"); Dreamdata total ~$80M→~$67M.
- Editorial flags kept in the takes: Bardeen = "monitor" (no funding since Jun 2022); Monaco traction all vendor-sourced; Lyzr $14.5M@$250M single-announcement-derived; Reo.dev customers vendor-named.

## GTM-eng hubs + stack tiers 2026-07-04
- /gtm-engineering/best-practices/ and /playbooks/ were never broken — they're category index templates (4 + 6 cards). Added editorial intro boxes (reading order + 2026 buyer rules + Stack Finder cross-link) to both templates.
- Stack tier pages: series-b (#235) and series-c (#236) posts were missing the 5-section narrative body (Why this stack / architecture / not-buying-yet / when-to-upgrade / alternatives) that seed/series-a/enterprise have. Written via REST post content (~4.4K chars each), incl. Salesloft-Clari merger caveat and change-of-control contract rule.

## Revenue Intelligence cleanup 2026-07-05
- Split RI (was 11) into: Sales Enablement (277: highspot, spekit, glean), Attribution & Analytics (278: dreamdata, hockeystack, factors-ai). RI keeps the true set: gong, chorus, sybill, spotlight-ai, aligned (5). 14 categories total.
- GOTCHA (self-inflicted, now understood): funding-events feed dedupes vendor-derived rows against funding_event posts by (vendor_id|event_date). Correcting a date on ONE side breaks the dedupe and double-lists the round — this double-counted Dreamdata's $55M until event #412 was aligned to 20251014. RULE: when correcting a round date, update the vendor profile AND its funding_event to the same date.
- Tagged Glean events #421/#285 and Dreamdata #412 with their new categories (event rows don't inherit vendor categories).

## Quiz price eyeball resolutions 2026-07-05
- HubSpot Sales Hub Starter: current official list IS $7/mo/seat annual ($20 monthly) — confirmed by two geo-independent reads of hubspot.com/pricing/sales (US fetch + INR page at identical 2.86x monthly/annual ratio). The $15 figure everywhere else is the stale prior list. Quiz 15→7; profile synced.
- Spotlight.ai HAS published pricing (was profiled as "not publicly listed"): Conversational Intelligence $20/user/mo, Deal Intelligence $40/user/mo, annual. Quiz 50→20; profile synced ($$ tier + pricing URL).
