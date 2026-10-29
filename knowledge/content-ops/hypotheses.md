# Content-ops — HYPOTHESES (unconfirmed; need more data)

Promote to `rules.md` at the third independent confirmation. Each entry states what would confirm or kill it.

## Category theses under test
1. **Nobody can price an agent standalone.** Five serious vendors, five different answers, and no published per-agent price list between them (ZoomInfo gives orchestration away and meters data; HubSpot moved two agents to pay-per-result; Gong capped AI credits per user; Salesforce sells per resolution then redefined the ARR metric). *Confirms:* a vendor publishes a per-agent or per-outcome price list. *Kills:* same. This is Q4 2026 prediction 5.
2. **"Give away the orchestration, charge for the grounding" is the data vendors' strategy.** Inferred from ZoomInfo shipping Agent Teams with no SKU while migrating to credit pricing. *Confirms:* Q3/Q4 earnings showing consumption revenue growth against flat agent monetisation. Evidence count: 1.
3. **One outlier deal dominates each quarter's GTM capital.** Sierra was 73% of Q2 2026; Clay 42% of Q3. Deal count is stable (4–6/quarter) while cheque size swings. *Confirms:* a third consecutive quarter with the same shape. Evidence count: 2.
4. **Being the last independent in an absorbed category is unstable.** Unify is the last intent-and-signal independent at scale. *Confirms:* Unify is acquired or raises at a materially higher mark. Q4 2026 prediction 4. Evidence count: pattern only.
5. **ZoomInfo gets taken private.** Carried from the Q2 2026 report, still unresolved. Supporting evidence has strengthened (market cap ~$1.2B from ~$3.5B, NRR 89%, $650.5M goodwill impairment, Mizuho to Underperform) but no deal. *Confirms:* an announcement. Open until further notice.

## Method theses under test
6. **Structural predictions hold; company-level predictions fail.** Across the Q2 report and the Q2 market map, every structural call landed and every named-company call missed. *Confirms:* the Q4 scorecard showing the same split. Evidence count: 2 (one report, one map, same quarter — arguably a single observation).
7. **The routine's roundup-level research misses small rounds at lower-profile tracked vendors.** The routine itself flags this. *Confirms:* a missed round surfacing later in a per-vendor sweep. Never yet tested with a deliberate per-vendor audit.
8. **Model list prices have a half-life shorter than a quarter, so any page quoting them must re-fetch the vendor pricing page on publish day.** Two full repricings landed in Q3–Q4 2026 (Jun–Jul, then Sep–Oct). The Q4 main map shipped with the superseded ladder because prices were carried over from our own pages. Our own pages are effectively an aggregator for this purpose. *Confirms:* another stale price caught at or after publish. Evidence count: 1.
9. **Hand-written vendor excerpts drift from the ACF record and are not caught by profile-first fixes.** Four found on 2026-10-29 (clay, hightouch, 11x, n8n). *Confirms:* drift found again after a record change. Mitigation to test: scan excerpts against `funding_stage`/`last_valuation` whenever a round is applied. Evidence count: 1 sweep.
