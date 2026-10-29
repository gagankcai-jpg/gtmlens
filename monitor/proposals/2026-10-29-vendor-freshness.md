# PROPOSE-ONLY — human applies

Vendor funding/corporate-record freshness check, 2026-10-29. Read-only research; no site, WordPress or repo records were changed.

Rule applied: a change is proposed only when it has an exact date plus a primary source (company/acquirer release) or 2+ independent reputable outlets. Aggregators (Crunchbase, CB Insights, Clay, Tracxn, PitchBook, Getlatka, Salestools, Signalbase, Dealroom feeds) are noted only as leads.

Summary: 7 PROPOSED CHANGE · 4 CURRENT · 3 CURRENT with an unverified lead noted.

---

## 447 · hightouch — CURRENT

The Series D is confirmed by the company blog: $150M at $2.75B, led by Goldman Sachs Alternatives Growth Equity and Bain Capital Ventures, published 2026-04-29. Nothing newer found.

- Primary: https://hightouch.com/blog/hightouch-funding-series-d (dated April 29, 2026)
- Business Wire release URL carries 20260427: https://www.businesswire.com/news/home/20260427054567/en/Hightouch-Raises-$150-Million-to-Reinvent-How-Marketing-Works-Using-AI (returned 403, so the dateline could not be read). Keep 20260429 because it matches the company blog. If the PR dateline turns out to be 04-27, a human may prefer that date.
- Note: total_raised $322M matches only aggregators (ZoomInfo, Owler); other aggregators say $172–182M. No primary source states a cumulative total. Leave as is, but treat it as unverified.

## 157 · typeform — PROPOSED CHANGE

The live round date and total look wrong. The company's own release dates the Series C to 2022-03-10 (not 07-26) and gives total funding as "more than $187M". The live total_raised of $135M equals the Series C alone.

| field | live | proposed |
|---|---|---|
| last_round_date | 20220726 | 20220310 |
| last_round_size | (blank) | $135M |
| total_raised | $135M | $187M+ |
| last_valuation | Not disclosed | Not disclosed (keep; TechCrunch reported $935M post-money, but that is one outlet) |

- Primary: https://typeform.com/blog/typeform-secures-135-million-series-c-funding-led-by-sofina (dateline Mar. 10, 2022; "more than $187 million" total)
- Independent: https://tech.eu/2022/03/11/making-forms-a-little-more-fun-lands-135-million-for-barcelona-startup-typeform/ (same figures)
- Business Wire: https://www.businesswire.com/news/home/20220310005141/en
- **Unverified lead (do not apply):** Bending Spoons has reportedly bid for Typeform. Bloomberg, citing Il Messaggero, reported a ~$255M offer on 2025-10-11 (https://www.bloomberg.com/news/articles/2025-10-11/bending-spoons-looks-to-buy-typeform-elysium-messaggero-says). AK&M reported a combined ~$500M bid for Typeform and Elysium. Signalbase and Dealroom feeds claim the deal closed, but no announcement from Bending Spoons or Typeform was found. Re-check next cycle.

## 156 · tally — CURRENT

Still bootstrapped; no acquisition found. In a 2025-10-15 Indie Hackers post, the company itself describes "our bootstrapped tiny team" at $4M ARR. One aggregator (StartupHub) lists $1M raised, but its HQ and founding-year details are also wrong, so it was disregarded.

## 155 · demandbase — PROPOSED CHANGE

A $175M financing from Vista Credit Partners was announced on 2023-02-16, after the live last_round_date.

| field | live | proposed |
|---|---|---|
| last_round_date | 20220421 | 20230216 |
| last_round_size | (blank) | $175M (credit financing, Vista Credit Partners) |
| stage | Series D+ | Series D+ (no change) |
| total_raised | ~$400M+ | no change proposed. Whether $400M+ counts this credit financing is unclear, so a human should decide whether to show equity and debt separately |

- Primary (investor/lender release): https://www.vistaequitypartners.com/news/demandbase-raises-175-million-in-new-financing/ (February 16, 2023)
- Independent: https://www.mediapost.com/publications/article/382633/b2b-tech-firm-demandbase-receives-175m-investment.html ; https://fintech.global/2023/02/20/demandbase-snares-175m-in-new-financing/
- **Unverified lead:** one aggregator lists a "Nov 2025 Series C+ $180M, Wellington Management" round. No primary or reputable source was found; do not apply.
- I could not find what the live 20220421 date refers to.

## 154 · phantombuster — CURRENT (unverified lead)

No acquisition or new funding found.
- **Unverified lead (status label):** CB Insights and StartupHub list a ~$670K seed (latest round dated June 2019, OneRagtime as an investor), which conflicts with "Bootstrapped". Aggregator-only; not applied. A human may want to check PhantomBuster's own statements before keeping "Bootstrapped".

## 153 · chorus — PROPOSED CHANGE (figure fix)

The acquisition record is otherwise confirmed: ZoomInfo announced it on 2021-07-13 for ~$575M all-cash, including a >$100M cash tax benefit. Chorus is still a ZoomInfo product; no change in status was found.

| field | live | proposed |
|---|---|---|
| total_raised | ~$85M | ~$100M |

- Globes (2021-07-13): https://en.globes.co.il/en/article-1001378006 ("raised $100 million")
- Calcalist (2021-07-13): https://www.calcalistech.com/articles/0,7340,L-3912362,00.html ("raised $100 million")
- ZoomInfo release: https://www.businesswire.com/news/home/20210713005614/en

## 104 · salesloft — PROPOSED CHANGE

Two problems with the record:

1. The Vista deal is misdated. Salesloft's own release announcing the Vista Equity Partners investment at a $2.3B valuation is dated 2021-12-23, not 2024.
2. Salesloft has since merged with Clari:
   - merger agreement announced 2025-08-07
   - merger completed 2025-12-03, with Steve Cox named CEO and no terms disclosed
   - the combined company took the Salesloft name on 2026-09-02, and only Clari Forecast keeps the Clari name.

| field | live | proposed |
|---|---|---|
| stage | Acquired | Merged — Clari + Salesloft (completed 2025-12-03; operates as Salesloft) |
| last_round_date | 20240313 | 20251203 (merger completion) |
| last_valuation | ~$2.3B (Vista acquisition, 2024) | ~$2.3B (Vista Equity Partners investment, Dec 2021); merger terms undisclosed |
| total_raised | ~$245M | no change proposed. Not verified, and no primary source states a total |

- Vista investment (primary): https://www.salesloft.com/company/newsroom/salesloft-announces-strategic-growth-investment-from-vista-equity-partners (December 23, 2021). The headline gives $2.3B. The release calls it a "strategic growth investment" and does not say "majority"; trade press (WSJ via pe-insights) described a majority stake.
- Merger agreement (primary): https://www.salesloft.com/company/newsroom/clari-and-salesloft-announce-merger-agreement (Aug. 7, 2025)
- Merger completion (primary): https://salesloft.com/company/newsroom/clari-salesloft-merger (December 3, 2025); also https://www.businesswire.com/news/home/20251203184266/en
- Rebrand (primary): https://www.salesloft.com/company/newsroom/salesloft-unveils-unified-brand-identity (September 2, 2026)
- The live 20240313 date has no funding event behind it that I could find. Salesloft's acquisition of Drift was in early 2024, which may be where it came from.
- Follow-on: any site record or copy for **Clari** should also reflect the merger and the rebrand. Clari is not in this batch.

## 103 · expandi — CURRENT

No funding or acquisition found for Expandi.io, the LinkedIn automation tool. ColdIQ (secondary) says it reached $10M ARR without outside funding.
- Name collision, do not apply: "Expandi Limited" (CEO Raffaele Apostoliti, a European adtech/martech group) bought Kompass on 2026-03-03 (https://www.solutions.kompass.com/blog/kompass-joins-forces-with-expandi-to-create-europes-leading-b2b-data-media-and-ai-powered-marketing-platform/). It does not appear to be the same company as expandi.io.

## 98 · heyreach — CURRENT (unverified lead)

No funding or acquisition found.
- **Unverified lead:** CB Insights lists an incubator/accelerator round on 2023-10-26 (EBRD Star Venture). Aggregator-only; not applied. If confirmed, "Bootstrapped" might need a qualifier.

## 92 · gong — CURRENT

The 2021-06-03 date, $7.25B valuation and $584M total all match TechCrunch (https://techcrunch.com/2021/06/03/gong-going-gangbusters-grabs-250m-series-e-on-7-25b-valuation/). No new round, IPO filing or acquisition found as of Oct 2026.
- Optional: last_round_size is blank and could be filled with $250M (Series E), per the same source.

## 91 · 6sense — PROPOSED CHANGE (figure fixes)

No round or acquisition after the Series E. The live date, total and valuation label need fixing.

| field | live | proposed |
|---|---|---|
| last_round_date | 20220125 | 20220120 |
| last_round_size | (blank) | $200M (Series E) |
| last_valuation | ~$5.2B (peak 2021) | ~$5.2B (Series E, Jan 2022) |
| total_raised | ~$525M | $426M (as of Series E) |

- Company release (PRNewswire, dateline Jan 20, 2022), as reposted by investor Insight Partners: https://www.insightpartners.com/ideas/6sense-announces-200-million-series-e-round-increasing-valuation-to-5-2-billion/ ("$426 million" total)
- Independent: https://siliconangle.com/?p=570294 (January 20, 2022; $200M at $5.2B, co-led by Blue Owl and MSD Partners)
- The ~$525–526M figure only appears in aggregators (Clay, Signalbase). No round after the Series E was found to explain it.

## 90 · outreach — PROPOSED CHANGE (date fix)

| field | live | proposed |
|---|---|---|
| last_round_date | 20210623 | 20210602 |
| last_round_size | (blank) | $200M (Series G) |

- Primary: https://www.prnewswire.com/news-releases/outreach-closes-200-million-round-4-4-billion-valuation-for-sales-engagement-category-leader-301304239.html (June 2, 2021; >$4.4B valuation; $489M total, which matches the live total)
- No 2025–2026 funding, acquisition or merger found. The 2026 "Outreach/Apollo" items are speculative blog posts.

## 83 · rb2b — UNVERIFIED LEAD (stage/total)

No funding round or acquisition was found from any source, so the live "Seed / ~$5M (estimated)" has no support. One podcast blurb describes the founder as having bootstrapped multiple companies, but that is not specific to RB2B. Do not change the record yet. A human should find a source for the seed and the $5M figure or remove them; per the accuracy rule, a missing figure is better than a wrong one. Note that the founder publicly reports ~$5M ARR, which may be where the "$5M" came from.

## 72 · apollo — PROPOSED CHANGE (date fix)

| field | live | proposed |
|---|---|---|
| last_round_date | 20230815 | 20230829 |

- SiliconANGLE (August 29, 2023): https://siliconangle.com/?p=628547
- Crunchbase News (August 29, 2023; $100M Series D led by Bain Capital Ventures at $1.6B; >$250M total): https://news.crunchbase.com/sales-marketing/apollo-io-funding-sales-tech-unicorn/
- Bloomberg (2023-08-29): https://www.bloomberg.com/news/articles/2023-08-29/apollo-io-is-valued-at-1-6-billion-in-sales-tech-financing
- No newer round found. The Pocus acquisition and new CFO are corporate news, not funding.

---
## APPLIED 2026-10-29 (user-approved: the 7 changes; Demandbase total excludes credit)
- **Spot-checked against primary sources before writing:** the Salesloft merger page (Dec 3, 2025), the 6sense release ($426M total, Jan 20, 2022) and the Typeform release ("more than $187 million", Mar 10, 2022).
- **Applied as proposed:** typeform, demandbase, chorus, salesloft, 6sense, outreach, apollo, plus last_round_source_url on each.
- **Demandbase:** `last_round_size_usd_m` left blank, so the credit facility is not counted as equity anywhere. `total_raised` unchanged.
- **Salesloft:** stage stays "Acquired", since the select field has no "Merged" option; the merger is described in `last_round_size`.
  - The excerpt's "acquired by Vista in 2024" is corrected.
  - The take gains a dated merger note; a full re-score is due in the December review.
- **Not applied:** the Gong round size (optional, not approved) and all unverified leads.
- **NEW: flagged for human review (tracker M&A records, not touched):**
  - #317 "Salesloft + Clari merger" is dated 2025-11-01; it completed 2025-12-03.
  - #297 "Salesloft acquired by Vista" is dated 2024-08-12; the Vista deal was 2021-12-23.
  - #298 "Drift acquired by Salesloft" is dated 2024-02-01; not checked.
- **APPLIED 2026-10-29 (user-approved):** #297 event_date 2024-08-12 → 2021-12-23 and #317 2025-11-01 → 2025-12-03. Titles updated and source_url set to the Salesloft newsroom primaries; tracker feed verified. #298 (Drift) is still unverified.
