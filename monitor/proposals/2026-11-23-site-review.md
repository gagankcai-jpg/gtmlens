# Site review — 2026-11-23 (PROPOSE-ONLY — human applies)

Inputs: `2026-11-23-funding-ma.md`, `2026-11-23-earnings-models.md`, plus a live crawl. The lead agent spot-checked the HubSpot CEO change (Nov 12 8-K; CFO Dive, CX Today) and Sierra's $400M+ ARR (sierra.ai blog).

## Health
- 156 sitemap URLs: all 200, no PHP errors.
- One page with 2 H1s: /stack-finder/ (Kadence page title + the page's own hero H1).
- No post published since 2026-10-19.
- Vendor profiles: 48 of 59 updated Oct–Nov; the other 11 were verified current on Oct 29 and left unstamped by design.

## Automation failure (root cause found)
- **What happened:** both Nov 1 GTMLens routines (funding refresh, Stack Movements) aborted within ~15s. Their first Bash call needed a permission approval with no one present ("Tool permission request failed: AbortError: Tool permission stream closed before response received").
- **Consequences:**
  - The October 2026 column (Oct 20–31) was never written.
  - The funding refresh did not run; monitor/state.json lastRun is still 2026-10-01.
  - Dec 1 runs will fail the same way.
- **Fix is user-owned:** pre-allow the tools these routines use (Bash for git/python/curl, WebSearch, WebFetch, Write/Edit under the repo) in settings, or run them in a mode that doesn't prompt.

## Stale or new, by item
1. **Frontier model prices moved (Nov 9 / Nov 17):**
   - Claude Fable 5.2 lists at $8/$40; Fable 5.1 is still listed at $10/$50.
   - GPT-6.2 Astra $8/$40, GPT-6.2 Sol $2/$10. The OpenAI date is secondary-only; its post is undated.
   - Frontier is still identical between the two labs, now at $8/$40; the mid tiers are unchanged.
   - Affects: comparison #464, vendors 68/361, quiz note, Q4 map Category 8, homepage entry prices.
2. **Gemini (vendor 360 + homepage):**
   - Argon goes to $4/$20 on Dec 1.
   - New GA models: Gemini 4 Carbon (Nov 10; $2/$10 → $4/$20 from Dec 1), Hydrogen (Nov 12; $1.50/$7.50), Neon (Nov 18; $3/$15).
   - 2.5 Pro and 2.5 Flash are redirected to 3.8 Flash and 3.5 Flash-Lite from Dec 1.
   - Pricing docs moved to aistudio.google.com.
3. **ZoomInfo Q3 (Nov 4, 8-K):**
   - Revenue $308.8M (−3% YoY); NRR 88%; 1,851 $100k+ customers; FY26 guidance raised to $1.2235–1.2265B.
   - **Q4-map prediction "NRR ≤ 89%" resolved TRUE.** The take-private prediction is still open.
   - Also new: a $44M settlement memorandum for the 2024 securities class action (Oct 19 8-K).
4. **HubSpot:**
   - Q3 (Nov 4): revenue $947.6M (+17%), 311,704 customers, FY26 guidance $3.685–3.687B. NRR 101% is secondary-only, so exclude it.
   - **CEO change Nov 12: Yamini Rangan out, co-founder Brian Halligan back as CEO.**
   - Incoming CFO Karen Burgess (Nov 30); Kate Bueker retires Feb 2027.
5. **Sierra:**
   - **$400M+ ARR** (sierra.ai blog; Oct 30 date per secondary coverage). $150M+ was added in Q3, and the doubling from $200M took ~5 months.
   - At $15.8B that is ~40x, not ~79x.
   - Affects: vendor 328, post 658, comparison 349, post 348. The reported ~$30B raise is unconfirmed, so leave it out.
6. **Salesforce:**
   - Agentforce Coworker standalone license at $50/user/mo (Nov 5); company-stated 185,000+ users.
   - Listen Labs acquisition closed Nov 2.
   - FQ3 results due Dec 2.
7. **Tracker additions (pass the gate):**
   - Rox $100M Series B at $1.3B, Nov 11.
   - Salesforce–Listen Labs, close Nov 2, undisclosed.
   - Siena $17M Series A, Oct 6.
   - Backfill lemlist–Claap, 2025-10-20 (Claap blog), value blank.
8. **Minor:** Gong exec hires (Nov 4); Apollo–Nevara team hire (Nov 18, no terms; lead only).

## Content gaps
- **October 2026 Stack Movements (Oct 20–31):** missing. Candidate items: Braze (Oct 20), AAIF Agent Skills (Oct 22), Sierra $400M (Oct 30), Merlin early termination.
- **November 2026 column:** due Dec 1. It would carry ZoomInfo/HubSpot Q3, the HubSpot CEO change, the Fable 5.2 / GPT-6.2 / Gemini 4 releases, Rox, Listen Labs and Agentforce Coworker.

## APPLIED 2026-11-23 (user-approved: items 1 and 2)
- **Tracker:** #777 Rox (round, 2026-11-11, $100M at $1.3B), #778 Listen Labs (ma, 2026-11-02), #779 Siena (round, 2026-10-06, $17M), #780 Claap (ma, 2025-10-20).
- **Models** (re-fetched from each vendor's own pricing page on 2026-11-23):
  - Comparison #464: table, text, verdict and rule B updated, with a dated note.
  - Vendors 68 and 361 entry prices; vendor 360 entry price and note (Carbon/Neon/Hydrogen, Dec 1 change; Argon no longer described as flagship).
  - Quiz stack-rules.json (live = repo); Q4 map Category 8.
- **Sierra $400M+:** vendor 328, post 658 (2 places), comparison 349, post 348 (top note).
- **ZoomInfo:** vendor 89 Q3 paragraph. Q4-map prediction 2 marked **[Resolved Nov 4, 2026: correct]**, NRR 88%.
- **HubSpot:** vendor 74 excerpt, SWOT, take (Q3 figures; Halligan returns as CEO Nov 12).
- **Salesforce:** vendor 88 note (Listen Labs, Agentforce Coworker $50/user/mo, Dec 2 results).
- **/stack-finder/:** now one H1 (functions.php `the_title` suppression extended).
- **Published post #787** "GTM Stack Movements: October 2026" with cover #788. Its scoreboard matches the tracker (Q4 to Oct 31: Siena + Braze pending).
- **Regression found and fixed:** the column took the homepage "Q4 2026 Flagship Report" slot (flagship = newest post). front-page.php now excludes category 81, so the Q4 map is restored. Live = repo.
- **Not done (user-owned):** pre-approve permissions for the scheduled routines before Dec 1.
