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

## Final pre-share review 2026-07-05
- Full crawl: 137 sitemap URLs all 200/no PHP errors; 443 internal links, 1 broken fixed (playbook linked nonexistent /compare/default-vs-chili-piper/ → repointed to /vendors/default/).
- DEDUP AUDIT (the Dreamdata bug generalized): found + fixed 3 more feed duplicates/phantoms — Unify $40M (event 20250715 vs profile 20250714, aligned); Clay: profile carried the Jan-2026 $5B TENDER date as a $100M "Series C" round + phantom event #411 (trashed) — real round is Aug 5 2025 @$3.1B CapitalG, total corrected $165M→$204M; Artisan: phantom Dec-2024 "$25M Series A" was the profile date (no such round exists — Series A announced Apr 9 2025), profile + event #308 aligned to 20250409.
- PACE METRIC fixed: was computed on bucket totals incl. M&A (Fin $3.6B made Q3 pace read ▼78%); now rounds-only per methodology (▼17%, label "$X in rounds"). Bars stay stacked-by-type (disclosed via legend).
- market-map.php $cat_label needed entries for all 3 new categories (chips rendered raw slugs otherwise). RULE: adding a vendor_category requires FOUR touches — seed list (functions.php), $cat_order + $cat_icons (front-page.php), $cat_label (market-map.php).
- page-funding-tracker.php local vs live: 4-line/115-char cosmetic divergence predating today; all functional probes identical.

## Content batch 3 + polish (2026-07-06)
- Published 4 articles (build-vs-buy-ai-sdr [flagship], sdr-hiring-counter-narrative, mcp-agent-native-gtm-stack, clari-salesloft-six-months) + warmly-alternatives (best-practice cat) + comparison #556 actively-ai-vs-unify ("the brain and the pipe"). All single-source stats attributed inline (The Signal n=62, UserGems churn, Sumble/Growth Unhinged).
- Featured images: 7 branded abstract covers (no title text — cards overlay their own) generated IN-BROWSER on canvas (procedural: seeded RNG, category palette, node motif) and uploaded via REST media — zero image bytes through agent context. file_upload tool rejects scratchpad paths; canvas.toBlob→FormData is the pattern.
- Polish: single-vendor hero stats now float-format fractional millions ($73.8M); .gl-sidebar__label nowrap; orphan category tile self-resolved at 14 categories.
- page-funding-tracker.php local/live divergence RESOLVED by full-file push of the repo copy (29,470 bytes, verified equal). Cause was cosmetic drift (foreach spacing, lost blanks/comments) from the pre-compaction hot-fix era.

## July refresh cycle (2026-07-23)

Executed after 2.5-week gap (last batch Jul 6). Research: two parallel agents (funding/M&A + product news, Jul 6–23 window), both primary-source verified before any write.

**Published:**
- Posts #576 gtm-stack-movements-july-2026, #577 context-layer-war (flagship). Covers media #578/#580 (canvas procedure, seeds 72/88, deep-dive palette).
- Vendors #561 Alta (ai-sdr 83, $25M Series A 20260708, IN Venture/Sumitomo; 3 founders incl. Mor Shabtai — press release listed a third founder the research agent missed; always re-verify primary), #563 Sable (sales-enablement 277, $45M 20260716, Sequoia+8VC, round has NO stage label — recorded unlabeled).
- Funding events #565 Alta round, #567 Sable round, #569 Ciro ma (Reevo; company release only, flagged), #571 Inconvo ma (Attio acqui-hire). Vendor-linked events dates aligned with profiles → dedupe held (verified: Q3 6 events $130M, 3 rounds/3 M&A, no dupes).
- Attio (#144) take: Inconvo tuck-in update para; last_updated 20260723.
- Clari six-months (#538): editor's note Jul 23 — vendor now claims "Spring 2026 platform integration" (Jul 14 Salesloft CI launch); December re-score will audit.

**New gotchas:**
- funding_event ACF analyst_note max 280 chars — ACF validation silently bounces publish (page reloads to post-new with wp-post-new-reload=true, no #message); check .acf-error-message when publish seems to no-op.
- Vendor profile template does NOT render founders field (data saved, not displayed — same for Gong).
- Rejected aggregator claim: "Glean $180M Series D at $2.7B Jul 2026" (aifunding.me) — contradicted by Glean's own record ($150M Series F at $7.2B Jun 2025). Do not publish.

**Watch items:** Zoom–Common Room close ("coming weeks" as of Jul 2 — still unconfirmed closed); MCP 2026-07-28 spec final (5 days after publish; article references RC); HubSpot Q2 product roundup (Jul 23) may add Warmly integration detail; Emergence Capital SDR survey (36% cut) publication date unconfirmed — candidate for reconciliation piece with sdr-hiring-counter-narrative once dated.

## Aug 6 follow-through (routine catch applied)

- Funding event #584 Seam AI ma 2026-07-21 (Clarify acquirer; intent-signal 82) — sourced from the Aug 1 routine's proposal, then 2-source confirmed (Clarify blog + GeekWire) before write. Tracker now Q3: 7 events, $130M (3 rounds, 4 M&A), dedupe clean.
- Event #483 Common Room note updated: close confirmed July 2026 per Zoom's own blog (Linda Lian + team joined). Watch item closed.
- Post #576 Stack Movements July revised in place (5 subs + CR close wording; dated "(Updated Aug 6)" note in tuck-in para). Local draft file synced byte-identical (5,382 chars).
- Routine SKILL.md gained "KNOWN INTENTIONAL DATA STATES" section (sable stage deliberately blank — unlabeled round) to stop false-positive gap flags.
- Verdict on first scheduled run (Aug 1): working as designed — propose-only degrade correct, no double-counting of manual July work, caught Clarify–Seam which both July research agents missed.
