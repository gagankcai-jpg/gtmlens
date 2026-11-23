# Vendor profile: Dreamteam (draft)

Status: draft for review. Nothing is published.
Sources: public only. No demo; no vendor-supplied material beyond the public website and the company's announcement post.
Category: CRM (vendor_category 45), alongside Salesforce, HubSpot, Attio, Pipedrive and Aurasell.
Tracker: link this vendor to funding_event #790 (2026-11-16). The vendor's last_round_date must also be 20261116, or the tracker will list the round twice.

---

## Header / record fields

| Field | Value | Source |
|---|---|---|
| Title | Dreamteam | — |
| vendor_url | https://www.dreamteam.ai | company site |
| pricing_page_url | https://www.dreamteam.ai/pricing | company site |
| founded | 2025 | TechCrunch, 2026-11-16 |
| founders | Anand Venkatraman (CEO), Ramesh Parthasarathy, Senthil Kanthaswamy | TechCrunch; company blog, 2026-11-17 |
| hq | California, US (TechCrunch says San Francisco, the contributed TNW piece says Sacramento; engineering in Chennai per TechCrunch) | see note |
| funding_stage | Series A | — |
| last_round_size / _usd_m | $53M (seed + Series A combined; split not disclosed) / 53 | TechCrunch; company blog |
| last_round_date | 20261116 | TechCrunch, 2026-11-16 |
| last_round_source_url | https://techcrunch.com/2026/11/16/freshworks-alums-raise-53m-to-take-on-salesforce-hubspot-with-ai-native-crm/ | — |
| last_valuation | Undisclosed | — |
| total_raised / _usd_m | $53M / 53 (seed led by Together Fund; Series A led by Accel; Premji Invest participating) | TechCrunch; company blog |
| entry_price | $49 per seat per month, or $490 per seat per year (list $588, "2 months free") · AI credits $30 per pack per month ($300/year) · free viewer seats · 14-day free trial, no card | dreamteam.ai/pricing, read 2026-11-23 |
| pricing_tier | $$ (matches Attio and Pipedrive, which are priced similarly per seat; credit packs come on top) | — |
| target_segment | Startup and mid-market B2B sales teams that want a CRM that fills itself in, rather than one reps maintain by hand | — |
| reviewer | Gagan Chawla | — |
| last_updated | 20261123 | — |

## Excerpt (meta description)

Dreamteam is an AI-native CRM from ex-Freshworks founders that captures deal context automatically and runs four AI teammates for research, follow-ups, pipeline risk and admin. $53M seed + Series A (Nov 2026). GTMLens analyst take.

## Analyst take

Dreamteam is a bet that the CRM itself, not just the tools around it, is due for replacement. Most AI-GTM money in the last two years went into layers on top of Salesforce and HubSpot: enrichment, AI SDRs, call recorders, forecasting. Dreamteam's pitch is that those layers exist because the system of record underneath is filled in by hand and goes stale. Its answer is a CRM that builds the record itself from email, calendar, meetings and notes, then puts four AI "teammates" on top: one researches accounts, one drafts follow-ups and next steps, one watches the pipeline for risk, and one configures the CRM in plain language. The company was founded in 2025 by three former Freshworks leaders and announced $53M across a seed (led by Together Fund) and a Series A (led by Accel) in November 2026, with Premji Invest participating.

The pricing is the clearest thing about it, and it is unusually transparent for the category: one plan at $49 per seat per month, or $490 a year, free viewer seats, and AI work billed separately in $30 credit packs (one pack covers roughly 100 new contacts enriched and 20 live deals a month, by the company's own definition). That model is honest about where the cost sits. It also means the seat price is the floor, not the bill: a team leaning hard on the agents should budget for several packs a month, and credits expire at the end of each billing cycle.

The caution is the evidence base, which is thin even by early-stage standards. The traction figures are company-stated and inconsistent across the company's own channels: its announcement post says more than 100 companies in 13 countries, a contributed article the same week says "hundreds." TechCrunch reports, from founder interviews, that Dreamteam replaced Salesforce at DMS Health and won Plex's ad-sales team over HubSpot; the company's own pages show those names only as logos and testimonials. Even the headquarters city differs between sources. The site displays a SOC 2 Type II badge without an audit period or auditor. None of this suggests the product is weak; it means there is not yet much a buyer can verify from outside.

For GTM leaders: Dreamteam is worth a look if you are a young sales team choosing a first real CRM, or a small team on HubSpot or Pipedrive whose records are always out of date. The 14-day trial needs no card, so the practical test is to connect one team's mailboxes and calendars and see how complete the records are after two weeks without anyone typing into them. If you run Salesforce with years of custom objects, integrations and reporting, the switching cost is the whole story, and nothing public yet shows Dreamteam handling a migration of that size.

The strategic risk is that "a CRM that fills itself in" is exactly where the incumbents are heading. Salesforce's Agentforce and HubSpot's Breeze agents target the same jobs inside the systems customers already own, and newer CRMs such as Attio and Aurasell are competing for the same first-CRM buyer. Dreamteam's room to win is depth of automatic capture, a simple price and openness: it exposes its data to Claude and ChatGPT through MCP and an API on every seat. $53M buys time to prove that; it does not yet prove it.

Verdict: Pilot for startup and small mid-market sales teams choosing or replacing a lightweight CRM, measured on record completeness after two weeks. Watch, rather than switch, if you are on an established Salesforce or HubSpot Enterprise instance. Revisit when Dreamteam publishes verifiable customer outcomes or a migration case study from an incumbent CRM.

## Best fit

- Startup and small mid-market B2B sales teams buying their first real CRM
- Teams on HubSpot Starter/Professional or Pipedrive whose records go stale because reps don't update them
- Founder-led and early sales teams that want account research and follow-up drafts built in, not bolted on
- Teams that want their CRM data available to Claude or ChatGPT through MCP

## Worst fit

- Enterprises with heavily customised Salesforce instances, custom objects and long reporting histories
- Organisations that need a long, independently verifiable track record before changing a system of record
- Teams that need to budget AI usage precisely before trial (credit consumption depends on activity)
- Buyers that require on-premises or single-tenant deployment (not offered publicly)

## SWOT

**Strengths**
- Automatic context capture from email, calendar, meetings and notes, so the record builds itself
- Four built-in AI teammates (research, sales follow-up, RevOps risk, admin configuration)
- Transparent published pricing ($49/seat/month) with free viewer seats and a no-card 14-day trial
- API and MCP access on every seat; well-funded for its stage ($53M, Accel and Together Fund)

**Weaknesses**
- Customer counts, wins and outcomes are company-stated and inconsistent across sources
- Founded 2025; a very short operating history for a system of record
- Usage-based AI credits make the real cost harder to predict than the seat price suggests
- SOC 2 Type II badge displayed without audit details

**Opportunities**
- Teams frustrated with CRM data quality and admin overhead at HubSpot and Salesforce renewals
- Becoming the system of record for teams building their own agents on Claude or ChatGPT
- Founders' Freshworks experience selling to SMB and mid-market at scale

**Threats**
- Salesforce Agentforce and HubSpot Breeze agents automating the same jobs inside incumbent CRMs
- Other AI-native CRMs (Attio, Aurasell) competing for the same first-CRM buyer
- High switching costs and integration ecosystems that keep customers on incumbents

---

## Editorial notes (not for publication)
- **Origin:** the weekly auto-ingest flagged the round (#790, approved 2026-11-23).
- **Sources checked 2026-11-23:**
  - TechCrunch (independent reporting).
  - Company blog "Introducing Dreamteam: $53M…" (primary; dated Nov 17).
  - dreamteam.ai homepage and pricing page.
  - TNW (a contributed article, "Not produced by the TNW newsroom", so it is treated as company-supplied, not as an independent source).
- **Round split:** TechCrunch assigns the seed to Together Fund and the Series A to Accel. The company blog says only "led by Together Fund and Accel". The split is attributed to TechCrunch reporting.
- **HQ conflict:** TechCrunch says San Francisco; the contributed TNW piece says Sacramento; the company site doesn't say. The header field says "California, US" until it's confirmed.
- **Unverified and labelled as such:**
  - 100+ customers in 13 countries, and "hundreds".
  - The DMS Health and Plex displacement stories.
  - SOC 2 Type II.
  - The eight-figure revenue target for end-2027.
  - Headcount of 35+.
- **Not used:** the founders' "25+ years" experience claim, homepage demo figures (mockups), testimonials.
- **pricing_tier "$$":** chosen for consistency with Attio ($35–79/user) and Pipedrive ($14–69/seat), which are both "$$".
