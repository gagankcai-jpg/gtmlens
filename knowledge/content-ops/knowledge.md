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

## August refresh cycle (2026-08-18)

Full review + Tier 1/2 execution. Research: 2 agents (Aug 1–18 window) + 4 direct verifications (Encore AI 2nd source, Breeze→Agent Hub multi-source, Claude 5 primary via anthropic.com, export-control timeline via CNBC).

**Published:**
- Posts #593 claude-capability-tracker-q3-2026, #594 the-repricing (flagship). Covers media #600/#602 (seeds 101/117).
- Funding events #589 Agency ma 20260804 ($17M per Klaviyo 10-Q, support-cx-agents), #591 Encore AI round 20260729 ($30M Series A Team8, support-cx-agents; missed by Aug 1 routine, caught in review).
- Editor's notes: #115 Q2 Claude tracker (note REPLACED, points to Q3 edition), #69/#113/#112 Claude cluster (prepended, "workflow patterns hold, models moved"), #577 Context-Layer War (ZoomInfo +1.2% Q2 + Breeze→Agent Hub + link to The Repricing).

**Facts locked (primary-sourced):**
- Claude Fable 5: launched Jun 9 2026, Mythos-class (above Opus), $10/$50 per M tokens, Opus 4.8 fallback for cyber/bio/distill (>95% sessions no fallback), usage-credit on plans after Jun 22. Export controls Jun 12–30 (CNBC + anthropic.com/news/redeploying-fable-5), global return Jul 1.
- ZoomInfo Q2 (Aug 5): $310.4M +1.2% YoY, hybrid consumption pricing from late Q3, migrations through 2027, zero GTM.AI in guidance.
- HubSpot Q2 (Aug 5): $911.7M +20%; Breeze brand retired ~Jul 23-30 → Agent Hub (beta); Revenue Hub launched Jul 30. "Changing products, pricing, GTM model" = mgmt quote; "credits throttled adoption" framing = analyst inference, attributed as such in article.
- Emergence "36% cut SDRs" survey = fielded April 2025 → reconciliation-piece idea DEAD; sdr-hiring-counter-narrative stands (cites Sumble/Growth Unhinged, different data).

**Tracker note:** quarterly bar tooltip sums rounds+M&A disclosed amounts (stacked-by-type design); headline capital metrics stay rounds-only. Agency amount_m=17 set (Fin precedent for disclosed M&A).

**Watchlist:** June ($20M pre-seed, Benioff-backed, single-source), Firmable (APAC, MCP server), allGood (Clari+Salesloft distribution), Relay shutdown (adjacent). Stack Movements August due ~Sep 1 (pair with routine run; Zoom Aug 25 earnings = first Common Room read). Quiz pricing re-verify ~Sep after ZoomInfo/HubSpot pricing shifts land. Highspot–Seismic still unclosed; no Gong S-1.

## Comparison-page audit (2026-08-22) — findings only, nothing applied
- 26 comparisons live; only actively-ai-vs-unify (Jul 6) and rb2b-vs-warmly (Jul 4 note) touched since Jun 7. Reference-data tables pull from vendor ACF, so profile corrections propagate; the dimension prose + "Side-by-side" tables in the long-form pages are hand-written and drift independently (sierra-vs-crescendo, cresta-vs-gong, decagon-vs-crescendo all contradict their own reference tables).
- Verdict-breaking staleness: claude-vs-gpt (Jun 7, pre-Fable 5/GPT-5.6); outreach-vs-salesloft (no Clari merger, no Omni Agent Apr 27); apollo-vs-zoominfo (AI-native dimension inverted by GTM.AI CLI/MCP; no consumption pricing); 11x-vs-artisan (Artisan no longer publishes pricing — artisan.co/pricing is "scoped on your plan"); tally-vs-typeform (Tally HAS a Stripe payment block).
- Verified external facts (primary/2-source): Attio $52M Series B led by GV, Aug 2025, $116M total (attio.com blog); n8n $180M Series C 2025-10-09 @$2.5B Accel/Nvidia, ~$254M total, SAP secondary 2026-05-12 @$5.2B; HockeyStack $20M Series A 2025-01-28 Bessemer; Sierra $350M Sept 2025 @$10B (Greenoaks) then $950M 2026-05-04 @$15.8B reported as Series E (TechCrunch) — profile says Series C; Decagon $481M total/6 rounds, Series D; Crescendo $50M TOTAL (own release) — profile "~$80M" wrong; Cresta total $282M (Tracxn); ZoomInfo mkt cap $1.16B (2026-08-21), HubSpot $11.97B, Salesforce ~$170–184B; GPT-5.6 Sol $5/$30, Terra $2/$12, Luna $0.20/$1.20 after Jul 30 cut.
- Live pricing pages (2026-08-22): Attio Plus $35 annual/$44 monthly, Pro $79/$99 (quiz stack-rules has attio=29 — re-check); Clay plans now Launch $167/mo + Growth $446/mo priced in Actions + data credits (old Starter/Explorer/Growth names dead); lemlist Email $55/$69, Multichannel $87/$109; Smartlead Base $32.50/$39, Pro $78.30/$94; Instantly split into Outreach/Credits/Bundles (Outreach Growth $47, bundles from $85); Zapier Pro 750 tasks $19.99 annual/$29.99 monthly, 2,000 tasks $49, Team from $69, Agents Pro $50 add-on; Factors Lite $199/mo, Basic $6K/yr, Growth $20K/yr (publishes pricing — page says it doesn't); Pipedrive (aggregators, site 403) Lite $14/Growth $39/Premium $59/Ultimate $79 annual, AI in all plans.
- Tracker implication: Attio/n8n/HockeyStack 2025 rounds are missing as funding_events → 2025 baseline understated, which flatters/distorts the YoY pace metric. If added, profile last_round_date must equal the event date (dedupe rule).
- Profile data gaps: decagon funding_stage blank (no stage chip on 3 pages); sable intentionally blank.

## Step 1 APPLIED (2026-08-22) — profile + event corrections
Method note: **ACF is writable over the REST API** on this install (`POST /wp-json/wp/v2/{vendor|funding_event}/{id}` with `{acf:{...}}` + X-WP-Nonce). Far safer than DOM editing. Gotchas found:
- vendor writes accept partial `acf` objects; **funding_event writes do NOT** — the schema marks company_name/vendor required, so you must GET `?context=edit`, merge, and POST the whole acf object back.
- `funding_stage` is an enum; REST rejects `""`. To blank it (unlabeled round) you must use the admin UI select ("- Select -") + `wp.data.dispatch('core/editor').savePost()`. That is how sable/hockeystack are blank.
- The nonce dies on page reload — re-fetch `admin-ajax.php?action=rest-nonce` after any navigation.
Vendors updated (16, all last_updated=20260822): attio 144 ($52M Series B 20250826, $116M total, live pricing $35/$79), n8n 86 (Series C, $180M 20251009, ~$254M, $2.5B→$5.2B SAP secondary), hockeystack 152 (stage blanked, undisclosed round 20260415, $50M+ total, **cleared a spurious pre-existing last_valuation_usd_m=50** that was rendering as a $50M valuation in the tracker), sierra 328 (Series D+, ~$1.6B), decagon 329 (Series D+, ~$481M), crescendo 340 (Series C, 20241002, **round size = Undisclosed** — the company's own release says "$50M in TOTAL financing", so the widely-copied "$50M round" is wrong; total $50M, ~$500M val), pipedrive 184, lemlist 181, factors-ai 101, clay 63, instantly 76, smartlead 67, zapier 182, zoominfo 89 (~$1.2B mkt cap), hubspot 74 (~$12B), salesforce 88 (~$171B).
PRIMARY-SOURCE CORRECTIONS to my own audit: Pipedrive is **$14/$24/$49/$69** per seat/mo annual (aggregators said $14/$39/$59/$79 — wrong); Clay's entry is **$54/mo** on annual (180K actions/yr), $167 is the monthly-billing headline; Apollo Basic unchanged at $49 (no edit needed). Lesson repeated: fetch the vendor's own pricing page, never an aggregator.
Funding events: #324 Attio — cleared `valuation_usd_m` 800→0 (no public source for an $800M valuation; profile always said undisclosed). #407 Sierra 2026-05-04 — stage Series C→Series D+, retitled to convention, **note corrected: the round was led by Tiger Global and GV, not Greenoaks** (Greenoaks led Sept 2025). #315 Sierra — was dated 20250915/Series B **with an empty `vendor` relationship** (floating row); now 20250903, Series C, linked to vendor 328, SiliconANGLE source. NEW #609 HockeyStack Series A 20250128 $20M (Bessemer).
Phantom rows resolved by the date fixes (all were profile-derived): Attio 2024-04-30, n8n 2024-12-12, HockeyStack 2024-04-01, Crescendo 2024-04-15. Verified 77 events, **zero duplicate (company|date) pairs**.
Tracker deltas: 2025 baseline now rounds=3/Q1 $7,335M · Q2 5 · Q3 6 ($603M incl. n8n+Attio) · Q4 4; 2026 Q2 gains the HockeyStack undisclosed round (7 rounds). Q3 2026 unchanged at 4 rounds/$160M + 5 M&A.

## Step 2 APPLIED (2026-08-22) — three comparison rewrites
Method: comparison CPT = `content` + ACF via REST, **except `winner_by_dimension` (repeater), which REST does not expose at all** (not readable or writable; ACF Pro's repeater UI also fails to render in wp-admin — `acf.getFieldType('repeater')` is false). The theme already ships the workaround: **`POST /wp-json/gtmlens/v1/acf-repeater` with `{post_id, field, rows[]}`** (functions.php §5b, cookie-auth + X-WP-Nonce, calls `update_field` server-side). Use it for any repeater write.
- **claude-anthropic-vs-openai-gpt (#464)** rebuilt on the current lineup. MAJOR FINDING: **Sonnet 5 shipped Jun 30 2026 ($2/$10, intro pricing now permanent) and Opus 5 shipped Jul 24 2026 ($5/$25, same as Opus 4.8, "close to the frontier intelligence of Claude Fable 5 at half the price")** — both from anthropic.com. Current tiers: Haiku 4.5 $1/$5 · Sonnet 5 $2/$10 · Opus 5 $5/$25 · Fable 5 $10/$50. GPT-5.6 after the Jul 30 cut: Luna $0.20/$1.20 · Terra $2/$12 · Sol $5/$30. New analytical spine: input prices are IDENTICAL at two tiers (Sonnet 5 vs Terra at $2; Opus 5 vs Sol at $5) with Claude 17% cheaper on output, **but GPT-5.6 Luna has no Claude equivalent** (5x cheaper input than Haiku) — the honest GPT win. Updated vendor 68 + 361 entry_price to the full ladders.
- **CORRECTION ISSUED on post #593 (Claude Capability Tracker Q3)**: it published Aug 18 describing a three-tier lineup with Opus 4.8 as workhorse, but Sonnet 5 and Opus 5 had already shipped. Correction note prepended. **The tracker body itself still needs a proper revision — outstanding.**
- **outreach-vs-salesloft (#110)** — was the worst page on the site: no mention of the Dec 3 2025 Clari merger, and its decision rule told buyers to choose Salesloft for Drift. NEW FACTS: Drift taken offline ~Sep 3 2025 after the **Aug 2025 UNC6395 OAuth supply-chain attack (700+ orgs incl. Cloudflare, Palo Alto, Proofpoint, Zscaler — Google Threat Intelligence Group)**, then formally **sunset Mar 6 2026 with 1mind named exclusive successor** (salesloft.com newsroom). Merged entity holds FOUR overlapping layers: Clari Forecast, Clari Copilot (Wingman 2022), Groove (Clari acquired Aug 2023, **targeted 6–9 month integration, still separate in 2026** — the base rate to quote at buyers), Salesloft. Real delivery: **Clari Copilot integrated into Salesloft Jun 2026** (single login, native call data). Outreach rebranded **Outreach.ai**, shipped **Omni + Agent Studio Apr 27 2026**. 8 new dimension rows + body added (page had NO body before).
- **apollo-vs-zoominfo (#109)** — the "Apollo is more AI-native" call is dead: **Apollo shipped MCP Feb 2026 free on every plan, AI Assistant Mar 2026, ChatGPT app Apr 29 2026**; ZoomInfo shipped CLI Jul 9 / GTM Bench Jul 10 / GTM.AI GA Jul 17. Now scored Tie. STALE FACT CORRECTED: **Apollo's free tier is 900 credits per seat per YEAR** (live pricing page), not "10,000 email credits/month". Repricing + vendor-risk (ZoomInfo $310.4M +1.2% YoY, ~$1.2B mkt cap, −63% YoY) now carry the analysis. 8 new dimension rows + body added (page had NO body before).

## Step 3 APPLIED (2026-08-22) — targeted fixes across the remaining comparison pages
Technique that made this cheap: the repeater endpoint replaces the WHOLE row set, and REST cannot read rows — so the browser script **fetches `/compare/<slug>/`, parses the rendered `.gl-dim-bars` block back into {dimension,winner,note}, applies edits by dimension name, and POSTs the full set**. Only the edits travel through context, not all 110 rows. Reusable helper pattern (`window._up(slug,id,aName,bName,edits)`) — worth rebuilding next time.
**BUG FIXED site-wide as a side effect:** legacy rows stored `winner` as literal "A"/"B". `$infer_winner()` in single-comparison.php only matches the winner string against the two VENDOR NAMES, so "A"/"B" fell through to `return 'tie'` — every legacy dimension bar was rendering 50/50 regardless of who won. The helper now normalises A→vendor-A name, B→vendor-B name, so the bars finally show direction on all 17 edited pages. (6sense-vs-demandbase and phantombuster-vs-heyreach were not edited and still carry A/B — fix if ever touched.)
Pages edited (17 repeater + 5 body): 11x-vs-artisan (Artisan no longer publishes pricing → Tie), factors-ai-vs-hockeystack (Factors DOES publish; HockeyStack $50M+), n8n-vs-zapier (Zapier's real prices — the old "$299 caps at 2,000 tasks" and "$1,200-1,600/mo at 50K tasks" were badly wrong; actual 50K tasks = $289/mo annual; + n8n Series C/SAP $5.2B), tally-vs-typeform (**Tally HAS a native Stripe payment block** — corrected in BOTH the dimension row and decision_rule_b, which is where the claim also lived), clay-vs-apollo + claygent-vs-11x + diy-apollo-smartlead-vs-clay (Clay March-2026 Actions repricing: Launch $54/mo annual, Growth $185/mo; old Starter/Explorer/$149/$349/$499 names all dead; also fixed "Series C in 2024"→Aug 2025), attio-vs-hubspot + attio-vs-pipedrive (Attio $35/$79; Pipedrive Lite/Growth/Premium/Ultimate $14/$24/$49/$69 with AI bundled; Breeze→Agent Hub; Inconvo), hubspot-vs-salesforce (Agent Hub + Agentforce/Fin), gong-vs-chorus (Aug 2026 release: ChatGPT Apps listing, admin-managed MCP clients, **monthly per-user AI credit limits — a Repricing datapoint**), spotlight-ai-vs-gong (Gong "$250M+ raised"→~$584M; removed the unverifiable "4,000+ customers" as a hard number → "reported in the thousands"), smartlead-vs-instantly (Instantly modular split + **API v1 deprecated Jan 19 2026**), lemlist-vs-smartlead (lemlist $55/$87 annual), rb2b-vs-warmly (Warmly roadmap now inside Agent Hub), heyreach-vs-expandi (added a verify-before-budgeting caveat).
Body-content pages: sierra-vs-crescendo (Sierra last round $950M May 2026 not "$350M Series C May 2025"; Crescendo "$50M total financing" not "$50M Series B"), sierra-vs-decagon (ARR multiples re-based to a reported ~$200M Sierra ARR ≈79x, both marked unaudited; the "either gets acquired" watch item now records that **Salesforce agreed to buy Fin (ex-Intercom) for $3.6B on Jun 15 2026**), cresta-vs-gong (Cresta round Apr 2025→Nov 2024, total ~$275M→~$282M, reviewed-date + Gong Aug release), decagon-vs-crescendo (Sierra "Series C"→"round", Fin), actively-ai-vs-unify (Seam AI→Clarify added to the consolidation list).
Verification: all 26 pages HTTP 200, 35 new-content assertions pass, **zero stale strings** from a 15-pattern scan. 24/26 stamped Aug 22; 6sense-vs-demandbase and phantombuster-vs-heyreach deliberately left at Apr 30 because nothing on them changed (never stamp a page you did not edit).
STILL OUTSTANDING: the Claude Q3 tracker (#593) body still needs a proper revision beyond its correction note; quiz stack-rules.json needs re-verification against the pricing corrected here (attio 29→35, clay 167→54-on-annual, lemlist 55, factors 199, pipedrive 14, smartlead 39, zapier 19.99).

## Step 4 APPLIED (2026-08-22) — quiz stack-rules.json re-verified
**How to push theme files (recorded properly this time):** wp-admin → Theme File Editor lists `assets/data/stack-rules.json` and it is writable. **GOTCHA that silently ate the first attempt:** the editor is a CodeMirror instance, so setting `document.getElementById('newcontent').value` does nothing — on submit CodeMirror writes its own (unchanged) doc back over the textarea and WP still reports "File edited successfully." Correct method: `document.querySelector('.CodeMirror').CodeMirror.setValue(s); cm.save();` then click #submit. Verify by re-fetching the file URL, never by trusting the success notice.
Live vs repo were NOT diverged — the 1,002-byte difference was purely CRLF (live) vs LF (repo). Both are now byte-identical at 22,796.
Price changes (all from vendors' own pricing pages today): **clay 167 → 54** (the 167 was the monthly-billing headline and the note wrongly called it "annual"; annual entry is $54/mo for 180K actions/yr — this matters because every other entry in the file uses the annual-rate convention), **attio 29 → 35**, **instantly 37 → 47** (Outreach module Growth). Verified-correct, left alone: apollo 49, hubspot 7 (re-checked hubspot.com), pipedrive 14, lemlist 55, factors-ai 199, smartlead 33, zapier 20, spotlight-ai 20, n8n 20.
Notes added/rewritten: claude-anthropic (was "Sonnet 4.6 $3/$15" → the current four-tier ladder), artisan + 11x (**no published pricing as of Aug 2026** — figures are third-party estimates, previously presented bare), zoominfo (flags the seat→consumption migration), hubspot (re-verified stamp). Header comment → August 2026.
CORRECTION to my own step-3 research: an aggregator fetch had given Instantly's Hypergrowth as $358; the live page says **$97** (Light Speed is $358). Nothing published used the wrong figure, but the Instantly vendor profile's "bundles from $85/mo annual" line came from that same unreliable fetch and **was replaced with the verified Outreach-module prices** — the live pricing page shows tabs for Outreach / Credits / VIP and no bundle at $85.
End-to-end quiz test passed: 5 questions → recommendation renders, Attio shows $35/mo, totals compute.
**FINDING TO RAISE (not changed — editorial, not data):** a Seed / $500-2,000-budget run recommends **Warmly (HubSpot) at $833/mo**, 83% of the ceiling, for a product whose standalone availability is uncertain post-acquisition. The caveat lives in `pricingNote` but the results UI never surfaces notes. Either retag warmly's fitTags away from seed budgets or surface pricingNote in the results card.

## Q3 tracker body revised (2026-08-22) — post #593
Replaced the whole body (5,952 → 11,181 chars) and swapped the step-2 correction note for a proper Editor's note explaining the revision. Rebuilt on the four-tier lineup (Fable 5 Jun 9 $10/$50 · Opus 5 Jul 24 $5/$25 · Sonnet 5 Jun 30 $2/$10 · Haiku 4.5 $1/$5) with the thesis flipped: **Opus 5, not Fable 5, is the quarter's story** — same price as Opus 4.8, "close to the frontier intelligence of Fable 5 at half the price" (vendor), and stronger at verifying its own work over long chains, which is the real GTM failure mode. Second call to action: **Sonnet 5 gives ~Opus 4.8 capability at 40% of the price**, so every Q2-era agent deserves an A/B before budget renewal.
NEW MATERIAL FOUND while researching (the original edition predated it): **from Aug 2 2026 Anthropic embeds an imperceptible watermark in text from models launched on/after that date**, plus signed C2PA metadata on SVG/PNG/JPG. Trigger is EU AI Act Article 50 (enforceable Aug 2; penalties up to €15M or 3% of global turnover) but the marking is applied **worldwide**. Pre-Aug-2 models covered by a transition period ending **Dec 2 2026**. Per Anthropic's own help centre: invisible, doesn't change readability, **persists through copying and "may persist through some editing"**; a detected mark means content "may have been processed by Claude" — NOT proof of authorship (it cannot separate generation from proofreading), and absence proves nothing. **No public detector has shipped yet.** Sources: support.claude.com/en/articles/16266773, plus Euronews/Forbes/TNW coverage.
This is the most GTM-consequential item of the quarter — it lands on every AI-drafted outbound email — so it got its own section with three ranked implications, framed as a compliance asset rather than a threat. Q4 watch list now includes the Dec 2 deadline and whether a detector actually ships.
Verified live: 12 required strings present, 5 stale strings gone, all 4 internal links 200. Homepage flagship unaffected (content-only edit does not change publish order).
OPEN EDITORIAL CALL (not actioned): the title still reads "The Mythos-Class Quarter", which was the right frame when Fable 5 led the piece but no longer matches a body arguing the frontier tier stopped being the interesting one. Not factually wrong — Fable 5 is Mythos-class and did ship in Q3 — so left for a human call.

## Stack Movements August 2026 (2026-09-01) — PUBLISHED

Scheduled routine `gtmlens-stack-movements-august` run, drafted at 11:30 and **published later the same day on the user's explicit instruction**. `content-drafts/gtm-stack-movements-august-2026.html` (909 words, 5 H2, fragment-clean: no h1, no inline styles).

**Published:** post **#630** `/gtm-stack-movements-august-2026/`, category 81 (Deep Dive), cover media **#629** (seed 139, deep-dive palette). Verified live: HTTP 200, zero PHP errors, 7/7 key strings present, 5 H2 → theme TOC, og:image set, all 5 internal links + the PNG resolve 200. Theme vendor auto-linking picked up Highspot / Outreach / Salesloft in the body unprompted.

**AUTH METHOD — the credential gap is still real.** `~/.config/gtmlens/wp.json` does **not** exist, so the routine's documented HTTP Basic path was unavailable and the scheduled run correctly degraded to propose-only. Publishing succeeded only because a **logged-in wp-admin session existed in the user's real Chrome** (claude-in-chrome), letting us use the repo's other documented path: cookie auth + `X-WP-Nonce` from `admin-ajax.php?action=rest-nonce`. The in-app browser pane was NOT logged in (redirected to wp-login.php) — only the real-Chrome surface worked. **For unattended runs to publish, someone must still create `~/.config/gtmlens/wp.json`.** Until then every scheduled run will draft-only, by design.
- Cover generated **in-browser on canvas** per the established house pattern (line 56), not PIL — the routine SKILL.md specifies PIL parameters, but PIL output would have to cross agent context as base64 to reach WP. Canvas → `toBlob` → FormData keeps image bytes out of context entirely. Python's `random.Random(139)` is not reproducible in JS, so node placement used mulberry32(139); all other spec parameters (1200x675, navy 13/31/60, 0.55 wash, t=x/W*0.7+y/H*0.3 with the 0.6 split, 44px dot grid r=1.6 @0.10, halo rings, 64px band, 22px Helvetica wordmark at x=36/160) were followed exactly. Rendered 11 nodes. **Consider updating SKILL.md to specify canvas rather than PIL** — the two disagree and canvas is what the site actually uses.
- Cosmetic: the theme's title card overlays the lower-left of the cover, partially covering the GTMLENS wordmark. Consistent with the line-56 note that cards overlay their own text; harmless, but the wordmark band is largely decorative on post heroes.

**Input gap:** the 09:00 `gtmlens-funding-refresh` produced NO September proposal file. Newest is still `monitor/proposals/2026-08-02.md` and `monitor/state.json` lastRun is still `2026-08-02`. So the Aug 18–31 window had zero routine coverage — all research below is this run's own. **Human should check why the funding routine did not run/emit on Sep 1.**

**Verified and used (exact date + primary or 2+ independent):**
- **Seismic–Highspot merger CLOSED 2026-08-18** (highspot.com blog + Businesswire/Morningstar + GeekWire). Seismic name, CEO Rob Tarkoff, Wahbe expected to board, 2,500 customers / 3.5M users. **Standing watchlist item now RESOLVED.**
- **Common Room: closed 2026-07-17, all-cash consideration $266.8M** ($198,789K goodwill, $43,800K intangibles, $24,209K other; preliminary allocation) — Zoom 10-Q for period ended 2026-07-31, PRIMARY. **This corrects the widely-reported "$250 million"** carried in call coverage. First hard price on any of the five Q3 signal-layer tuck-ins (all previously undisclosed). Common Room lifetime raised ~$52.9M → ~5x.
- **Zoom Q2 FY27 (2026-08-25):** revenue $1,277.2M +4.9%; Enterprise $787.5M +7.8%; Online $489.7M +0.6%; FY27 guide $5.085–5.095B; Virtual Agent customers +256% YoY — all from SEC 8-K ex-99.1, PRIMARY.
- Zoom call claims attributed as company-claimed in the article: Revenue Accelerator paid customers +41% YoY, Workplace AI licensed MAU +125% (not in filings). CFO Michelle Chang "de minimis" framing on Common Room contribution.
- **Salesforce Q2 FY27 (2026-08-26):** revenue $11.35B +11%; Agentforce ARR >$1.5B +240%; AI & Data ARR ~$3.9B +210%; 3.2B AWUs in Q2 +97% QoQ; FY27 guide raised $46.1–46.4B (investor.salesforce.com + CNBC + 24/7 Wall St). **Caveat flagged in article: effective Q2 FY27 Agentforce ARR now includes Slackbot and Headless 360 — denominator redefined, growth rate not comparable.**
- **Claudeforce announced 2026-08-26** (Salesforce + Anthropic): Claude as reasoning model inside Salesforce; "Salesforce in Claude" plugin with 37 prebuilt sales skills; select pilot now, open beta expected Sept 2026 (salesforce.com/investor PR + VentureBeat + digitalcommerce360).
- **Clari + Salesloft Aug 11 2026 release** (community.clari.com release notes, primary): Salesloft MCP server as ChatGPT custom connector (Agentic customers, ChatGPT Business/Enterprise); **transcript-retrieval tool REMOVED from the Claude MCP connector** ("protocol limitations"); External Client Application on AppExchange with required migration by **2026-09-02**.
- **Relay.app**: shutdown announced 2026-07-16, free accounts closed 2026-08-15, paid ends 2026-09-14; Jacob Bank + staff to Google Chrome. Watchlist item now dated.

**Deliberately OMITTED (failed the bar):**
- HubSpot Agent Hub credit pricing ($0.01/credit, ~$0.50/resolution, ~$1/lead, ~$0.10/data answer) — **third-party sources only** (engaging.io, hubjoy, fastslowmotion); hubspot.com/pricing/agent-hub 404s. Do not publish until primary-verified.
- Salesforce's reported ~$300M 2026 Anthropic token spend + $300M equity stake — single search-summary sourcing, big number, not primary-verified. Omitted per "a wrong figure is worse than a missing one."
- ZoomInfo 20% RIF / ~600 roles: real but **dated 2026-05-11** (8-K), out of the Aug 18–31 window. Not used.
- Zoom–BrightHire: announced 2025-11-13, out of window.
- No AI GTM venture round anywhere in Aug 18–31 met the bar. Aug 4 Klaviyo–Agency and Jul 29 Encore AI already tracked (#589/#591); Klaviyo 10-Q Aug 4 asset-assignment re-confirmed in passing.

**Scoreboard as published in the draft (unchanged from Aug 18 state):** Q3 = $160M / 4 rounds (Aligned $60M Jul 1, Alta $25M Jul 8, Sable $45M Jul 16, Encore AI $30M Jul 29) + 5 M&A (Common Room, Ciro, Inconvo, Seam AI, Agency).

**FOR HUMAN REVIEW (no tracker/vendor records written — propose-only, per task scope):**
1. ~~Publish the draft~~ — **DONE**, post #630 live. Remaining WP-side items are the record decisions below, all of which change tracker numbers and were deliberately left alone.
2. **Highspot vendor record** — merger closed 2026-08-18; `funding_stage` still not flipped. Decide Acquired vs Merged. Also a **tracker-convention call**: signed Q1 2026, closed Q3 2026 — does it become a Q3 M&A event (would make Q3 six) or stay recorded at announcement? Draft says "pending record decision" and does not backdate.
3. **Common Room funding event** — the site records undisclosed terms; the 10-Q gives $266.8M and a 2026-07-17 close date. Worth updating amount + close date on the event record.
4. Funding routine did not emit a Sep 1 proposal; `state.json` lastRun still 2026-08-02.
5. Watchlist still open, nothing found this run: June (Benioff pre-seed, single-source), Firmable, allGood, **Gong — still no S-1 on EDGAR**.

## Site review + Tier 1 data repair (2026-10-19)
**Review finding:** nothing published since Sep 1 (48 days). The data routines ran fine on Sep 1 and Oct 1; the EDITORIAL layer stopped because `gtmlens-stack-movements-august` was a one-time task that auto-disabled after firing. September's column was never drafted. See decisions/2026-10-19-monthly-column-automation.md. Site health itself is clean: 150 sitemap URLs all 200, zero PHP errors.
**Verified the Repricing figure stood up:** one source reported ZoomInfo's FY26 guidance inverted; the Aug 5 Q2 release did RAISE it to $1.207-1.217B (from $1.185-1.205B). Our article is correct. New material it predates: NRR slipped to 89%, a $650.5M goodwill impairment, and a Mizuho downgrade to Underperform.
### Applied
- **Clay Series D** (vendor 63 + new event #633): $115M at **$7.1B**, 2026-09-09, Wellington-led. Confirmed against Clay's own announcement, which states the round and valuation but **does NOT say post- or pre-money and gives no total raised** — so `last_valuation` reads "$7.1B (Series D, Sep 2026)" with no post-money claim, and total is "~$319M" (derived: $204M + $115M, flagged as derived in the event note). 17,000+ teams is company-stated.
- **DoubleO.ai M&A** (new event #634): ZoomInfo acquisition, 2026-09-30, undisclosed, category 84 orchestration.
- **clay-vs-apollo #77** "Valuation and Longevity Risk" rewritten — the other two Clay comparisons needed no edit because their stale figures came from the reference table, which auto-updates off the profile. Useful general rule: **fix the vendor profile first, then re-scan; most comparison "staleness" resolves itself.**
- **highspot 332** analyst take: appended a dated Update recording the 2026-08-18 Seismic close (Seismic surviving brand, Tarkoff CEO, Wahbe to board, Permira controlling, no value disclosed, 2,500 customers/3.5M users company-stated). Deliberately KEPT the original "2-3yr decline into... strategic consolidation by a CRM/RevOps platform" prediction and marked it `[Resolved]` — it called the outcome, so it is a track-record asset, not an error to erase. The pre-existing "2024 Series F at $2.3B" error was already corrected by the Jul 2026 update note on the same field.
- **post #630** (Stack Movements August): top Update note + inline marker. It had said "five recorded acquisitions... pending record decision" while the tracker said six and closed.
- **Date-format normalisation:** 22 vendors had dashed `last_round_date` and 7 had dashed `last_updated` (not just gong/zoominfo as first scoped). All normalised to YYYYMMDD. **Verified safe by snapshotting the tracker feed before and after: 80 events, row set byte-identical, zero duplicates** — the dedupe key was unaffected. Dates and review stamps kept their values (no false freshness).
### Schema gotchas learned this run
- The vendor `funding_stage` enum and the funding_event `stage` enum BOTH use **"Series D+"** — plain "Series D" is rejected with `rest_invalid_param`. The two enums are otherwise different (event stage also has Pre-seed/Growth/IPO/Other).
- **funding_event `vendor` cannot be empty over REST** ("must contain at least 1 item"), yet every untracked-target M&A on the site has an empty vendor. Those were made in the classic editor, which permits it. For an untracked target, create via `post-new.php?post_type=funding_event` (set `#title`, `acf[field_fe_*]`, leave vendor blank, click `#publish`) — REST is only viable when the target is a tracked vendor.
- A publish bounce to `post-new.php?...&wp-post-new-reload=true` is NOT always an ACF validation failure — check for `#message` "Post published" before assuming; it can be an intermediate redirect.
### Tracker state after Tier 1
80 events. **Q3 2026: 5 rounds / $275M + 7 M&A = 12 events** (was 4 / $160M + 6). Tracker "Last updated" now 2026-09-30. Q4 2026 legitimately empty — 19 days in, nothing confirmed yet.

## Tier 2 editorial catch-up (2026-10-19)
### Published
- **#638 GTM Stack Movements: September–October 2026** (slug gtm-stack-movements-september-october-2026, cat 81, cover seed 151). Covers Clay's round, the Sep 30–Oct 1 agent-builder wave, ZoomInfo's one-day acquisition-to-product turn, SoundHound–LivePerson, Publicis–LiveRamp, allGood, Relay.app's end, Stuut.
- **#642 State of AI GTM Q3 2026: Seats Out, Agents Free** (slug state-of-ai-gtm-q3-2026, **cat 173 state-of**, cover seed 163, 3,390 words). House quarterly format: scorecard → structural story → funding/M&A → category movements → FM layer → buyer recs by stage → Q4 predictions → analyst view → methodology. Added a forward-pointer from the Q2 report (#147).
### The scorecard is the spine — and it is unflattering
Graded the Q2 report's six predictions: **four missed, one unscoreable, one directionally right via a different mechanism.** Also graded the Q2 market map's (#114) four Q3 predictions: three missed, one partially right. The pattern across both: **every structural call held (signal layer consolidates, pricing moves off seats, augment-judgment compounds) and every company-level call failed.** Prediction 3 ("Smartlead crosses $50M ARR") was **unscoreable by construction** — Smartlead is bootstrapped and publishes no ARR. Fix applied: every Q4 prediction now carries an explicit `Resolves:` criterion. Keep that rule.
### Analytical finding worth reusing: strip the FM rounds AND the outlier
GTM-only venture rounds by quarter (foundation models excluded): 2025Q2 5/$352M · 2025Q3 6/$603M · 2025Q4 3/$239M · 2026Q1 2/$285M · 2026Q2 6/$1,305M · 2026Q3 5/$275M. Deal COUNT is stable; cheque size swings entirely on one deal per quarter — **Sierra was 73% of Q2, Clay 42% of Q3**. Q3 is −54% YoY vs Q3 2025. Never compare raw quarter totals without excluding FM rounds (Q1 2026 raw is $152B) and naming the outlier.
### Market maps: do NOT rewrite them
The maps' own editor's notes establish the model: **the interactive map is data-driven and always current; the three map POSTS are dated narrative snapshots.** So the correct refresh was dated update notes + forward-pointers, not new Q3/Q4 editions — and the Q3 state-of's "Category Movements" section now carries the current narrative, which would have duplicated a new map edition. Applied notes to #114 (predictions graded), #368 (AI SDR: no shutdown; Alta and Sable are new funded entrants the four positions don't cover; Artisan's pricing transparency reversed so both Position 1 vendors are quote-only), #355 (customer agents: Fin→Salesforce $3.6B, LivePerson→SoundHound, Agency→Klaviyo, Encore AI; corrected Crescendo to $50M total).
### Data fix found while writing
Common Room event #483 carried **no price** although our own August edition had published $266.8M from Zoom's 10-Q. Now recorded. **GOTCHA: `amount_usd_m` is an integer field (`step=1`)** — 266.8 fails ACF validation with "the two nearest valid values are 266 and 267". Stored 267 with the exact figure in the analyst note. Q3 disclosed M&A value is now $284M across 2 of 7 deals; the other five are undisclosed, so the Common Room comp (~5x lifetime funding, 75% goodwill) is the category's only real public anchor.
### Homepage bug fixed
`front-page.php` sparkline counted ALL events but labelled them "rounds" (tooltip + "N rounds tracked"), overstating round counts — Q3 read "12 rounds" when 5 are rounds. Its own `aria-label` already said "deals". Relabelled to "deals" in both places via Theme File Editor (CodeMirror method). Repo and live both updated.
Also corrected on the Highspot take (live-site only, not in repo): the body claimed "The 2024 Series F at $2.3B"; the round was **January 2022 at $3.5B**. The July 2026 data note already carried the right figure ~1,500 chars further down, but the wrong number sat at the point of the claim, so it is now marked inline. Lesson: appending a correction note does not fix a wrong figure a reader hits first — mark it where it appears.

## Q4 2026 market maps published (2026-10-19)
Three new editions, all cat 139, each grading its Q2 predecessor and pointing back; Q2 editions kept and prefixed with a "Superseded" note.
- **#654 ai-sdr-q4-2026-market-map** (cover seed 177). Spine: **capital inversion** — every dollar of new 2026 capital in the category went to Positions 2–5; **11x and Artisan have raised nothing since Nov 2024**. Added **Position 5: product-grounded agents (Alta, Sable)** — agents bounded by a system the agent can read (your CRM, your product) rather than by a prospect list. Graded Q2's calls: the "Position-1 exits to a CRM incumbent by Q4" call missed (both CRMs bought, neither bought an AI SDR); "augmentation becomes a feature" missed (Actively AI raised $45M at $250M); the vertical call was right but our Monaco figure was off by 10x; the TCPA trigger never fired. Retired the "reply rates 4-6% → sub-2%" claim as **unscoreable** (our own unsourced estimate).
- **#658 ai-customer-agents-q4-2026-market-map** (seed 189). Spine: **acquisition dollars (~$4.8B: Salesforce–Fin $3.6B, NICE–Cognigy $955M, SoundHound–LivePerson ~$250M EV, Klaviyo–Agency ≤$17M) now run ~1.8x the combined lifetime funding of every AI-native startup in the map**. Graded all five Q2 "outcomes we're tracking": Sierra has disclosed no ARR since $100M in Nov 2025; no Decagon vertical; FM encroachment arrived as distribution (Claudeforce, Codex) not a runtime; **the Salesforce-M&A signal fired with a different target** — it bought Fin at $3.6B rather than Sierra at $15.8B, which names the category's exit price; no voice regulation. Added a platform/incumbent-owned position. Corrected Crescendo ($50M total) and Cresta (last *disclosed* val $1.6B Mar 2022, Series D val never disclosed), and **added Netomi, which the Q2 map omitted entirely**.
- **#662 ai-gtm-market-map-q4-2026** (seed 201). Spine: **five of nine categories now have a platform answer rather than a specialist one.** Opens with an explicit **audit of the Q2 edition's errors** — Clay's Series C was $100M/CapitalG/Aug 2025 not "$46M/a16z/Feb 2026"; 11x's rounds were 2024 not "Jan 2026"; **lemlist's "$32M Series A (Nov 2025)" does not exist**; **withdrew the unsupported "Gong moved to consumption pricing in Q1 2026"**; and flagged Smartlead's ARR as unscoreable after our own two pages contradicted each other ($10M vs $30M). New rule applied: **attach funding figures only to the 58 vendors we track and verify**; name untracked vendors as context without numbers.
### Data corrections surfaced BY the re-score (the real value of doing it)
- **MISSED ROUND FOUND: Bland $50M Series C, 2026-06-16, Dell Technologies Capital**, total past $100M (Fortune + TNW + Dealroom + pulse2). Recorded as vendor 339 + event #652. **This moved Q2 2026 from 6 rounds/$1,305M to 7/$1,355M — figures I had published in the Q3 state-of hours earlier, so that report now carries a correction note** (Sierra's share 73%→70%, ex-largest $355M→$405M). Confirms the standing hypothesis that roundup-level research misses rounds; promote toward a rule if it happens again.
- **11x record was badly wrong**: total $20M→~$76M, stage Series A→Series B, and `last_valuation` held $50M which was actually the *round size*. Now $50M round / ~$350M val / 20241111, with the $24M Series A preserved as event #650 at the date we already held (flagged in the note as not re-verified).
- **Artisan** total $25M→~$37M (the $12M YC W24 seed was missing). Event #308 title said 2025-04-15 while its date was 20250409 — retitled.
- **Monaco** was already correct on-site ($35M Series A Feb 2026 + $50M Series B May 2026, $85M+) — it was the Q2 *map* that was 10x stale, not the record.
- **Unresolved discrepancy left alone:** Fortune puts Bland's $40M Series B in Jan 2025; our event #403 has 2024-09-12. One source each, so changing it would break our own two-source gate. Logged as a watch item.
- **Potential tracker event not recorded (propose-only):** lemlist acquired **Claap** (~7 people, ~$2M ARR) in October 2025 — cash + vendor loan + convertible + earn-out, ~$25M all-in per Founderpath. A tracked vendor acquiring an untracked target, same shape as Attio–Inconvo which we did record. Historical (Oct 2025), so it would change a past quarter: human call.

## Full-site review (2026-10-29) — findings only, nothing applied
No site changes or routine runs since 2026-10-19. 155 sitemap URLs, all 200, zero PHP errors, **zero broken internal links** (458 targets). (First crawl pass dropped the page-sitemap on a timeout — crawl artifact; re-filled. Run sub-sitemap fetches with a 60s timeout.)
### Published claims now wrong (model layer moved Sep–Oct)
Primary sources (platform.claude.com pricing, anthropic.com/news, developers.openai.com pricing): **Fable 5.1 $10/$50 (Sep 1, with Mythos 5.1) · Opus 5.5 $4/$20 (Sep 22) · Sonnet 5.5 $2/$10 (Sep 28) · Haiku 5.5 $0.10/$0.50 ≤100k prompts (Oct 7)**. OpenAI: **GPT-6 Astra $10/$50 · GPT-6.1 Sol $2/$10 · GPT-6.1 Terra $1/$5 · GPT-6 Luna $0.10/$0.50** (GPT-5.6 still listed; Sol now $4/$20). Our Aug thesis ("Luna has no Claude equivalent", "Claude 17% cheaper on output") is inverted: identical at frontier and volume; OpenAI ~half at the two middle tiers (tier pairing is our inference). Stale on: claude-vs-gpt comparison (+/compare/ excerpt), Q3 tracker, Q3 state-of, Q4 main map, vendors claude-anthropic + openai-gpt (feed homepage), quiz claude note.
Also wrong: **Salesforce completed Fin on 2026-09-10** (salesforce.com) — 3 pages still say "closing expected early 2027". **AI SDR Q4 map's "Neither bought an AI SDR" is false** — Salesforce closed Qualified (Piper) Apr 1 2026 (~$1.2B per secondary citing its 10-Q; verify) and Dreamforce lists **Agentforce Hunter (outbound) GA November**. **Claudeforce reached beta** (Salesforce Dreamforce roundup) — Sep–Oct column and Q3 report both say we found no confirmation. Gemini profile names "Gemini 3.5 Flash" as default; secondary sources say it was deprecated Oct 8 — needs a primary-sourced refresh.
### New events not covered
Salesforce–Braze ~$5.66B EV announced 2026-10-20 ($47.50/sh, close ~FY2028); Salesforce's 2026 buys (Qualified, Fin, Braze, Spindle AI, Loops, Merlin AI) absent from its vendor take. Anthropic donated Agent Skills to the Agentic AI Foundation 2026-10-22. ZoomInfo Q3 results Nov 4 (resolves Q4 prediction 1); HubSpot ~Nov 4–5 (unconfirmed). No AI-GTM rounds found Oct 19–29 (roundup-level only).
### Technical / SEO
- 46 posts + 4 about pages emit two H1s: Kadence `h1.entry-title` is only CSS-hidden (functions.php ~L2106) while the insight hero adds `h1.gl-ih-title`. Suppress server-side.
- Missing meta description: actively-ai-vs-unify, vendors alta/netomi/sable, gtm-engineering index/best-practices/playbooks. 93 descriptions >165 chars, 18 titles >65.
- 110 images missing alt (80 = homepage market-map logos).
- Orphan: /privacy-policy/. Single inbound: /about/press/, /about/team/.
- Links to non-existent comparisons (redirect to /compare/): clari-vs-custom-ai-forecasting (from playbook-hubspot-forecast-claude), hubspot-vs-attio (from bp-choosing-crm-seed-stage — real slug is attio-vs-hubspot). 30 links route via redirects (mostly /category/*).
- Freshness: 15 vendor profiles last updated May–Jun 2026; 6 stack pages carry no Last-updated stamp.
### Time-sensitive
Nov 1 column task targets "October 2026" but the Sep–Oct edition already covers to Oct 19 — the prompt does not say how to scope a partial month; fix before it fires.
Hypothesis #7 (roundup research misses rounds): 1 confirmation (Bland, found 2026-10-19).

## Tier 1–3 fixes applied (2026-10-29)
### Tier 1 — published claims corrected
- **Vendors** claude-anthropic (68) and openai-gpt (361): entry_price set to the Oct 2026 ladders; `last_updated` 20261029. No other field carried stale model names.
- **Comparison #464 rebuilt.** Sections 1–3 and 6, the verdict, decision rules A and B, and a new excerpt all rest on "identical at the frontier and volume tiers, GPT-6.1 half price in the middle". Tier pairing is labelled as our inference. Haiku 5.5's 5× price step above 100k-token prompts is called out as the real volume-tier differentiator. Sections 4–5 kept; Claudeforce is described as a partner channel, not an Anthropic app. Opens with a dated update note.
- **Quiz** stack-rules.json Claude note is on the Oct 2026 ladder (live = repo, verified by fetch).
- **Correction or update notes:**
  - **Inline fix + note on current pieces:** #662 (Category 8 model ladder), #658 (Fin completed Sep 10), #654 (Qualified/Piper bought Apr 2026, about $1.2B fair value per the 10-Q), #638 (Claudeforce in beta per the Dreamforce roundup of Sep 28).
  - **Top note only on preserved snapshots:** #593 Q3 tracker; #642 Q3 report (Fin, models, Claudeforce, Agent Skills → AAIF Oct 22 — the prediction-6 grade stands); #355 Q2 CX map (inline "(update: completed Sep 10)").
- **Gemini (360):** primary source ai.google.dev pricing (page updated 2026-10-28):
  - Gemini 4 Argon $2/$10 to Nov 30, then $4/$20.
  - 3.8 Flash $0.75/$3.75 to Dec 31, then $1.50/$7.50.
  - 3.5 Flash-Lite $0.30/$2.50.
  - The May analyst take now opens with a dated caveat (not rewritten; capability claims not re-verified).
- **My own error, owned:** the Q4 main map (#662) was published this month with the June–July model ladder, which had already been replaced. Pricing was carried over from our own pages instead of re-fetched.
### Tier 2
- **Salesforce take (88):** new paragraph on the buy-not-build pattern.
  - Qualified: Apr 2026, ~$1.2B fair value, 10-Q.
  - Fin: $3.6B, completed Sep 10.
  - Contentful: ~$1.5B cash, pending per the Jul 31 10-Q.
  - Braze: ~$5.66B EV, $47.50/sh cash, Oct 20, close in FY2028. Sources: Braze 8-K, Reuters, TNW.
  - Verdict gains a Marketing Cloud renewal caution.
  - Not included (unverified): Spindle AI, Loops, Merlin AI, Agentforce Hunter.
- **HubSpot Agent Hub:** only secondary sources, which conflict on whether Breeze was retired and on July vs INBOUND timing. HubSpot take left unchanged.
- **M&A proposals** in `monitor/proposals/2026-10-29-review.md`:
  - Qualified fails the exact-day gate.
  - Braze passes (pending deal).
  - Contentful fails (no announcement date).
### Tier 3
- **Duplicate H1 fixed server-side** (functions.php, `gtmlens_suppress_duplicate_h1` + `gtmlens_demote_content_h1`). Verified one H1 on posts, /about/ and the about subpages; titles and og:title intact.
  - How it works: `the_title()` prints nothing for an empty title. The filter blanks the queried post's title in the loop (posts) or after `wp_body_open` (/about/, where Kadence's hero prints before the loop). It skips anything inside `the_content`, so our hero keeps the H1.
  - The `kadence_page_title` / `kadence_disable_post_title` filters at the about hook never worked.
- **Meta descriptions:**
  - Excerpts written for alta/netomi/sable/actively-ai-vs-unify.
  - The 3 GTM-engineering template pages get fallback descriptions in the RankMath filter.
  - The homepage description now uses live counts. It had said 31 vendors/20 comparisons; live is 58/26/6.
- **Vendor excerpts drift from ACF.** They are hand-written, feed meta descriptions, and are not rendered from ACF, so rule 11 does not cover them. Fixed:
  - clay: Series C $3.1B → Series D $7.1B.
  - hightouch: $1.2B → $2.75B.
  - 11x: $20M A at $50M → $24M A + ~$50M B at ~$350M.
  - n8n: adds the Series C at $2.5B.
  - Left alone: typeform (excerpt ~$945M vs record "Not disclosed") and hubspot (FY2023 revenue): dated, not contradicted.
- **Image alt finding was a crawler false positive.** crawl.py counted `alt=""` as missing. Homepage logos are decorative inside labelled links, so `alt=""` is correct. 0 images lack an alt attribute. Fix the crawler before trusting this metric again.
- **Links:**
  - hubspot-vs-attio → attio-vs-hubspot.
  - clari-vs-custom-ai-forecasting → /clari-salesloft-six-months/ (no Clari vendor profile exists; /vendors/clari/ redirects to /vendors/).
  - Homepage footer adds Privacy Policy, and Team/Contact point at /about/… (they went through redirects).
- **Stack pages** now show "Last updated" from post_modified. Dates: Apr 30 (×3), May 2, Jul 4 (×2). See decisions/2026-10-29-stack-freshness-stamp.md.
### Open after this pass
- ZoomInfo Q3 Nov 4 (grade Q4 prediction 1).
- HubSpot results ~Nov 4–5.
- Q4 Claude capability tracker due ~Nov; it should supersede the #593 note.
- Dec 2 watermark transition.
- Dec Clari+Salesloft re-score.
- Bland Series B date dispute.
- lemlist–Claap proposal.
- Qualified exact day.
- HubSpot Agent Hub primary source.
- Agentforce Hunter GA (Nov) unverified.
- WP credential still absent.
### Vendor freshness sweep (2026-10-29, propose-only)
14 May–Jun profiles checked against primary sources; output in `monitor/proposals/2026-10-29-vendor-freshness.md`. Result: 7 proposed changes, 4 current, 3 current with unverified leads. **Most fixes are old errors, not new events.** Round dates are off by days to weeks (Apollo, Outreach, 6sense, Typeform), and the Salesloft record says 2024 for Vista's Dec 2021 deal and omits the Clari merger (completed 2025-12-03; operates as Salesloft from 2026-09-02). Demandbase took $175M from Vista Credit Partners on 2023-02-16. RB2B's "Seed / ~$5M" has no source at all. This is the first deliberate per-vendor audit, and it supports hypothesis 7 in spirit: roundup-level research misses things, and here it was wrong historical data rather than missed rounds. Nothing applied; awaiting user approval (rule 15). Any date fix must also move the matching funding_event (rule 12).
- **Applied 2026-10-29 (user-approved):** 7 vendor record fixes, spot-checked against primary sources first. The Salesloft excerpt and take were also corrected. Demandbase's credit facility is kept out of `total_raised` and `_usd_m`, the house convention now that the user has chosen it: **debt financing is not counted as raised equity.** Tracker M&A records #317 and #297 are wrongly dated and flagged for human review. The vendor `funding_stage` select has no "Merged" option; use "Acquired" and describe the merger in the text.
- **Tracker fixes applied (user-approved):** #297 Vista–Salesloft is now 2021-12-23 and #317 Clari merger is now 2025-12-03. **REST gotchas for funding_event:**
  - Omit `vendor` when it is null.
  - Send empty numeric fields as `null`, not `""` (`rest_invalid_type`).
  - `event_date` accepts either `Y-m-d` or `Ymd` (both are in the DB). **Correction:** the mixed formats are harmless, because the feed runs every date through `gtmlens_normalize_date()` (functions.php ~L1408) and nothing orders by raw meta. Prefer `Y-m-d` for consistency only.
  - A blank `stage` is rejected on write (`rest_invalid_param`). Omit the key to leave it untouched.
- **#298 Drift fixed (2024-02-13).** All three Salesloft M&A events had wrong dates (off by 12 days, 3 months and ~2.6 years), and at least one had a non-resolving source URL. Early hand-entered M&A records (ids ~297–317) look unreliable as a batch. Worth a sweep of the remaining early M&A events against primaries.
- **M&A sweep applied 2026-10-29 (user-approved):**
  - #299 Pipedrive: event_date 2020-11-15 → 2020-11-12 (Pipedrive newsroom release via PR Newswire + TechCrunch + tech.eu).
  - #299 source_url: the stored URL was a 404; replaced with the real release.
  - #299 valuation_usd_m: $1.5B (anonymous TechCrunch sources only) blanked per rule 4.
  - #484 Fin note: "Close expected Q4 FY27" replaced with "Completed Sep 10, 2026".
  - 7 M&A dates re-saved as Y-m-d (cosmetic; see correction above).
  - All 4 early hand-entered M&A events (297/298/299/317) had wrong dates and 2 had dead or invented source URLs. Treat any pre-2026 hand-entered record as unverified until it is checked against a primary source.
- **Hightouch + n8n cross-site pass (2026-10-29, user-approved):**
  - **Hightouch:** ARR was overstated 2× ($200M+ vs $100M company-stated, Apr 2026), and the 65% growth claim was unsourced.
  - **Census:** treated as an independent competitor, though Fivetran agreed to buy it 2025-05-01.
  - **n8n Series B:** misstated three different ways across three posts ($35M Dec 2025 Sequoia; €60M late/Oct 2025 €300M Sequoia; €60M 2025). The truth is €55M, Mar 2025, Highland Europe.
  - **n8n total raised:** $254M → $240M (n8n primary).
  - **Lesson:** the Q2 2026 pieces (114 map, 147 report) were drafted with unverified funding figures. Treat any Q2 2026 funding claim as unverified until checked. Supports hypothesis 9 (excerpt drift); I propagated one bad figure myself while fixing another. Recheck the whole sentence, not just the field being corrected.
- **Highspot pass (2026-10-29, user-approved):** the "April 2024 $2.3B" claim from the August correction (rule 9) had survived in swot_weaknesses for two months. Rule 9 needs a full-record grep, not just the take, whenever a claim is corrected. Also fixed: Series F date (2022-01-13), total ($654M → $648M, company-stated), stale IPO/acquisition SWOT lines, a top-of-take pointer to the merger, and the empty excerpt. Product brand is now "Highspot by Seismic".
- **Clay pass (2026-10-29, user-approved):**
  - **Entry price:** the published "$54/mo" was a component. Clay's plan price = actions tier + data-credit tier (Launch $54 + $113 = $167 annual; $60 + $125 = $185 monthly).
  - **ARR:** "$200M+" was overstated; company-stated is $100M in Dec 2025, "on track for $200M" in mid-2026. **Third vendor in a row (Hightouch, Clay; Highspot's valuation) where a profile overstated a growth or valuation figure. Promote hypothesis-level suspicion: any ARR figure in a vendor take needs a company-stated source and date.**
  - **Total:** $319M → $317M from Clay's itemized rounds; tender offers are not raised capital.
  - **Stamp side effect:** editing a stack moves its post_modified stamp even when the costs were not re-verified. Revisit the stamp decision if this recurs.
- **Apollo pass (2026-10-29, user-approved):**
  - **Pocus:** acquired by Apollo on Mar 19 2026, written up in two posts but missing from the tracker; now event #711. **Covered-in-prose-but-not-recorded is a new failure mode.** Worth a sweep: grep posts for "acquired"/"bought" and diff against M&A events.
  - **CEO change:** Matt Curl replaced Tim Zheng on Feb 3 2026 and the site never mentioned it.
  - **Pricing:** apollo.io/pricing renders prices client-side, so WebFetch sees none. Read it in Chrome (the toggle switches annual/monthly).
  - **Funding_event via classic editor:** event_type is a select (not a radio); category is a select2 AJAX field (inject an Option with the term id); the date hidden input takes Ymd.
- **M&A sweep (2026-10-29, user-approved):**
  - Added 10 tracker events.
  - Q2 2026 map had 3 invented or misattributed deals (Slintel→ZoomInfo is really 6sense; Boostup→Mediafly and Kaia→Demodesk have no evidence). That makes **5 fabricated or mis-sourced claims in the Q2 2026 pieces now.**
  - **Conventions now set by the user:** include pre-2025 deals; record **close** dates (announcement date only for pending deals or where no close date is confirmed); **blank** values unless a price was announced.
  - **Gotchas:**
    - REST create of funding_event works when the `vendor` key is omitted. Only *clearing* vendor on an existing record fails.
    - Adding events changes published quarter counts, so grep posts for "N acquisitions" after every tracker addition.
    - Linking an event to a vendor whose last_round_date differs double-lists the row in the feed.
- **Close-date convention applied to all 2026 M&A events (2026-10-29):**
  - Fin → Sep 10 (moves Q2 → Q3), Common Room → Jul 17, Agency → Aug 5 (announcement; still pending).
  - Q3 2026 tracker = 10 acquisitions, 3 with disclosed price (Common Room, Agency, Fin).
  - Outstanding conflict: two existing values come from 10-Qs (Agency $17M, Common Room $266.8M) under the new "blank unless announced" rule.
- **ZoomInfo pass (2026-10-29, user-approved):**
  - **Errors fixed:** the "$1.5B ARR" comparison claim (actual FY2025 revenue was $1,249.5M), Chorus "June 2021" (it was July), Workbounce "November 2024" (only the year is sourced), and the ticker anachronism (ZI → GTM on 2025-05-13).
  - **Profile update:** the vendor take got its 2026 update.
  - **Follow-up:** Q3 results Nov 4 resolve two published predictions; grade them on that day.
  - **Pattern across today's six vendor passes:** pages updated in August carried accurate events but stale numbers. Every page that hard-codes a market cap, ARR or revenue figure is a staleness liability.
- **Gong pass (2026-10-29, user-approved):** the profile's core narrative (flat-to-declining, secondary trades below the mark, "below $5B") was contradicted by Gong's own May 12 2026 release: ARR >$500M, >55% YoY, ten quarters of acceleration, 5,000+ companies. The profile was last updated May 3, nine days before. Fourth vendor today whose analyst narrative had calcified around a 2024–25 thesis. **Hypothesis 10 (new): profiles last edited before a company's most recent company-stated metric release are presumptively stale; check each vendor's newsroom for releases newer than its last_updated.** Evidence count: Gong, Hightouch, Clay, ZoomInfo.

## Staleness sweep applied (2026-10-29, user-approved)
38 profiles were swept by 3 research agents. 24 were fixed; 14 were current.

**Profiles fixed:**
- **RB2B:** bootstrapped per founder; unsourced seed and valuation removed; pricing.
- **Spekit:** $45M Series B Jan 19 2022, ~$60M total.
- **Lyzr:** $14.5M Mar 9 2026 at ~$250M; the July $100M Series B is noted as in progress, not recorded.
- **Default:** founder is Nico Ferreyra; $20M total; pricing.
- **Sybill:** pricing; Series A date 2024-07-31.
- **Bardeen:** Aug 2024 Dropbox/HubSpot strategic round; unsourced 2025 round removed.
- **Reo.Dev:** $11.3M Series A Jul 17 2026.
- **Unify:** Jun 24 relaunch; per-seat pricing.
- **Pylon:** pricing; 1,500+ customers.
- **Aurasell:** 50+ customers, $6M+ ARR, HQ San Mateo.
- **Monaco:** SWOT aligned.
- **Glean:** $300M+ ARR (May 28 2026).
- **Crescendo:** Andy Lee CEO from Jul 14 2026.
- **Warmly:** "agreed to acquire"; pricing; valuation undisclosed.
- **Spotlight.ai:** bootstrapped after an angel pre-seed; founded 2022.
- **Sierra:** see sierra.md.
- **Pipedrive:** Nova launch Sep 16; restructuring announced Oct 29.
- **Zapier:** 10,000+ apps; "Pro" plan name.
- **lemlist:** ~€40M ARR (CFNews); Claap acquisition note.
- **HockeyStack:** $5M framing removed.
- **Attio:** $340M numeric valuation cleared; AI credits.
- **Instantly:** $30M ARR labelled an estimate.
- **HubSpot:** Q2 2026 figures, Agent Hub, Oct 15 pricing changes.
- **Smartlead:** $32.
- **HeyReach:** plan names.
- **Aligned:** Seller Agent.
- **PhantomBuster:** unsourced $2M cleared.

**Warmly softened across 9 pages** ("agreed to acquire", close unconfirmed): posts 537, 576, 489, 642, 654, 662, 114; comparison 107; event #482 note.

**Tracker:**
- New rounds: #765 Reo.Dev $11.3M 2026-07-17 and #766 Lyzr $14.5M 2026-03-09. The amount field accepts decimals; the feed truncates to integers.
- Q1 2026 is now 3 rounds / ~$300M; Q3 2026 is now 6 / ~$286M.
- Dated corrections added to posts 642 (4 places), 638 and 630.

**Not applied, unverified:**
- lemlist CEO (Tenot vs Moubeche).
- Pipedrive–Outfunnel.
- lemlist–Claap tracker record (no exact deal date from a primary source).
- Default Series A date (CMS metadata only).
- Spotlight HQ.
- Zapier Agents add-on price.
- Factors.ai funding (flagged, no source either way).
- Sierra "Series E" label (no reputable primary).
- Founder-post ARR leads: HeyReach $19M, RB2B $10M.
- Alta run-rate.
- Glean–Aryn acquisition (aggregator only).

**Pattern (feeds hypothesis 10):** most errors were unsourced header funding figures present since creation (RB2B, Spekit, Lyzr, Default, Bardeen, Spotlight, Attio, PhantomBuster, Factors.ai), not missed news. **Proposed rule candidate:** no header funding or valuation figure without a source URL in last_round_source_url. Evidence count: 9 in one sweep.

**Ops note:** firing ~35 parallel page fetches right after a LiteSpeed purge produced transient 500s on Hostinger. Verify sequentially.
