# PROPOSE-ONLY — human applies

Newsroom staleness sweep, batch A. Run date: 2026-10-29. Profiles read live from gtmlens.com/vendors/<slug>/ on 2026-10-29.

How to read this:
- **Post-update** rows are events dated after the profile's last_updated date.
- **Pre-existing** rows are errors already on the page when it was last updated. They turned up while checking, and some are internal contradictions between the header and the body.
- **Live page** rows (pricing) compare against the vendor's own pricing page as fetched on 2026-10-29. Vendors don't date their pricing pages, so the change date is unknown.
- Founder social posts surfaced via ARR trackers are marked **LEAD**. I could not read the original post text, so verify it before publishing.

---

## 156 | Tally (last_updated 2026-05-01) — CURRENT

No material change found. No newsroom; searches found no funding, acquisition or leadership news.

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Pricing (Pro) | $29/mo Pro | Matches: Pro $29/mo monthly ($290/yr); Business $89/mo monthly | observed 2026-10-29 | https://tally.so/pricing (embedded plan JSON) |

---

## 154 | Phantombuster (last_updated 2026-05-01) — CURRENT (one internal inconsistency)

No material change found. Searches found no 2026 funding, acquisition or CEO news. The pricing page needs JavaScript, so I could not verify the $56 Starter price.

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Header "Last round" (pre-existing) | "Bootstrapped · $2M · Jan 2020" | Sidebar says "Total raised N/A (bootstrapped)". The header's $2M has no source and contradicts the sidebar; aggregators show only a small (<$1M) early round. Suggest dropping the "$2M · Jan 2020" fragment unless a primary source is found. | n/a | (aggregators only, not citable) |

---

## 103 | Expandi (last_updated 2026-05-01) — CURRENT

No material change found.

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Pricing | from $99/mo per LinkedIn account | Matches: Business $99/mo monthly, $79/mo annual (20% off), 7-day trial | observed 2026-10-29 | https://expandi.io/pricing/ |

---

## 98 | HeyReach (last_updated 2026-05-03) — STALE (minor)

No funding or M&A news. The company remains bootstrapped.

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| ARR (not on profile) | none | **LEAD, company-stated:** founder Nikola Velkovski posted that HeyReach crossed $19M ARR (dashboard screenshot). Verify the post before adding. | 2026-10-16 | Founder post https://x.com/nikolak47/status/2110823465616113946 (via aggregator arr.club, not citable alone) |
| Pricing plan name | "from $79/mo (Starter, 1 LinkedIn account)" | The plan is now called **Growth** at $79/sender/mo monthly ($64/seat/mo annual). Other plans: Agency $999/mo (25 senders) and Unlimited $2,999/mo. | observed 2026-10-29 | https://www.heyreach.io/pricing |
| SWOT Strengths | "pricing discipline … at the $79–$299/month tier" | No $299 tier exists. The tiers are $79/sender, $999 and $2,999. | observed 2026-10-29 | https://www.heyreach.io/pricing |

---

## 83 | RB2B (last_updated 2026-05-03) — WRONG

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Funding (header + sidebar) | Header "Total raised $5M · Last valuation $18M · Seed · $3M · Jun 2024"; sidebar "Seed · ~$5M (estimated) · Valuation N/A" | **No primary source for any raise.** The founder states RB2B is bootstrapped: "I just bootstrapped RB2B to $5m ARR in 13mo with 5 people" (company newsletter). Header and sidebar also contradict each other on valuation. Suggest: Stage = Bootstrapped; Total raised = N/A; remove $18M valuation and the $3M seed. | 2025-11-05 (newsletter) | https://newsletter.rb2b.com/p/19m-arr-w-9-fte-early-plays-rb2b-retention-com |
| ARR (not on profile) | none | **LEAD, company-stated:** founder Adam Robinson posted that RB2B hit $10M ARR with one full-time employee. Post timestamp decoded from the status ID. Verify the post before adding. | 2026-10-06 | https://x.com/RetentionAdam/status/2107607461343678829 (via arr.club, not citable alone) |
| Pricing | "Free tier, up to 100 visitor IDs/mo; paid plans from ~$149/mo" | Free = 150 resolutions/mo, **company-level IDs only** (no person-level, no emails). **Starter $79/mo** (300 resolutions). Pro from $149. Pro+ from $199. So the "paid from ~$149" figure and the free-tier description are both wrong. This also weakens the Analyst Take's "free tier gives named person" framing. | observed 2026-10-29 | https://www.rb2b.com/pricing |

---

## 477 | Aligned (last_updated 2026-07-02) — CURRENT (one product update)

The funding facts check out: $60M Series B led by PeakSpan, $73.8M total, announced 2026-07-01 ([SiliconANGLE](https://siliconangle.com/2026/07/01/aligned-bags-60m-funding-build-ai-native-sales-execution-layer-enterprise-deals/), [ynetnews](https://www.ynetnews.com/business/article/hkkvgugqme)).

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Analyst Take ("whether the agentic layer ships as real autonomy or assisted templates") | open question | Aligned launched **Seller Agent**, an AI agent inside the deal workspace (CEO Gal Aga quoted). Consider updating the watch item. The claim of a "first" agent is company marketing. | 2026-10-14 | Press release syndications: https://customerthink.com/?p=1084980 , https://www.generation-nt.com/communiques-presse/aligned-introduces-seller-agent-the-industry-eur-first-ai-agent-2082784 |
| Traction (company-stated) | "ARR tripled in 12 months" | Matches the Series B release (3x ARR, 142% NRR, company-stated) | 2026-07-01 | as above |

---

## 494 | Netomi (last_updated 2026-07-04) — CURRENT

No material change found after 2026-07-04. The newsroom items are undated. The Series C ($110M, led by Accenture Ventures, Adobe participating) matches: BusinessWire release 2026-04-29 / coverage 2026-04-30 (https://www.businesswire.com/news/home/20260429618643/en/). The Salesforce–Fin $3.6B reference (announced 2026-06-15) is accurate.

---

## 347 | Spekit (last_updated 2026-07-04) — WRONG (pre-existing; no post-update news)

No funding, M&A or CEO news after 2026-07-04. The CEO is still Melanie Fellay (per Spekit's CTO-appointment release, 2026-04-08: https://www.newswire.com/news/spekit-names-glen-pendley-as-chief-technology-officer-22759982).

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Last round (header + sidebar) | "Series B · $13M · May 2024" | **Series B was $45M, led by Craft Ventures**, bringing total to ~$60M. No primary source exists for a $13M May-2024 extension. | 2022-01-19 | Spekit release https://www.newswire.com/news/spekit-raises-45m-to-reinvent-training-for-remote-teams-21606672 ; https://builtin.com/articles/spekit-raises-45m-series-b-hiring |
| Valuation | "$200M (Series B, 2024)" | No primary source. Suggest "Undisclosed". | n/a | — |
| Body "Update (Jul 2026)" | "total raised is ~$75M with nothing new since the 2021 Series B" | Contradicts the header ($60M). Primary says ~$60M total after a **2022** Series B. | 2022-01-19 | as above |
| SWOT / Analyst Take | "$13M Series B extension in 2024 … suggests slower growth" | Based on the unsourced extension. Remove or rewrite. | — | — |

---

## 346 | Lyzr (last_updated 2026-07-04) — STALE + WRONG

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Last round / total / valuation (post-update) | "Series A · $8M · Jul 2024 · Total $10M · Valuation $30M" | **Series B in progress, company-stated:** Lyzr says it is "on track" to raise **$100M at ~$500M valuation**. As of the reports it had not closed, and no lead investor was named. Some later outlets say "closed", but those trace to company statements; I found no closing confirmation. | 2026-07-09 | Bloomberg https://news.bloomberglaw.com/artificial-intelligence/a-startup-that-builds-ai-agents-used-one-to-raise-100-million ; TNW https://thenextweb.com/news/lyzr-ai-agent-100-million-series-b |
| Same fields (pre-existing) | as above | **$14.5M Series A+ led by Accenture, at ~$250M valuation** (Rocketship VC participating). The body text already mentions this, but the header and sidebar still show $8M / $10M / $30M. | 2026-03-09 | Bloomberg https://www.bloomberg.com/news/articles/2026-03-09/agentic-ai-startup-lyzr-raises-funds-at-250-million-valuation ; MarketScreener https://www.marketscreener.com/news/lyzr-inc-announced-that-it-has-received-14-5-million-in-funding-from-rocketship-vc-llc-accentur-ce7e5fded98af222 |
| Series A date | "Jul 2024" | Unverified. TNW calls the $8M Series A "late 2025", which conflicts with Jul 2024. I could not resolve which is right. | ? | TNW (above) |
| Body "total funding to roughly $37M" | ~$37M | Not supported. $8M + $14.5M ≈ $22.5M disclosed, before the Series B. | — | — |
| SWOT "$8M Series A is small" | — | Out of date given the $250M (Mar) and ~$500M in-progress (Jul) valuations. | — | — |

---

## 345 | Default (last_updated 2026-07-04) — WRONG

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Founder (Analyst Take + SWOT) | "Jordan Crawford's RevOps community presence"; "Founder narrative (Jordan Crawford)" | **Default's CEO & co-founder is Nico Ferreyra** (byline on Default's own funding and launch posts). Jordan Crawford is not Default's founder. | n/a (current) | https://www.default.com/post/agent-launch ; https://www.default.com/post/introducing-default-2-0 |
| Last round (header + sidebar) | "Series A · $12M · Jul 2024", valuation ~$50M | Primary: "announcing our Series A led by 8VC, with participation from Craft and Jack Altman, bringing us to $20m in total funding". **The round size and valuation are not disclosed**, so drop $12M and $50M. Date: the page's datePublished metadata is 2026-05-27 (a CMS field, so confirm it). Jul 2024 is not supported. | 2026-05-27 (page metadata) | https://www.default.com/post/agent-launch |
| Pricing | "third-party figures conflict (~$500–750/mo entry, unverified)" | **Pricing is now published:** Growth **$12,000/yr** (24k enrichment credits, 200k workflow runs) + seats ($540/user/yr routing+scheduling, $240/user/yr scheduling). Enterprise $25,000/yr. Entry is ~$1,000/mo, not $500–750. | observed 2026-10-29 | https://www.default.com/pricing |

---

## 344 | Sybill (last_updated 2026-07-04) — STALE

No funding or M&A news after 2026-07-04.

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Pricing | "Essentials ~$19/user/mo · Business ~$79/user/mo (published)" | Plans are now **Free $0 (500 credits/wk) · Pro $30/user/mo · Business $90/user/mo · Enterprise custom**, credit-metered. The "Essentials" plan no longer exists. The page has a monthly/annual (save 17%) toggle, and it is unclear which basis the $30/$90 shown is. The "pricing-transparency outlier … from roughly $19" line in the body is stale. | observed 2026-10-29 | https://www.sybill.ai/pricing |
| Last round date (header) | "Series A · $11M · Sep 2024" | Announced **2024-07-31**. The body already says "July 2024", so the header contradicts it. | 2024-07-31 | TechCrunch 2024-07-31 "Sybill raises $11M…"; ChannelLife https://channellife.com.au/story/ai-startup-sybill-secures-usd-11-million-to-enhance-b2b-sales |
| Valuation | "$50M (Series A, 2024)" | No primary source. Suggest "Undisclosed". | — | — |

---

## 343 | Bardeen.ai (last_updated 2026-07-04) — WRONG (pre-existing; no post-update news)

No 2026 funding, acquisition or shutdown news found. Pricing matches the live page (Basic $10/mo, Premium $50/mo).

| Field | Live text | True now | Date | Source |
|---|---|---|---|---|
| Body "no funding, acquisition, or major partnership news has surfaced in roughly four years" | — | **Wrong.** Bardeen announced additional strategic funding from **Dropbox and HubSpot** (reported ~$3M) with go-to-market partnerships. | 2024-08-08 | BusinessWire release https://www.businesswire.com/news/home/20240808892830/en ; TechCrunch 2024-08-08 "AI business agent startup Bardeen pulls in strategic investment from Dropbox and HubSpot" |
| Header "Series A · $15M · Jan 2025", sidebar "~$80M (Series A, 2025)" | — | The body itself dates the $15.3M Insight-led Series A to **June 2022**. No Jan-2025 round or $80M valuation is supported. Suggest the header read "Strategic · Aug 2024" (amount per release) with the Series A as 2022, and valuation Undisclosed. I did not re-verify the exact Series A date. | — | — |
| SWOT "$15M Series A extension in 2025 telegraphs slower growth" | — | Based on the unsupported 2025 round. Remove. | — | — |

---

## 342 | Relevance AI (last_updated 2026-07-04) — CURRENT

No material change found: no Series C, M&A or ARR disclosure. The $24M Series B led by Bessemer (May 2025, $37M total) matches (https://www.forbes.com.au/news/entrepreneurs/australian-ai-agent-crew-relevance-ai-bag-24-million/). The pricing page needs JavaScript, so I could not verify it.

## APPLIED 2026-10-29 (user-approved: "apply all including tracker rounds")
See knowledge/content-ops/knowledge.md "Staleness sweep applied" for the full list. Live-checked: all edited pages return 200; the only old strings left are quoted inside correction notes.
