# Content-ops — RULES (confirmed; apply by default)

Promoted here after 3+ independent confirmations. Demote to `hypotheses.md` if contradicted.

## Accuracy gates
1. **Nothing is published or recorded without an exact date AND either a primary source or two independent sources.** Single-source items carry an inline flag ("single-source", "company-claimed", "analyst inference"). *A wrong figure is worse than a missing one.*
2. **Never take prices or round terms from an aggregator.** Go to the vendor's own pricing page or the company's own announcement. Confirmed repeatedly and expensively: Pipedrive ($39/$59/$79 vs actual $24/$49/$69), Clay (plan price = actions tier + data-credit tier; as of 2026-10-29 the $167 headline IS the annual-billing rate and $185 is monthly — a component like the $54 actions tier was wrongly published as the entry price. Re-derive from the add-on tables, don't read the headline), Instantly (Hypergrowth reported $358, actual $97).
3. **Vendor benchmarks, customer counts, ARR and retention claims are attributed, never stated as fact.**
4. **Undisclosed deal values are recorded as undisclosed, never estimated** — do not impute a price from a stale valuation.
5. **Quarterly capital comparisons exclude foundation-model rounds, and name the outlier deal.** Raw totals are meaningless (Q1 2026 raw = $152B). Always report GTM-only, and say which single deal drove the quarter.

## Editorial integrity
6. **Every published prediction carries an explicit resolution criterion** ("Resolves: …"). Learned the hard way — the Q2 report predicted Smartlead's ARR, which is unpublishable by a bootstrapped company and therefore unscoreable by construction.
7. **Grade past predictions in the next edition, including the misses.** The Q3 report went 0-for-4 on resolvable Q2 calls; publishing that is worth more than the predictions were.
8. **Preserve a resolved prediction and mark it `[Resolved]`** rather than deleting it — a call that came true is a track-record asset (Highspot's consolidation prediction).
9. **Appending a correction note does not fix a wrong figure upstream.** Mark it where the claim appears; a reader hits the body before the note (Highspot's "2024 Series F at $2.3B" sat 1,500 chars above its own correction).
10. **Never stamp `last_updated` on a page you did not actually change.** An honest stale date beats a false fresh one.

## Data-propagation order
11. **Fix the vendor profile first, then re-scan.** Comparison reference tables render from vendor ACF, so most "stale comparison" findings resolve themselves. Only hand-written prose needs separate edits. (Aug and Oct both confirmed this.)
12. **When correcting a round date, update the vendor profile AND its funding_event to the same date** — the feed dedupes on `(vendor_id|event_date)` and a mismatch double-lists the round.
13. **Snapshot the tracker feed before and after any bulk data change**, then diff the row set. This is what made the 22-vendor date normalisation safe.

## Process
14. **M&A record-writing is always human-reviewed.** The monthly routine's apply endpoint handles rounds only and must never receive an acquisition.
15. **Production writes of researched figures happen only after the user has seen the exact records.**
16. **Publish the intended flagship last** — the homepage flagship is the newest published post.
17. **Covers** are procedurally generated (canvas in-browser or PIL): navy base (13,31,60), diagonal gradient alpha 0.55 over palette [(76,29,149),(109,40,217),(236,72,153)], 44px dot grid, 9–13 seeded nodes with halos, bottom band with "GTMLENS · INDEPENDENT ANALYST VIEW". Seeds used: 72, 88, 101, 117, 139, 151, 163.
18. **Never store credentials in the repo.** The WP Application Password belongs at `~/.config/gtmlens/wp.json` (user-created; still absent as of 2026-10-19).
19. **M&A tracker conventions (user decision 2026-10-29; see decisions/2026-10-29-ma-tracker-conventions.md):**
    - Include deals from any year.
    - Record the **close** date (the announcement date only when the deal is pending or the close is unconfirmed, with the other date in the note).
    - Leave the value **blank unless the parties announced a price**.
    - Named exceptions, not precedent: Agency $17M and Common Room $266.8M (10-Q-derived).
    - After any date move or new event, grep posts for quarter acquisition counts and add dated notes.
