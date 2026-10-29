## Decision: M&A tracker conventions: include all eras, record close dates, leave values blank unless announced (with two named exceptions)

## Context
The 2026-10-29 M&A sweep found deals described in posts but missing from the tracker, and existing events that mixed announcement and close dates. The user set the conventions on 2026-10-29.

## Alternatives considered
1. **Scope:** keep the tracker to 2025+ only, or include older deals the site discusses. → Include older deals.
2. **Date:** announcement date, close date, or whichever was recorded first. → Close date for completed deals. Use the announcement date only for pending deals or where no close date can be confirmed, with the other date in analyst_note.
3. **Value:**
   - (a) any figure from any source;
   - (b) only a price announced by the parties;
   - (c) announced prices plus figures from acquirer filings (10-Q/10-K/8-K).
   → (b), blank otherwise.
4. **Existing filing-derived values (Agency "up to $17M", Klaviyo 10-Q; Common Room $266.8M, Zoom 10-Q):** blank them, or keep them as exceptions. → **Keep as exceptions.**

## Reasoning
- **Close dates** put a deal in the quarter it actually changed ownership. That matters for quarterly scoreboards.
- **Blank-unless-announced** avoids presenting accounting allocations (fair value, net cash) as deal prices.
- **The two exceptions** are already central to published analysis: the August column's Common Room correction and the CX map's $4.8B total. Blanking them would force retractions of figures that are accurately sourced and clearly labelled as filing-derived in those posts.

## Trade-offs accepted
- The value field is not uniform. Two filing-derived values sit alongside blanks for comparable deals (Qualified, Contentful, Clearbit, LivePerson). Any future value must be announced, and **these two exceptions are not precedent.**
- Close-date moves can shift published quarter counts (Fin moved Q2 → Q3). Every move requires a grep for "N acquisitions" and dated notes on affected posts.

## Supersedes
None. First logged decision on tracker M&A conventions. (Related: rules.md rule 4, "undisclosed deal values are recorded as undisclosed, never estimated".)
