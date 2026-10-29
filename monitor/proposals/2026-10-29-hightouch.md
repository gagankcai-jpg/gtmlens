# Hightouch: cross-site review, 2026-10-29 (PROPOSE-ONLY — human applies)

Pages mentioning Hightouch: vendor 447, post 114 (Q2 2026 main map, preserved snapshot), post 470 (May–June column).

## Verified correct
- **Series D:** $150M at $2.75B, Apr 29, 2026, led by Goldman Sachs and Bain Capital Ventures. Source: hightouch.com/blog/hightouch-funding-series-d. Matches vendor 447 and post 470.
- **Post 470:** all Hightouch claims match.

## Vendor 447: wrong or unsupported
| field | live | proposed | source |
|---|---|---|---|
| excerpt | "$200M+ ARR at a $2.75B valuation" | "$100M+ ARR (company-stated, Apr 2026) and a $2.75B valuation" | TechCrunch Apr 15 2026; co-CEO Kashish Gupta LinkedIn; Adweek |
| analyst_take | "$200M+ ARR at 65% YoY growth is real" | "Crossing $100M ARR in April 2026 (company-stated)" — 65% removed (no source) | same |
| swot_strengths | "$200M+ ARR with 65% YoY growth" | "$100M+ ARR (company-stated, Apr 2026)" | same |
| swot_threats | "Census matching on price and feature parity (and going public-cloud-marketplace-first)" | "Fivetran, which agreed to acquire Census on May 1, 2025, now bundles reverse ETL with ingestion" | Fivetran press release 2025-05-01; TechCrunch |
| swot_weaknesses | "Census is a credible direct competitor…" | "Fivetran (with Census, acquired 2025) is a credible bundled competitor…" | same |
| entry_price | "Free tier; Starter from ~$450/mo; Pro/Enterprise custom" | "Free tier (Basic Reverse ETL, up to 2 active syncs); Agentic Marketing Platform and Composable CDP are quote-only — pricing page, Oct 2026" | hightouch.com/pricing |
| total_raised | "$322M" | "~$322M (third-party tally; not company-disclosed)" | aggregators disagree ($315M / $322M / $402M) |

My error: earlier today I rewrote the excerpt from "$1.2B Series C" to "$2.75B valuation" but carried the unverified "$200M+ ARR" forward.

## Post 114 (Q2 map, preserved): inline correction needed (rule 9)
- "Hightouch and Census continue their competition for the reverse-ETL / CRM sync category": Census had been under agreement to Fivetran since May 1, 2025, a year before this map.
- Same paragraph, out of Hightouch scope: "n8n's $35M Series B (December 2025, Sequoia Europe-led)" conflicts with the n8n record (€60M Series B led by Highland Europe in 2025; Series C at $2.5B, Oct 2025). Needs its own check.
- "AI Decisioning (launched Q1 2026)": secondary coverage says Hightouch's AI products launched in late 2024. Unverified; flag only.

## APPLIED 2026-10-29 (user-approved)
- **Vendor 447:**
  - Excerpt, take and strengths now say $100M+ ARR (company-stated, Apr 2026); the 65% claim is removed.
  - Threats and weaknesses now cover Fivetran+Census instead of an independent Census.
  - entry_price set from the pricing page; total_raised labelled as a third-party tally; last_updated 20261029.
  - Live page verified: no "$200M".
- **Post 114:** inline correction at the Census sentence. AI Decisioning launch date not changed (unverified).

# n8n check — PROPOSE-ONLY
- **Verified:**
  - **Series B:** €55M (~$60M), Mar 24–25 2025, led by Highland Europe with HV Capital, Sequoia, Felicis and Harpoon. Source: n8n blog post by Jan Oberhauser (via mirror), EU-Startups, Nordic9. Valuation ~€250M is TechCrunch-reported, not company-stated.
  - **Series C:** $180M, Oct 9 2025, led by Accel, at $2.5B. **Total funding $240M** (blog.n8n.io/series-c, primary).
- **Proposed changes:**

| where | live | proposed |
|---|---|---|
| vendor 86 total_raised | ~$254M (254) | $240M (240), per n8n's Series C post. The SAP stake was a secondary sale, so it adds no raised capital |
| vendor 86 excerpt | "€60M Series B led by Highland Europe in 2025" | "€55M Series B led by Highland Europe in March 2025" (my wording from earlier today; €60M was wrong) |
| post 114 Q2 map | "n8n's $35M Series B (December 2025, Sequoia Europe-led)" | inline correction: €55M, March 2025, Highland Europe-led; plus the $180M Accel-led Series C at $2.5B (Oct 2025) that the map missed |
| post 147 Q2 state-of (2 places) | "€60M Series B in late 2025 at a reported €300M valuation"; "n8n €60M Series B (October 2025)… €300M valuation with Sequoia Europe leading" | inline correction with the same facts. The Oct 2025 event was the $180M Series C at $2.5B |
| post 148 Apr column | "€60M Series B (Highland Europe, 2025)" | "€55M Series B (Highland Europe, March 2025)" |

- **Correct already:** post 662 (Q4 map: $180M Series C Oct 2025 at $2.5B).
- **Unverified, flag only:** post 147's "50,000+ active installations" (n8n's March 2025 figure was 200k+ active users).

## n8n APPLIED 2026-10-29 (user-approved, all five)
- **Vendor 86:** total_raised ~$254M → $240M (240), per n8n's Series C post. Excerpt "€60M … in 2025" → "€55M … in March 2025". last_updated 20261029.
- **Post 114 (Q2 map):** sentence now cites the $180M Series C (Oct 2025, Accel, $2.5B), with an inline correction quoting the original "$35M Series B (December 2025, Sequoia Europe-led)".
- **Post 147 (Q2 state-of):** both passages corrected inline with dated notes (€55M Series B Mar 2025, Highland Europe; $180M Series C Oct 2025, Accel, $2.5B).
- **Post 148 (Apr column):** "€60M Series B (Highland Europe, 2025)" → "€55M Series B (Highland Europe, March 2025)".
- **Live check:** all 5 pages return 200. The only remaining old figures sit inside the correction notes, by design.
- **Not changed:** post 147's "50,000+ active installations" (unverified, flag only).
