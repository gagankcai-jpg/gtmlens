# PROPOSE-ONLY — human applies

Funding & M&A catch-up for 2026-10-01 → 2026-11-23 (the Nov 1 routine did not run). Researched 2026-11-23.

**Already recorded in the tracker for Oct–Nov 2026** (from `window.GTMLENS_FUNDING_DATA`, 94 rows): only **Braze**: ma, 2026-10-20, $5,660M, PENDING (Salesforce). Most recent rows before the window: DoubleO.ai (ZoomInfo, 2026-09-30), Fin/Intercom (2026-09-10), Clay Series D+ (2026-09-09). None of the items below are in the tracker.

## Proposed new tracker events

| Company | event_type | Stage | Amount (USD M) | Exact date | Category guess | Primary source | Second source |
|---|---|---|---|---|---|---|---|
| Rox | round | Series B | 100 (at $1.3B valuation) | 2026-11-11 | AI SDR / revenue agents (agentic CRM) | https://www.prnewswire.com/news-releases/rox-raises-100-million-series-b-at-1-3-billion-valuation-to-grow-revenue-for-the-global-2000--302936434.html (Nov 11, 2026, 08:00 ET) | https://ventureburn.com/rox-raises-100-million-ai-agents-outsell-salesforce-crm/ (Nov 12). Reuters also reported it, but I couldn't open the Reuters page; only syndication sites returned in search |
| Listen Labs | ma (acquired by Salesforce) | Acquired | undisclosed (a reported ~$2B traces to Business Insider, not confirmed) | 2026-11-02 (close; agreement 2026-09-29) | Customer research / VoC (adjacent; Marketing & Service Cloud) | https://www.salesforce.com/news/stories/salesforce-signs-definitive-agreement-to-acquire-listen-labs/ (editor's note: "Salesforce completed its acquisition of Listen Labs on November 2, 2026.") | https://siliconangle.com/2026/10/01/salesforce-to-acquire-ai-customer-research-startup-listen-labs-in-reported-2b-deal/ (announcement coverage) |
| Siena | round | Series A | 17 | 2026-10-06 (company post; see note) | AI customer-service agents | https://siena.cx/blog/siena-raises-17m-series-a | https://thenextweb.com/news/siena-17m-series-a-york-ie-ai-agents (Oct 7); https://therecursive.com/romanian-founded-siena-raises-over-eur15m-series-a-to-build-an-agent-of-record-for-consumer-brands (Oct 7) |

Notes:
- **Rox:** General Catalyst and March Capital co-led. Sequoia, GV and Factory returned. Databricks Ventures and Prosperity7 joined (the list beyond the co-leads comes from secondary outlets). Total raised is about $150M. ARR growth (">10x") is company-stated, and no absolute figure was disclosed. TechCrunch (2026-03-12) had earlier reported, from unnamed sources, a $1.2B mark with no amount; neither company confirmed it, so don't record that as a separate round.
- **Listen Labs:** Salesforce never disclosed a price. If the tracker records it, use amount_m 0 or undisclosed, not $2B. It is adjacent to the core categories, comparable to the Contentful and Braze rows already in the tracker. Drop it if it falls outside the site's scope.
- **Siena:** The date is ambiguous by one day. The article body on siena.cx says Oct 6, 2026, but the page metadata says Nov 18, 2026, and both outlets published on Oct 7. York IE led, with Joyance, Aglaé and Sierra Ventures. Total raised is $29.7M. Not a profiled vendor. If the 06 vs 07 gap matters, have a human check the siena.cx post.

## Leads (not verified)

These did not meet the bar: no exact date confirmed, no primary source, or uncertain scope.
- **Sequel.io:** $15M led by Sorenson Capital; total funding about $26M. Unite.ai says it was a Series B announced 2026-11-18. Dealroom calls it "early-stage" and aifunding.me "undisclosed round". Webinar and marketing-agent platform, adjacent to GTM. I found no primary release. TNW covered it: https://thenextweb.com/news/sequel-15m-webinars-company-websites-ai-agents
- **Decagon:** A CO/AI item dated 2026-10-28 claims a "$65M Series B". That conflicts with Decagon's Jan 2026 $250M Series D at $4.5B and is probably a recycled 2024 story. Do not record it.
- **CX Network weekly roundups (Oct 2/9/23/30, Nov 6/13):** The site returned 403 to fetches. Search snippets mention Ando ($20M, out of stealth, date unclear) and an unnamed company reaching a $1.2B valuation (roundup dated Nov 13). Worth a manual skim.
- **LangChain:** A $300M round reported in a roundup around 2026-11-14. That is agent infrastructure, probably out of scope; unverified.
- **Coveo acquires Anglera:** 2026-10-29 per search snippet. Product-data enrichment for retail, likely out of scope.
- **DoubleO.ai (already recorded 2026-09-30):** One snippet cites a Business Wire item dated 2026-10-01. The tracker date is probably right (announcement Sep 30, wire Oct 1), but a human may want to confirm.
- **Backfill candidates outside the window, not in the tracker:**
  - Orum acquired Scout AI, 2026-07-15, undisclosed: https://www.prnewswire.com/news-releases/orum-acquires-scout-ai-to-give-sales-teams-phone-data-they-can-trust-302826549.html
  - lemlist acquired Claap: see status check 3.
- Searches of profiled vendors found no Oct–Nov 2026 rounds or M&A for 11x, Artisan, Monaco, Bland, Netomi, Pylon, Relevance AI, Lyzr, Sierra, Decagon, Cresta, Crescendo, Attio, Unify, HockeyStack, Default, Common Room, Apollo, Outreach, Salesloft or Clay. Search coverage of these names was combined and shallow, so a miss is not proof that nothing happened.

## Status checks

1. **Has HubSpot–Warmly (announced 2026-06-30) closed?** No primary source confirms a close. Warmly's post (https://www.warmly.ai/p/blog/warmly-is-joining-hubspot) still says it "has entered into an agreement to be acquired" and has no update note. Coverage of HubSpot's Q3 2026 results (Nov 4) that I found does not mention Warmly. ir.hubspot.com returned 403 to my fetch. Keep the tracker row as "close not yet confirmed". A human should check HubSpot's Q3 10-Q (subsequent events / acquisitions note) or the IR newsroom.
2. **Salesforce–Braze (2026-10-20):** Still pending. The signing date may be Oct 19: one proxy summary on Quartr says the merger agreement was signed then, while the press release and tracker use Oct 20, the announcement date, which is correct for the tracker. Braze has filed proxy material (Quartr lists proxy-filing events), and the agreement targets a shareholder meeting within about 45 days after SEC review. I found no vote date, HSR status or close. Expected close is still Salesforce FY2028 (from Feb 1, 2027). No tracker change needed.
3. **lemlist–Claap:** This was **October 2025, not 2026**. Primary source: Claap's blog, dated 2025-10-20: https://claap.io/blog/claap-is-joining-lemlist-to-build-the-next-generation-platform-for-sales (FR: https://www.claap.io/fr/blog/claap-rejoint-lemlist). lemlist's blog says it spent "over 15M$": https://www.lemlist.com/blog/lemlist-just-spent-over-15m-to-buy-ai-sales-intelligence-platform-claap. Trade press (Demand Gen Report, Siècle Digital) published 2025-10-21. Reported price ranges vary ($15–30M, €12–25M, "$25M" per Founderpath), so record the amount as ">$15M (company-stated)" or undisclosed. The tracker has no lemlist–Claap row. If backfilled: event_type ma, date 2025-10-20, outside this window.

## APPLIED 2026-11-23 (later), weekly auto-ingest queue
- Published funding_event #790 Dreamteam, round, 2026-11-16: $53M seed + Series A (Together Fund / Accel; Premji Invest), category CRM (45), valuation blank. Sources: TechCrunch + TNW (+ YourStory). The tracker feed lists it once.
- Trashed 8 stale or out-of-scope auto-drafts: #791 (Crunchbase voice-AI roundup), #770 Rune (defense), #632 Listen Labs (M&A already recorded), #583 Encore AI (duplicate of #591), #582 Smallest.ai (voice infra), #474 Fin (already recorded), #472 (essay), #471 Glean (revenue figure, not a round).
- Q4 2026 rounds on the tracker are now Siena, Rox and Dreamteam. Still provisional (rule 20).
- Published vendor #801 Dreamteam (/vendors/dreamteam/), category CRM (45), last_round_date 20261116; funding_event #790 now links vendor 801. Feed lists the round once.
