## Decision: Stop recommending Warmly to seed-stage buyers in the Stack Finder, and surface pricing caveats in the results card

## Context
A Stack Finder run at stage=seed / motion=inbound / budget=$500–2,000 recommended **Warmly (HubSpot) at $833/mo** — 47% of the budget ceiling — for a product whose standalone availability is uncertain since the June 2026 HubSpot acquisition. The caveat existed in the vendor's `pricingNote` but the results UI never rendered notes, so the buyer never saw it. Root cause was narrower than it looked: a rule in `stack-rules.json` picked Warmly for `stage: [seed, series-a]` while **Warmly's own `fitTags` start at series-a** — the rule contradicted the vendor record.

## Alternatives considered
1. Retag Warmly's `fitTags` to exclude seed. Rejected: the rule, not the tags, was driving the pick — the tags were already correct.
2. Drop "seed" from the Warmly rule and stop there. Rejected: nothing else matches seed+inbound for intent-signal, so the category would silently disappear from a seed stack.
3. Drop "seed" from the Warmly rule AND add a seed+inbound rule picking RB2B, plus render `pricingNote` in the results card.

## Reasoning
Option 3. RB2B is the correct seed answer on the merits — free tier, $0 entry, already tagged for seed and inbound/PLG, no post-acquisition uncertainty — and the rule set should agree with the vendor records rather than override them. Rendering `pricingNote` fixes the broader class of problem: several entries carry caveats ("verify standalone availability", "no published pricing as of Aug 2026", "third-party estimate") that the buyer could not see. The note was already being passed into the Alpine row data; only the template was missing.

## Trade-offs accepted
- Seed+inbound stacks now total ~$104/mo instead of ~$937/mo, which may read as under-specified to someone expecting a fuller stack. Accepted: the previous total was mostly one questionable line item.
- Warmly is still recommended at series-a, now with its acquisition caveat visible. Accepted: at that budget the product is defensible if the buyer verifies availability, which the note now tells them to do.
- Touching `functions.php` carries site-down risk. Mitigated by an HTML-only change, a `<?php` tag-count check before saving, and an immediate 200/no-fatal verification across five pages.
- Rendering notes makes some result rows two lines tall. Accepted; a `<small>` inside the existing flexible grid cell leaves the 4-column layout intact.

## Supersedes
Nothing. Resolves the open item raised in the 2026-08-22 quiz re-verification.
