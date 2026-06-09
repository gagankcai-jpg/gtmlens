## Decision: Next-phase roadmap (post stack-builder fix, pre-advisory)

## Context
After shipping market map, 20 comparisons, 6 stacks (with Series B + C added), and LiteSpeed auto-purge, the site has solid infrastructure but uneven content depth and one critical primary surface (Stack Builder Quiz) that's built but unmounted. User asked for the next-phase priority list, with explicit "no advisory yet" constraint and stack builder quiz called out as priority.

## Alternatives considered
1. Front-load vendor profile depth across all 30 skeletons before any new surfaces.
2. Build Claude-for-GTM hub + attribution chip first (plan Part 2).
3. Lead with the quiz sprint, then content depth in parallel.

## Reasoning
Option 3 wins. The quiz is the highest-leverage primary surface — everything is built (329 lines of JS, 687 lines of rules JSON, shortcode registered), it's just not mounted on any page. Largest delta-per-hour. Vendor depth (option 1) compounds slowly and benefits from existing under-utilized assets being live first. Claude-for-GTM (option 2) is a content vertical that depends on the basic site producing inbound traffic; it goes later.

## Trade-offs accepted
- 30 vendor skeletons remain visible as skeletons until P1 sprint. Mitigated by adding "Profile coming" badge.
- No newsletter capture yet means content investment leaks until P2. Acceptable for ~1-2 weeks.
- Funding tracker, About hub, and Lighthouse pass deferred.

## Roadmap

### P0 — Stack Builder Quiz sprint
1. Audit `assets/data/stack-rules.json` against the 31 live `vendor` posts. Produce reconciliation list of broken slugs (e.g. `prospeo`, `syncgtm`).
2. Add `series-b` and `series-c` tier branches to rules JSON with vendor-fit logic.
3. Mount `[stack_quiz]` shortcode on a new WP page at `/stack-builder/quiz/`. Update homepage CTA + archive-stack hero to point there.
4. Wire result page → Fluent Forms inline + MailerLite tag `stack-builder-{tier}`.

### P1 — Vendor profile depth
Bring 10 highest-intent vendors to Clay-grade (SWOT + analyst take + last_updated + alternatives wired + methodology footer): Apollo, HubSpot, Smartlead, 11x, HeyReach, RB2B, Outreach, Salesforce, Gong, ZoomInfo. Remaining 20 stay as skeletons with "Profile coming" badge.

### P2 — Newsletter capture
Install Fluent Forms + MailerLite. Wire embed on home, every insight footer, and quiz result. Until this exists, every other content investment leaks.

### P3 — Two flagship insights
- AI GTM Market Map Q2 2026 (narrative around the existing visual map; names the AI-native enterprise quadrant occupants)
- GTM Harness Drift (the thesis post)

### P4 — Internal link audit
Mechanical pass after P3 ships: every vendor → 2 insights, every insight → 2–3 vendors. Cheap, mechanical, compounds SEO.

### Deferred (named, not scheduled)
- About hub (team / press / contact)
- Claude-for-GTM hub + "Powered by Claude AI" attribution chip
- Lighthouse + schema validation gate
- Funding tracker rolling list
- Advisory section (Phase 2, explicitly out of scope until further notice)

## Supersedes
None — this is the first roadmap decision logged after the gtm-intelligence-site-plan.md / build-plan strategy docs.
